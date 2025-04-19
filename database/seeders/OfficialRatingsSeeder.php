<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OfficialRatingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Use Carbon to get the current timestamp for both created_at and updated_at fields.
        $now = Carbon::now();

        // Define the list of ratings.
        // Movie ratings (MPAA) and TV series ratings (TV Parental Guidelines)
        $ratings = [
            // Movie Ratings
            ['id' => 1, 'name' => 'G',      'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'PG',     'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'PG-13',  'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'R',      'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'NC-17',  'created_at' => $now, 'updated_at' => $now],

            // Series Ratings (TV Parental Guidelines)
            ['id' => 6,  'name' => 'TV-Y',   'created_at' => $now, 'updated_at' => $now],
            ['id' => 7,  'name' => 'TV-Y7',  'created_at' => $now, 'updated_at' => $now],
            ['id' => 8,  'name' => 'TV-G',   'created_at' => $now, 'updated_at' => $now],
            ['id' => 9,  'name' => 'TV-PG',  'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'name' => 'TV-14',  'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'name' => 'TV-MA',  'created_at' => $now, 'updated_at' => $now],
        ];

        // Insert the ratings into the official_ratings table.
        DB::table('official_ratings')->insert($ratings);
    }
}
