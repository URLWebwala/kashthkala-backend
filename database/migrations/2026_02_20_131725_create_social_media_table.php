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
        Schema::create('social_media', function (Blueprint $table) {
            defaultColumns($table, function (Blueprint $table) {
                $table->text('whatsapp_url')->nullable();
                $table->text('facebook_url')->nullable();
                $table->text('twitter_url')->nullable();
                $table->text('instagram_url')->nullable();
                $table->text('linkedin_url')->nullable();
                $table->text('youtube_url')->nullable();
                $table->string('whatsapp_icon')->nullable();
                $table->string('facebook_icon')->nullable();
                $table->string('twitter_icon')->nullable();
                $table->string('instagram_icon')->nullable();
                $table->string('linkedin_icon')->nullable();
                $table->string('youtube_icon')->nullable();
                $table->string('mobile')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media');
    }
};
