<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'logo', 'name', 'field', 'description', 'address', 'map_link', 'phone_number', 'mobile_number', 'email', 'instagram', 'facebook',
        'twitter', 
    ];
}
