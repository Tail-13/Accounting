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
        Schema::create('journal_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_id');
            $table->unsignedBigInteger('account_id');
            $table->bigInteger('amount');
            $table->enum('entry', ['debit','','credit']);

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('journal_id')->references('id')->on('journals')->onDelete('cascade');

            $this->baseProperties($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_details');
    }
};
