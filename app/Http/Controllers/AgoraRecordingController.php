<?php

namespace App\Http\Controllers;

use App\Service\Agora\Recording;
use Illuminate\Http\Request;
use App\Event;
use App\EventRecording;
use Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AgoraRecordingController extends Controller
{
    public function startRecording($id)
    {

        $event = Event::findOrFail( $id );

        $event->token = $event->generateToken();
        
        $uid = "123429836";

        $uid = random_int(100000000, 999999999);
        $uid = (string)$uid;

        $recording = new Recording();
        
        // 1. Acquire Resource ID
        $rid = $recording->acquire($event->channel_name, $uid);
        
        // 2. Start Recording
        $r = $recording->start($event->channel_name, $uid, $event->token, $rid);
        $rid = $r['resourceId'] ?? "";
        $sid = $r['sid'] ?? "";
        if( !empty($rid) && !empty($sid) ) {
            // Save ResourceID, SID to database for finding recorded files later.
            $eventRecording = EventRecording::create([
                'event_id' => $event->id,
                'rid' => $r['resourceId'],
                'sid' => $r['sid'],
                'uid' => $uid,
                'status' => 'start',
            ]);
            return response()->json([
                'success' => true,
                'body' => $r
            ]);
        } else {
            
            return response()->json([
                'success' => false,
                'message' => $r
            ]);
        }
    }

    public function status()
    {
        $recording = new Recording();
        $response = $recording->status( request()->get('rid'), request()->get('sid') );

        $eventRecording = EventRecording::where([
            'rid' => request()->get('rid'),
            'sid' => request()->get('sid'),
        ])->first();

        $eventRecording->file_lists = json_encode($response['serverResponse']);
        $eventRecording->status = 'progress';
        $eventRecording->save();

        return response()->json($response);
    }

    public function stopRecording($id)
    {
        $event = Event::find($id);

        $eventRecording = EventRecording::where([
            'rid' => request()->get('resourceId'),
            'sid' => request()->get('sid'),
            'event_id' => $event->id
        ])->first();
        
        $recording = new Recording();
        
        $stopResponse = $recording->stop(
            $event->channel_name, 
            $eventRecording->uid, 
            $eventRecording->rid, 
            $eventRecording->sid
        );

        $rid = $stopResponse['resourceId'] ?? "";
        $sid = $stopResponse['sid'] ?? "";
        if(!empty($rid) && !empty($sid) && isset($stopResponse['serverResponse'])) {
            $eventRecording->file_lists = json_encode($stopResponse['serverResponse'] ?? null);
            $eventRecording->status = 'stop';
            $eventRecording->save();

            // Run save recording command.
            \Artisan::call('event:save-recordings');
        }
        return response()->json($stopResponse);
    }

    public function saveRecordings()
    {
        \Artisan::call('event:save-recordings');
        echo \Artisan::output();
    }

}
