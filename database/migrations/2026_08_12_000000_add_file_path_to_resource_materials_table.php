<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resource_materials', function (Blueprint $table) {
            $table->string('file_path', 500)->nullable()->after('file_url');
            // Allow file_url to be nullable — user can upload a file instead
            $table->string('file_url', 1000)->nullable()->change();
            $table->string('file_type', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('resource_materials', function (Blueprint $table) {
            $table->dropColumn('file_path');
            $table->string('file_url', 1000)->nullable(false)->change();
            $table->string('file_type', 50)->nullable(false)->default('pdf')->change();
        });
    }
};
