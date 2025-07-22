<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordResetConfirmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255|exists:users,email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|max:20|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'This email does not exist in our records.',
            'code.required' => 'Reset code is required.',
            'code.size' => 'Reset code must be exactly 6 characters.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password cannot be longer than 20 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $email = $this->input('email');
            $password = $this->input('password');
            $user = User::where('email', $email)->first();
            if ($user && Hash::check($password, $user->password)) {
                $validator->errors()->add('password', 'The new password cannot be the same as your current password.');
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ], 422));
    }
}