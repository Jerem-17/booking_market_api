<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Pays;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\CategorieVitrineSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            RoleSeeder::class,
        );
        $this->call(
            CategorieBoufSeeder::class,
        );
        $this->call(
            CategorieVitrineSeeder::class,
        );
          $this->call(
            PaysSeeder::class,
        );
        $this->call(
            ServiceSeeder::class,
        );

    }
}
