<?php

namespace App\Http\Resources\Api\v1\User;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'image' => $this->image,
            'phone' => $this->phone,
            'email' => $this->email,
            'verified' => (bool)$this->verified,
            'gender' => $this->gender,
            'gender_name' => gender($this->gender),
            'code' => $this->generatedCode,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'dob' => $this->dob,
            'created_at' => Carbon::parse($this->created_at)->format(DATE_FORMAT_DOTTED),
            'local' => $this->local,
            'notification' => (bool)$this->notification,
            'unread_notifications' => (int)$this->unread_notifications,
            'access_token' => $this->access_token,
            'expires_in' => auth('student')->factory()->getTTL() * 60
        ];
        return $response;
    }
}
