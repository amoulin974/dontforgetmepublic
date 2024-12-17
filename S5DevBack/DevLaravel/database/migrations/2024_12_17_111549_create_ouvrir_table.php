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
        Schema::create('ouvrir', function (Blueprint $table) {
            $table->unsignedBigInteger('idCreneau');
            $table->unsignedBigInteger('idEntreprise');
            $table->timestamps();

            $table->foreign('idCreneau')->references('id')->on('creneaus');
            $table->foreign('idEntreprise')->references('id')->on('entreprises');
        
            $table->primary(['idCreneau', 'idEntreprise']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ouvrir');
    }
};
