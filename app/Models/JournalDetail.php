<?php

namespace App\Models;

use App\Traits\CustomTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class JournalDetail extends Model
{
    use HasFactory, CustomTraits;
    protected $hidden = self::baseAttribute;

    public function journal(){
        return $this->belongsTo(Journal::class);
    }

    public function account() {
        return $this->belongsTo(Account::class);
    }

    public function create($data) {
        try {
            $newDetails = new JournalDetail();
            $newDetails->journal_id = $data['journal_id'];
            $newDetails->account_id = $data['account_id'];
            $newDetails->amount = $data['amount'];
            $newDetails->entry = $data['entry'];
            $newDetails->baseCreate($data['user_id']);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit($data, $id) {
        try {
            $detail = JournalDetail::where('is_deleted', false)->find($id);
            if($detail) {
                $detail->account_id = $data['account_id'] ? $data['account_id'] : $detail->account_id;
                $detail->amount = $data['amount'] ? $data['amount'] : $detail->amount;
                $detail->entry = $data['entry'] ? $data['entry'] : $detail->entry;
                $detail->baseUpdate($data['user_id)']);
                return $detail;
            }
            throw new \Exception("journal detail (ID = $id) not found", Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
