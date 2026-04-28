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
        Schema::create('sub_category', function (Blueprint $table) {
            defaultColumns($table, function (Blueprint $table) {
                $table->integer('category_id');
                $table->string('subcategory_name')->unique();
                $table->text('subcategory_image');
                $table->string('subcategory_icon')->nullable();
                $table->string('subcategory_slug')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_category');
    }
};
