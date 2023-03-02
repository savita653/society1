<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    use HasFactory;
    
    protected $fillable=[

        'name',
        'description',
        'image'
    ];

    public function houses()
    {
        return $this->hasMany(House::class);
    }

}
