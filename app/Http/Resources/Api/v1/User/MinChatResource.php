<?php

namespace App\Http\Resources\Api\v1\User;

use App\Http\Resources\Api\v1\AdResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MinChatResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->uuid,
            'chat_image' => $this->image,
            'ad_id' => $this->ad_id,
            'sender_image' => optional($this->receiver)->image,
            'sender_name' => optional($this->receiver)->name,
            'ad_title' => optional($this->ad)->title,
            'last_message' => optional($this->messages()->get()->last())->message,
            'unReadMessages' => 0,
            'isManager' => $this->isManager,
            'manager_name' => optional($this->manager)->name,
            'manager_image' => optional($this->manager)->image,
            'created_at' => Carbon::parse($this->created_at)->format(DATE_FORMAT_DOTTED),
        ];

        return $response;
    }
}
