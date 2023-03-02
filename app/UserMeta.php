<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    use HasFactory;
    
    protected $table = 'users_meta';
    protected $fillable = ['meta_key', 'meta_value', 'user_id'];

}

