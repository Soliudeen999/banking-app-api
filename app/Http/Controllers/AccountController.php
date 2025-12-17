<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientBalanceException;
use App\Http\Requests\Account\IntraBankTransferRequest;
use App\Http\Requests\Account\StoreAccountRequest;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            'data' => $account
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

        if($currentUserAccountForThisType){
            throw ValidationException::withMessages(['type' => 'You already have this type of account']);
        }

        $accountNo = null;

        $notExisting = true;

        while($notExisting){
            $accountNo = random_int(1000000000, 9999999999);
            $notExisting = Account::query()->where('account_number', $accountNo)->exists();
        }

        $data['user_id'] = auth()->id();
        $data['account_number'] = $accountNo;

        $account = Account::create($data);

        return response()->json([
            'message' => 'Account Created Successfully',
            'data' => $account
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account): JsonResponse
    {
        return response()->json([
            'message' => 'Account retrieved successfully.',
            'data' => $account->loadMissing('user:id,name')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();
        return response()->json([
           'message' => 'Accout deleted tem '
        ]);
    }

    public function intraBankTransfer(IntraBankTransferRequest $request, Account $account)
    {
        $data = $request->validated();

        if(
            $account->main_balance < $data['amount']
        ){
            throw ValidationException::withMessages(['amount' => 'Insufficient Balance']);
        };

        return response()->json(['message' => 'successfull']);
    }

    public function forceDelete(string|int $account)
    {
        $account = Account::query()->onlyTrashed()->findOrFail($account);
        $account->forceDelete();

        return response()->json([
           'message' => 'Accout deleted tem '
        ]);
    }
}
