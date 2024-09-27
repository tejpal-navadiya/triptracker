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
        Schema::create('py_subscription_plans', function (Blueprint $table) {
            $table->string('sp_id')->unique()->primary();
            $table->string('sp_name')->nullable();
            $table->string('sp_amount')->nullable()->default('0');
            $table->string('sp_month')->nullable()->default('0');
            $table->text('sp_desc')->nullable();
            $table->string('sp_user')->nullable();
            $table->tinyInteger('sp_status')->default('0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('py_subscription_plans');
    }
};
