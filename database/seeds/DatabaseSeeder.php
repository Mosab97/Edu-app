<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call(ManagerTableSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(TeacherSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(ISeedOauthPersonalAccessClientsTableSeeder::class);
        $this->call(ISeedOauthClientsTableSeeder::class);

    }
}