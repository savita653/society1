<?php

use App\Keyword;
use Illuminate\Http\Request;
use App\Event;
use App\Video;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
  'prefix' => 'auth'
], function () {
  Route::post('login', 'AuthController@login');
  Route::post('register', 'AuthController@register');

  Route::group([
    'middleware' => 'auth:api'
  ], function () {
    Route::get('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@user');
  });
});


Route::get('/email/exist', function () {
  $email = request()->get('email');
  if (App\User::where('email', $email)->count() == 0) {
    return response()->json(true);
  } else {
    $responseText = 'There is an account associated with this email address. <a href="' . route('login') . '?email=' . $email . '">Click Here</a> to sign in.<br>After Sign in, you can apply for a presenter.';

    return response()->json($responseText);
  }
})->name('api.email.unique');

Route::get('/search-keywords', function() {
  $kerywords = Keyword::pluck('keyword_name')->toArray();
  return response()->json($kerywords);
})->name('api.keywords');

Route::get('/events', function() {
  $events = Event::where('status', Event::UPCOMING)->limit(2)->get();
  $eventsArr = [];
  foreach($events as $event) {
    $temp['id'] = $event->id;
    $temp['title'] = $event->title;
    $temp['start_date_time'] = $event->start_date_time;
    $temp['image'] = $event->getImage();
    $eventsArr[] = $temp;
  }
  return response()->json($eventsArr);
});

Route::get('/videos', function() {
  $videos = Video::where('status', 'publish')->limit(4)->get();
  $videosArr = [];
  foreach($videos as $video) {
    $temp['id'] = $video->id;
    $temp['title'] = $video->title;
    $temp['image'] = $video->getImage();
    $videosArr[] = $temp;
  }
  return response()->json($videosArr);
});