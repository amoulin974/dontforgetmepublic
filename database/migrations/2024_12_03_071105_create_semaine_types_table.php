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
        Schema::create('semaine_types', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->json('planning');
            $table->timestamps();
            $table->unsignedBigInteger('idEntreprise');
            $table->foreign('idEntreprise')->references('id')->on('entreprises');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semaine_types');
    }
};
