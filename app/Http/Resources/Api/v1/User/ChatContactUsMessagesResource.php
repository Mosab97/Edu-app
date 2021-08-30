<?php

namespace App\Http\Resources\Api\v1\User;

use App\Http\Resources\Api\v1\AdResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatContactUsMessagesResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'isManager' => $this->isManager,
            'message' => $this->message,
            'image' => $this->image,
        ];
        if ($this->isManager) {
            if (!is_array($except_arr_resource) || !in_array('sender', $except_arr_resource)) $response['sender'] = [
                'name' => t('Manager'),
                'image' => defaultManagerImage(),
            ];
            if (!is_array($except_arr_resource) || !in_array('receiver', $except_arr_resource)) {
                $chat = optional($this->chat);
                $user = optional($chat)->user;
                $response['receiver'] = [
                    'id' => optional($user)->id,
                    'name' => optional($user)->name,
                    'image' => optional($user)->image,
                ];
            }
        } else {
            if (!is_array($except_arr_resource) || !in_array('receiver', $except_arr_resource)) $response['receiver'] = [
                'name' => t('Manager'),
                'image' => defaultManagerImage(),
            ];
            if (!is_array($except_arr_resource) || !in_array('sender', $except_arr_resource)) {
                $chat = optional($this->chat);
                $user = optional($chat)->user;
                $response['sender'] = [
                    'id' => optional($user)->id,
                    'name' => optional($user)->name,
                    'image' => optional($user)->image,
                ];
            }

        }

        return $response;
    }
}
