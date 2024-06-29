<?php

namespace App\Http\Middleware\Custom;

use App\Models\AccessToken;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        AccessToken::deleteExpiredTokens();

        $token = AccessToken::where("token", $request->bearerToken())->first();
        if($token){
            if($token->expires_at > Carbon::now()){
                $token->last_accessed = Carbon::now();
                $token->save();
                if($request->ip() == $token->ip){
                    $user = User::where("id", $token->user_id)->first();
                    $request->merge(["user_id"=> $user->id]);
                    return $next($request);
                }
            }
        }
        return response()->json(["message"=> "unauthorized access!"], Response::HTTP_FORBIDDEN);
    }
}
