<?php

namespace App\Http\Resources\Api\v1\General;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationReasource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'seen' => (boolean)$this->seen,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
//            'others' => $this['data']['others'],
        ];
    }
}
