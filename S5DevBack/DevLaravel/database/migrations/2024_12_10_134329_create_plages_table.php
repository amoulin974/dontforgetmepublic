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
        Schema::create('plages', function (Blueprint $table) {
            $table->id();
            $table->date('datePlage');
            $table->time('heureDeb')->check('heureDeb > heureFin');
            $table->time('heureFin')->check('heureFin < heureDeb');
            $table->time('interval');
            $table->json('planTables');
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plages');
    }
};
