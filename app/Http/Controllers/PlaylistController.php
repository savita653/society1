<?php

namespace App\Http\Controllers;

use App\Event;
use App\Notifications\EventReminder;
use App\Playlist;
use App\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JamesMills\LaravelTimezone\Facades\Timezone;
use Thomasjohnkane\Snooze\Serializer;
use Thomasjohnkane\Snooze\Models\ScheduledNotification;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        request()->validate([
            'model_type' => "bail|required|in:event,video",
            'model_id' => "bail|required|numeric|exists:{$request->model_type}s,id",
            'type' => "bail|required|in:reminder"
        ]);
        
        
        $playlist = Playlist::where([
            'model_type' => $request->model_type,
            'model_id' => $request->model_id,
            'user_id' => auth()->user()->id,
            'playlist_type' => $request->type
        ])->first();

        if($playlist) {
            $playlist->delete();
            
            if($request->model_type == 'event') {
                $event = Event::find($request->model_id);
                $record = ScheduledNotification::where(
                    'notification',
                     Serializer::create()->serializeNotifiable(new EventReminder($event))
                )->where([
                    'target_id' => auth()->user()->id,
                    'target_type' => 'App\User'
                ])->first();
                
                if($record) {
                    $record->delete();
                }
            }

            return response()->json([
                'success' => true,
                'newtext' => 'Request Reminder',
                'code' => 'success',
                'title' => 'Success!',
                'message' => 'Reminder Cancelled.'
            ]);
        } else {
            Playlist::create([
                'model_type' => $request->model_type,
                'model_id' => $request->model_id,
                'playlist_type' => $request->type,
                'user_id' => auth()->user()->id,
            ]);

            // Schedule Reminder
            try {
                if($request->model_type == 'event') {
                    $event = Event::find($request->model_id);
                    auth()->user()->notifyAt(
                        new EventReminder($event), 
                        Carbon::parse(Timezone::convertToLocal($event->start_date_time))
                    );
                }
            } catch(\Exception $e) {
                logger("Runtime Error:" . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'newtext' => 'Cancel Reminder',
                'code' => 'success',
                'title' => 'Success!',
                'message' => 'Reminder Requested.',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
