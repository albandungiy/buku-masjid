<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(DefaultUserTableSeeder::class);
        $this->call(DefaultBookTableSeeder::class);
        $this->call(DefaultCatergoryTableSeeder::class);

        if (config('features.ziswaf.is_active')) {
            $this->call(ZiswafSeeder::class);
        }
    }
}
