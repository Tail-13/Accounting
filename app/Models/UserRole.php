<?php

namespace App\Models;

use App\Traits\CustomTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;
    use CustomTraits;
    public $timestamps = false;
    protected $fillable = ['*'];

    public function role() {
        return $this->belongsTo(Role::class, 'role_code', 'code');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function get(){
        $userRole = new UserRole();
        $userRole->where('is_deleted', false)->get();
    }

    public function checkRole($user_id, $roleName = null) {
        $userRole = new UserRole();
        $role = $userRole->with('role')->where('user_id', $user_id)->first();
        return $role->role->name;
    }
}
