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
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('status');
            $table->timestamp('photo_submitted_at')->nullable()->after('photo_path');
            $table->string('verification_status')->nullable()->after('photo_submitted_at'); // 'pending', 'approved', 'rejected'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['photo_path', 'photo_submitted_at', 'verification_status']);
        });
    }
};
