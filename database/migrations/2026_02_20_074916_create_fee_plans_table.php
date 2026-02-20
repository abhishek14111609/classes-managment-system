<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fee_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->string('name');                        // e.g. "Monthly Tuition", "Sports Fee"
            $table->string('fee_type');                    // tuition | sports | transport | exam | other | half_yearly
            $table->string('sport_level')->nullable();     // null | basic | advanced
            $table->decimal('amount', 10, 2);
            $table->decimal('late_fee_per_day', 10, 2)->default(0);
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });

        // Add fee_plan_id to fees table so each fee knows which plan it came from
        Schema::table('fees', function (Blueprint $table) {
            $table->unsignedBigInteger('fee_plan_id')->nullable()->after('school_id');
            $table->foreign('fee_plan_id')->references('id')->on('fee_plans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropForeign(['fee_plan_id']);
            $table->dropColumn('fee_plan_id');
        });
        Schema::dropIfExists('fee_plans');
    }
};
