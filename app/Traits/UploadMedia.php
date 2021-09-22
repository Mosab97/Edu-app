<?php
/**
Dev Mosab Irwished
eng.mosabirwished@gmail.com
WhatsApp +970592879186
 */
namespace App\Traits;

use App\Models\File;
use App\Models\Group;
use Carbon\Carbon;

trait UploadMedia
{
public function uploadImage($file, $path = '')
    {
        $fileName = $file->getClientOriginalName();
        $file_exe = $file->getClientOriginalExtension();
        $new_name = uniqid() . '.' . $file_exe;
        $directory = 'uploads' . '/' . $path;//.'/'.date("Y").'/'.date("m").'/'.date("d");
        $destienation = public_path($directory);
        $file->move($destienation, $new_name);
        return [
            'path' => $directory . '/' . $new_name,
            'name' => $fileName,
            'extension' => $file_exe,
        ];
    }


    public function getImageAttribute($value)
    {
        $file = $this->image_target()->first();
        return is_null($file) ? defaultUserImage() : $file->path;
    }

    public function getVideoAttribute($value)
    {
        $file = $this->video_target()->first();
        return is_null($file) ? defaultUserVideo() : $file->path;
    }



    public function video_target()
    {
        return $this->hasOne(File::class, 'target_id')->where(['target_type' => self::class, 'files_number' => File::files_number['one_file'], 'files_type' => File::files_type['video']]);
    }

    public function image_target()
    {
        return $this->hasOne(File::class, 'target_id')->where(['target_type' => self::class, 'files_number' => File::files_number['one_file'], 'files_type' => File::files_type['image']]);
    }

    public function files()
    {
        return $this->hasMany(File::class, 'target_id')->where(['target_type' => self::class, 'files_number' => File::files_number['multi_file']]);
    }




    public function addImageMedia($image)
    {
        $file = $this->uploadImage($image, self::manager_route);
        $file_target = $this->image_target;
        if (isset($file_target)) {
            $file_target->update([
                'name' => $file['name'],
                'extension' => $file['extension'],
                'path' => $file['path'],
                'files_number' => File::files_number['one_file'],
                'files_type' => File::files_type['image'],
                'target_id' => $this->id,
                'target_type' => Group::class,
            ]);
        } else {
            $file_target = $this->image_target()->create([
                'name' => $file['name'],
                'extension' => $file['extension'],
                'path' => $file['path'],
                'files_number' => File::files_number['one_file'],
                'files_type' => File::files_type['image'],
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
                'files_number' => File::files_number['one_file'],
                'files_type' => File::files_type['video'],
                'target_id' => $this->id,
                'target_type' => Group::class,
            ]);
        } else {
            $file_target = $this->video_target()->create([
                'name' => $file['name'],
                'extension' => $file['extension'],
                'path' => $file['path'],
                'files_number' => File::files_number['one_file'],
                'files_type' => File::files_type['video'],
                'target_id' => $this->id,
                'target_type' => Group::class,
            ]);
        }
        return $file_target;
    }

    public function updateMedia(\Illuminate\Http\Request $request, $group)
    {
        $files = [];
        if ($request->hasFile('image')) $files['image_id'] = $this->addImageMedia($request->image)->id;
        if ($request->hasFile('video')) $files['video_id'] = $this->addVideoMedia($request->video)->id;
//        $group = Group::find($this->id);
//        if (sizeof($files) > 0) $group->update(['image_id' => $files['image_id']]);
    }



}
