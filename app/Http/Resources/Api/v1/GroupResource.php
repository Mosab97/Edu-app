<?php

namespace App\Http\Resources\Api\v1;

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
            'level' => $this->level,
            'gender' => $this->gender,
            'time' => $this->time,
        ];
        return $response;
    }
}