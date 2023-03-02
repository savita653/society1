<?php

namespace App\Http\Controllers\Presenter;

use App\Http\Controllers\Controller;
use App\Event;
use App\Helpers\Helper;
use JamesMills\LaravelTimezone\Facades\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class EventController extends Controller
{
    public function __construct()
    {
        View::share('pageConfigs', [
            'showMenu' => false,
            'pageHeader' => true,
            'mainLayoutType' => 'subscriber'
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if(request()->ajax()) {

            $events = Event::join('users', 'users.id', '=', 'events.user_id')
                ->join('event_user', 'event_user.event_id', '=', 'events.id')
                ->where('event_user.user_id', auth()->user()->id)
                ->where('events.status', 'publish');

            if(request()->get('view') == 'calendar') {
                $events->select('events.*', 'start_date_time as start');
                return $events->get()->toJson();
            }

            $events->select('events.*', 'users.name as user_name', 'users.last_name as user_last_name', 'users.email as user_email');

            return datatables()->of($events)
                ->editColumn('title', function($event) {
                    return Helper::tooltip($event->title, 25);
                })
                ->editColumn('created_at', function($event) {
                    return Timezone::convertToLocal($event->created_at, 'd M Y');
                })
                ->editColumn('status', function($event) {
                    return $event->statusHtml();
                })
                ->editColumn('start_date_time', function($event) {
                    return Timezone::convertToLocal($event->start_date_time, 'd M Y h:i A');
                })
                ->addColumn('user_name', function($event) {
                    $user = $event->user_name . $event->user_last_name ?? "";
                    return $user . "<br>" . "<a href='mailto:{$event->User_email}'>{$event->user_email}</a>";
                })
                ->addColumn('action', function($event) {
                    $eventInfoUrl = route('presenter.events.show', $event->id);
                    $liveUrl = route('live.event.index', $event->id);
                    return "
                        <button 
                            class='btn btn-primary get-content btn-icon btn-sm'
                            data-title='Event Information'
                            data-url='$eventInfoUrl'
                        ><i data-feather='info'></i></button>
                        <a 
                            href='$liveUrl'
                            class='btn btn-primary  btn-sm'
                        > <i data-feather='play-circle'></i> Enter Live</a>
                    ";

                })
                ->rawColumns(['action', 'is_active', 'users', 'status', 'title', 'user_name'])
                ->make(true);
        } else {
            
            $breadcrumbs = [
                    ['link' => "/", 'name' => "Dashboard"], 
                    ['name' => "Events"]
                ];
            return view('/content/events/presenter/index', compact('breadcrumbs'));
        }

    }

    public function show($id)
    {
        $record = Event::findOrFail($id);
        $record->start_date_time = Timezone::convertToLocal($record->start_date_time, 'd M Y h:i A');
        return view('/content/events/manage/show', compact('record'));
    }
}
