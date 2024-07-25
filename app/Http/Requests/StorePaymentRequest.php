<?php

namespace App\Http\Requests;

use App\Constants\CurrentTypes;
use App\Constants\PaymentGateway;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use function Laravel\Prompts\alert;

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
        alert('1');

        return [
            'site_id' => ['required', 'numeric', 'exists:sites,id'],
            'locale' => ['required', 'string'],
            'total' => ['required', 'integer', 'min:1', 'max:999999999999'],
            'description' => ['string'],
            'currency' => ['required', Rule::in(CurrentTypes::toArray())],
            //'site_id' => ['required', 'numeric', 'exists:sites,id'],
            //'currency' => ['required', ],
            //'gateway' => ['required', Rule::in(PaymentGateway::toArray())],
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        // Agregar valor por defecto para 'description' si no está presente
        if (! isset($data['description'])) {
            $data = $data + ['description' => 'Valor por defecto'];
        }

        if (! isset($data['currency'])) {
            $data = $data + ['currency' => 'CLP'];
        }

        return $data;
    }

    public function validationData()
    {
        $data = $this->all();

        if (! isset($data['description'])) {
            $data += ['description' => ''];
        }

        /*if (!isset($data['currency'])) {
            $data += ['currency' => 'CLP'];
        }*/

        return $data;
    }
}
