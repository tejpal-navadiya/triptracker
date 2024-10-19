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
        Schema::create('service_error_log', function (Blueprint $table) {
            $table->increments('service_log_id');
            $table->string('service_name');
            $table->string('user_id')->default(0);
            $table->text('message');
            $table->text('requested_field');
            $table->text('response_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_error_log');
    }
};
