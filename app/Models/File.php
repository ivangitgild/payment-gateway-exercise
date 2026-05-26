<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    protected $fillable = [
        'disk',
        'bucket',
        'path',
        'filename',
        'original_name',
        'mime_type',
        'size',
    ];

}
