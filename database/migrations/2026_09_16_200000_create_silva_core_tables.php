<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('company')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 5)->unique();
            $table->boolean('is_default')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        $now = now();
        DB::table('languages')->insert([
            [
                'name' => 'Türkçe',
                'code' => 'tr',
                'is_default' => true,
                'status' => true,
                'order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'English',
                'code' => 'en',
                'is_default' => false,
                'status' => true,
                'order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        Schema::create('static_translations', function (Blueprint $table) {
            $table->id();
            $table->string('lang_key', 5);
            $table->string('group')->default('general');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->unique(['lang_key', 'group', 'key']);
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('type')->default('generic');
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('content_text')->nullable();
            $table->string('image')->nullable();
            $table->longText('body_content')->nullable();
            $table->longText('extra_content')->nullable();
            $table->string('why_us_main_title')->nullable();
            $table->string('why_us_title_1')->nullable();
            $table->text('why_us_desc_1')->nullable();
            $table->string('why_us_title_2')->nullable();
            $table->text('why_us_desc_2')->nullable();
            $table->string('why_us_title_3')->nullable();
            $table->text('why_us_desc_3')->nullable();
            $table->string('mission_title')->nullable();
            $table->text('mission_content')->nullable();
            $table->json('extras')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->timestamps();
        });

        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->string('lang_key', 5);
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('content_text')->nullable();
            $table->text('body_content')->nullable();
            $table->text('extra_content')->nullable();
            $table->json('extras')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->string('why_us_main_title')->nullable();
            $table->string('why_us_title_1')->nullable();
            $table->text('why_us_desc_1')->nullable();
            $table->string('why_us_title_2')->nullable();
            $table->text('why_us_desc_2')->nullable();
            $table->string('why_us_title_3')->nullable();
            $table->text('why_us_desc_3')->nullable();
            $table->string('mission_title')->nullable();
            $table->text('mission_content')->nullable();
            $table->timestamps();
            $table->unique(['page_id', 'lang_key']);
        });

        // product_id FK is added in catalog migration after products exist
        Schema::create('offer_requests', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source', 30)->default('form');
            $table->string('status', 30)->default('pending');
            $table->string('name');
            $table->string('company');
            $table->string('email');
            $table->string('phone');
            $table->json('areas')->nullable();
            $table->string('budget')->nullable();
            $table->string('employees')->nullable();
            $table->json('configuration')->nullable();
            $table->json('items')->nullable();
            $table->text('message')->nullable();
            $table->text('admin_note')->nullable();
            $table->boolean('is_read')->default(false);
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_requests');
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('static_translations');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('admins');
    }
};
