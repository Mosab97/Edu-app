<?php

namespace App\Http\Resources\Api\v1\Student;

use App\Http\Resources\Api\v1\General\AgeResource;
use App\Http\Resources\Api\v1\General\LevelResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];

        $response = [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,
            'price' => $this->price,
            'students_number_max' => $this->students_number_max,
            'gender' => gender($this->gender),
            'time' => $this->time,
            'number_of_joined_students' => $this->students->count(),
            'level' => new LevelResource($this->level),
            'age' => new AgeResource($this->age),
        ];
        return $response;
    }
}
