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
        Schema::create('effectuer', function (Blueprint $table) {
            $table->unsignedBigInteger('idUser');
            $table->unsignedBigInteger('idReservation');
            $table->unsignedBigInteger('idActivite');
            $table->date('dateReservation');
            $table->enum('typeNotif', ['SMS', 'Mail'])->nullable();
            $table->string('numTel')->nullable();
            $table->timestamps();

            $table->foreign('idUser')->references('id')->on('users');
            $table->foreign('idReservation')->references('id')->on('reservations');
            $table->foreign('idActivite')->references('id')->on('activites');

            $table->primary(['idUser', 'idReservation', 'idActivite']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('effectuer');
    }
};
