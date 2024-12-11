<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('siren')->nullable();
            $table->string('adresse');
            $table->string('metier');
            $table->string('description');
            $table->string('type');
            $table->string('numTel');
            $table->string('email');
            $table->json('cheminImg')->nullable(); // ->default(json_encode(['https://static.thenounproject.com/png/1584264-200.png']));
            $table->integer('publier')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
