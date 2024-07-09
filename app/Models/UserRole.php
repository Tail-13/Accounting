<?php

namespace App\Models;

use App\Traits\CustomTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory, CustomTraits;
    public $timestamps = false;
    protected $fillable = ['*'];
    protected $hidden = self::baseAttribute;

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

    public static function checkRole($user_id, $roleName) {
        $userRole = new UserRole();
        $role = $userRole->with('role')->where('user_id', $user_id)->first();
        if($role->role->name == $roleName) {
            return true;
        }
        return false;
    }
}
