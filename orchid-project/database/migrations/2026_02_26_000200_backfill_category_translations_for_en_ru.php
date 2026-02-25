<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('category_translations')) {
            return;
        }

        $locales = ['en', 'ru'];

        DB::table('category')
            ->select('id')
            ->orderBy('id')
            ->get()
            ->each(function ($category) use ($locales): void {
                $lv = DB::table('category_translations')
                    ->where('category_id', $category->id)
                    ->where('locale', 'lv')
                    ->first();

                if (! $lv) {
                    return;
                }

                foreach ($locales as $locale) {
                    $exists = DB::table('category_translations')
                        ->where('category_id', $category->id)
                        ->where('locale', $locale)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    DB::table('category_translations')->insert([
                        'category_id' => $category->id,
                        'locale' => $locale,
                        'name' => $lv->name,
                        'slug' => $lv->slug,
                        'description' => $lv->description,
                        'meta_title' => $lv->meta_title ?? null,
                        'meta_description' => $lv->meta_description ?? null,
                        'og_description' => $lv->og_description ?? null,
                        'twitter_description' => $lv->twitter_description ?? null,
                        'meta_keywords' => $lv->meta_keywords ?? null,
                    ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('category_translations')) {
            return;
        }

        DB::table('category_translations')
            ->whereIn('locale', ['en', 'ru'])
            ->delete();
    }
};
