<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStandardRate extends Model
{
    public function user_rate_message()
    {
        return $this->belongsTo(UserRateMessage::class, 'parent_id');
    }
}
