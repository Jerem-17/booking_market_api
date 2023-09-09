<?php

namespace Database\Seeders;

use App\Models\Pays;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pays = [
            [
                'nom' => 'Togo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Mali',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nom' => 'Gabon',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];
        Pays::insert($pays);
    }
}
