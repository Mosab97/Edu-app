<?php

namespace App\Http\Resources\Api\v1\User;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'rate' => (double)$this->stars_number,
            'comment' => $this->content_rating,
            'date' => $this->created_at->format(DATE_FORMAT),
            'time' => $this->created_at->format(TIME_FORMAT),
            'username' => optional($this->user)->name,
        ];

        return $response;
    }
}
