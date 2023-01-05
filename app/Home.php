<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    protected $fillable = [
        'title', 'description', 'company_overview', 'image',
    ];
}
