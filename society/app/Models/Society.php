<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    use HasFactory;

    protected $fillable=[

        'name',
        'description',
        'parent_id',
        'image'
    ];

    public function scopeSociety($query)
    {
        
        return $query->where('parent_id', 0);
    }

    public function scopeHouse($query)
    {

        return $query->where('parent_id', '>', 0);
    }
}
