<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountTypeRestController extends Controller
{
    public function get(Request $request) {
        try {
            $accountTypes = AccountType::getAll();
            return response()->json(['data' => $accountTypes], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
