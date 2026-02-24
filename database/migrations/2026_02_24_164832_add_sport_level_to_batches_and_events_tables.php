<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('batches', 'sport_level')) {
            Schema::table('batches', function (Blueprint $table) {
                $table->string('sport_level')->nullable()->after('capacity');
            });
        }

        if (!Schema::hasColumn('sports_events', 'sport_level')) {
            Schema::table('sports_events', function (Blueprint $table) {
                $table->string('sport_level')->nullable()->after('location');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn('sport_level');
        });

        Schema::table('sports_events', function (Blueprint $table) {
            $table->dropColumn('sport_level');
        });
    }
};
