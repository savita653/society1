<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;
    
    protected $fillable =[

        'society_id',
        'name',
        'image',
        'capacity',
        'address',
        'house_no',
        'owner',
        'resident'
    ];

    public function scopeHouses($query, $id)
    {
        return $query->where('society_id', $id);
    }

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function user()
    {

        return $this->belongsto(User::class, 'house_id');
    }

    public function owners()
    {

        return $this->hasOne(User::class ,'id', 'owner');
    }

    public function residents()
    {

        return $this->hasOne(User::class ,'id', 'resident');
    }
}
