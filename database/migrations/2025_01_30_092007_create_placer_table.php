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
        Schema::create('placer', function (Blueprint $table) {
            $table->unsignedBigInteger('idPlage');
            $table->unsignedBigInteger('idUser');
            $table->timestamps();
    
            $table->foreign('idPlage')->references('id')->on('plages');
            $table->foreign('idUser')->references('id')->on('users');
    
            $table->primary(['idPlage', 'idUser']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placer');
    }
};
