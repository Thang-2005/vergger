<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Any authenticated user can access this (handled by middleware usually)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'shipping_address_id' => 'nullable|exists:shipping_address,id',
            'full_name' => 'required_if:shipping_address_id,null|string|max:255',
            'phone' => 'required_if:shipping_address_id,null|string|max:20',
            'address' => 'required_if:shipping_address_id,null|string|max:500',
            'city' => 'required_if:shipping_address_id,null|string|max:100',
            'payment_method' => 'required|in:cod,vnpay',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required_if' => 'Vui lòng nhập tên người nhận',
            'phone.required_if' => 'Vui lòng nhập số điện thoại',
            'address.required_if' => 'Vui lòng nhập địa chỉ',
            'city.required_if' => 'Vui lòng nhập thành phố',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
        ];
    }
}
