<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'file_name', 'url', 'mime_type', 'width', 'height', 'size', 'alt_text',
    ];
}
