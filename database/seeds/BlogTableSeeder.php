<?php

use Illuminate\Database\Seeder;

class BlogTableSeeder extends Seeder
{

    public function run()
    {

        for ($i = 1; $i <= 9; $i++) \App\Models\Blog::create([
            'title' => [
                'ar' => 'Blog Title ar' . $i,
                'en' => 'Blog Title en' . $i,
            ],
            'details' => [
                'ar' => 'Blog details ar' . $i,
                'en' => 'Blog details en' . $i,
            ],
        ]);
    }
}
