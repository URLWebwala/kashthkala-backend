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
        Schema::create('login_history', function (Blueprint $table) {
            defaultColumns($table, function (Blueprint $table) {
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('user_agent')->nullable();
                $table->timestamp('login_at')->useCurrent();
                $table->timestamp('logout_at')->nullable();
                $table->string('device_type')->nullable();
                $table->string('location')->nullable();
                $table->text('token')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_history');
    }
};
