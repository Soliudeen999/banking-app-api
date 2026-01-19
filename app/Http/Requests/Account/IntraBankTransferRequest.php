<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class IntraBankTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $account = $this->route()->parameter('account');

        $maxTransferrableAmount = match ($account->tier) {
            1 => 50000,
            2 => 500000,
            default => 0
        };

        return [
            'amount' => [
                'required',
                'numeric',
                'min:10',
                Rule::unless($maxTransferrableAmount == 0, ['max:'.$maxTransferrableAmount]),
            ],
            'account_number' => ['required', 'min:10', 'string', 'max:10', Rule::exists('govt_accounts', 'account_number')->where(function ($query) {
                $query->where('bank_code', $this->input('bank_code'));
            })],
            'pin' => ['required', 'string', 'size:4'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'bank_code' => ['required', 'string', 'exists:banks,code'],
            'narration' => ['sometimes', 'string', 'max:500'],
        ];
    }

    public function prepareForValidation()
    {
        $this->mergeIfMissing([
            'bank_code' => '001122',
        ]);
    }

    public function after(): array
    {
        return [
            function(Validator $validator){
                $account = $this->route()->parameter('account');
                $destinationAccount = $this->input('account_number');

                if($account->account_number === $destinationAccount){
                    $validator->errors()->add('account_number', 'You can not use same account for sending and receiving');
                }
            },

            function(Validator $validator){
                $account = $this->route()->parameter('account');
                $amountToSend = $this->input('amount');

                if($account->main_balance < $amountToSend){
                    $validator->errors()->add('amount', 'Insufficient balance for this transaction');
                }
            }
        ];
    }
}
