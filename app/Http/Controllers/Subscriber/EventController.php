<?php

namespace App\Http\Controllers\Subscriber;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Event;
use Illuminate\Support\Facades\View;

class EventController extends Controller
{
    public $pageConfigs;

    public function __construct()
    {
        View::share('pageConfigs', [
            'showMenu' => false,
            'pageHeader' => false,
            'mainLayoutType' => 'subscriber'
        ]);
    }

    public function index()
    {
        if (request()->get('s')) {
            $events = Event::where('status', Event::UPCOMING)
                ->whereHas('keywords', function ($q) {
                    $q->where('keyword_name', 'like', "%" . request()->get('s') . "%");
                });
        } else {
            $events = Event::where('status', Event::UPCOMING);
        }

        $events->orderBy('start_date_time', 'asc');
        
        $events = $events->paginate(9);

        return view('content/subscriber/events/index', compact('events'));
    }

    public function show(Request $request, $id)
    {
        $pageConfigs = [
            'pageHeader' => false,
            'showMenu' => false,
            'verticalMenuNavbarType' => 'hidden',
            'pageClass' => 'chat-application',
            'mainLayoutType' => 'subscriber'
        ];

        $event = Event::findOrFail($id);
       
        if( auth()->user()->hasRole('super_admin') || auth()->user()->id == $event->user_id ) {} else {
            if($event->status != Event::UPCOMING) {
                abort(403, "Access Denied");
            }
        }

        $event->token = $event->generateToken();

        $keywords = $event->keywords()->pluck('keyword_name')->toArray();
        $events = Event::where('status', Event::UPCOMING)
                ->whereHas('keywords', function ($q) use ($keywords) {
                    foreach($keywords as $keyword) {
                        $q->orWhere('keyword_name', 'like', "%" . $keyword . "%");
                    }
                })
                ->where('id', '<>', $event->id)
                ->orderBy('start_date_time', 'asc')->limit(3)->get();

        return view('content/subscriber/events/show', compact('event', 'events', 'pageConfigs'));
    }
}
