<?php

namespace App\Models;

use App\Traits\CustomTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class Journal extends Model
{
    use HasFactory, CustomTraits;

    protected $hidden = self::baseAttribute;
    public $timestamps = false;

    public function detail() {
        return $this->hasMany(JournalDetail::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getByUser($user_id) {
        try {
            $journal = Journal::where('is_deleted', false)->where('user_id', $user_id)->with("detail")->get();
            if($journal->isNotEmpty()) {
                return $journal;
            }
            throw new \Exception("user (ID = $user_id) has no data", Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create($data){
        try {
            DB::transaction(function() use ($data){
                $journal = new Journal();
                $debit = $credit = 0;

                $journal->user_id = $data['user_id'];
                $journal->description = $data['description'];
                $journal->date = $data['date'];
                foreach($data['journal_details'] as $detail) {
                    $detail['entry'] == 'debit' ? $debit += $detail['amount'] : $credit += $detail['amount'];
                    $detailData[] = [
                        'account_id' => $detail['account_id'],
                        'amount' => $detail['amount'],
                        'entry' => $detail['entry'],
                        'user_id' => $data['user_id']
                    ];
                }
                if($debit == $credit) {
                    $journal->baseCreate($data['user_id']);
                    foreach($detailData as $data) {
                        $data['journal_id'] = $journal->id;
                        $journalDetail = new JournalDetail();
                        $journalDetail->create($data);
                    }
                    return $journal;
                }
                throw new \Exception("debit and credit not balanced", Response::HTTP_BAD_REQUEST);
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit($data, $id){
        try {
            DB::transaction(function() use ($data, $id) {
                $debit = $credit = 0;

                $journal = Journal::where('is_deleted', false)->where('user_id', $data['user_id'])->find($id);
                if(!$journal) {
                    throw new \Exception('journal edit failed: journal (ID = ' . $id . ') not found', Response::HTTP_NOT_FOUND);
                }

                $journal->description = $data['description'] ?? $journal->description;
                $journal->date = $data['date'] ?? $journal->date;

                if($data['journal_details']){
                    foreach($data['journal_details'] as $detail) {
                        $detail['entry'] == "debit" ? $debit += $detail['amount'] : $credit += $detail['amount'];
                    }
                    if($debit == $credit) {
                        foreach($data['journal_details'] as $detail) {
                            if($exists = JournalDetail::find($detail['id'])) {
                                $exists->account_id = $detail['account_id'] ?? $exists->account_id;
                                $exists->amount = $detail['amount'] ?? $exists->amount;
                                $exists->entry = $detail['entry'] ?? $exists->entry;
                            }
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function softDelete($user_id, $id) {
        try {
            $journal = Journal::where('is_deleted', false)->find($id);
            if($journal) {
                $journal->baseDelete($user_id, 'detail');
                return $journal;
            }
            throw new \Exception("journal (ID = $id) not found", Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function restore($user_id, $id) {
        try {
            $journal = Journal::where('is_deleted', true)->find($id);
            if($journal) {
                $journal->baseRestore($user_id, 'detail');
                return $journal->with('detail');
            }
            throw new \Exception("journal (ID = $id) not found", Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
