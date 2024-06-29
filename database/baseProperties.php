<?php
namespace App\Database;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
 class baseProperties {
    public static function add(Blueprint $table) {
        $table->boolean('is_deleted')->default(false);
        $table->timestamp('created_at');
        $table->unsignedBigInteger('created_by');
        $table->timestamp('updated_at')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->timestamp('deleted_at')->nullable();
        $table->unsignedBigInteger('deleted_by')->nullable();

        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('updated_by')->references('id')->on('users');
        $table->foreign('deleted_by')->references('id')->on('users');
    }
}
