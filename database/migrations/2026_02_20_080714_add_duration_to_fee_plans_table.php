<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fee_plans', function (Blueprint $table) {
            // Duration is separate from category (fee_type)
            // e.g. a plan can be "Sports" category + "Monthly" duration
            $table->string('duration')->nullable()->after('fee_type');
            // fee_type now only holds category values; update existing rows safely
        });

        // Migrate existing rows: if fee_type is a duration value, move it to duration and set fee_type = 'other'
        \DB::statement("
            UPDATE fee_plans
            SET duration = fee_type,
                fee_type = 'other'
            WHERE fee_type IN ('monthly','quarterly','half_yearly','annual')
        ");
    }

    public function down(): void
    {
        Schema::table('fee_plans', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
};
