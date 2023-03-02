<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Event;

class LiveEventController extends Controller
{
    public function index(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        
        $pageConfigs = [
            'layoutWidth' => 'boxed',
            'pageHeader' => false,
            'showMenu' => false,
            'verticalMenuNavbarType' => 'hidden',
            'pageClass' => 'chat-application',
            'footerType' => 'hidden'
            // 'contentLayout' => "content-left-sidebar",
        ];

        if(auth()->user()->hasRole('presenter')) [
            $pageConfigs['mainLayoutType'] = 'subscriber'
        ];

        $event->token = $event->generateToken();
        return view('content/live/index', compact('event', 'pageConfigs'));
    }
}
