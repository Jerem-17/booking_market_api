<?php

namespace Database\Seeders;

use App\Models\CategorieBouf;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorieBoufSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorieBoufs = [
            [
                'type' => 'rafraichissement',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'consistance',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        CategorieBouf::insert($categorieBoufs);
    }
}
