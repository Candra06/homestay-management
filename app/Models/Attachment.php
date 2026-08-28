<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'attachments';
    protected $fillable = [
        'reff_feature',
        'file_url',
        'file_name',
        'original_name',
        'mime_type',
        'reff_id',
        'file_size',
    ];
}
