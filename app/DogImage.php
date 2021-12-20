<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DogImage extends Model
{
    protected $fillable = [
        'image_url',
        'breed_name',
    ];
}
