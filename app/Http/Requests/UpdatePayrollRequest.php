<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayrollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'salary' => 'required|numeric',
            'payment_date' => 'required|date',
            'taxes' => 'nullable|array',
            'taxes.income_tax' => 'nullable|numeric',
            'taxes.social_security' => 'nullable|numeric',
            'taxes.pension' => 'nullable|numeric',
        ];
    }
}
