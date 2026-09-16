<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('project_type_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_type_id')->constrained('project_types')->cascadeOnDelete();
            $table->string('lang_key', 5);
            $table->string('name')->nullable();
            $table->timestamps();
            $table->unique(['project_type_id', 'lang_key']);
        });

        Schema::create('project_places', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('project_place_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_place_id')->constrained('project_places')->cascadeOnDelete();
            $table->string('lang_key', 5);
            $table->string('name')->nullable();
            $table->timestamps();
            $table->unique(['project_place_id', 'lang_key']);
        });

        Schema::create('project_cities', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('project_city_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_city_id')->constrained('project_cities')->cascadeOnDelete();
            $table->string('lang_key', 5);
            $table->string('name')->nullable();
            $table->timestamps();
            $table->unique(['project_city_id', 'lang_key']);
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('place', 32)->default('indoor');
            $table->string('type', 32)->default('konut');
            $table->string('city', 64)->nullable();
            $table->string('city_label', 120)->nullable();
            $table->string('product_name')->nullable();
            $table->string('year', 16)->nullable();
            $table->string('area', 64)->nullable();
            $table->string('main_image')->nullable();
            $table->json('gallery')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('home_status')->default(false);
            $table->timestamps();
            $table->index(['status', 'order']);
            $table->index(['place', 'type', 'city']);
        });

        Schema::create('project_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('lang_key', 5);
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('lead')->nullable();
            $table->longText('body')->nullable();
            $table->json('feats')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
            $table->unique(['project_id', 'lang_key']);
            $table->unique(['lang_key', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_translations');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_city_translations');
        Schema::dropIfExists('project_cities');
        Schema::dropIfExists('project_place_translations');
        Schema::dropIfExists('project_places');
        Schema::dropIfExists('project_type_translations');
        Schema::dropIfExists('project_types');
    }
};
