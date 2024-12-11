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
        Schema::create('constituer', function (Blueprint $table) {
            $table->unsignedBigInteger('idSemaineType');
            $table->unsignedBigInteger('idJourneeType');
            $table->timestamps();

            $table->foreign('idSemaineType')->references('id')->on('semaine_types');
            $table->foreign('idJourneeType')->references('id')->on('journee_types');

            $table->primary(['idSemaineType', 'idJourneeType']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constituer');
    }
};
