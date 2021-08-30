<?php

namespace App\Http\Resources\Api\v1\User;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        $others = optional($this['data'])['others'];
        $others['type_name'] = optional(optional($others)['type_name'])[lang()];
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'seen' => (boolean)$this->seen,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
//            'icon' => $this->icon,
            'others' => $others,
        ];
    }
}
