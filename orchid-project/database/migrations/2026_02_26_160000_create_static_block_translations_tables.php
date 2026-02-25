<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('about_translations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('about_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['about_id', 'locale']);
            $table->foreign('about_id')->references('id')->on('about')->onDelete('cascade');
        });

        Schema::create('company_translations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->json('items')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'locale']);
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
        });

        Schema::create('material_page_translations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('material_page_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->string('soft_title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['material_page_id', 'locale']);
            $table->foreign('material_page_id')->references('id')->on('material_page')->onDelete('cascade');
        });

        Schema::create('partners_translations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('partners_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->timestamps();
            $table->unique(['partners_id', 'locale']);
            $table->foreign('partners_id')->references('id')->on('partners')->onDelete('cascade');
        });

        Schema::create('counter_translations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('counter_id');
            $table->string('locale')->index();
            $table->json('items')->nullable();
            $table->timestamps();
            $table->unique(['counter_id', 'locale']);
            $table->foreign('counter_id')->references('id')->on('counter')->onDelete('cascade');
        });

        Schema::create('work_block_translations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('work_block_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->timestamps();
            $table->unique(['work_block_id', 'locale']);
            $table->foreign('work_block_id')->references('id')->on('work_block')->onDelete('cascade');
        });

        Schema::create('request_translations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['request_id', 'locale']);
            $table->foreign('request_id')->references('id')->on('request')->onDelete('cascade');
        });

        Schema::create('materials_translations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('materials_id');
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['materials_id', 'locale']);
            $table->foreign('materials_id')->references('id')->on('materials')->onDelete('cascade');
        });

        $now = now();

        DB::table('about')->orderBy('id')->get()->each(function ($row) use ($now): void {
            DB::table('about_translations')->insert([
                'about_id' => $row->id,
                'locale' => 'lv',
                'title' => $row->title,
                'description' => $row->description,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        DB::table('company')->orderBy('id')->get()->each(function ($row) use ($now): void {
            DB::table('company_translations')->insert([
                'company_id' => $row->id,
                'locale' => 'lv',
                'title' => $row->title,
                'items' => $row->items,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        DB::table('material_page')->orderBy('id')->get()->each(function ($row) use ($now): void {
            DB::table('material_page_translations')->insert([
                'material_page_id' => $row->id,
                'locale' => 'lv',
                'title' => $row->title,
                'soft_title' => $row->soft_title,
                'description' => $row->description,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        DB::table('partners')->orderBy('id')->get()->each(function ($row) use ($now): void {
            DB::table('partners_translations')->insert([
                'partners_id' => $row->id,
                'locale' => 'lv',
                'title' => $row->title,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        DB::table('counter')->orderBy('id')->get()->each(function ($row) use ($now): void {
            DB::table('counter_translations')->insert([
                'counter_id' => $row->id,
                'locale' => 'lv',
                'items' => $row->items,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        DB::table('work_block')->orderBy('id')->get()->each(function ($row) use ($now): void {
            DB::table('work_block_translations')->insert([
                'work_block_id' => $row->id,
                'locale' => 'lv',
                'title' => $row->title,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        DB::table('request')->orderBy('id')->get()->each(function ($row) use ($now): void {
            DB::table('request_translations')->insert([
                'request_id' => $row->id,
                'locale' => 'lv',
                'title' => $row->title,
                'description' => $row->description,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        DB::table('materials')->orderBy('id')->get()->each(function ($row) use ($now): void {
            DB::table('materials_translations')->insert([
                'materials_id' => $row->id,
                'locale' => 'lv',
                'name' => $row->name,
                'description' => $row->description,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials_translations');
        Schema::dropIfExists('request_translations');
        Schema::dropIfExists('work_block_translations');
        Schema::dropIfExists('counter_translations');
        Schema::dropIfExists('partners_translations');
        Schema::dropIfExists('material_page_translations');
        Schema::dropIfExists('company_translations');
        Schema::dropIfExists('about_translations');
    }
};
