<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('fee_payment_id')->nullable()->after('fee_id');
            $table->foreign('fee_payment_id')->references('id')->on('fee_payments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['fee_payment_id']);
            $table->dropColumn('fee_payment_id');
        });
    }
};
