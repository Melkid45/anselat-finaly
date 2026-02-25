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
        if (Schema::hasColumn('works', 'slug')) {
            try {
                Schema::table('works', function (Blueprint $table): void {
                    $table->dropUnique('works_slug_unique');
                });
            } catch (\Throwable) {
                // Index may not exist depending on local migration history.
            }
        }

        $columnsToDrop = [
            'name',
            'slug',
            'description',
            'client',
            'date',
            'place',
            'about_title',
            'meta_title',
            'meta_description',
            'og_description',
            'twitter_description',
            'meta_keywords',
            'category',
        ];

        foreach ($columnsToDrop as $column) {
            if (! Schema::hasColumn('works', $column)) {
                continue;
            }

            Schema::table('works', function (Blueprint $table) use ($column): void {
                $table->dropColumn($column);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('works', function (Blueprint $table): void {
            $table->string('name')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('client')->nullable();
            $table->string('date')->nullable();
            $table->text('place')->nullable();
            $table->string('about_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('og_description')->nullable();
            $table->text('twitter_description')->nullable();
            $table->text('meta_keywords')->nullable();
        });
    }
};
