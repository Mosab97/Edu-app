<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\User\ChatResource;
use App\Http\Resources\Api\v1\User\ProfileResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{

    public function toArray($request)
    {
        $order = $this->orders()->first();
        $request['except_arr_resource'] = ['ad'];
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'deep_link' => url('api/v1/ad/' . $this->id),
            'rate' => $this->rate,
            'price_type' => $this->price_type,
            'owner_type' => $this->owner_type,
            'ad_type' => $this->ad_type,
            'car_status' => $this->car_status,
            'order_id' => optional($order)->id,
            'order_uuid' => optional($order)->uuid,
            'is_checkout' => (bool)$this->is_checkout,
            'views' => $this->views,
            'price' => $this->price,
            'image' => $this->image,
            'details' => $this->details,
            'show_phone_number' => $this->show_phone_number,
            'show_location' => $this->show_location,
            'argent_ad' => $this->argent_ad,
            'title' => $this->title,
            'sold' => $this->sold,
            'address' => $this->address,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'featured' => $this->featured,
            'created_at' => Carbon::parse($this->created_at)->format(DATE_FORMAT_DOTTED),
            'favorited' => $this->favorited,
            'km' => $this->km,
        ];
        if (!is_array($except_arr_resource) || !in_array('chat', $except_arr_resource)) {
            $user = apiUser();
            if (isset($user) && $this->owner_id != $user->id) {
                $chat = $this->chats()->where('created_by_id', $user->id)->first();
//                $response['chat_id'] = new ChatResource($chat);
                $response['chat_id'] = optional($chat)->uuid;
            } else {
                $response['chat'] = null;
            }
        }


        if (!is_array($except_arr_resource) || !in_array('fuel_type', $except_arr_resource)) $response['fuel_type'] = new FuelTypeResource($this->fuel_type);
        if (!is_array($except_arr_resource) || !in_array('car_model', $except_arr_resource)) $response['car_model'] = new CarModelResource($this->car_model);
        if (!is_array($except_arr_resource) || !in_array('owner', $except_arr_resource)) $response['owner'] = new ProfileResource($this->owner);
        if (!is_array($except_arr_resource) || !in_array('city', $except_arr_resource)) $response['city'] = new CityResource($this->city);
        if (!is_array($except_arr_resource) || !in_array('stakeholder', $except_arr_resource)) $response['stakeholder'] = new StakeholderResource($this->stakeholder);
        if (!is_array($except_arr_resource) || !in_array('category', $except_arr_resource)) $response['category'] = new CategoryResource($this->category);
        if (!is_array($except_arr_resource) || !in_array('images', $except_arr_resource)) $response['images'] = AdImageResource::collection($this->images);
        if (!is_array($except_arr_resource) || !in_array('gear_type', $except_arr_resource)) $response['gear_type'] = new GearTypeResource($this->gear_type);
        return $response;

    }
}
