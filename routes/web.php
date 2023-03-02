<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgoraRecordingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CropImageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LiveEventController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\PresenterController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Presenter\EventController as PresenterEventController;
use App\Http\Controllers\Subscriber\EventController as SubscriberEventController;
use App\Http\Controllers\Subscriber\VideoController as SubscriberVideoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\ProductController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/version', function() {
	$results = DB::select( DB::raw('SHOW VARIABLES LIKE "%version%"') );
	foreach($results as $result) {
		echo '<p><strong>' . $result->Variable_name . ':</strong> ' . $result->Value . '</p>';
	}
});



/**
 * Guest Routes
 */
Auth::routes(['verify' => true]);

// Membership level
Route::get('/membership-level/{parentId}', [AccountController::class, 'membershipLevels'])->name('membership-level');

// Become a Presenter
Route::get('become-a-presenter', [RegisterController::class, 'showRegistrationFormPresenter'])->name('become-a-presenter');
Route::post('become-a-presenter', [RegisterController::class, 'registerPresenter'])->name('become-a-presenter');

/**
 * Auth Route
 */

// Route::group(['middleware' => ['auth', 'is_active']], function() {
	/**
	 * Account - Index
	 */
	Route::get('/account', [AccountController::class, 'index'])->name('account');

	// Change Password
	Route::post('/change-password', [AccountController::class, 'changePassword'])->name('changePassword');

	// Change Email
	Route::post('/change-email', [AccountController::class, 'changeEmail'])->name('changeEmail');

	// Update Basic Information
	Route::post('/update-basic-info', [AccountController::class, 'updateBasicInformation'])->name('updateBasicInfo');
	Route::post('/institution-info', [AccountController::class, 'institutionInfo'])->name('institutionInfo');

	// Crop & Image Upload
	Route::post('crop-image/{guard}', [CropImageController::class, 'cropImage'])->name('crop_image');
	Route::post('upload-image/{guard}', [CropImageController::class, 'uploadImage'])->name('upload_image');

	/**
	 * Subscription - Required
	 */
	Route::get('/subscription-required', function() {
		if ( auth()->user()->subscribed('default') ) {
			return redirect('/');
		}
		$pageConfigs = [
			'pageHeader' => false,
			'showMenu' => false,
			'mainLayoutType' => 'subscriber'
		];
		return view('content.subscriber.subscription', compact('pageConfigs'));
	})->name('subscription-required');

	/**
	 * Subscribe to newsletter
	 */
	Route::post('subscribe-to-newsletter', [AccountController::class, 'subscribeToNewsletter'])->name('subscribe-newsletter');
// });


/**
 * Super Admin, Admin, Presenter Routes - Common routes
 */
Route::group(['prefix' => 'app', 'middleware' => ['auth', 'is_active', 'role:super_admin|admin|presenter']], function () {
	Route::get('live/event/{id}', [LiveEventController::class, 'index'])->name('live.event.index');
});

/**
 * Super Admin & Admin Routes - Common routes
 */
Route::group(['prefix' => 'app', 'middleware' => ['auth', 'is_active', 'role:super_admin|admin']], function () {
	// Events Section
	Route::get('manage/event/{id}/restore', [EventController::class, 'restore'])->name('events.restore');
	Route::resource('manage/events', EventController::class);

	// Videos Section
	Route::get('manage/video/{id}/restore', [VideoController::class, 'restore'])->name('videos.restore');
	Route::resource('manage/videos', VideoController::class);
});

/**
 * Super Admin, Admin, Presenter Routes - Common routes
 */
Route::group(['prefix' => 'app', 'middleware' => ['auth', 'is_active', 'role:super_admin|admin|presenter']], function () {
	// Agora Cloud Recording
	Route::post('event-recording/{id}/agora/start/call', [AgoraRecordingController::class, 'startRecording'])->name('agora.recording.start-call');
	Route::post('event-recording/{id}/agora/stop/call', [AgoraRecordingController::class, 'stopRecording'])->name('agora.recording.stop-call');
	Route::get('recording-status', [AgoraRecordingController::class, 'status'])->name('agora.recording-status');
});


/**
 * Super Admin Routes
 */
Route::group(['prefix' => 'super-admin', 'middleware' => ['auth', 'role:super_admin']], function() {
	Route::get('/', [DashboardController::class, 'superAdmin'])->name('super-admin.home');

	// Admins section
	Route::get('admin/{id}/restore', [AdminController::class, 'restore'])->name('admins.restore');
	Route::resource('admins', AdminController::class);

	// Presenters Section
	Route::get('presenter/{id}/restore', [PresenterController::class, 'restore'])->name('presenters.restore');
	Route::resource('presenters', PresenterController::class);

	// Subscriber Section
	Route::get('user/{id}/restore', [SubscriberController::class, 'restore'])->name('users.restore');
	Route::resource('users', SubscriberController::class);

	// User Activites section
	Route::get('user-activities', [ActivityController::class, 'index'])->name('super-admin.user_activities');
	Route::get('user-activities/show/{id}', [ActivityController::class, 'show'])->name('super-admin.user_activities.show');

	Route::get('/users-list', [UserController:: class, 'getUsers'])->name('users.get');
});


