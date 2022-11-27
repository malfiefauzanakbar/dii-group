<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $fillable = [
        'title', 'description', 'who_we_are', 'vision', 'mission', 'business_field', 'owner_profile', 'coorporate_values',        
    ];
}
