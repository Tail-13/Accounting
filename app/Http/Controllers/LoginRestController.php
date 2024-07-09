<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class LoginRestController extends Controller
{
    public function login(Request $request) {
        try {
            $type = filter_var($request->user, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = User::where($type, $request->user)->where('is_deleted', false)->first();
            if($user){
                $expires = $request->remember ? Carbon::now()->addMonths(3) : Carbon::now()->addHours(3);
                if(Hash::check($request->password, $user->password)){
                    $accessToken = new AccessToken();
                    $data = [
                        'user_id' => $user->id,
                        'for' => $request->user . ' login',
                        'expires_at' => $expires,
                        'user_agent' => $request->header('User-Agent'),
                        'ip' => $request->ip(),
                        'last_accessed' => Carbon::now(),
                    ];
                    $token = $accessToken->create($data)->token;
                    return response()->json(['access_token' => $token], Response::HTTP_OK);
                }
            }
            return response()->json(['errors' => "incorrect $type or password"], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], $e->getCode());
        }
    }

    public function logout(Request $request) {
        try {
            if($request->user_id) {
                if($request->header('user_agent')) {

                }
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], $e->getCode());
        }
    }
}
