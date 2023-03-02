<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/setup';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
		], [
			'email.unique' => 'There is an account associated with this email address. <a href="' . route('login') . '?email=' . $data['email'] . '">Click Here</a> to sign in.<br>After Sign in, you will get the option to become a subscriber.'
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	protected function create(array $data)
	{

		return User::create([
			'name' => $data['name'],
			'last_name' => $data['last_name'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
		]);
	}

	// Register
	public function showRegistrationForm()
	{
		$pageConfigs = ['blankPage' => true];

		return view('/auth/register', [
			'pageConfigs' => $pageConfigs
		]);
	}

	public function showRegistrationFormPresenter()
	{
		$pageConfigs = ['blankPage' => true];

		return view('/auth/become-a-presenter', [
			'pageConfigs' => $pageConfigs
		]);
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
	 */
	public function registerPresenter(Request $request)
	{
		$request->validate([
			'name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:8'],
			'institution_name' => 'required|string|max:255',
			'department' => 'required|string|max:255',
			'about_presentation' => 'required|string',
		], [
			'email.unique' => 'There is an account associated with this email address. <a href="' . route('login') . '?email=' . $request->email . '">Click Here</a> to sign in.'
		]);

		$user = new User;
		$user->name = $request->name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->password = Hash::make($request->password);
		$user->save();

		event(new Registered($user));

		$user->updateOrCreateMeta("institution_name", $request->institution_name);
		$user->updateOrCreateMeta("department", $request->department);
		$user->updateOrCreateMeta("about_presentation", $request->about_presentation);
		$user->updateOrCreateMeta("presentation_keywords", json_encode($request->presentation_keywords ?? []));
		
		if($request->has('is_published')) {
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

		$user->syncRoles(['presenter']);

		$this->guard()->login($user);

		return redirect(route('home'));

		// return $request->wantsJson()
		// 	? new JsonResponse([], 201)
		// 	: redirect($this->redirectPath());
	}

	/**
	 * The user has been registered.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  mixed  $user
	 * @return mixed
	 */
	protected function registered(Request $request, $user)
	{
		$user->syncRoles(['subscriber']);
		
	}
}
