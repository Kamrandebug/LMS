<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('playlist_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('playlist_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_set_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('module_number');
            $table->string('label', 60)->nullable();
            $table->tinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['playlist_id', 'module_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playlist_modules');
    }
};
