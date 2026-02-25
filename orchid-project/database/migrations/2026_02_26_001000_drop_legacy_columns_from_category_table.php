<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('category', 'slug')) {
            try {
                Schema::table('category', function (Blueprint $table): void {
                    $table->dropUnique('category_slug_unique');
                });
            } catch (\Throwable) {
                // Index may already be absent.
            }
        }

        foreach (['name', 'slug', 'description'] as $column) {
            if (! Schema::hasColumn('category', $column)) {
                continue;
            }

            Schema::table('category', function (Blueprint $table) use ($column): void {
                $table->dropColumn($column);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('category', 'name')) {
            Schema::table('category', function (Blueprint $table): void {
                $table->string('name')->nullable();
            });
        }

        if (! Schema::hasColumn('category', 'slug')) {
            Schema::table('category', function (Blueprint $table): void {
                $table->string('slug')->nullable();
            });
        }

        if (! Schema::hasColumn('category', 'description')) {
            Schema::table('category', function (Blueprint $table): void {
                $table->text('description')->nullable();
            });
        }
    }
};
