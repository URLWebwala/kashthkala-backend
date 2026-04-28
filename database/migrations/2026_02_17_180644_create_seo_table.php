<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seo', function (Blueprint $table) {
            defaultColumns($table, function (Blueprint $table) {
                $table->string('meta_title', 191);
                $table->string('meta_author', 191)->nullable();
                $table->string('page_title', 191)->nullable();
                $table->string('page_name', 50);
                $table->longText('meta_description')->nullable();
                $table->longText('meta_keywords')->nullable();
                $table->string('slug', 191)->nullable()->unique();
                $table->string('canonical_url', 191)->nullable();
                $table->string('robots', 191)->default('index,follow');
                $table->tinyInteger('noindex')->default(0)->comment('0=index, 1=noindex');
                $table->tinyInteger('nofollow')->default(0)->comment('0=follow, 1=nofollow');
                $table->string('language', 10)->default('en');
                $table->string('website_h1', 191)->nullable();
                $table->longText('content')->nullable();
                $table->string('og_title', 191)->nullable();
                $table->longText('og_description')->nullable();
                $table->string('og_image', 255)->nullable();
                $table->string('og_url', 191)->nullable();
                $table->string('og_type', 50)->default('website');
                $table->string('og_locale', 10)->default('en_US');
                $table->string('twitter_card', 50)->default('summary_large_image');
                $table->string('twitter_title', 191)->nullable();
                $table->longText('twitter_description')->nullable();
                $table->string('twitter_image', 255)->nullable();
                $table->string('twitter_site', 100)->nullable();
                $table->string('twitter_creator', 100)->nullable();
                $table->longText('schema_json')->nullable();
                $table->decimal('page_priority', 2, 1)->default(0.5);
                $table->enum('changefreq', [
                    'always',
                    'hourly',
                    'daily',
                    'weekly',
                    'monthly',
                    'yearly',
                    'never'
                ])->default('monthly');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo');
    }
};
