<?php

namespace App\Http\Requests;

use App\Constants\DocumentTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user_id = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', Rule::unique('users', 'email')->ignore($user_id)],
            'password' => ['nullable', 'string', 'min:8'],
            'document_type' => ['required', 'string', Rule::in(DocumentTypes::toArray())],
            'document' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string', Rule::unique('users', 'phone')->ignore($user_id)],
            'role' => ['required', 'in:super_admin,admin,guest'],
        ];
    }
}
