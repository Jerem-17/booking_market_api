<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Boutique;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BoutiqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $boutiques = [
            [
                'image' => 'img/boutiques/default.jpg',
                'etablissement' => "babiere et con",
                'user_id' => "1",
                'pays_id' => 1,
                'region' => 0,
                'service_id' => 2,
                'latitude' => 80.545614,
                'longitude' => 16.684145,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'img/boutiques/default.jpg',
                'etablissement' => "lagare",
                'user_id' => "2",
                'pays_id' => 2,
                'region' => 0,
                'service_id' => 1,
                'latitude' => 12.54158,
                'longitude' => 11.87154558,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'etablissement' => "rbkpearl",
                'user_id' => "3",
                'pays_id' => 1,
                'region' => 5,
                'service_id' => 3,
                'latitude' => 11.652486845,
                'longitude' => 1.564458748,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        Boutique::insert($boutiques);
    }
}
