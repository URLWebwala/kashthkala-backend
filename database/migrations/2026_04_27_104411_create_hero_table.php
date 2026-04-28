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
        Schema::create('hero', function (Blueprint $table) {
            defaultColumns($table, function (Blueprint $table) {
                $table->string('tagline')->nullable();
                $table->string('main_title')->nullable();
                $table->text('description')->nullable();
                $table->string('primary_button_text')->nullable();
                $table->string('primary_link')->nullable();
                $table->string('secondary_button_text')->nullable();
                $table->string('secondary_link')->nullable();
                $table->string('image')->nullable();
                $table->integer('display_order')->default(1);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero');
    }
};
