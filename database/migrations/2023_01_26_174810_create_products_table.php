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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 30)->nullable(false);
            $table->integer('prix')->nullable(false);
            $table->text('description')->nullable(false);
            $table->foreignUuid('boutique_id')->constrained('boutiques');
            $table->tinyInteger('suplement')->nullable(false);
            $table->foreignId('categoriebouf_id')->constrained('categorieboufs');
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
        Schema::dropIfExists('products');
    }
};
