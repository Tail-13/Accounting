<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountRestController extends Controller
{
    public function get(Request $request){
        try {
            $account = new Account();
            return response()->json(['data' => $account->getByUser($request->user_id)], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], $e->getCode());
        }
    }
}
