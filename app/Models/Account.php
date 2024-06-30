<?php

namespace App\Models;

use App\Traits\CustomTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class Account extends Model
{
    use HasFactory, CustomTraits;

    public $timestamps = false;
    protected $fillable = ["*"];
    protected $hidden = self::baseAttribute;

    public function journal() {
        return $this->hasMany(Journal::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function accountType(){
        return $this->belongsTo(AccountType::class);
    }

    public function getByUser($user_id) {
        try {
            $account = Account::where('user_id', $user_id)->get();
            if ($account->count() > 0) {
                return $account;
            }
            throw new \Exception("user (ID = $user_id) has no data", Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create($data){
        try {
            $account = new Account();

            if($account->where('name', 'ilike', $data['name'])->where('user_id', $data['user_id'])->exists()){
                throw new \Exception($data['name'] . ' already exists', Response::HTTP_CONFLICT);
            }

            $account->name = $data['name'];
            $account->description = $data['description'];
            $account->account_type_id = $data['account_type_id'];
            $account->balance = $data['balance'];
            $account->user_id = $data['user_id'];
            $account->baseCreate($data['user_id']);
            return $account;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit($data, $id){
        try {
            $account = Account::where('user_id', $data['user_id'])
            ->where('is_deleted', false)
            ->where('name', 'ilike', $data['name']);

            if($account->exists()) {
                throw new \Exception($data['name'] . ' already exists', Response::HTTP_CONFLICT);
            }

            $account = Account::where('is_deleted', false)->where('user_id', $data['user_id'])->find($id);
            if($account->user_id == $data['user_id']) {
                $account->name = $data['name'] ?? $account->name;
                $account->description = $data['description'] ?? $account->description;
                $account->account_type_id = $data['account_type_id'] ?? $account->account_type_id;
                $account->balance = $data['balance'] ?? $account->balance;
                $account->baseUpdate($data['user_id']);
                return $account;
            }
            throw new \Exception("account (ID = $id) not found", Response::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function softDelete($user_id, $id){
        try {
            $account = Account::where('is_deleted', false)->find($id);
            if($account){
                $account->baseDelete($user_id, "journal");
                return $account;
            }
            throw new \Exception("account (ID = $id) not found", Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore($user_id, $id){
        try {
            $account = Account::where('is_deleted', false)->find($id);
            if($account) {
                $account->baseRestore($user_id, "journal");
                return $account;
            }
            throw new \Exception("account (ID = $id) not found", Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getMessage());
        }
    }
}
