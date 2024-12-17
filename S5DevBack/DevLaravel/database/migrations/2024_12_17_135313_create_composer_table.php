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
        Schema::create('composer', function (Blueprint $table) {
            $table->unsignedBigInteger('idPlage');
            $table->unsignedBigInteger('idActivite');
            $table->timestamps();
    
            $table->foreign('idPlage')->references('id')->on('plages');
            $table->foreign('idActivite')->references('id')->on('activites');
    
            $table->primary(['idPlage', 'idActivite']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composer');
    }
};
