<?php

namespace App\Http\Requests\Account;

use App\Enums\AccountCurrency;
use App\Enums\AccountType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
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
            'currency' => ['sometimes', new EnumValue(AccountCurrency::class)],
            'type' => ['sometimes', new EnumValue(AccountType::class)],
        ];
    }

    public function prepareForValidation()
    {
        $this->mergeIfMissing([
           'currency' => 'NGN',
           'type' => 'savings'
        ]);
    }
}
