<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add extended columns to the users table.
     *
     * Uses hasColumn guards so this is safe to run even if the columns were
     * previously added by an older (now-removed) migration.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'intent')) {
                $table->string('intent')->nullable();
            }

            if (! Schema::hasColumn('users', 'theme_preference')) {
                $table->string('theme_preference')->nullable();
            }

            if (! Schema::hasColumn('users', 'total_streak')) {
                $table->integer('total_streak')->default(0);
            }

            if (! Schema::hasColumn('users', 'last_activity_date')) {
                $table->date('last_activity_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['intent', 'theme_preference', 'total_streak', 'last_activity_date'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
