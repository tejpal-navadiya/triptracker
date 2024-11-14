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
        Schema::create('library', function (Blueprint $table) {
            $table->integer('lib_id')->unique()->autoIncrement();
            $table->string('lib_category')->nullable();
            $table->string('lib_name')->nullable();
            $table->string('tag_name')->nullable();
            $table->text('lib_basic_information')->nullable();
            $table->text('lib_image')->nullable();
            $table->tinyInteger('lib_status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library');
    }
};
