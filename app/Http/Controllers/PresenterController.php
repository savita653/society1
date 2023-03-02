<?php

namespace App\Http\Controllers;

use App\Notifications\AccountApproved;
use App\User;
use Illuminate\Http\Request;
use JamesMills\LaravelTimezone\Facades\Timezone;
use Illuminate\Support\Facades\Hash;
use App\UserMeta;

class PresenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            switch(request()->get('view')) {
                case 'all':
                    $users = User::role('presenter')->where('id', '<>', auth()->user()->id);
                    break;
                case 'trash':
                    $users = User::role('presenter')->onlyTrashed()->where('id', '<>', auth()->user()->id);
                    break;
            }

            return datatables()->of($users)
            ->editColumn('created_at', function($user) {
                return Timezone::convertToLocal($user->created_at, 'd M Y');
            })
            ->editColumn('is_active', function($user) {
                if($user->approved()) {
                    return $user->statusHtml();
                } else if($user->profile_status == 'decline') {
                    return '<span class="badge badge-pill badge-danger">Declined</span>';
                } else {
                    return '<span class="badge badge-pill badge-danger">Not Approved</span>';
                }
            })
            ->addColumn('action', function($user) {
                $deleteUrl = route('presenters.destroy', $user->id);

                if($user->trashed()) {
                    $url = route('presenters.restore', $user->id);

                    return "
                        <button 
                            class='btn btn-primary restore-record btn-icon btn-sm'
                            data-title='Restore'
                            data-url='$url'
                        ><i data-feather='refresh-cw'></i></button>

                        <button 
                            data-toggle='tooltip'
                            data-title='Delete Parmanently'
                            class='btn btn-danger delete-record btn-icon btn-sm'
                            data-url='$deleteUrl'
                        ><i data-feather='trash'></i></button>
                    ";
                } else {
                    $url = route('presenters.edit', $user->id);

                    return "
                        <button 
                            class='btn btn-primary get-content btn-icon btn-sm'
                            data-title='View Presenter'
                            data-url='$url'
                        ><i data-feather='info'></i></button>
    
                        <button 
                            data-toggle='tooltip'
                            data-title='Move to Trash'
                            class='btn btn-danger delete-record btn-icon btn-sm'
                            data-url='$deleteUrl'
                        ><i data-feather='trash'></i></button>
                    ";

                }
            })
            ->rawColumns(['action', 'is_active'])
            ->make(true);
        } else {
            $pageConfigs = ['pageHeader' => true];
            $breadcrumbs = [
                    ['link' => "/", 'name' => "Dashboard"], 
                    ['name' => "Presenters"]
                ];
            return view('/content/presenters/index', compact('breadcrumbs'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/content/presenters/create');
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

        $user->syncRoles(['presenter']);

        if($request->has('notify')) {
            // Todo - Send  Credentias on email.
            $user->sendCredentials($request->password);
        }

        return response()->json([
            'success' => true,
            'code' => 'success',
            'title' => 'Congratulations',
            'message' => 'Presenter created successfully.'
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $userMeta = [];
        $userMetaRecords = UserMeta::where('user_id', $user->id)->get();
        foreach ($userMetaRecords as $meta) {
            $userMeta[$meta->meta_key] = $meta->meta_value;
        }
        $userAddress = $user->address('institution_address');
        return view('/content/presenters/edit', compact('user', 'userAddress', 'userMeta'));
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
        // $passwordValidation = !empty($request->password) ? 'required|min:6' : '';
        // $request->validate($this->validationRules([
        //     'email' => 'required|email|unique:users,email,' . $id,
        //     'password' => $passwordValidation
        // ]));

        // $user->name = $request->name;
        // $user->email = $request->email;
        // if(!empty($request->password)) {
        //     $user->password = Hash::make($request->password);
        // }
        if($request->has('profile_status') && in_array($request->profile_status, array_keys(config('setting.profile_status')))) {
            $user->profile_status = $request->profile_status;
            $user->notify(new AccountApproved);
        }
        
        if($request->has('is_active')) {
            $user->is_active = $request->is_active == 1 ? true : false;
        }
        $user->save();


        if(!empty($request->password) && $request->has('notify')) {
            // Todo - Send  Credentias on email.
            $user->sendCredentials($request->password);
        }
        // Todo - Send Credentias on email.

        return response()->json([
            'success' => true,
            'code' => 'success',
            'title' => 'Congratulations',
            'message' => 'Presenter updated successfully.'
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
                'message' => 'Presenter permanently deleted successfully.'
            ]);
        } else {
            $user->delete();
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Presenter moved to trash successfully.'
            ]);
        }
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        if($user->trashed()) {
            $user->restore();
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Presenter user restored successfully.'
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
