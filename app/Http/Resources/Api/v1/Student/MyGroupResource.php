<?php

namespace App\Http\Resources\Api\v1\Student;

use App\Http\Resources\Api\v1\General\AgeResource;
use App\Http\Resources\Api\v1\General\LevelResource;
use App\Http\Resources\Api\v1\General\ProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MyGroupResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];

        $teacher = $this->teacher;
        $response = [
            'id' => $this->id,
            'name' => $this->name,
//            'number_of_joined_students' => $this->students()->count(),
            'number_of_joined_students' => count($this->students),
            'students_images' => $this->students->pluck('student')->pluck('image')->all(),
            'teacher' => isset($teacher)?new ProfileResource($this->teacher):null,
        ];
        return $response;
    }
}
