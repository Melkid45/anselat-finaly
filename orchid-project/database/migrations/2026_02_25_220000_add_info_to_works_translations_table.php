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
        Schema::table('works_translations', function (Blueprint $table): void {
            $table->json('info')->nullable()->after('place');
        });

        DB::table('works')
            ->select(['id', 'info'])
            ->orderBy('id')
            ->get()
            ->each(function ($work): void {
                if (empty($work->info)) {
                    return;
                }

                DB::table('works_translations')
                    ->where('works_id', $work->id)
                    ->where('locale', 'lv')
                    ->update(['info' => $work->info]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('works_translations', function (Blueprint $table): void {
            $table->dropColumn('info');
        });
    }
};
