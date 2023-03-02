<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;
use App\Event;
use App\Helpers\Helper;
use JamesMills\LaravelTimezone\Facades\Timezone;
use Storage;
use App\Keyword;
use Illuminate\Support\Facades\View;
use App\File;

class VideoController extends Controller
{

    public function __construct()
    {
        View::share('keywords', Keyword::all());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {

            if (auth()->user()->hasRole('super_admin')) {
                $videos = Video::with('user')->where('videos.id', '<>', 0);
                if (request()->get('view') == 'trash') {
                    $videos->onlyTrashed();
                }
            } else {
               
                if (request()->get('view') == 'trash') {
                    
                    $videos = Video::onlyTrashed()->leftJoin('events', 'events.id', '=', 'videos.event_id')
                        ->leftJoin('event_user', 'event_user.event_id', '=', 'events.id')
                        ->where(function($query) {
                            $query->where('event_user.user_id', auth()->user()->id);
                            $query->orWhere('videos.user_id', auth()->user()->id);
                        })
                        ->select('videos.*');
                } else {
                    $videos = Video::leftJoin('events', 'events.id', '=', 'videos.event_id')
                    ->leftJoin('event_user', 'event_user.event_id', '=', 'events.id')
                    ->where(function($query) {
                        $query->where('event_user.user_id', auth()->user()->id);
                        $query->orWhere('videos.user_id', auth()->user()->id);
                    })
                    ->select('videos.*');
                }
            }


            if (request()->get('video_status') != "") {
                $videos->where('videos.status', request()->get('video_status'));
            }

            return datatables()->of($videos)
                ->editColumn('title', function ($video) {
                    $title = Helper::tooltip($video->title, 50);
                    $videoLink = route('subscriber.videos.show', $video->id);
                    return "<a href='$videoLink' target='blank'>$title <i data-feather='external-link'></i> </a>";
                })
                ->editColumn('path', function($video) {
                    $video->path = config('setting.s3_url') . $video->path;
                    return "<a data-title='$video->title' class='cursor-pointer text-primary view-video' data-toggle='modal' data-target='#dynamic-modal' data-href='$video->path'>View</a>";
                })
                ->editColumn('created_at', function ($video) {
                    return Timezone::convertToLocal($video->created_at, 'd M Y');
                })
                ->editColumn('status', function ($video) {
                    return $video->statusHtml();
                })
                ->editColumn('start_date_time', function ($video) {
                    return Timezone::convertToLocal($video->start_date_time, 'd M Y h:i A');
                })
                ->addColumn('users', function ($video) {
                    if (isset($video->user->id)) {
                        if (auth()->user()->id == $video->user->id) {
                            return "Me";
                        } else {
                            $user = $video->user;
                            return $video->user->fullName() . "<br>" . "<a href='mailto:{$user->email}'>{$user->email}</a>";
                        }
                    } else {
                        return "N/A";
                    }
                })
                ->addColumn('last_name', function($video) {
                    if(!isset($video->user->id)) { return "N/A"; }
                    return $video->user->last_name;
                })
                ->addColumn('email', function($video) {
                    if(!isset($video->user->id)) { return "N/A"; }
                    return $video->user->email;
                })
                ->addColumn('action', function ($video) {
                    
                    $deleteUrl = route('videos.destroy', $video->id);

                    if ($video->trashed()) {
                        $url = route('videos.restore', $video->id);

                        return "
                            <button 
                                class='btn btn-primary restore-record btn-icon btn-sm'
                                data-title='Restore'
                                data-url='$url'
                            ><i data-feather='refresh-cw'></i></button>

                            <button 
                                data-toggle='tooltip'
                                data-title='Delete Parmanently'
                                class='btn btn-danger delete-record btn-icon btn-sm'
                                data-url='$deleteUrl'
                            ><i data-feather='trash'></i></button>
                        ";
                    } else {
                        $url = route('videos.edit', $video->id);

                        return "
                            <button 
                                class='btn btn-primary get-content btn-icon btn-sm'
                                data-title='Edit Video'
                                data-url='$url'
                            ><i data-feather='edit-3'></i></button>

                            <button 
                                data-toggle='tooltip'
                                data-title='Move to Trash'
                                class='btn btn-danger delete-record btn-icon btn-sm'
                                data-url='$deleteUrl'
                            ><i data-feather='trash'></i></button>

                        ";
                    }
                })
                ->rawColumns(['action', 'path', 'is_active', 'users', 'status', 'title'])
                ->make(true);
        } else {

            $pageConfigs = ['pageHeader' => true];
            $breadcrumbs = [
                ['link' => "/", 'name' => "Dashboard"],
                ['name' => "Videos"]
            ];
            return view('/content/videos/manage/index', compact('breadcrumbs'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/content/videos/manage/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'video' => 'mimes:mp4,jpg,jpeg|nullable',
            'status' => 'required|in:publish,pending'
        ]);

        $path = Storage::disk('s3')->put('videos', $request->video);

        $video = Video::create([
            'title' => $request->title,
            'status' => $request->status,
            'user_id' => auth()->user()->id,
            'path' => $path
        ]);

        foreach($request->keywords as $keywordId) {
            if(!is_numeric($keywordId)) {
                $keyword = Keyword::create([
                    'keyword_name' => $keywordId,
                ]);
                $keywordId = $keyword->id;
            }

            $keywordIds[] = $keywordId;
        }

        $video->keywords()->sync($keywordIds ?? []);

        if ($request->hasFile('logo')) {
            $video->addOrUpdateLogo($request->file('logo'));
        }

        return response()->json([
            'success' => true,
            'code' => 'success',
            'message' => 'File uploaded successfully.',
            'title' => 'Congratulations',
            'path' => $path
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        return view('/content/videos/manage/edit', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|max:255',
            'status' => 'required|in:publish,pending'
        ]);

        $video->title = $request->title;
        $video->status = $request->status;
        $video->save();

        $keywordIds = [];
        
        foreach($request->keywords as $keywordId) {
            if(!is_numeric($keywordId)) {
                $keyword = Keyword::create([
                    'keyword_name' => $keywordId,
                ]);
                $keywordId = $keyword->id;
            }

            $keywordIds[] = $keywordId;
        }

        $video->keywords()->sync($keywordIds ?? []);

        if ($request->hasFile('logo')) {
            $video->addOrUpdateLogo($request->file('logo'));
        }

        return response()->json([
            'success' => true,
            'code' => 'success',
            'title' => 'Congratulations!',
            'message' => 'Video information updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        // $record = Event::withTrashed()->findOrFail($id);
        $record = $video;
        if ($record->trashed()) {
            if ($record->path) {
                $pathLocation = $record->path;
            } else {
                $pathLocation = "";
            }

            $record->forceDelete();

            if (!empty($pathLocation)) {
                // Storage::disk('s3')->delete($pathLocation);
            }

            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Video permanently deleted successfully.'
            ]);
        } else {
            $record->delete();
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Video moved to trash successfully.'
            ]);
        }
    }

    public function restore($id)
    {
        $record = Video::withTrashed()->findOrFail($id);
        if ($record->trashed()) {
            $record->restore();
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Video restored successfully.'
            ]);
        } else {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
    }
}
