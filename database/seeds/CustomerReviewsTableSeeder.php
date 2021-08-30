<?php

use Illuminate\Database\Seeder;

class CustomerReviewsTableSeeder extends Seeder
{

    public function run()
    {
        for ($i = 1; $i <= 9; $i++) \App\Models\CustomerReviews::create([
            'rate' => collect(range(1, 5))->random(),
            'title' => [
                'ar' => 'CustomerReviews Title ar' . $i,
                'en' => 'CustomerReviews Title en' . $i,
            ],
            'details' => [
                'ar' => 'CustomerReviews details ar' . $i,
                'en' => 'CustomerReviews details en' . $i,
            ],
            'customer_name' => [
                'ar' => 'CustomerReviews customer_name ar' . $i,
                'en' => 'CustomerReviews customer_name en' . $i,
            ],

        ]);
    }
}
