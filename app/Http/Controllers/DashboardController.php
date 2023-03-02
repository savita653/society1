<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Video;
use App\Event;

class DashboardController extends Controller
{	
	public function superAdmin()
	{
		$pageConfigs = ['pageHeader' => false];

		$stats['admins_count'] = User::role('admin')->count();
		$stats['presenters_count'] = User::role('presenter')->count();
		$stats['subscribers_count'] = User::role('subscriber')->count();
		$stats['events_count'] = Event::count();
		$stats['videos_count'] = Video::count();

		return view('/content/dashboard/super_admin', ['pageConfigs' => $pageConfigs, 'stats' => $stats]);
	}

	public function admin()
	{
		$pageConfigs = ['pageHeader' => false];

		$stats['admins_count'] = User::role('admin')->count();
		$stats['presenters_count'] = User::role('presenter')->count();
		$stats['subscribers_count'] = User::role('subscriber')->count();
		$stats['events_count'] = Event::count();
		$stats['videos_count'] = Video::count();

		return view('/content/dashboard/admin', ['pageConfigs' => $pageConfigs, 'stats' => $stats]);
	}

	public function presenter()
	{

	}


	public function index()
	{
		$pageConfigs = [
			'pageHeader' => false,
			'showMenu' => false,
			'mainLayoutType' => 'subscriber'
		];

		if (
			( auth()->user()->hasRole('presenter') && auth()->user()->hasRole('subscriber') )
			||
			auth()->user()->hasRole('subscriber') 
			||
			auth()->user()->hasRole('super_admin')
			||
			auth()->user()->hasRole('admin')
		) {

			$events = Event::where('status', 'publish')
				->orderBy('start_date_time', 'asc')
				// ->where('start_date_time', '>=', date('Y-m-d H:i:s'))
				->limit(6)->get();

			$videos = Video::where('status', 'publish')
				->limit(8)->get();
			
			return view('/content/dashboard/subscriber', [
				'pageConfigs' => $pageConfigs, 'mainLayoutType' => 'subscriber', 
				'events' => $events,
				'videos' => $videos
			]);
		} else if (auth()->user()->hasRole('presenter')) {

			return redirect(route('presenter.events.index'));
			
		} else {
			
			abort(403, "Access Denied");

		}
	}
}
