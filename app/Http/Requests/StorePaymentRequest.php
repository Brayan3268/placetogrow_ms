<?php

namespace App\Http\Requests;

use App\Constants\CurrencyTypes;
use App\Constants\LocalesTypes;
use App\Http\PersistantsLowLevel\SitePll;
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
            'locale' => 'required|in:'.implode(',', array_column(LocalesTypes::cases(), 'name')),
            'total' => ['integer', 'min:1', 'max:999999999999'],
            'description' => ['string'],
            'currency' => ['nullable', Rule::in(CurrencyTypes::toArray())],
            'invoice_id' => [
                function ($attribute, $value, $fail) {
                    if ($value !== null && $value !== '0' && ! \App\Models\Invoice::where('id', $value)->exists()) {
                        $fail('El valor de invoice_id debe existir en la base de datos.');
                    }
                },
            ],
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        //dd($data);

        if (! isset($data['description'])) {
            $data['description'] = 'Valor por defecto';
        }

        //dd($data);

        if (! isset($data['currency'])) {
            $data['currency'] = SitePll::get_site_currecy($data['site_id']);
        }

        //dd($data);

        return $data;
    }

    public function validationData()
    {
        $data = $this->all();

        return $data;
    }
}
