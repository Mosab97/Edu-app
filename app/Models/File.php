<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded = [];

    public function getPathAttribute($value)
    {
        return asset($value);
        return is_null($value) ? defaultUserImage() : asset($value);
    }
}
