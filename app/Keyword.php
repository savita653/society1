<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Event;
use App\Video;
use Spatie\Activitylog\Traits\LogsActivity;

class Keyword extends Model
{
    use HasFactory, LogsActivity;

	protected static $logOnlyDirty = true;
	protected static $logFillable = true;
	protected $guarded = [];
    
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function video()
    {
        return $this->belongsToMany(Video::class);
    }
}
