<?php

namespace App\Http\Requests\Account;

use App\Models\CashDeposit;
use Illuminate\Foundation\Http\FormRequest;

class CashDepositRequest extends FormRequest
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
        return [
            'amount' => ['required', 'numeric', 'min:10'],
            'account_number' => ['required', 'string', 'exists:accounts,account_number'],
            'account_name' => ['required', 'string'],
            'depositor_name' => ['required', 'string', 'max:250', 'min:3'],
            'depositor_phone' => ['required', 'string', 'min:11', 'max:14', 'regex:/^\+?\d+$/'],
            'narration' => ['sometimes', 'string', 'max:500'],
            'branch' => ['string', 'sometimes', 'max:100'],
            'staff_id' => ['sometimes'],
            'status'
        ];
    }

    public function prepareForValidation()
    {
        $this->mergeIfMissing([
            'branch' => 'favours branch',
        ]);

        $this->merge([
            'staff_id' => auth()->id(),
            'status' => 'completed'
        ]);
    }

    public function genReferenceNumber(): string
    {
        $ref = uniqid('DEP-', true);
        if(CashDeposit::where('reference', $ref)->exists()){
            $this->genReferenceNumber();
        }
        return $ref;
    }
}
