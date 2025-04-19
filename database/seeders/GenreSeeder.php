<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            'Action', 'Adventure', 'Comedy', 'Drama', 'Thriller',
            'Horror', 'Mystery', 'Sci-Fi', 'Fantasy', 'Romance',
            'Documentary', 'Animation', 'Crime', 'Musical',
            'Historical', 'Biopic', 'Family', 'Sports'
        ];

        foreach ($genres as $genre) {
            DB::table('genres')->insert([
                'name' => $genre,
                'slug' => Str::slug($genre)
            ]);
        }
    }
}
