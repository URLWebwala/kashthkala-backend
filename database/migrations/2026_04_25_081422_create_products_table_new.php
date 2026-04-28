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
        Schema::create('products', function (Blueprint $table) {
            defaultColumns($table, function (Blueprint $table) {
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('sku')->nullable();
                $table->string('category_id')->nullable();
                $table->string('sub_category_id')->nullable();
                $table->string('brand')->nullable();
                $table->string('visibility')->default('public');
                $table->text('short_description')->nullable();
                $table->longText('full_description')->nullable();
                $table->decimal('price', 10, 2)->default(0);
                $table->decimal('sale_price', 10, 2)->nullable();
                $table->integer('stock_quantity')->default(0);
                $table->string('stock_status')->default('in_stock');
                $table->boolean('allow_backorders')->default(false);
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('keywords')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
