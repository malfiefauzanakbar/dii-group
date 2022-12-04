<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $fillable = [
        'name', 'mobile_phone', 'email', 'type', 'message',
    ];
}
