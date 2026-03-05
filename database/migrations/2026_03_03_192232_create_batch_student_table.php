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
        Schema::create('batch_student', function (Blueprint $column) {
            $column->id();
            $column->foreignId('batch_id')->constrained()->onDelete('cascade');
            $column->foreignId('student_id')->constrained()->onDelete('cascade');
            $column->date('enrollment_date')->nullable();
            $column->boolean('is_active')->default(true);
            $column->timestamps();

            $column->unique(['batch_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_student');
    }
};
