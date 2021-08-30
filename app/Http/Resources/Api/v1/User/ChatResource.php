<?php

namespace App\Http\Resources\Api\v1\User;

use App\Http\Resources\Api\v1\AdResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->uuid,
            'isClosed' => (bool)$this->isClosed,
        ];
        if (!is_array($except_arr_resource) || !in_array('ad', $except_arr_resource)) $response['ad'] = new AdResource($this->ad);
        if (!is_array($except_arr_resource) || !in_array('owner', $except_arr_resource)) $response['owner'] = new ProfileResource($this->owner);
        if (!is_array($except_arr_resource) || !in_array('receiver', $except_arr_resource)) $response['receiver'] = new ProfileResource($this->receiver);
        if (!is_array($except_arr_resource) || !in_array('messages', $except_arr_resource)) $response['messages'] = ChatMessagesResource::collection($this->messages);

        return $response;
    }
}
