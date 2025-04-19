<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdvisoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $advisories = [
            ['name' => 'Language'],
            ['name' => 'Drugs'],
            ['name' => 'Violence'],
            ['name' => 'Sex'],
            ['name' => 'Nudity'],
            ['name' => 'Flashing Lights'],
        ];

        foreach ($advisories as &$advisory) {
            $advisory['created_at'] = Carbon::now();
            $advisory['updated_at'] = Carbon::now();
        }

        DB::table('advisories')->insert($advisories);
    }
}
