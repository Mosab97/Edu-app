<?php

namespace App\Http\Resources\Api\v1;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'days_number' => $this->days_number,
            'price_per_day' => $this->price_per_day,
            'is_most_wanted' => $this->is_most_wanted,
        ];
        return $response;
    }
}
