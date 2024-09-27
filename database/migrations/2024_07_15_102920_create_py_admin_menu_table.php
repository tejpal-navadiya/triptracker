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
        Schema::create('py_admin_menu', function (Blueprint $table) {
            $table->string('mid')->unique()->primary();
            $table->string('mname')->nullable();
            $table->string('mtitle')->nullable();
            $table->integer('pmenu')->nullable()->default('0');
            $table->integer('is_deleted')->nullable()->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('py_admin_menu');
    }
};
