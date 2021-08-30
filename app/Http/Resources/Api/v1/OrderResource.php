<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\User\ProfileResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'ad_price' => $this->ad_price,
            'tax_cost' => $this->tax_cost,
            'commission_cost' => $this->commission_cost,
            'total_cost' => $this->total_cost,
        ];
        if (!is_array($except_arr_resource) || !in_array('client', $except_arr_resource)) $response['client'] = new ProfileResource($this->client);
        if (!is_array($except_arr_resource) || !in_array('ad', $except_arr_resource)) $response['ad'] = new AdResource($this->ad);
        return $response;

    }
}
