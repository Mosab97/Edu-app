<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded = [];
    public const files_number = [
        'one_file' => 'one_file',
        'multi_file' => 'multi_file',
    ];
    public const files_type = [
        'image' => 'image',
        'video' => 'video',
        'other' => 'other',
    ];

    public function getPathAttribute($value)
    {
        return asset($value);
        return is_null($value) ? defaultUserImage() : asset($value);
    }
}
