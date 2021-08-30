<?php

use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 5; $i++) {
            $country = \App\Models\Country::create([
                'name' => [
                    'ar' => 'Country' . $i,
                    'en' => 'Country' . $i,
                ]
            ]);
            for ($city = 1; $city <= 5; $city++) {
                App\Models\City::create([
                    'name' => [
                        'ar' => 'City' . ($i + $city + $country->id),
                        'en' => 'City' . ($i + $city + $country->id),
                    ],
                    'country_id' => $country->id,
                ]);
            }
        }
    }
}
