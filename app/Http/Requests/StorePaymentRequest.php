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
            'locale' => ['required', 'string'],
            'amount' => ['required', 'integer', 'min:1', 'max:999999999999'],
            'site_id' => ['required', 'numeric', 'exists:sites,id'],
            'currency' => ['required', Rule::in(CurrentTypes::toArray())],
            'gateway' => ['required', Rule::in(PaymentGateway::toArray())],
        ];
    }
}
