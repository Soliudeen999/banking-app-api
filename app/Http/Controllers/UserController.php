<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\SetTransactionPinRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function currentUser()
    {
        $user = Auth::user();

        return response()->json([
            'message' => 'Current User Info',
            'data' => $user,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function setTransaction(SetTransactionPinRequest $request)
    {
        $pin = $request->validated('transaction_pin');

        $user = Auth::user();
        $user->trnx_pin = Hash::make($pin);
        $user->save();

        return response()->json([
            'message' => 'Transaction PIN set successfully',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
