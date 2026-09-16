<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->string('icon_home')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('home_status')->default(true);
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->timestamps();
        });

        Schema::create('product_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->string('lang_key', 5);
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('home_description')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable();
            $table->string('color', 64)->nullable();
            $table->string('panel_size', 64)->nullable();
            $table->string('size_extra', 120)->nullable();
            $table->string('thick', 32)->nullable();
            $table->boolean('indoor')->default(true);
            $table->boolean('outdoor')->default(true);
            $table->boolean('depot')->default(true);
            $table->string('title')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('main_image')->nullable();
            $table->string('hover_image')->nullable();
            $table->string('source_url')->nullable();
            $table->json('gallery')->nullable();
            $table->json('documents')->nullable();
            $table->json('features')->nullable();
            $table->json('technical_specs')->nullable();
            $table->json('certificates')->nullable();
            $table->json('advantages')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('home_status')->default(true);
            $table->string('badge', 32)->nullable();
            $table->unsignedInteger('menu_order')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->timestamps();
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('lang_key', 5);
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('why_title')->nullable();
            $table->text('certificates_intro')->nullable();
            $table->text('advantages_intro')->nullable();
            $table->string('cta_title')->nullable();
            $table->text('cta_text')->nullable();
            $table->json('features')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->json('technical_specs')->nullable();
            $table->json('certificates')->nullable();
            $table->json('advantages')->nullable();
            $table->timestamps();
        });

        Schema::table('offer_requests', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });

        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('hex', 32)->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('product_color_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_color_id')->constrained('product_colors')->cascadeOnDelete();
            $table->string('lang_key', 5);
            $table->string('name')->nullable();
            $table->timestamps();
            $table->unique(['product_color_id', 'lang_key']);
        });

        Schema::create('product_badges', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('product_badge_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_badge_id')->constrained('product_badges')->cascadeOnDelete();
            $table->string('lang_key', 5);
            $table->string('name')->nullable();
            $table->timestamps();
            $table->unique(['product_badge_id', 'lang_key']);
        });

        $now = now();
        $badges = [
            ['slug' => 'yeni', 'name_tr' => 'Yeni', 'name_en' => 'New', 'order' => 1],
            ['slug' => 'cok-satan', 'name_tr' => 'Çok Satan', 'name_en' => 'Best Seller', 'order' => 2],
            ['slug' => 'indirim', 'name_tr' => 'İndirim', 'name_en' => 'Sale', 'order' => 3],
        ];

        foreach ($badges as $badge) {
            $id = DB::table('product_badges')->insertGetId([
                'slug' => $badge['slug'],
                'order' => $badge['order'],
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            DB::table('product_badge_translations')->insert([
                [
                    'product_badge_id' => $id,
                    'lang_key' => 'tr',
                    'name' => $badge['name_tr'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'product_badge_id' => $id,
                    'lang_key' => 'en',
                    'name' => $badge['name_en'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }

        Schema::create('product_features', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('filter_key', 32);
            $table->string('filter_value', 64);
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('product_feature_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_feature_id')->constrained('product_features')->cascadeOnDelete();
            $table->string('lang_key', 5);
            $table->string('name')->nullable();
            $table->timestamps();
            $table->unique(['product_feature_id', 'lang_key']);
        });

        $features = [
            ['slug' => 'size-600x1200', 'filter_key' => 'size', 'filter_value' => '600x1200', 'order' => 1, 'tr' => '600×1200', 'en' => '600×1200'],
            ['slug' => 'thick-3-4', 'filter_key' => 'thick', 'filter_value' => '3-4', 'order' => 2, 'tr' => '3–4 mm', 'en' => '3–4 mm'],
            ['slug' => 'thick-3-6', 'filter_key' => 'thick', 'filter_value' => '3-6', 'order' => 3, 'tr' => '3–6 mm', 'en' => '3–6 mm'],
            ['slug' => 'indoor', 'filter_key' => 'indoor', 'filter_value' => '1', 'order' => 4, 'tr' => 'İç mekana uygun', 'en' => 'Suitable for indoor use'],
            ['slug' => 'outdoor', 'filter_key' => 'outdoor', 'filter_value' => '1', 'order' => 5, 'tr' => 'Dış mekana uygun', 'en' => 'Suitable for outdoor use'],
            ['slug' => 'depot', 'filter_key' => 'depot', 'filter_value' => '1', 'order' => 6, 'tr' => 'Stokta', 'en' => 'In stock'],
        ];

        foreach ($features as $row) {
            $id = DB::table('product_features')->insertGetId([
                'slug' => $row['slug'],
                'filter_key' => $row['filter_key'],
                'filter_value' => $row['filter_value'],
                'order' => $row['order'],
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            DB::table('product_feature_translations')->insert([
                [
                    'product_feature_id' => $id,
                    'lang_key' => 'tr',
                    'name' => $row['tr'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'product_feature_id' => $id,
                    'lang_key' => 'en',
                    'name' => $row['en'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('offer_requests', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::dropIfExists('product_feature_translations');
        Schema::dropIfExists('product_features');
        Schema::dropIfExists('product_badge_translations');
        Schema::dropIfExists('product_badges');
        Schema::dropIfExists('product_color_translations');
        Schema::dropIfExists('product_colors');
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_category_translations');
        Schema::dropIfExists('product_categories');
    }
};
