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
        Schema::table('category', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        DB::table('category')
            ->select('id', 'name')
            ->orderBy('id')
            ->get()
            ->each(function ($category): void {
                $baseSlug = Str::slug((string) $category->name);

                if ($baseSlug === '') {
                    $baseSlug = 'category-' . $category->id;
                }

                $slug = $baseSlug;
                $suffix = 2;

                while (
                    DB::table('category')
                        ->where('id', '!=', $category->id)
                        ->where('slug', $slug)
                        ->exists()
                ) {
                    $slug = $baseSlug . '-' . $suffix;
                    $suffix++;
                }

                DB::table('category')
                    ->where('id', $category->id)
                    ->update(['slug' => $slug]);
            });

        Schema::table('category', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
