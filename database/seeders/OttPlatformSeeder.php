<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OttPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $platforms = [
            ['name' => 'Netflix', 'slug' => Str::slug('Netflix'), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prime', 'slug' => Str::slug('Prime'), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Internal App', 'slug' => Str::slug('Internal App'), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Disney+', 'slug' => Str::slug('Disney+'), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Apple TV+', 'slug' => Str::slug('Apple TV+'), 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('ott_platforms')->insert($platforms);
    }
}
