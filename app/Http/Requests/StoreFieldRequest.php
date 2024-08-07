<?php

namespace App\Http\Requests;

use App\Constants\DocumentTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFieldRequest extends FormRequest
{
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
            'site_id' => ['required', 'numeric', 'exists:sites,id'],
            'name_field' => ['required', 'string', 'max:50'],
            'name_field_user_see' => ['required', 'string', 'max:50'],
            'field_type' => ['required'],
            'is_optional' => ['required', 'numeric', 'in:0,1'],
            'is_modify' => ['required', 'numeric', 'in:0,1'],
            'values' => ['nullable'],
        ];
    }
}
