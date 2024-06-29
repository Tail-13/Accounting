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
        Schema::create('access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->text('for');
            $table->unsignedBigInteger('user_id');
            $table->string('ip');
            $table->string('user_agent');
            $table->timestamp('expires_at');
            $table->timestamp('last_accessed');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_tokens');
    }
};
