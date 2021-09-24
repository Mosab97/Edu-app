<?php

namespace App\Models;

use App\Http\Resources\Api\v1\Teacher\LessonResource;
use App\Traits\UploadMedia;
use Illuminate\Database\Eloquent\Model;
use ZipStream\File;

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

   public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function files()
    {
        return $this->hasMany(GroupFile::class, 'group_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function age()
    {
        return $this->belongsTo(Age::class);
    }

    public function scopeCheckStudent($query, User $student)
    {
        $query->whereHas('students', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        });
    }

    protected $casts = [
        'gender' => 'integer'
    ];


}
