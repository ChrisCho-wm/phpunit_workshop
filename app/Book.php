<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'bib_key',
        'info_url',
        'preview',
        'preview_url',
    ];
}
