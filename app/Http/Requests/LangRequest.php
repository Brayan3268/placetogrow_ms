<?php

namespace App\Http\Requests;

use App\Constants\Languages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'locale' => ['required', Rule::in(Languages::get_all_languages())],
        ];
    }
}
