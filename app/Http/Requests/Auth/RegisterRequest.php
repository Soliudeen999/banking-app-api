<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:users,email'],
            'name' => ['required', 'string'],
            'dob' => ['sometimes', 'date', 'before:'.now()->subYears(18)],
            'address' => ['sometimes', 'string', 'min:5', 'max:500'],
            'phone' => ['required', 'min:11', 'max:14', 'string'],
            'gender' => ['string', 'in:male,female,others', 'required'],
            'profile_image' => ['sometimes', 'image', 'size:1024'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'gender.in' => 'The selected gender is not valid. Options are (male,female,others)',
        ];
    }
}
