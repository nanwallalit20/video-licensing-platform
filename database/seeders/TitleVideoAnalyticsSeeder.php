<?php

namespace Database\Seeders;

use App\Enums\Devices;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TitleVideoAnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $devices = array_column(Devices::cases(), 'value');

        // Get existing IDs
        $countries = DB::table('countries')->pluck('id')->toArray();
        $titleIds = DB::table('titles')->pluck('id')->toArray();
        $mediaIds = DB::table('media')->pluck('id')->toArray();
        $platformIds = DB::table('ott_platforms')->pluck('id')->toArray();

        // Generate dates for last 10 months
        $dates = [];
        for ($i = 9; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $daysInMonth = $month->daysInMonth;

            // Add multiple dates for each month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dates[] = $month->copy()->setDay($day);
            }
        }

        // Create records for each date
        foreach ($dates as $date) {
            // Create 3-5 records per day
            $recordsPerDay = rand(3, 5);

            for ($i = 1; $i <= $recordsPerDay; $i++) {
                DB::table('title_video_analytics')->insert([
                    'title_id' => $titleIds[array_rand($titleIds)],
                    'media_id' => $mediaIds[array_rand($mediaIds)],
                    'platform_id' => $platformIds[array_rand($platformIds)],
                    'total_views' => rand(1000, 10000),
                    'unique_views' => rand(800, 8000),
                    'total_watch_time' => rand(3600, 36000), // 1-10 hours in seconds
                    'completion_rate' => rand(50, 95),
                    'device_type' => $devices[array_rand($devices)],
                    'country_id' => $countries[array_rand($countries)],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
