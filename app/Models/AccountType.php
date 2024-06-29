<?php

namespace App\Models;

use App\Traits\CustomModelTraits;
use App\Traits\CustomTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class AccountType extends Model
{
    use HasFactory;
    use CustomTraits;
    public $timestamps = false;
    protected $fillable = ["*"];
    public function account(){
        return $this->hasMany(Account::class);
    }

    public static function getAll() {
        try {
            $accountType = AccountType::where('is_deleted', false)->get();
            if($accountType->isEmpty()) {
                throw new \Exception("no data found", Response::HTTP_NO_CONTENT);
            }
            return $accountType;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create($data){
        try {
            $type = new AccountType();
            if ($type->where('is_deleted', false)->where('name', 'ilike', $data['name'])->exists) {
                throw new \Exception($data['name'] . ' already exists!', Response::HTTP_CONFLICT);
            }
            $type->name = $data['name'];
            $type->description = $data['description'];
            $type->baseCreate($data['user_id']);
            return $type;
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function edit($data, $id) {
        try {
            $type = AccountType::where('is_deleted', false)->find($id);

            if(AccountType::where('name', 'ilike', $data['name'])->exists()) {
                throw new \Exception($data['name'] . ' already exists', Response::HTTP_CONFLICT);
            }
            if($type) {
                if ($type->required) {
                    throw new \Exception($data['name'] . ' should not be altered', Response::HTTP_FORBIDDEN);
                }

                $type->name = $data['name'];
                $type->description = $data['description'] ?? $type->description;
                $type->baseUpdate($data['user_id']);
                return $type;
            }
            throw new \Exception("account type (ID = $id) not found", Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function softDelete($user_id, $id){
        try {
            $type = AccountType::where('is_deleted', false)->find($id);
            if ($type) {
                $type->baseDelete($user_id, "account");
                return $type;
            }
            throw new \Exception("account type (ID = $id) not found", Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore($user_id, $id){
        try {
            $type = AccountType::where('is_deleted', true)->find($id);
            if ($type) {
                $type->baseRestore($user_id, "account");
                return $type;
            }
            throw new \Exception("account type (ID = $id) not found", Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
