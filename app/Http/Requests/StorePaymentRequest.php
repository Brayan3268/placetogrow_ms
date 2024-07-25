<?php

namespace App\Http\Requests;

use App\Constants\CurrencyTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
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
            'locale' => ['string'],
            'total' => ['integer', 'min:1', 'max:999999999999'],
            'description' => ['string'],
            'currency' => ['nullable', Rule::in(CurrencyTypes::toArray())],
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        if (! isset($data['description'])) {
            $data['description'] = 'Valor por defecto';
        }

        if (! isset($data['currency'])) {
            $data['currency'] = 'CLP';
        }

        return $data;
    }

    public function validationData()
    {
        $data = $this->all();

        return $data;
    }
}
