<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountTypeRestController extends Controller
{
    public function get($id = null) {
        try {
            if ($id) {
                return response()->json(['data' => AccountType::getById($id)], Response::HTTP_OK);
            }
            $accountTypes = AccountType::getAll();
            return response()->json(['data' => $accountTypes], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function create(Request $request) {
        try {
            if(UserRole::checkRole($request->user_id, 'admin')){
                
            }
        } catch (\Exception $e) {

        }
    }
}
