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
        Schema::create('affecter', function (Blueprint $table) {
            $table->unsignedBigInteger('idUser');
            $table->unsignedBigInteger('idCreneau');
            $table->unsignedBigInteger('idReservation');
            $table->timestamps();

            $table->foreign('idUser')->references('id')->on('users');
            $table->foreign('idCreneau')->references('id')->on('creneaus');
            $table->foreign('idReservation')->references('id')->on('reservations');

            $table->primary(['idUser', 'idCreneau', 'idReservation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affecter');
    }
};
