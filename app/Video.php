<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Video extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected static $logOnlyDirty = true;
	protected static $logFillable = true;
	protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class);
    }

    public function statusHtml()
    {
        $text = config('setting.video_status')[$this->status];
        switch ($this->status) {
            case 'pending':
            case 'draft':
                $class = "danger";
                break;

            case 'publish':
                $class = "primary";
                break;
        }

        return "<span class='badge badge-pill badge-$class'>$text</span>";
    }

    public function addOrUpdateLogo($file)
    {
        try {
            $path = $file->store('public/media_images');
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
                // Storage::delete($fileToDelete);
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Video Image Upload Error: [$this->title]: " . $e->getMessage());
            return false;
        }
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
}
