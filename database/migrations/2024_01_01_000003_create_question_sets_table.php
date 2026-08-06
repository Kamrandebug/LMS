<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->tinyInteger('set_number');
            $table->tinyInteger('question_count')->default(20);
            $table->boolean('is_active')->default(true);
            $table->tinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['topic_id', 'set_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_sets');
    }
};
