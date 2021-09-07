<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public const levels = [
        'level One' => 1,
        'level Two' => 2,
        'level Three' => 3,
    ];

    public function getImageAttribute($value)
    {
        return is_null($value) ? defaultUserImage() : asset($value);
    }

    public function students()
    {
        return $this->hasMany(StudentGroups::class);
    }

}
