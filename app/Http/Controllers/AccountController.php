<?php

namespace App\Http\Controllers;

use App\MembershipLevel;
use App\User;
use App\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helper;
use Newsletter;


class AccountController extends Controller
{
    public function index()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['name' => "Account Settings"]];

        if ( auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin')) {
            $pageConfigs = [
                'pageHeader' => false,
            ];
        } else {
            $pageConfigs = [
                'pageHeader' => false,
                'mainLayoutType' => 'subscriber',
                'showMenu' => false
            ];
        }

        $userMeta = [];
        $userMetaRecords = UserMeta::where('user_id', auth()->user()->id)->get();
        foreach ($userMetaRecords as $meta) {
            $userMeta[$meta->meta_key] = $meta->meta_value;
        }

        $userAddress = auth()->user()->address('institution_address');

        return view('account/index', [
            'breadcrumbs' => $breadcrumbs,
            'pageConfigs' => $pageConfigs,
            'userMeta' => $userMeta,
            'userAddress' => $userAddress
        ]);
    }

    public function changePassword()
    {
        $user = auth()->user();

        request()->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Your current password is incorrect.');
                    }
                },
            ],
            'password' => 'required|min:6|confirmed',
        ]);

        $user->password = bcrypt(request()->get('password'));
        $user->save();

        return response()->json([
            'success' => true,
            'code' => 'success',
            'message' => 'Password changed successfully',
            'title' => 'Congatulations!'
        ]);
    }

    public function changeEmail(Request $request)
    {
        $user = auth()->user();

        request()->validate([
            'email' => [
                'required',
                'email',
                'confirmed',
                'unique:users,email,' . $user->id
            ]
        ]);

        $user->email_verified_at = null;
        $user->email = request()->get('email');
        $user->save();

        // Send Email verification
        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'code' => 'success',
            'title' => 'Congratulations!',
            'message' => 'Your email address has changed. Please verify your new email address.'
        ]);
    }

    public function subscribeToNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $user = auth()->user();
            Newsletter::subscribeOrUpdate( $request->email, ['FNAME'=> $user->name, 'LNAME'=> $user->last_name] );
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations!',
                'message' => 'You have subscribed to ' . config('app.name') . ' News & Events successfully.'
            ]);
        } catch(\Exception $e) {
            logger("Mailchimp API Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'title' => 'Oops!',
                'message' => 'Something went wrong.'
            ]);
        }
    }

    public function updateBasicInformation(Request $request)
    {
        $user = auth()->user();
        request()->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'timezone' => 'required'
        ]);

        $user->name = request()->get('name');
        $user->last_name = request()->get('last_name');
        $user->timezone = request()->get('timezone');
        $user->can_contact = $request->has('can_contact') ? true : false;
        $user->save();

        $user->updateOrCreateMeta(USER::EMAIL_NOTIFICATION, request()->has('email_notification') ? true : false);
        $user->updateOrCreateMeta("areas_of_interest", json_encode($request->areas_of_interest ?? []));
        $user->updateOrCreateMeta(User::SUBSCRIBE_NEWSLETTER, $request->has('newsletter') ? 1 : 0);
        $user->updateOrCreateMeta(User::EMAIL_NOTIFICATION, $request->has('email_notification') ? 1 : 0);

        try {
            if($request->has('newsletter')) {
                Newsletter::subscribeOrUpdate( $user->email, ['FNAME'=> $user->name, 'LNAME'=> $user->last_name] );
            } else {
                Newsletter::unsubscribe($user->email);
            }
        } catch(\Exception $e) {
            logger("Mailchimp API Error: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'code' => 'success',
            'title' => 'Congratulations!',
            'message' => 'Changes saved successfully.'
        ]);
    }

    public function institutionInfo(Request $request)
    {
        $user = auth()->user();

        request()->validate([
            'institution_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'street_name' => 'required|max:500',
            'city' => 'required|max:255',
            'state' => 'required|max:255',
            'postal_code' => 'required|max:255',
            'country' => 'required|max:255',
        ]);

        $user->updateOrCreateMeta("institution_name", $request->institution_name);
        $user->updateOrCreateMeta("department", $request->department);

        // Address
        $user->updateOrCreateAddress("institution_address", [
            "street_name" => $request->street_name,
            "city" => $request->city,
            "state" => $request->state,
            "postal_code" => $request->postal_code,
            "country" => $request->country,
        ]);


        return response()->json([
            'success' => true,
            'code' => 'success',
            'title' => 'Congratulations!',
            'message' => 'Changes saved successfully.'
        ]);
    }

    public function setup(Request $request)
    {
        if (auth()->user()->subscribed('default')) {
            return redirect(route('home'));
        }
        switch (request()->method()) {
            case 'GET':
                $pageConfigs = [
                    'bodyClass' => "bg-full-screen-image",
                    'blankPage' => true
                ];

                $userMeta = [];
                $userMetaRecords = UserMeta::where('user_id', auth()->user()->id)->get();
                foreach ($userMetaRecords as $meta) {
                    $userMeta[$meta->meta_key] = $meta->meta_value;
                }

                $userAddress = auth()->user()->address('institution_address');

                $membershipLevels = MembershipLevel::where('parent_id', 0)->get();

                return view('/account/setup', [
                    'pageConfigs' => $pageConfigs,
                    'userMeta' => $userMeta,
                    'userAddress' => $userAddress,
                    'membershipLevels' => $membershipLevels
                ]);

                break;

            case 'POST':

                if (!auth()->user()->hasVerifiedEmail()) {
                    return response()->json([
                        'success' => false,
                        'code' => 'error',
                        'title' => 'Oops!',
                        'message' => 'Your email address is not verified.',
                    ]);
                }
                $request->validate([
                    'institution_name' => 'required|string|max:255'
                ]);

                $user = auth()->user();
                $user->can_contact = $request->has('can_contact') ? true : false;
                $user->save();

                $user->updateOrCreateMeta("institution_name", $request->institution_name);
                $user->updateOrCreateMeta("department", $request->department);
                $user->updateOrCreateMeta("areas_of_interest", json_encode($request->areas_of_interest ?? []));

                $user->updateOrCreateMeta("hear_about_us", request()->has('hear_us_other') ?  $request->hear_us_other : $request->hear_about_us);
                $user->updateOrCreateMeta(User::SUBSCRIBE_NEWSLETTER, $request->has('newsletter') ? 1 : 0);
                $user->updateOrCreateMeta(User::EMAIL_NOTIFICATION, $request->has('email_notification') ? 1 : 0);
                
                try {
                    if($request->has('newsletter')) {
                        Newsletter::subscribeOrUpdate( $user->email, ['FNAME'=> $user->name, 'LNAME'=> $user->last_name] );
                    } else {
                        Newsletter::unsubscribe($user->email);
                    }
                } catch(\Exception $e) {
                    logger("Mailchimp API Error " . $e->getMessage());
                }

                // Address
                $user->updateOrCreateAddress("institution_address", [
                    "street_name" => $request->street_name,
                    "city" => $request->city,
                    "state" => $request->state,
                    "postal_code" => $request->postal_code,
                    "country" => $request->country,
                ]);

                $membershipLevels = [];
                foreach (MembershipLevel::all()->toArray() as $record) {
                    $membershipLevels[$record['id']] = $record;
                }

                // Membership Level
                for ($i = 1; $i <= 3; $i++) {
                    $key = 'membership_level_' . $i;

                    UserMeta::where([
                        'meta_key' => $key,
                        'user_id' => $user->id
                    ])->delete();

                    $level = request()->get($key);
                    if (!empty($level)) {
                        $lastLevel = $level;
                        $value = $membershipLevels[$level];
                        if ($membershipLevels[$level]['required_textbox']) {
                            $value['other_value'] = request()->get('membership_input_' . $level);
                        }
                        $user->updateOrCreateMeta($key, json_encode($value));
                    }
                }

                // Check if eligible for discount
                $membershipLevel = MembershipLevel::find($lastLevel);

                $priceId = auth()->user()->getPriceId();

                if($priceId == false) {
                    return response()->json([
                        'success' => false,
                        'code' => 'error',
                        'title' => 'Oops!',
                        'message' => 'Please select your membership level.'
                    ]);
                }

                $checkout = auth()->user()->newSubscription(Helper::SUBSCRIPTION_DEFAULT, $priceId)
                        ->allowPromotionCodes()
                        ->checkout([
                            'success_url' => route('home'),
                            'cancel_url' => route('subscriber.setup'),
                            'billing_address_collection' => 'required',
                        ]);


                return view('inc/stripe/btn', compact('checkout'));

                break;
        }
    }

    public function membershipLevels($parentId)
    {
        return MembershipLevel::where('parent_id', $parentId)->get();
    }

    public function pendingAccount()
    {
        $pageConfigs = [
            'bodyClass' => "bg-full-screen-image",
            'blankPage' => true
        ];
        return view('account/pending', compact('pageConfigs'));
    }

    public function applyAsPresenter(Request $request)
    {
        switch ($request->method()) {
            case 'GET':
                $user = auth()->user();
                $pageConfigs = ['blankPage' => true, 'mainLayoutType' => 'subscriber', 'showMenu' => false];
                $userMeta = [];
                $userMetaRecords = UserMeta::where('user_id', $user->id)->get();
                foreach ($userMetaRecords as $meta) {
                    $userMeta[$meta->meta_key] = $meta->meta_value;
                }
                $userAddress = $user->address('institution_address');
                return view('content.presenters.apply', compact('user', 'userAddress', 'userMeta', 'pageConfigs'));
                // return view('content.presenters.apply', [
                //     'user' => $user,
                //     'userAddress' => $userAddress,
                //     'userMeta' => $userMeta,
                //     'pageConfigs' => $pageConfigs,
                // ]);
                break;

            case 'POST':
                $request->validate([
                    'institution_name' => 'required|string|max:255',
                    'department' => 'required|string|max:255',
                    'about_presentation' => 'required|string',
                ]);

                $user = auth()->user();

                $user->updateOrCreateMeta("institution_name", $request->institution_name);
                $user->updateOrCreateMeta("department", $request->department);
                $user->updateOrCreateMeta("about_presentation", $request->about_presentation);
                $user->updateOrCreateMeta("presentation_keywords", json_encode($request->presentation_keywords ?? []));

                if ($request->has('is_published')) {
                    $user->updateOrCreateMeta("presentation_published_info", $request->presentation_published_info ?? "");
                }

                // Address
                $user->updateOrCreateAddress("institution_address", [
                    "street_name" => $request->street_name,
                    "city" => $request->city,
                    "state" => $request->state,
                    "postal_code" => $request->postal_code,
                    "country" => $request->country,
                ]);

                $user->assignRole('presenter');

                return redirect(route('user.account-pending'));

                break;
        }
    }
}
