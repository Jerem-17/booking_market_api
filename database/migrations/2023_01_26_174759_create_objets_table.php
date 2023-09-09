<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes');
            $table->integer('quantite')->nullable(false);
            $table->integer('quantite_final')->nullable(false);
            $table->string('article', 50)->nullable(false);
            $table->double('prix_unitaire')->nullable(false);
            $table->integer('pu_final')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('objets');
    }
};
