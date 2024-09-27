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
        Schema::create('ta_user_access', function (Blueprint $table) {
            $table->string('id')->unique()->primary();
            $table->string('sp_id')->nullable()->default('0');
            $table->string('mname')->nullable();
            $table->string('mtitle')->nullable();
            $table->string('mid')->nullable()->default('0');
            $table->string('is_access')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('py_user_access');
    }
};
