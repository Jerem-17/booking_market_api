<?php

namespace Database\Seeders;

use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zones = [
            [
                'zone' => 'zone1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'zone' => 'zone2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        Zone::insert($zones);
    }
}
