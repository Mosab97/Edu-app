<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $guarded = [];
    public function getDemonstrationVideoAttribute($value)
    {
        return is_null($value) ? defaultUserVideo() : asset($value);
    }

}
