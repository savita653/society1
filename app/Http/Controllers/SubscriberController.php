<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use JamesMills\LaravelTimezone\Facades\Timezone;
use Illuminate\Support\Facades\Hash;
use App\UserMeta;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            switch (request()->get('view')) {
                case 'all':
                    $users = User::role('subscriber')->where('users.id', '<>', auth()->user()->id);
                    break;
                case 'trash':
                    $users = User::role('subscriber')->leftJoin('subscriptions', 'subscriptions.user_id', '=', 'users.id')
                        ->onlyTrashed()
                        ->where('users.id', '<>', auth()->user()->id)->select('users.*', 'subscriptions.stripe_status as stripe_status');
                    break;
            }
            $users->where('email_verified_at', '<>', NULL);

            if(request()->get('can_contact')) {
                $users->where('can_contact', request()->get('can_contact'));
            }
            if(request()->get('subscription_status')) {
                // $users->where('can_contact', request()->get('can_contact'));
            }

            return datatables()->of($users)
                ->editColumn('created_at', function ($user) {
                    return Timezone::convertToLocal($user->created_at, 'd M Y');
                })
                ->editColumn('email', function($user) {
                    return "<a href='mailto:{$user->email}'>{$user->email}</a>";
                })
                ->editColumn('last_name', function($user) {
                    if(is_null($user->last_name) || $user->last_name == "") {
                        return "N/A";
                    }
                    return $user->last_name;
                })
                ->editColumn('is_active', function ($user) {
                    return $user->statusHtml();
                })
                ->editColumn('can_contact', function($user) {
                    return $user->can_contact ? "Yes" : "No";
                })
                ->addColumn('subscription_status', function($user) {
                    if ($user->subscribed('default')) {
                        return "Subscribed";
                    } else {
                        return "Not Subscribed";
                    } 
                })
                ->addColumn('action', function ($user) {

                    $showUrl = route('users.show', $user->id);
                    $title = $user->fullName() . " - " . $user->email;
                    return "
                        <button 
                            data-toggle='tooltip'
                            title='View details'
                            class='btn btn-primary get-content btn-icon btn-sm'
                            data-title='{$title}'
                            data-url='$showUrl'
                        ><i data-feather='info'></i></button>
                    ";

                })
                ->rawColumns(['action', 'is_active', 'subscription_status', 'email'])
                ->make(true);
        } else {
            $pageConfigs = ['pageHeader' => true];
            $breadcrumbs = [
                ['link' => "/", 'name' => "Dashboard"],
                ['name' => "Subscribers"]
            ];
            return view('/content/users/index', compact('breadcrumbs'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/content/users/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRules());

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->is_active = true;
        $user->save();

        $user->syncRoles(['user']);

        if ($request->has('notify')) {
            // Todo - Send  Credentias on email.
            $user->sendCredentials($request->password);
        }

        return response()->json([
            'success' => true,
            'code' => 'success',
            'title' => 'Congratulations',
            'message' => 'Subscriber created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $userMeta = [];
        $userMetaRecords = UserMeta::where('user_id', $user->id)->get();
        foreach ($userMetaRecords as $meta) {
            $userMeta[$meta->meta_key] = $meta->meta_value;
        }
        $userAddress = $user->address('institution_address');
        return view('/content/users/show', compact('user', 'userAddress', 'userMeta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('/content/users/edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $passwordValidation = !empty($request->password) ? 'required|min:6' : '';
        $request->validate($this->validationRules([
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => $passwordValidation
        ]));

        $user->name = $request->name;
        $user->email = $request->email;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->is_active = $request->is_active == 1 ? true : false;
        $user->save();


        if (!empty($request->password) && $request->has('notify')) {
            // Todo - Send  Credentias on email.
            $user->sendCredentials($request->password);
        }
        // Todo - Send Credentias on email.

        return response()->json([
            'success' => true,
            'code' => 'success',
            'title' => 'Congratulations',
            'message' => 'Subscriber updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        if ($user->trashed()) {
            $user->forceDelete();
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Subscriber permanently deleted successfully.'
            ]);
        } else {
            $user->delete();
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Subscriber moved to trash successfully.'
            ]);
        }
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        if ($user->trashed()) {
            $user->restore();
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Subscriber restored successfully.'
            ]);
        } else {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
    }

    public function validationRules($overrideRule = [])
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|max:255'
        ];
        return array_merge($rules, $overrideRule);
    }
}
