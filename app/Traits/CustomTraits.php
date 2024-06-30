<?php

namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;

trait CustomTraits {
    const baseAttribute = [
        'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_by', 'deleted_at', 'is_deleted'
    ];

    public function baseProperties($table) {
        $table->boolean("is_deleted")->default(false);
        $table->unsignedBigInteger('created_by');
        $table->timestamp('created_at');
        $table->unsignedBigInteger('updated_by')->nullable(true);
        $table->timestamp('updated_at')->nullable(true);
        $table->unsignedBigInteger('deleted_by')->nullable(true);
        $table->timestamp('deleted_at')->nullable(true);

        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
    }

    public function baseCreate($user_id) {
        $user = User::find($user_id);
        if($user) {
            $this->created_by = $user->id;
            $this->created_at = Carbon::now();
            $this->save();
            return true;
        }
        return false;
    }

    public function baseUpdate($user_id) {
        $user = User::find($user_id);
        if($user) {
            $this->updated_by = $user->id;
            $this->updated_at = Carbon::now();
            $this->save();
            return true;
        }
        return false;
    }

    public function baseDelete($user_id, $relation = null) {
        $user = User::find($user_id);
        if ($user) {
            $this->is_deleted = true;
            $this->deleted_by = $user->id;
            $this->deleted_at = Carbon::now();
            $this->save();

            if ($relation && method_exists($this, $relation)) {
                foreach ($this->$relation as $relate) {
                    $relate->baseDelete($user_id);
                }
            }
            return true;
        }
        return false;
    }

    public function baseRestore($user_id, $relation = null) {
        $user = User::find($user_id);
        if($user) {
            $this->is_deleted = false;
            $this->updated_at = Carbon::now();
            $this->updated_by = $user_id;
            $this->deleted_at = null;
            $this->deleted_by = null;
            $this->save();

            if ($relation && method_exists($this, $relation)) {
                foreach ($this->$relation as $relate) {
                    $relate->baseRestore($user_id);
                }
            }
            return true;
        }
        return false;
    }
}
