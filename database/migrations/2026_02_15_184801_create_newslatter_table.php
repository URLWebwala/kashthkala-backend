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
        Schema::create('newslatter', function (Blueprint $table) {
            defaultColumns($table, function (Blueprint $table) {
                $table->text('email')->nullable();
                $table->text('phone')->nullable();
                $table->text('service_id')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newslatter');
    }
};
