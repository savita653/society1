<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Spatie\Activitylog\Models\Activity;
use App\User;

class ActivityController extends Controller
{
	public function __construct()
	{
		config([
			'users' => User::orderBy('name', 'asc')->get(),
		]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$breadcrumbs = [
			['link' => "admin.home", 'name' => "Dashboard"], ['name' => 'User Activities']
		];

		$pageConfigs = [
			//'pageHeader' => false,
			//'contentLayout' => "content-left-sidebar",
		];

		$records = Activity::where('id', '!=', 0);
		if (request()->ajax()) {

			if (request()->has('user_id') && !empty(request()->get('user_id'))) {
				$records->where('activity_log.causer_id', request()->get('user_id'));
			}
			

			return Datatables::of($records)
				->editColumn('created_at', function ($row) {
					return date("d M Y h:i A", strtotime($row->created_at));
				})
				->editColumn('description', function ($row) {
					if (!is_null($row->subject_type) && !empty($row->subject_type)) {
						$arr = explode("\\", $row->subject_type);
						$on = $arr[count($arr) - 1];
						$on = ucfirst($on);
					} else {
						$on = '';
					}
					return ucfirst($row->description) . ' ' . $on;
				})
				->editColumn('ip_address', function ($row) {
					return $row->ip_address ?? "N/A";
				})
				->editColumn('properties', function($row) {
					if($row->properties == "[]") {
						return "N/A";
					}
					$link = route('super-admin.user_activities.show', $row->id);
					return "<button data-url='$link' data-toggle='modal' data-target='#dynamic-modal' class='view-log btn-icon btn btn-primary'>View</button>";
				})
				->addColumn('user', function ($row) {
					if( is_null($row->causer_id)) {
						return "N/A";
					}
					$user = User::where('id', $row->causer_id)->first();
					if($user) {
						return $user->fullName();
					}
					return "N/A";
				})
				->rawColumns(['user', 'properties'])
				->make(true);
		} else {
			return view('content.activities.index', compact('breadcrumbs', 'pageConfigs'));
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$record = Activity::findOrFail($id);
		dd( json_decode($record->properties, true) );
		return view('content.activities.show', compact('record'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
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
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
