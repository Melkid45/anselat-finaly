<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('works', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        DB::table('works')
            ->select('id', 'name')
            ->orderBy('id')
            ->get()
            ->each(function ($work): void {
                $baseSlug = Str::slug((string) $work->name);
                if ($baseSlug === '') {
                    $baseSlug = 'work-' . $work->id;
                }

                $slug = $baseSlug;
                $suffix = 2;

                while (
                    DB::table('works')
                        ->where('id', '!=', $work->id)
                        ->where('slug', $slug)
                        ->exists()
                ) {
                    $slug = $baseSlug . '-' . $suffix;
                    $suffix++;
                }

                DB::table('works')
                    ->where('id', $work->id)
                    ->update(['slug' => $slug]);
            });

        Schema::table('works', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
