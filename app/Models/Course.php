<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}