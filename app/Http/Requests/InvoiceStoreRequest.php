<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStoreRequest extends FormRequest
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
            'service_id' => 'required|exists:services,id',
            'quotation_id' => 'required|exists:service_quotations,id',
            'cus_id' => 'required|exists:customers,id',
            'place_of_supply' => 'nullable|string',
            'gst' => 'nullable|string',
            'invoice_date' => 'nullable|date',
            'payment_reference' => 'nullable|string',
            'due_date' => 'nullable|date',
            'payment_terms' => 'nullable|string',
            'customer_reference' => 'nullable|string',
            'sales_person' => 'nullable|exists:users,id',
            'sales_team' => 'nullable|string',
            'recipient_bank' => 'nullable|string',
            'delivery_date' => 'nullable|date',
            'incoterm' => 'nullable|string',
            'incoterm_location' => 'nullable|string',
            'fiscal_position' => 'nullable|string',
            'cash_round_method' => 'nullable|string',
            'auto_post' => 'nullable|string',
            'to_check' => 'nullable|boolean',
            'total' => 'nullable|numeric',
            'tax_amt' => 'nullable|numeric',
            'untax_amt' => 'nullable|numeric',
            'journal' => 'nullable|string',
            'pay_method' => 'nullable|string',
            'pay_date' => 'nullable|date',
            'pay_amt'=>'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
    
            'lines' => 'nullable|array',
            'lines.*.pro_id' => 'nullable|exists:products,id',
            'lines.*.label' => 'nullable|string',
            'lines.*.quantity' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'lines.*.unit_price' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'lines.*.taxes' => 'nullable|json',
            'lines' => 'pro_id_required_if_filled',
            'lines.*.section' => 'nullable|string',
            'lines.*.note' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'service_id.required' => 'Service ID is required.',
            'service_id.exists' => 'Service ID must exist in the services table.',
            'lines.pro_id_required_if_filled' => 'Product is required or delete.',
            'quotation_id.required' => 'The quotation ID is required.',
            'quotation_id.exists' => 'The selected quotation ID is invalid.',
            'cus_id.required' => 'The customer ID is required.',
            'cus_id.exists' => 'The selected customer ID is invalid.',
            'place_of_supply.string' => 'The place of supply must be a string.',
            'gst.string' => 'The GST must be a string.',
            'invoice_date.date' => 'The invoice date is not a valid date.',
            'payment_reference.string' => 'The payment reference must be a string.',
            'due_date.date' => 'The due date is not a valid date.',
            'payment_terms.string' => 'The payment terms must be a string.',
            'customer_reference.string' => 'The customer reference must be a string.',
            'sales_person.exists' => 'Sales person must exist in the users table.',
            'sales_team.string' => 'The sales team must be a string.',
            'recipient_bank.string' => 'The recipient bank must be a string.',
            'delivery_date.date' => 'The delivery date is not a valid date.',
            'incoterm.string' => 'The incoterm must be a string.',
            'incoterm_location.string' => 'The incoterm location must be a string.',
            'fiscal_position.string' => 'The fiscal position must be a string.',
            'cash_round_method.string' => 'The cash round method must be a string.',
            'auto_post.string' => 'The auto post must be a string.',
            'to_check.boolean' => 'The to check field must be true or false.',
            'total.numeric' => 'The total must be a numeric value.',
            'tax_amt.numeric' => 'The tax_amt must be a numeric value.',
            'untax_amt.numeric' => 'The untax_amt must be a numeric value.',

            'lines.array' => 'The lines must be an array.',
            'lines.*.pro_id.exists' => 'The selected product ID is invalid.',
            'lines.*.label.string' => 'The label must be a string.',
            'lines.*.quantity.numeric' => 'The quantity must be a numeric value.',
            'lines.*.unit_price.numeric' => 'The unit price must be a numeric value.',
            'lines.*.quantity.regex' => 'The quantity must be a number with up to two decimal places.',
            'lines.*.unit_price.regex' => 'The unit price must be a number with up to two decimal places.',

            'lines.*.taxes.json' => 'The taxes must be a valid JSON string.',
            
            'lines.*.section.string' => 'The section must be a string.',
            'lines.*.note.string' => 'The note must be a string.',
        ];
    }

}