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
        Schema::create('predefine_task', function (Blueprint $table) {

            $table->string('pre_task_id')->unique()->primary();
            $table->string('pre_task_name')->nullable();
            $table->string('pre_task_msg')->nullable();
            $table->tinyInteger('status')->default(0)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predefine_task');
    }
};
