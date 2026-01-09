<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\ReportTransactionRequest;
use App\Mail\ReportTransactionMail;
use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class AccountTransactionController extends Controller
{
    public function index(Account $account): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Transactions retrieved successfully',
            'transactions' => $account->transactions()->latest()->paginate(25)
        ]);
    }

    public function show(Account $account, AccountTransaction $transaction): JsonResponse
    {
        abort_if(
            $transaction->account_id !== $account->id,
            404,
            'Transaction not found for this account'
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction retrieved successfully',
            'transaction' => $transaction
        ]);
    }

    public function report(
        Account $account,
        AccountTransaction $transaction,
        ReportTransactionRequest $request,
    ): JsonResponse {
        abort_if(
            $transaction->account_id !== $account->id,
            404,
            'Transaction not found for this account'
        );

        Mail::to(config('banking.support_email'))->send(new ReportTransactionMail($transaction, $request->validated('comment')));

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction reported successfully',
        ]);
    }
}
