<?php

namespace App\Http\Requests\Order;

use App\Services\OrderService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class OrderStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id'               => 'required|unique:orders|string|max:100',
            'name'             => 'required|string|max:255',
            'address'          => 'required|array',
            'address.city'     => 'required|string|max:255',
            'address.district' => 'required|string|max:255',
            'address.street'   => 'required|string|max:255',
            'price'            => 'required|numeric|min:0|max:2147483647',
            'currency'         => 'required|in:'. join(',',OrderService::getCurrencyList()),
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
