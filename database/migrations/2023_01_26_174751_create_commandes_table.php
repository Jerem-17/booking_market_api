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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 255)->unique()->nullable(false);
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignId('address_id')->constrained('addresses');
            $table->double('prix_total')->nullable(false);
            $table->foreignUuid('boutique_id')->nullable()->constrained("boutiques");
            $table->boolean('statut_paiement')->default(false);
            $table->enum('statut_commande',['pending','canceled','finish'])->default('pending');
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
        Schema::dropIfExists('commandes');
    }
};
