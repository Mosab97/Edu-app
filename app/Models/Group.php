<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
//    public const levels = [
//        'level One' => 1,
//        'level Two' => 2,
//        'level Three' => 3,
//    ];
protected $guarded = [];

    public function getImageAttribute($value)
    {
        return is_null($value) ? defaultUserImage() : asset($value);
    }

    public function getVideoAttribute($value)
    {
        return is_null($value) ? defaultUserVideo() : asset($value);
    }


    public function students()
    {
        return $this->hasMany(StudentGroups::class);
    }



    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function age()
    {
        return $this->belongsTo(Age::class);
    }

    protected $casts = [
        'gender' => 'integer'
    ];


}
