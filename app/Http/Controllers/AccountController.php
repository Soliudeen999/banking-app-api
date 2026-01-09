<?php

namespace App\Http\Controllers;

use App\Helpers\TransactionHelper;
use App\Http\Requests\Account\IntraBankTransferRequest;
use App\Http\Requests\Account\StoreAccountRequest;
use App\Jobs\RecordTransactionWithGovtJob;
use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $account = Account::query()
            ->where('user_id', auth()->id())
            ->get();

        return response()->json([
            'message' => 'Accounts retrieved successfully',
            'data' => $account,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request): JsonResponse
    {
        $data = $request->validated();

        $currentUserAccountForThisType = Account::query()
            ->where('user_id', auth()->id())
            ->whereType($data['type'])
            ->first();

        if ($currentUserAccountForThisType) {
            throw ValidationException::withMessages(['type' => 'You already have this type of account']);
        }

        $accountNo = null;

        $notExisting = true;

        while ($notExisting) {
            $accountNo = random_int(1000000000, 9999999999);
            $notExisting = Account::query()->where('account_number', $accountNo)->exists();
        }

        $data['user_id'] = auth()->id();
        $data['account_number'] = $accountNo;

        $account = Account::create($data);

        return response()->json([
            'message' => 'Account Created Successfully',
            'data' => $account,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account): JsonResponse
    {
        return response()->json([
            'message' => 'Account retrieved successfully.',
            'data' => $account->loadMissing('user:id,name'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return response()->json([
            'message' => 'Accout deleted temporarily.',
        ]);
    }

    public function intraBankTransfer(IntraBankTransferRequest $request, Account $account): JsonResponse
    {
        TransactionHelper::verifyUserPin(
            $account->user,
            $request->validated('pin')
        );

        $data = $request->validated();

        $transactions = DB::transaction(function() use ($data, $account) {
            $mainBalance = $account->main_balance;

            $account->update([
                'main_balance' => $mainBalance - $data['amount'],
                'ledger_balance' => $account->ledger_balance - $data['amount'],
                'debits' => $account->debits + $data['amount']
            ]);

            $destinationAccount = Account::where('account_number', '=', $data['account_number'])->first();

            $destinationAccount->update([
                'main_balance' => $destinationAccount->main_balance + $data['amount'],
                'ledger_balance' => $destinationAccount->ledger_balance + $data['amount'],
                'credits' => $destinationAccount->credits + $data['amount']
            ]);

            $genReference = uniqid(prefix: 'gen_' . '001122' . $account->id, more_entropy: true);

            $sendingAccountTrnx = $account->transactions()->create([
                'type' => 'debit',
                'reference' => uniqid(prefix: 'txn_' . '001122', more_entropy: true),
                'gen_reference' => $genReference,
                'amount' => $data['amount'],
                'balance_before' => $mainBalance,
                'balance_after' => $account->main_balance, // The new balance is here after update
                'narration' => $data['narration'] ?? 'Intra bank transfer',
                'status' => 'completed',
                'related_account_number' => $destinationAccount->account_number,
                'related_bank_code' => '001122',
                'related_account_name' => $destinationAccount->user->name,
            ]);

            $receivingAccountTrnx = AccountTransaction::create([
                'account_id' => $destinationAccount->id,
                'reference' => uniqid(prefix: 'txn_' . '001122' . $destinationAccount->id, more_entropy: true),
                'gen_reference' => $genReference,
                'type' => 'credit',
                'balance_before' => $destinationAccount->main_balance - $data['amount'],
                'balance_after' => $destinationAccount->main_balance,
                'narration' => $data['narration'] ?? 'Intra bank transfer',
                'amount' => $data['amount'],
                'status' => 'completed',
                'related_account_number' => $account->account_number,
                'related_bank_code' => '001122',
                'related_account_name' => $account->user->name,
            ]);

            return [$sendingAccountTrnx, $receivingAccountTrnx];
        });

        RecordTransactionWithGovtJob::dispatchAfterResponse($transactions);

        return response()->json(['message' => 'successfull', 'data' => $transactions[0]]);
    }

    public function forceDelete(string|int $account)
    {
        $account = Account::query()->onlyTrashed()->findOrFail($account);
        $account->forceDelete();

        return response()->json([
            'message' => 'Accout deleted tem ',
        ]);
    }
}
