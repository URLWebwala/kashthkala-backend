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
        Schema::create('smtp_settings', function (Blueprint $table) {
            defaultColumns($table, function (Blueprint $table) {
                $table->string('host')->nullable();
                $table->string('port')->nullable();
                $table->string('username')->nullable();
                $table->text('password')->nullable();
                $table->enum('encryption', ['tls', 'ssl'])->default('tls');
                $table->string('from_address')->nullable();
                $table->string('from_name')->nullable();
                $table->string('reply_to_address')->nullable();
                $table->string('reply_to_name')->nullable();
                $table->string('cc_address')->nullable();
                $table->string('bcc_address')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smtp_settings');
    }
};
