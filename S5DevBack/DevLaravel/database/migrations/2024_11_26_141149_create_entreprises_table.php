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
            $table->string('metier')->nullable();
            $table->string('description')->nullable();
            $table->string('numTel');
            $table->string('email');
            $table->json('cheminImg')->nullable(); // ->default(json_encode(['https://static.thenounproject.com/png/1584264-200.png']));
            $table->integer('publier')->default(0);
            $table->json('typeRdv')->nullable();
            $table->integer('capaciteMax');
            $table->unsignedBigInteger('idCreateur');
            $table->timestamps();
            $table->foreign('idCreateur')->references('id')->on('users');
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
