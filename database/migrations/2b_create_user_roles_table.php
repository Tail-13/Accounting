<?php

use App\Traits\CustomTraits;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use CustomTraits;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("role_code");
            $table->unsignedBigInteger("user_id")->unique();

            $table->foreign("user_id")->references('id')->on('users')->onDelete('cascade');
            $table->foreign("role_code")->references('code')->on('roles')->onDelete('cascade');

            $this->baseProperties($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
