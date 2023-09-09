<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::create([
            'user_id' => '779852121221',
            'nom' => 'Avepozo',
            'latitude' => 6.889888,
            'longitude' => 45.555222,
        ]);
    }
}
