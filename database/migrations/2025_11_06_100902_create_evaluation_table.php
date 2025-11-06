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
        Schema::create('evaluation', function (Blueprint $table) {
            $table->id();
            $table->integer('IdUtilisateur');
            $table->decimal('Note', 2, 1);
            $table->integer('IdTrajet');
            $table->date('DateCommentaire');

            $table->foreignId('IdUtilisateur')->constrained('Utilisateurs')->onDelete('cascade');
            $table->foreignId('IdTrajet')->constrained('Trajets')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaliation');
    }
};
