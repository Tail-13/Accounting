<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserRestController extends Controller
{
    public function register(Request $request) {
        try {
            $data = [
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->email
            ];
            $user = new User();
            return response()->json(['success' => $user->create($data)], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], $e->getCode());
        }
    }
}
