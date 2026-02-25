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
        Schema::create('hero_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hero_id')->constrained('hero')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('first_title')->nullable();
            $table->string('second_title')->nullable();
            $table->text('description')->nullable();

            $table->unique(['hero_id', 'locale']);
        });

        DB::table('hero')
            ->select('id', 'first_title', 'second_title', 'description')
            ->orderBy('id')
            ->get()
            ->each(function ($hero): void {
                DB::table('hero_translations')->insert([
                    'hero_id' => $hero->id,
                    'locale' => 'lv',
                    'first_title' => $hero->first_title,
                    'second_title' => $hero->second_title,
                    'description' => $hero->description,
                ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_translations');
    }
};
