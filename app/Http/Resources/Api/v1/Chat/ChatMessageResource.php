<?php

namespace App\Http\Resources\Api\v1\Chat;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'message' => $this->message,
            'timestamp' => Carbon::parse($this->updated_at)->format(DATE_FORMAT_FULL),
            'group_id' => $this->group_id,
            'sender_id' => $this->sender_id,
        ];
        return $response;

    }
}
