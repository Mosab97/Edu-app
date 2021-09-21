<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $guarded = [];
    protected $table = 'teachers';


    public function getDemonstrationVideo()
    {
        $file = File::where(['target_id' => $this->id, 'target_type' => Teacher::class])->first();
        return !isset($file) ? defaultUserVideo() : $file->path;
    }

}
