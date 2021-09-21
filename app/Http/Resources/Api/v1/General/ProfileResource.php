<?php

namespace App\Http\Resources\Api\v1\General;

use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
//        $image = $this->image->path;
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'user_type' => $this->user_type,
            'status' => api($this->status),
            'image' => $this->getImage(),
            'phone' => $this->phone,
            'verified' => (bool)$this->verified,
            'gender' => gender($this->gender),
            'code' => $this->generatedCode,
            'created_at' => Carbon::parse($this->created_at)->format(DATE_FORMAT_DOTTED),
            'access_token' => $this->access_token,
        ];
        if ($this->user_type == User::user_type['TEACHER']) {
            /** @var Teacher $teacher_details */
            $teacher_details = $this->teacher_details;
//            dd($teacher_details->demonstration_video);
            return array_merge($response, [
                'major' => optional($teacher_details)->major,
                'experience' => optional($teacher_details)->experience,
                'demonstration_video' => optional($teacher_details)->getDemonstrationVideo(),
            ]);
        }
        return $response;
    }
}
