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
        Schema::create('library_category', function (Blueprint $table) {
            $table->integer('lib_cat_id')->unique()->autoIncrement();
            $table->string('lib_cat_name')->nullable();
            $table->tinyInteger('lib_cat_status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_category');
    }
};
