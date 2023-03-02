<?php

namespace App\Http\Controllers\Subscriber;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Video;
use Illuminate\Support\Facades\View;

class VideoController extends Controller
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
            $videos = Video::where('status', 'publish')
                ->whereHas('keywords', function ($q) {
                    $q->where('keyword_name', 'like', "%" . request()->get('s') . "%");
                });
        } else {
            $videos = Video::where('status', 'publish');
        }

        $videos = $videos->paginate(9);

        return view('content/subscriber/videos/index', compact('videos'));
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

        $video = Video::findOrFail($id);
        if( auth()->user()->hasRole('super_admin') || auth()->user()->id == $video->user_id ) {} else {
            if($video->status != 'publish') {
                abort(403, "Access Denied");
            }
        }
        $keywords = $video->keywords()->pluck('keyword_name')->toArray();

        $videos = Video::where('status', 'publish')
                ->whereHas('keywords', function ($q) use ($keywords) {
                    foreach($keywords as $keyword) {
                        $q->orWhere('keyword_name', 'like', "%" . $keyword . "%");
                    }
                })->limit(3)->get();

        return view('content/subscriber/videos/show', compact('video', 'videos', 'pageConfigs'));
    }
}
