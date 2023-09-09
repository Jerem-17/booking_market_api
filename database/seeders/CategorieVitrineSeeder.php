<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\CategorieVitrine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorieVitrineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorieVitrines = [
            [
                'type' => 'vetement',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'electronique',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        CategorieVitrine::insert($categorieVitrines);
    }
}
