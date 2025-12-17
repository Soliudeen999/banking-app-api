<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

        $maxTransferrableAmount = match($account->tier){
            1 => 50000,
            2 => 500000,
            default => 0
        };

        return [
            'amount' => [
                'required',
                'numeric',
                'min:10',
                Rule::unless($maxTransferrableAmount == 0, ['max:' . $maxTransferrableAmount])
            ],
            'account_number' => ['required', 'min:10', 'string', 'max:10', 'exists:accounts,account_number']
        ];
    }
}
