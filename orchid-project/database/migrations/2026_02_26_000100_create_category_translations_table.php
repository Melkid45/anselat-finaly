<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained('category')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('og_description')->nullable();
            $table->text('twitter_description')->nullable();
            $table->text('meta_keywords')->nullable();

            $table->unique(['category_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });

        DB::table('category')
            ->select(['id', 'name', 'slug', 'description'])
            ->orderBy('id')
            ->get()
            ->each(function ($category): void {
                DB::table('category_translations')->insert([
                    'category_id' => $category->id,
                    'locale' => 'lv',
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_translations');
    }
};
