<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Objet;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ObjetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $objets = [
            [
                'commande_id' => "1",
                'quantite' => 1,
                'article' => "wazo",
                'prix_unitaire' => 500,
                'correctif' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        Objet::insert($objets);
    }
}
