<?php

namespace App\Models;

use App\Traits\UploadMedia;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use UploadMedia;

//    public const levels = [
//        'level One' => 1,
//        'level Two' => 2,
//        'level Three' => 3,
//    ];
    protected $guarded = [];
    public const manager_route = 'groups';

    public function getImageAttribute($value)
    {
        $image = $this->image_target;
        return is_null($image) ? defaultUserImage() : $image->path;
    }

    public function getVideoAttribute($value)
    {
        $video = $this->video_target;
        return is_null($video) ? defaultUserVideo() : $video->path;
    }


    public function students()
    {
        return $this->hasMany(StudentGroups::class);
    }

    public function video_target()
    {
        return $this->belongsTo(File::class, 'video_id');
    }

    public function image_target()
    {
        return $this->belongsTo(File::class, 'image_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'target_id')->where('target_type' . self::class);
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

    public function addImageMedia($image)
    {
        $file = $this->uploadImage($image, self::manager_route);
        $file_target = $this->image_target;
        if (isset($file_target)) {
            $file_target->update([
                'name' => $file['name'],
                'extension' => $file['extension'],
                'path' => $file['path'],
                'target_id' => $this->id,
                'target_type' => Group::class,
            ]);
        } else {
            $file_target = $this->image_target()->create([
                'name' => $file['name'],
                'extension' => $file['extension'],
                'path' => $file['path'],
                'target_id' => $this->id,
                'target_type' => Group::class,
            ]);
        }
        return $file_target;
    }

    public function addVideoMedia($video)
    {
        $file = $this->uploadImage($video, self::manager_route);
        $file_target = $this->video_target;
        if (isset($file_target)) {
            $file_target->update([
                'name' => $file['name'],
                'extension' => $file['extension'],
                'path' => $file['path'],
                'target_id' => $this->id,
                'target_type' => Group::class,
            ]);
        } else {
            $file_target = $this->video_target()->create([
                'name' => $file['name'],
                'extension' => $file['extension'],
                'path' => $file['path'],
                'target_id' => $this->id,
                'target_type' => Group::class,
            ]);
        }
        return $file_target;
    }

    public function updateMedia(\Illuminate\Http\Request $request,$group)
    {
        $files = [];
        if ($request->hasFile('image')) $files['image_id'] = $this->addImageMedia($request->image)->id;
        if ($request->hasFile('video')) $files['video_id'] = $this->addVideoMedia($request->video)->id;
        $group = Group::find($this->id);
        if (sizeof($files) > 0) $group->update(['image_id' => $files['image_id']]);
    }


}
