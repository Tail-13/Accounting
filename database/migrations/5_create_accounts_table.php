<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\CustomTraits;

return new class extends Migration
{
    use CustomTraits;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('account_type_id');
            $table->bigInteger('balance')->nullable()->default(0);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('cascade');

            $this->baseProperties($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
