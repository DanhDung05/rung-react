<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Phase 1
            \Database\Seeders\SettingSeeder::class,
            \Database\Seeders\UserSeeder::class,
            \Database\Seeders\CategorySeeder::class,
            \Database\Seeders\DestinationSeeder::class,

            // Phase 2
            \Database\Seeders\TagSeeder::class,
            \Database\Seeders\TourSeeder::class,
            \Database\Seeders\PostSeeder::class,

             // Phase 3
            \Database\Seeders\SliderSeeder::class,
            \Database\Seeders\PageSeeder::class,
            \Database\Seeders\FaqSeeder::class,
            \Database\Seeders\MenuSeeder::class,
            \Database\Seeders\ContactSeeder::class,



        ]);
    }
}