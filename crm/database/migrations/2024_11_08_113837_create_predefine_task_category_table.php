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
        Schema::create('predefine_task_category', function (Blueprint $table) {
            $table->integer('task_cat_id')->unique()->autoIncrement();
            $table->string('task_cat_name')->nullable();
            $table->tinyInteger('task_cat_status')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predefine_task_category');
    }
};
