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
        Schema::create('decomposer', function (Blueprint $table) {
            $table->unsignedBigInteger('idReservation');
            $table->unsignedBigInteger('idCreneau');
            $table->timestamps();

            $table->foreign('idReservation')->references('id')->on('reservations');
            $table->foreign('idCreneau')->references('id')->on('creneaus');

            $table->primary(['idReservation', 'idCreneau']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decomposer');
    }
};
