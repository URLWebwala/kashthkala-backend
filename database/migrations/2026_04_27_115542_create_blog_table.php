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
        Schema::create('blog', function (Blueprint $table) {
            defaultColumns($table, function (Blueprint $table) {
                $table->string('title')->nullable();
                $table->string('slug')->nullable();
                $table->string('author_name')->nullable();
                $table->unsignedBigInteger('blog_category_id')->nullable();
                $table->string('visibility')->default('Public');
                $table->dateTime('published_at')->nullable();
                $table->string('image')->nullable();
                $table->longText('content')->nullable();
                $table->string('meta_title')->nullable();
                $table->string('meta_keyword')->nullable();
                $table->text('meta_description')->nullable();
                $table->integer('total_view')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog');
    }
};
