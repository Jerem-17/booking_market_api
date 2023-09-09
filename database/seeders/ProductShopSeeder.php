<?php

namespace Database\Seeders;

use App\Models\ProductShop;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productShops = [
            [
                'boutique_id' => "3",
                'article' => "sandal",
                'categorievitrine_id' => 1,
                'prix' => "1000",
                'dm' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        ProductShop::insert($productShops);
    }
}
