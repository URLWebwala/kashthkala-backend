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
        Schema::create('branding_settings', function (Blueprint $table) {
            defaultColumns($table, function (Blueprint $table) {
                $table->string('website_logo')->nullable();
                $table->string('website_favicon')->nullable();
                $table->string('meta_favicon')->nullable();
                $table->string('app_favicon')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branding_settings');
    }
};
