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
        Schema::table('works', function (Blueprint $table): void {
            $table->string('meta_title')->nullable()->after('about_title');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('og_description')->nullable()->after('meta_description');
            $table->text('twitter_description')->nullable()->after('og_description');
            $table->text('meta_keywords')->nullable()->after('twitter_description');
            $table->json('og_image')->nullable()->after('meta_keywords');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('works', function (Blueprint $table): void {
            $table->dropColumn([
                'meta_title',
                'meta_description',
                'og_description',
                'twitter_description',
                'meta_keywords',
                'og_image',
            ]);
        });
    }
};
