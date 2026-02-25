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
        if (! Schema::hasTable('works_translations')) {
            return;
        }

        $targetLocales = ['en', 'ru'];

        DB::table('works')
            ->select('id')
            ->orderBy('id')
            ->get()
            ->each(function ($work) use ($targetLocales): void {
                $lv = DB::table('works_translations')
                    ->where('works_id', $work->id)
                    ->where('locale', 'lv')
                    ->first();

                if (! $lv) {
                    return;
                }

                foreach ($targetLocales as $locale) {
                    $existing = DB::table('works_translations')
                        ->where('works_id', $work->id)
                        ->where('locale', $locale)
                        ->first();

                    $payload = [
                        'name' => $existing?->name ?: $lv->name,
                        'description' => $existing?->description ?: $lv->description,
                        'client' => $existing?->client ?: $lv->client,
                        'date' => $existing?->date ?: $lv->date,
                        'place' => $existing?->place ?: $lv->place,
                        'info' => $existing?->info ?: $lv->info,
                        'about_title' => $existing?->about_title ?: $lv->about_title,
                        'meta_title' => $existing?->meta_title ?: $lv->meta_title,
                        'meta_description' => $existing?->meta_description ?: $lv->meta_description,
                        'og_description' => $existing?->og_description ?: $lv->og_description,
                        'twitter_description' => $existing?->twitter_description ?: $lv->twitter_description,
                        'meta_keywords' => $existing?->meta_keywords ?: $lv->meta_keywords,
                    ];

                    $incomingSlug = $existing?->slug ?: $lv->slug;
                    $payload['slug'] = $this->makeUniqueSlug($locale, $incomingSlug, $work->id);

                    if ($existing) {
                        DB::table('works_translations')
                            ->where('id', $existing->id)
                            ->update($payload);
                    } else {
                        DB::table('works_translations')->insert(array_merge([
                            'works_id' => $work->id,
                            'locale' => $locale,
                        ], $payload));
                    }
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left empty. This migration only backfills missing values.
    }

    private function makeUniqueSlug(string $locale, ?string $slug, int $worksId): ?string
    {
        $slug = trim((string) $slug);
        if ($slug === '') {
            return null;
        }

        $baseSlug = $slug;
        $candidate = $baseSlug;
        $suffix = 2;

        while (
            DB::table('works_translations')
                ->where('locale', $locale)
                ->where('slug', $candidate)
                ->where('works_id', '!=', $worksId)
                ->exists()
        ) {
            $candidate = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $candidate;
    }
};
