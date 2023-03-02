<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseResidence extends Model
{
    use HasFactory;

    protected $table = "house_residences";

    protected $fillable =[

        'owner',
        'user_id',
        'house_id',
        'resident',
        'total_member',
       
    ];
    
}
