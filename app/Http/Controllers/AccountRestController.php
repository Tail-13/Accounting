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

    public function create(Request $request) {
        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'account_type_id' => $request->account_type_id,
                'balance' => $request->balance,
                'user_id' => $request->user_id,
            ];
            $account = new Account();
            $account->create($data);
            return response()->json(['success' => $data['name'] . ' account created'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], $e->getCode());
        }
    }
}
