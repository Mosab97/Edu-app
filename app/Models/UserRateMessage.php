<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRateMessage extends Model
{
    protected $guarded = [];

    public function standard_rates()
    {
        return $this->hasMany(UserStandardRate::class,'parent_id');
    }
}
