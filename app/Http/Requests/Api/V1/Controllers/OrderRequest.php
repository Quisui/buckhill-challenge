<?php

namespace App\Http\Requests\Api\V1\Controllers;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'order_status_id' => ['required', 'exists:order_statuses,uuid'],
            'products' => ['required', 'array'],
            'delivery_fee' => ['required', 'numeric'],
            'amount' => ['required', 'numeric']
        ];
    }
}
