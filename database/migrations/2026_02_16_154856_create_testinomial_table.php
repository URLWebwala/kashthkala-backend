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
        Schema::create('testinomial', function (Blueprint $table) {
            defaultColumns($table, function (Blueprint $table) {
                $table->string('image')->nullable();
                $table->string('client_name')->nullable();
                $table->string('rating')->nullable();
                $table->string('designation')->nullable();
                $table->string('states')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testinomial');
    }
};
