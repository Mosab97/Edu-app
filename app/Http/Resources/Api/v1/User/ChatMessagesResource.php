<?php

namespace App\Http\Resources\Api\v1\User;

use App\Http\Resources\Api\v1\AdResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessagesResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->uuid,
            'message' => $this->message,
            'image' => $this->image,
        ];
        if (!is_array($except_arr_resource) || !in_array('sender', $except_arr_resource)) $response['sender'] = new ProfileResource($this->sender);
        if (!is_array($except_arr_resource) || !in_array('receiver', $except_arr_resource)) $response['receiver'] = new ProfileResource($this->receiver);

        return $response;
    }
}
