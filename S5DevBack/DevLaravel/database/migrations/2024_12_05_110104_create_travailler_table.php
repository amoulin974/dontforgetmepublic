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
        Schema::create('travailler', function (Blueprint $table) {
            $table->unsignedBigInteger('idUser');
            $table->unsignedBigInteger('idEntreprise');
            $table->unsignedBigInteger('idActivite');
            $table->enum('statut', ['Invité', 'Employé', 'Admin', 'AdminCreateur'])->default('Employé');
            $table->timestamps();

            $table->foreign('idUser')->references('id')->on('users');
            $table->foreign('idEntreprise')->references('id')->on('entreprises');
            $table->foreign('idActivite')->references('id')->on('activites');

            $table->primary(['idUser', 'idEntreprise', 'idActivite']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travailler');
    }
};
