<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JournalRestController extends Controller
{
    public function get(Request $request, $user_id = null) {
        try {
            $journal = new Journal();
            if($user_id) {
                if(UserRole::where('is_deleted', false)->where('user_id', $request->user_id)->where('role_code', 0)->first()) {
                    return response()->json(['data' => $journal->getByUser($user_id)], Response::HTTP_OK);
                }
            }
            return response()->json(['data' => $journal->getByUser($request->user_id)], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], $e->getCode());
        }
    }

    public function create(Request $request) {
        try {
            $journal = new Journal();
            $data = [
                'user_id' => $request->user_id,
                'description' => $request->description,
                'date' => $request->date,
                'journal_details' => $request->journal_details,
            ];
            $journal->create($data);
            return response()->json(['success' => 'journal created'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], $e->getCode());
        }
    }

    public function edit(Request $request, $id) {
        try {
            $journal = new Journal();
            $role = UserRole::where('is_deleted', false)->where('user_id', $request->user_id)->where('role_code', 0)->first();
            $userJournal = Journal::where('user_id', $request->user_id)->where('is_deleted')->find($id);
            if($role || $userJournal) {
                $data = [
                    'description' => $request->description,
                    'date' => $request->date,
                    'journal_details' => $request->journal_details,
                ];
                $journal->edit($data, $id);
                return response()->json(['success' => 'journal edited'], Response::HTTP_OK);
            }
            return response()->json(["error" => 'forbidden access'], Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], $e->getCode());
        }
    }

    public function delete(Request $request, $id) {
        try {
            $role = UserRole::where('is_deleted', false)->where('user_id', $request->user_id)->where('role_code', 0)->first();
            $userJournal = Journal::where('user_id', $request->user_id)->where('is_deleted')->find($id);
            if($role || $userJournal) {
                $journal = new Journal();
                $journal->softDelete($request->user_id, $id);
                return response()->json(['success' => 'journal deleted'], Response::HTTP_OK);
            }
            return response()->json(["error" => 'forbidden access'], Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], $e->getCode());
        }
    }

    public function restore(Request $request, $id) {
        try {
            $role = UserRole::where('is_deleted', false)->where('user_id', $request->user_id)->where('role_code', 0)->first();
            $userJournal = Journal::where('user_id', $request->user_id)->where('is_deleted')->find($id);
            if($role || $userJournal) {
                $journal = new Journal();
                $journal->restore($request->user_id, $id);
                return response()->json(['success' => 'journal deleted'], Response::HTTP_OK);
            }
            return response()->json(["error" => 'forbidden access'], Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], $e->getCode());
        }
    }
}
