<?php

namespace Database\Seeders;

use App\Models\Commande;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $commandes = [
            [
                'reference' => "owergfwor",
                'user_id' => "1",
                'prix_total' => "500",
                'boutique_id' => null,
                'statut_paiement' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];
        Commande::insert($commandes);
    }
}
