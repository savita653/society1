<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\EventRecording;
use Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Event;

class SaveEventRecordings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:save-recordings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find recordings for Events and save to the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $s3Files = Storage::disk('s3')->files( config('setting.event_recording_folder_name') );

        $eventRecordings = EventRecording::where('status', '<>', 'completed')->get();

        foreach($eventRecordings as $eventRecording) {
           $sid = $eventRecording->sid;
           $foundFiles = Arr::where($s3Files, function($value, $key) use ($sid) {
                return Str::startsWith( basename($value), $sid) && pathinfo($value, PATHINFO_EXTENSION) == "mp4";
           });
           if(!empty($foundFiles)) {
               $foundFiles = array_values($foundFiles);
               $eventRecording->status = 'completed';
               $eventRecording->save();

               // Create Video
               $recordingPath = $foundFiles[0];
               $eventRecording->event->createVideo($recordingPath);
               $eventRecording->event->status = Event::ARCHIVE;
               $eventRecording->event->save();
               
               $this->info("Saved $sid : $recordingPath");
           }
        }

        $this->info('Saving Recordings Finished.');
    }
}
