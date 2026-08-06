<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('exam_type', ['fia', 'ppsc', 'fpsc', 'custom'])->default('ppsc');
            $table->tinyInteger('total_questions')->default(100);
            $table->smallInteger('duration_minutes')->default(90);
            $table->decimal('negative_marking_value', 3, 2)->default(0.25);
            $table->boolean('has_negative_marking')->default(true);
            $table->integer('attempt_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_exams');
    }
};
