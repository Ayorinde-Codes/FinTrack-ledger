<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_number' => 'required|unique:invoices',
            'amount' => 'required',
            'due_date' => 'required|date',
            'status' => 'required',
            'recurrence' => 'nullable|string',
            'next_invoice_date' => 'nullable|date'
        ];
    }
}
