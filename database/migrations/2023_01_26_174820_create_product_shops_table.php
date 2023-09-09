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
        Schema::create('productshops', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('boutique_id')->constrained('boutiques');
            $table->string('article', 30)->nullable(false);
            $table->foreignId('categorievitrine_id')->constrained('categorievitrines');
            $table->string('prix', 60)->nullable(false);
            $table->tinyInteger('dm')->nullable(false);
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
        Schema::dropIfExists('productshops');
    }
};
