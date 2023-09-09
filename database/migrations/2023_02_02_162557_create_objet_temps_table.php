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
        Schema::create('objet_temps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retour_id')->constrained('retours');
            $table->foreignId('objet_id')->constrained('objets');
            $table->double('prix')->nullable(false);
            $table->integer('quantite')->nullable(false);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('objet_temps');
    }
};
