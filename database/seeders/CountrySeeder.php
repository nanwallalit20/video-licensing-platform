<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = [
            [
                'name'         => 'China',
                'country_code' => '86',
                'iso_3_code'   => 'CHN',
                'iso_2_code'   => 'CN',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'India',
                'country_code' => '91',
                'iso_3_code'   => 'IND',
                'iso_2_code'   => 'IN',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'United States',
                'country_code' => '1',
                'iso_3_code'   => 'USA',
                'iso_2_code'   => 'US',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Indonesia',
                'country_code' => '62',
                'iso_3_code'   => 'IDN',
                'iso_2_code'   => 'ID',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Pakistan',
                'country_code' => '92',
                'iso_3_code'   => 'PAK',
                'iso_2_code'   => 'PK',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Brazil',
                'country_code' => '55',
                'iso_3_code'   => 'BRA',
                'iso_2_code'   => 'BR',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Nigeria',
                'country_code' => '234',
                'iso_3_code'   => 'NGA',
                'iso_2_code'   => 'NG',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Bangladesh',
                'country_code' => '880',
                'iso_3_code'   => 'BGD',
                'iso_2_code'   => 'BD',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Russia',
                'country_code' => '7',
                'iso_3_code'   => 'RUS',
                'iso_2_code'   => 'RU',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Mexico',
                'country_code' => '52',
                'iso_3_code'   => 'MEX',
                'iso_2_code'   => 'MX',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Japan',
                'country_code' => '81',
                'iso_3_code'   => 'JPN',
                'iso_2_code'   => 'JP',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Ethiopia',
                'country_code' => '251',
                'iso_3_code'   => 'ETH',
                'iso_2_code'   => 'ET',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Philippines',
                'country_code' => '63',
                'iso_3_code'   => 'PHL',
                'iso_2_code'   => 'PH',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Egypt',
                'country_code' => '20',
                'iso_3_code'   => 'EGY',
                'iso_2_code'   => 'EG',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Vietnam',
                'country_code' => '84',
                'iso_3_code'   => 'VNM',
                'iso_2_code'   => 'VN',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'DR Congo',
                'country_code' => '243',
                'iso_3_code'   => 'COD',
                'iso_2_code'   => 'CD',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Turkey',
                'country_code' => '90',
                'iso_3_code'   => 'TUR',
                'iso_2_code'   => 'TR',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Iran',
                'country_code' => '98',
                'iso_3_code'   => 'IRN',
                'iso_2_code'   => 'IR',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Germany',
                'country_code' => '49',
                'iso_3_code'   => 'DEU',
                'iso_2_code'   => 'DE',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Thailand',
                'country_code' => '66',
                'iso_3_code'   => 'THA',
                'iso_2_code'   => 'TH',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'United Kingdom',
                'country_code' => '44',
                'iso_3_code'   => 'GBR',
                'iso_2_code'   => 'GB',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'France',
                'country_code' => '33',
                'iso_3_code'   => 'FRA',
                'iso_2_code'   => 'FR',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Italy',
                'country_code' => '39',
                'iso_3_code'   => 'ITA',
                'iso_2_code'   => 'IT',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'South Africa',
                'country_code' => '27',
                'iso_3_code'   => 'ZAF',
                'iso_2_code'   => 'ZA',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Tanzania',
                'country_code' => '255',
                'iso_3_code'   => 'TZA',
                'iso_2_code'   => 'TZ',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Myanmar',
                'country_code' => '95',
                'iso_3_code'   => 'MMR',
                'iso_2_code'   => 'MM',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'South Korea',
                'country_code' => '82',
                'iso_3_code'   => 'KOR',
                'iso_2_code'   => 'KR',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Colombia',
                'country_code' => '57',
                'iso_3_code'   => 'COL',
                'iso_2_code'   => 'CO',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Kenya',
                'country_code' => '254',
                'iso_3_code'   => 'KEN',
                'iso_2_code'   => 'KE',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Spain',
                'country_code' => '34',
                'iso_3_code'   => 'ESP',
                'iso_2_code'   => 'ES',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Argentina',
                'country_code' => '54',
                'iso_3_code'   => 'ARG',
                'iso_2_code'   => 'AR',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Algeria',
                'country_code' => '213',
                'iso_3_code'   => 'DZA',
                'iso_2_code'   => 'DZ',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Sudan',
                'country_code' => '249',
                'iso_3_code'   => 'SDN',
                'iso_2_code'   => 'SD',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Ukraine',
                'country_code' => '380',
                'iso_3_code'   => 'UKR',
                'iso_2_code'   => 'UA',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Uganda',
                'country_code' => '256',
                'iso_3_code'   => 'UGA',
                'iso_2_code'   => 'UG',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Iraq',
                'country_code' => '964',
                'iso_3_code'   => 'IRQ',
                'iso_2_code'   => 'IQ',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Afghanistan',
                'country_code' => '93',
                'iso_3_code'   => 'AFG',
                'iso_2_code'   => 'AF',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Poland',
                'country_code' => '48',
                'iso_3_code'   => 'POL',
                'iso_2_code'   => 'PL',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Canada',
                'country_code' => '1',
                'iso_3_code'   => 'CAN',
                'iso_2_code'   => 'CA',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'name'         => 'Morocco',
                'country_code' => '212',
                'iso_3_code'   => 'MAR',
                'iso_2_code'   => 'MA',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
        ];

        DB::table('countries')->insert($countries);
    }
}
