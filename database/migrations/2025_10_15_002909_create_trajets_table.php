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
        Schema::create('trajets', function (Blueprint $table) {
            $table->id();
            $table->integer('IdConducteur');
            $table->integer('Distance');
            $table->string('Depart');
            $table->string('Destination');
            $table->date('DateTrajet');
            $table->time('HeureTrajet');
            $table->integer('Prix');
            $table->tinyInteger('PlacesDisponible');
            $table->string('TypeConversation');
            $table->tinyInteger('Musique');
            $table->tinyInteger('Fumeur');
            
            $table->foreignId('IdConducteur')->constrained('conducteurs')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trajets');
    }
};
