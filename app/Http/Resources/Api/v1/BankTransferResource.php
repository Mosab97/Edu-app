<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class BankTransferResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];

        $response = [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'bank_id' => $this->bank_id,
            'owner_name' => $this->owner_name,
            'amount' => $this->amount,
            'date' => $this->date,
            'image' => $this->image,
        ];
        return $response;
    }
}