/**
 * Admin Routes
 */
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'is_active', 'role:admin'], 'name' => 'admin'], function() {
	Route::get('/', [EventController::class, 'index'])->name('home');
});


/**
 * Presenter Routes
 */
Route::group(['middleware' => ['auth', 'is_approved', 'role:presenter'], 'prefix' => 'presenter'], function () {
	Route::get('events', [PresenterEventController::class, 'index'])->name('presenter.events.index');
	Route::get('event/{id}', [PresenterEventController::class, 'show'])->name('presenter.events.show');
});


/**
 * Subscriber Route - Inactive
 */
Route::group([ 'middleware' => ['auth', 'verified', 'role:super_admin|subscriber|presenter|admin'] ], function() {
	/**
	 * Setup Subscription
	 */
	Route::get('/setup', [AccountController::class, 'setup'])->name('subscriber.setup');
	Route::post('/setup', [AccountController::class, 'setup'])->name('subscriber.setup');
	
	/**
	 * Apply for Presenter - from Subscriber Dashboard
	 */   
	Route::get('apply-as-presenter', [AccountController::class, 'applyAsPresenter'])->name('apply.presenter');
	Route::post('apply-as-presenter', [AccountController::class, 'applyAsPresenter'])->name('apply.presenter');

	/**
	 * Account pending - Presenter
	 */
	Route::get('/account-pending', [AccountController::class, 'pendingAccount'])->name('user.account-pending');
});



/**
 * Subscriber - Active
 * Super Admin can also access 
 */
Route::group(['middleware' => ['auth', 'verified', 'is_active', 'subscribed', 'role:super_admin|admin|subscriber|presenter' ]], function() {
	/**
	 * Home Page
	 */
	Route::get('/', [DashboardController::class, 'index'])->name('home');

	// Events
	Route::get('/events', [SubscriberEventController::class, 'index'])->name('subscriber.events.index');
	Route::get('/event/{id}', [SubscriberEventController::class, 'show'])->name('subscriber.events.show');

	// Videos
	Route::get('/videos', [SubscriberVideoController::class, 'index'])->name('subscriber.videos.index');
	Route::get('/video/{id}', [SubscriberVideoController::class, 'show'])->name('subscriber.videos.show');

	// Playlist - handles Request Reminders
	Route::resource('/playlist', PlaylistController::class);
});

 	

//users

Route::get('users', [UserController::class, 'index'])->name('user.index');

Route::get('users/create', [UserController::class, 'create'])->name('user.create');
Route::post('users/store', [UserController::class, 'store'])->name('user.store');

Route::get('users/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
Route::post('users/update/{user}', [UserController::class, 'update'])->name('user.update');

Route::delete('users/{user}', [UserController::class, 'delete'])->name('user.delete');

Route::post('login/check',[UserController::class, 'login'])->name('login/check');
Route::get('logout',[UserController::class, 'logout']);


//societies routes ..

Route::get('societies', [SocietyController::class, 'index'])->name('society.index');

Route::get('societies/create', [SocietyController::class, 'create'])->name('society.create');
Route::post('society/store', [SocietyController::class, 'store'])->name('society.store');

Route::get('societies/{society}/edit', [SocietyController::class, 'edit'])->name('society.edit');
Route::post('societies/update/{society:id}', [SocietyController::class, 'update'])->name('society.update');

Route::delete('societies/delete/{society}', [SocietyController::class, 'delete'])->name('society.delete');

//houses

Route::get('houses', [HouseController::class, 'show'])->name('society.house.show');

Route::get('societies/{society}/houses', [HouseController::class, 'index'])->name('society.house.index');

Route::get('societies/{society}/houses/create', [HouseController::class, 'create'])->name('society.house.create');
Route::post('societies/{society}/houses/store', [HouseController::class, 'store'])->name('society.house.store');

Route::get('societies/{society}/houses/{house}/edit', [HouseController::class, 'edit'])->name('society.house.edit');
Route::post('societies/{society}/houses/{house}/update', [HouseController::class, 'update'])->name('society.house.update');

Route::delete('societies/houses/{house}/delete', [HouseController::class, 'delete'])->name('society.house.delete');

























































Route::get('/home-v1', function() {
	$pageConfigs = [
		'showMenu' => false
	];

	return view('layouts/subscriberLayout', compact('pageConfigs'));
});