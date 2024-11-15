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
        Schema::create('email_category', function (Blueprint $table) {
            $table->integer('email_cat_id')->unique()->autoIncrement();
            $table->string('email_cat_name')->nullable();
            $table->tinyInteger('email_cat_status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_category');
    }
};
