<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'reference' => $this->reference,
            'gen_reference' => $this->gen_reference,
            'type' => $this->type,
            'amount' => $this->amount,
            'balance_before' => $this->balance_before,
            'balance_after' => $this->balance_after,
            'narration' => $this->narration,
            'status' => $this->status,
            'related_account_number' => $this->related_account_number,
            'related_bank_code' => $this->related_bank_code,
            'related_account_name' => $this->related_account_name,
        ];
    }
}
