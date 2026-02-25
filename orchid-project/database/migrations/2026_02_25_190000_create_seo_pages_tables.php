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
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();
            $table->json('og_image')->nullable();
            $table->timestamps();
        });

        Schema::create('seo_page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seo_page_id')->constrained('seo_pages')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('slug')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('og_description')->nullable();
            $table->text('twitter_description')->nullable();
            $table->text('meta_keywords')->nullable();

            $table->unique(['seo_page_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_page_translations');
        Schema::dropIfExists('seo_pages');
    }
};
