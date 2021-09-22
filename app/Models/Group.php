<?php

namespace App\Models;

use App\Traits\UploadMedia;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use UploadMedia;

    protected $guarded = [];
    public const manager_route = 'groups';

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
        return $this->belongsTo(User::class,'teacher_id');
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
