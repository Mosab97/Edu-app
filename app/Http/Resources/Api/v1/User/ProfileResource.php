<?php

namespace App\Http\Resources\Api\v1\User;

use App\Http\Resources\Api\v1\CityResource;
use App\Http\Resources\Api\v1\CountryResource;
use App\Models\ClientRateAd;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $res = [
            'avg_rate' => (double)number_format($this->rate, DECIMAL_DIGIT_NUMBER, DECIMAL_SEPARATOR, DIGIT_THOUSANDS_SEPARATOR),
            'total_rantings_number' => ClientRateAd::currentOwner($this->id)->count(),
            'ranting_numbers' => [
                "first" => ClientRateAd::currentOwner($this->id)->where('stars_number', 1)->count(),
                'second' => ClientRateAd::currentOwner($this->id)->where('stars_number', 2)->count(),
                'third' => ClientRateAd::currentOwner($this->id)->where('stars_number', 3)->count(),
                'fourth' => ClientRateAd::currentOwner($this->id)->where('stars_number', 4)->count(),
                'fifth' => ClientRateAd::currentOwner($this->id)->where('stars_number', 5)->count(),
            ],
        ];
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'ads_number' => $this->ads()->count(),
            'buys_number' => $this->owned_chats()->count(),
            'is_registered' => $this->is_registered,
            'username' => $this->username,
            'image' => $this->image,
            'phone' => $this->phone,
            'email' => $this->email,
            'verified' => (bool)$this->verified,
            'gender' => $this->gender,
            'gender_name' => gender($this->gender),
            'code' => $this->generatedCode,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'dob' => $this->dob,
            'show_phone_number' => (bool)$this->show_phone_number,
            'created_at' => Carbon::parse($this->created_at)->format(DATE_FORMAT_DOTTED),
            'local' => $this->local,
            'notification' => (bool)$this->notification,
            'unread_notifications' => (int)$this->unread_notifications,
            'rate' => $res,
            'access_token' => $this->access_token,
        ];
        if (!is_array($except_arr_resource) || !in_array('city', $except_arr_resource)) {
            $city = $this->city;
            $response['city'] = isset($city) ? new CityResource($city) : null;
        }
        if (!is_array($except_arr_resource) || !in_array('country', $except_arr_resource)) {

            $request['except_arr_resource'] = ['cities'];
            $country = optional($this->city)->country;
            $response['country'] = isset($country) ? new CountryResource($country) : null;
        }
        return $response;
    }
}
