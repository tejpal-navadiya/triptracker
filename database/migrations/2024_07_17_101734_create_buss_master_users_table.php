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
        
            $table->string('id')->unique()->primary();
            $table->string('user_agencies_name')->nullable();
            $table->string('user_franchise_name')->nullable();
            $table->string('user_consortia_name')->nullable();
            $table->string('user_first_name')->nullable();
            $table->string('user_last_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_iata_clia_number')->nullable();
            $table->string('user_clia_number')->nullable();
            $table->string('user_iata_number')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('user_password')->nullable();
            $table->string('user_image')->nullable();
            $table->string('user_address')->nullable();
            $table->string('buss_unique_id')->nullable();
            $table->integer('user_state')->nullable();
            $table->string('user_city')->nullable();
            $table->string('user_zip')->nullable();
            $table->rememberToken();
            $table->string('sp_id')->nullable();
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
