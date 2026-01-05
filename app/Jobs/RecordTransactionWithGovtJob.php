<?php

namespace App\Jobs;

use App\Models\AccountTransaction;
use App\Models\GovtTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RecordTransactionWithGovtJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $transactions){
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dataToSave = collect($this->transactions)->map(fn($transaction) => [
            'type' => $transaction->type,
            'reference' => $transaction->reference,
            'account_number' => $transaction->sourceAccount->account_number,
            'bank_code' => '001122', // Assuming a fixed bank code for govt account
            'narration' => $transaction->narration,
            'related_account_number' => $transaction->related_account_number,
            'related_bank_code' => $transaction->related_bank_code,
            'amount' => $transaction->amount,
            'status' => $transaction->status,
        ])->toArray();

        logger()->info('Recording govt transactions', $dataToSave);
        GovtTransaction::insert($dataToSave);
    }
}
