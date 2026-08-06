<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('mock_exam_question_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('question_set_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('report_type', ['wrong_answer', 'typo', 'wrong_set', 'confusing_explanation', 'other'])->default('wrong_answer');
            $table->text('description')->nullable();
            $table->string('reporter_ip', 45)->nullable();
            $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_reports');
    }
};
