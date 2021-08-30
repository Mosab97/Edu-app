<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];

        $response = [
            'id' => $this->id,
            'name' => $this->name,
        ];
        if (!is_array($except_arr_resource) || !in_array('cities', $except_arr_resource))
            $response['cities'] = CityResource::collection($this->cities);
        return $response;
    }
}
