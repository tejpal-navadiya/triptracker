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
        Schema::create('buss_master_users', function (Blueprint $table) {
            
            $table->id();
            $table->string('user_first_name')->nullable();
            $table->string('user_last_name')->nullable();
            $table->string('user_email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('user_phone')->nullable();
            $table->string('user_password')->nullable();
            $table->string('user_image')->nullable();
            $table->string('user_business_name')->nullable();
            $table->string('buss_unique_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->string('user_city_name')->nullable();
            $table->string('user_pincode')->nullable();
            $table->rememberToken();
            $table->integer('sp_id')->nullable();
            $table->string('sp_expiry_date')->nullable();
            $table->tinyInteger('isActive')->default(0)->nullable();
            $table->tinyInteger('user_status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buss_master_users');
    }
};
