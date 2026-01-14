<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\CashDepositRequest;
use App\Jobs\RecordTransactionWithGovtJob;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\CashDeposit;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CashDepositController extends Controller
{
    public function store(CashDepositRequest $request): JsonResponse
    {
        $data = $request->validated();

        $account = Account::with(['user'])->where('account_number', $data['account_number'])->first();

        if($account->user->name !== $data['account_name']){
            throw ValidationException::withMessages(['account_name' => 'Invalid Account name']);
        }

        $data['reference'] = $request->genReferenceNumber();

        $transaction = DB::transaction(function() use ($data, $account): AccountTransaction {

            $amount = $data['amount'];

            CashDeposit::create($data);

            $account->update([
                'main_balance' => $account->main_balance + $amount,
                'ledger_balance' => $account->ledger_balance + $amount,
                'credits' => $account->credits + $amount
            ]);

            $transaction = $account->transactions()->create([
                'type' => 'credit',
                'reference' => uniqid('txn_' . '001122' . $account->id, true),
                'gen_reference' => uniqid('gen_' . '001122' . $account->id, true),
                'amount' => $amount,
                'balance_before' => $account->main_balance - $amount,
                'balance_after' => $account->main_balance,
                'narration' => 'Cash Deposit',
                'status' => 'completed',
            ]);

            RecordTransactionWithGovtJob::dispatchAfterResponse($transaction);
            return $transaction;
        });

        return response()->json([
            'message' => 'Cash deposited successfully.',
            'data' => $transaction
        ]);
    }
}
