<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use App\File;
use Illuminate\Support\Facades\Storage;
use App\Service\Agora\RtcTokenBuilder;
use DateTimeZone;
use DateTime;
use DateInterval;
use Spatie\CalendarLinks\Link;
use JamesMills\LaravelTimezone\Facades\Timezone;
use App\Keyword;
use App\User;
use Spatie\Activitylog\Traits\LogsActivity;

class Event extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
	protected static $logOnlyDirty = true;
	protected static $logFillable = true;
	protected $guarded = [];

    protected $dates = ['start_date_time'];

    const UPCOMING = 'publish';
    const ARCHIVE = 'finish';
    const DRAFT = 'pending';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function isReminderRequested()
    {
        $playlist = Playlist::where([
            'model_id' => $this->id,
            'model_type' => 'event',
            'playlist_type' => 'reminder',
            'user_id' => auth()->user()->id
        ])->first();
        return $playlist ? true : false;
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class);
    }

    public function eventAdmin()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function statusHtml()
    {
        $text = config('setting.event_status')[$this->status];
        switch ($this->status) {
            case 'pending':
                $class = "danger";
                break;

            case 'publish':
                $class = "primary";
                break;

            case 'finish':
                $class = "success";
                break;
        }

        return "<span class='badge badge-pill badge-$class'>$text</span>";
    }

    public function logo()
    {
        return $this->belongsTo('App\File', 'logo_id');
    }

    public function getImage()
    {
        $randomNumber = rand(1, 39);
        return !is_null($this->logo) ? url(Storage::url($this->logo->location)) : url('images/banner/placeholder.jpg');
    }


    public function addOrUpdateLogo($file)
    {
        try {
            $path = $file->store('public/event_images');
            $fileName = $file->getClientOriginalName();
            $extension = $file->extension();

            if (!is_null($this->logo_id)) {
                $fileRecord = File::findOrFail($this->logo_id);
                $fileToDelete = $fileRecord->location;
            } else {
                $fileRecord = new File;
                $fileToDelete = "";
            }

            $fileRecord->title = $fileName;
            $fileRecord->location = $path;
            $fileRecord->extension = $extension;
            $fileRecord->save();

            $this->logo_id = $fileRecord->id;
            $this->save();

            if (!empty($fileToDelete)) {
                // Not deleting file as it may be used for video.
                // Storage::delete($fileToDelete);
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Event Image Upload Error: [$this->title]: " . $e->getMessage());
            return false;
        }
    }

    public function generateToken($uid = 0, $user = 'host')
    {
        $appID = config('services.agora.appId');
        $appCertificate = config('services.agora.certificate');
        $channelName = $this->channel_name;
        // $uid = 0;
        $uidStr = "2882341273";
        
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 86400;
        $currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        return RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);
    }

    public function calendarLinks()
    {
        $from = Timezone::convertToLocal($this->start_date_time, 'Y-m-d H:i');
        
        $from = DateTime::createFromFormat('Y-m-d H:i', $from);

        // Adding 4 hours
        $to = $from->add(new DateInterval("PT4H"));

        // $to = $from->addMinutes(20);
        $link = Link::create($this->title, $from, $to);

        return [
            'ics' => [
                'link' => $link->ics(),
                'label' => 'Apple',
            ],
            'google' => [
                'link' => $link->google(),
                'label' => 'Google',
            ],
            'outlook' => [
                'link' => $link->webOutlook(),
                'label' => 'Web Outlook',
            ],
            'yahoo' => [
                'link' => $link->yahoo(),
                'label' => 'Yahoo',
            ],
        ];
        
    }

    public function createVideo($recordingPath)
    {
        $video = Video::create([
            'event_id' => $this->id,
            'logo_id' => $this->logo_id,
            'status' => 'publish',
            'path' => $recordingPath,
            'user_id' => $this->user_id,
            'title' => $this->title
        ]);

        $video->keywords()->sync($this->keywords()->pluck('keyword_id')->toArray() ?? []);
    }
}
