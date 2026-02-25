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
        Schema::create('works_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('works_id')->constrained('works')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
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

            $table->unique(['works_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });

        DB::table('works')
            ->select([
                'id',
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
            ])
            ->orderBy('id')
            ->get()
            ->each(function ($work): void {
                DB::table('works_translations')->insert([
                    'works_id' => $work->id,
                    'locale' => 'lv',
                    'name' => $work->name,
                    'slug' => $work->slug,
                    'description' => $work->description,
                    'client' => $work->client,
                    'date' => $work->date,
                    'place' => $work->place,
                    'about_title' => $work->about_title,
                    'meta_title' => $work->meta_title,
                    'meta_description' => $work->meta_description,
                    'og_description' => $work->og_description,
                    'twitter_description' => $work->twitter_description,
                    'meta_keywords' => $work->meta_keywords,
                ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works_translations');
    }
};
