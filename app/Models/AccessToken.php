<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccessToken extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['*'];

    public function create($data){
        try {
            $user = User::where('is_deleted', false)->find($data['user_id']);
            if($user) {
                $token = Str::random(80);
                $accessToken = AccessToken::where('user_id', $user->id)->where('ip', $data['ip'])->where('user_agent', $data['user_agent'])->first();
                if($accessToken) {
                    $accessToken->token = $token;
                    $accessToken->for = $data['for'];
                    $accessToken->last_accessed = $data['last_accessed'];
                } else {
                    $accessToken = new AccessToken();
                    $accessToken->token = $token;
                    $accessToken->user_id = $user->id;
                    $accessToken->for = $data['for'];
                    $accessToken->ip = $data['ip'];
                    $accessToken->expires_at = $data['expires_at'];
                    $accessToken->user_agent = $data['user_agent'];
                    $accessToken->last_accessed = $data['last_accessed'];
                }
                $accessToken->save();
                return $accessToken;
            }
            throw new \Exception('user not found', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function hardDelete($data) {
        $accessTokens = AccessToken::where('user_id', $data['user_id'])
                        ->where('user_agent', $data['user_agent'])
                        ->where('ip', $data['ip'])->first();
        if($accessTokens) {
            $accessTokens->delete();
        }
    }

    public static function deleteExpiredTokens(){
        DB::delete("DELETE FROM access_tokens WHERE expires_at < NOW()");
    }
}
