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
        Schema::table('trajets', function (Blueprint $table) {
            $table->boolean('RappelEmail')->default(false)->after('Fumeur');
            $table->boolean('RappelEnvoye')->default(false)->after('RappelEmail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trajets', function (Blueprint $table) {
            $table->dropColumn(['RappelEmail', 'RappelEnvoye']);
        });
    }
};

