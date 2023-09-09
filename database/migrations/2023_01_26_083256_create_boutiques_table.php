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
        Schema::create('boutiques', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('image')->nullable(true);
            $table->string('etablissement', 30)->nullable(false);
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignId('pays_id')->constrained('pays');
            $table->string('contact');
            $table->foreignId('service_id')->constrained('services');
            $table->double('latitude')->nullable(false);
            $table->double('longitude')->nullable(false);
            $table->text('adresse')->nullable(false);
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
        Schema::dropIfExists('boutiques');
    }
};
