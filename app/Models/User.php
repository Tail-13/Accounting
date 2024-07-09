<?php

namespace App\Models;
use App\Traits\CustomTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    use HasFactory;
    use CustomTraits;
    public $timestamps = false;

    protected $guarded = ["id"];
    protected $fillable = ['*'];

    protected $hidden = [
        'password',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
        'is_deleted',
    ];

    public function setPasswordAttribute($password) {
        $this->attributes["password"] = Hash::make($password);
    }

    public function create($data) {
        try {
            $user = new User();

            if($user->where('username', $data['username'])->exists()) {
                throw new \Illuminate\Validation\ValidationException("username " . $data['username'] . " already exists", Response::HTTP_BAD_REQUEST);
            }

            if($user->where('email', $data['email'])->exists()){
                throw new \Exception("email " . $data['email'] . " already exists", Response::HTTP_BAD_REQUEST);
            }

            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->created_at = now();
            $user->save();

            return $user;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit($data, $id) {
        try {
            $user = User::find($id);

            if(!$user) {
                throw new \Exception("user (ID = $id) not found", Response::HTTP_BAD_REQUEST);
            }
            if(User::where('username', $data['username'])->orWhere('email', $data['email'])->exists()){
                throw new \Exception("user already existts", Response::HTTP_CONFLICT);
            }

            $user->username = $data['username'];
            $user->password = $data['password'];
            $user->email = $data['email'];
            $user->updated_at = now();
            $user->updated_by = $data['user_id'];
            $user->save();
            return $user;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function softDelete($user_id, $id) {
        try {
            $user = User::where('is_deleted', false)->find($id);
            if($user) {
                $user->baseDelete($user_id);
                return $user;
            }
            throw new \Exception("user not found", Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function restore($user_id, $id) {
        try {
            $user = User::where('is_deleted', false)->find($id);
            if($user) {
                $user->baseRestore($user_id);
                return $user;
            }
            throw new \Exception("user not found", Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
