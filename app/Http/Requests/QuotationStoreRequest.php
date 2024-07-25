<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuotationStoreRequest extends FormRequest
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
    public function rules()
    {
        return [
            'service_id' => 'required|exists:services,id',
            'cus_id' => 'required|exists:customers,id',
            'gst' => 'required|string',
            'expiration' => 'nullable|date',
            'pricelist' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'sales_person' => 'nullable|exists:users,id',
            'sales_team' => 'nullable|string',
            'online_signature' => 'nullable|boolean',
            'online_payment' => 'nullable|boolean',
            'customer_reference' => 'nullable|string',
            'tags' => 'nullable|string',
            'fiscal_position' => 'nullable|string',
            'incoterm' => 'nullable|string',
            'incoterm_location' => 'nullable|string',
            'shipping_policy' => 'nullable|string',
            'delivery_date' => 'nullable|date',
            'source_document' => 'nullable|string',
            'campaign' => 'nullable|string',
            'medium' => 'nullable|string',
            'source' => 'nullable|string',
            'total' => 'nullable|numeric',
            'tax_amt' => 'nullable|numeric',
            'untax_amt' => 'nullable|numeric',

            'lines' => 'nullable|array',
            'lines.*.pro_id' => 'nullable|exists:products,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.quantity' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'lines.*.unit_price' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'lines.*.taxes' => 'nullable|json',
            'lines' => 'pro_id_required_if_filled|option_pro_id_required_if_filled',
            
            'lines.*.section' => 'nullable|string',
            'lines.*.note' => 'nullable|string',
            'lines.*.option_pro_id' => 'nullable|exists:products,id',
            'lines.*.option_description' => 'nullable|string',
            'lines.*.option_quantity' => 'nullable|numeric',
            'lines.*.option_unit_price' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'lines.pro_id_required_if_filled' => 'Product is required or delete.',
            'lines.option_pro_id_required_if_filled' => 'Product is required or delete.',
            'service_id.required' => 'Service ID is required.',
            'service_id.exists' => 'Service ID must exist in the services table.',
            'cus_id.required' => 'Customer is required.',
            'cus_id.exists' => 'Customer ID must exist in the customers table.',
            'gst.required' => 'GST is required.',
            'gst.string' => 'GST must be a string.',
            'expiration.date' => 'Expiration date must be a valid date.',
            'pricelist.string' => 'Pricelist must be a string.',
            'payment_terms.string' => 'Payment terms must be a string.',
            'sales_person.exists' => 'Sales person must exist in the users table.',
            'sales_team.string' => 'Sales team must be a string.',
            'online_signature.boolean' => 'Online signature must be a boolean value.',
            'online_payment.boolean' => 'Online payment must be a boolean value.',
            'customer_reference.string' => 'Customer reference must be a string.',
            'tags.string' => 'Tags must be a string.',
            'fiscal_position.string' => 'Fiscal position must be a string.',
            'incoterm.string' => 'Incoterm must be a string.',
            'incoterm_location.string' => 'Incoterm location must be a string.',
            'shipping_policy.string' => 'Shipping policy must be a string.',
            'delivery_date.date' => 'Delivery date must be a valid date.',
            'source_document.string' => 'Source document must be a string.',
            'campaign.string' => 'Campaign must be a string.',
            'medium.string' => 'Medium must be a string.',
            'source.string' => 'Source must be a string.',
            'total.numeric' => 'total must be an numeric.',
            'tax_amt.numeric' => 'tax_amt must be an numeric.',
            'untax_amt.numeric' => 'tax_amt must be an numeric.',
            
            'lines.array' => 'Lines must be an array.',
            
            'lines.*.pro_id.exists' => 'Product ID must exist in the products table.',
            'lines.*.description.string' => 'Line description must be a string.',
            'lines.*.quantity.numeric' => 'The quantity must be a numeric value.',
            'lines.*.unit_price.numeric' => 'The unit price must be a numeric value.',
            'lines.*.quantity.regex' => 'The quantity must be a number with up to two decimal places.',
            'lines.*.unit_price.regex' => 'The unit price must be a number with up to two decimal places.',

            'lines.*.taxes.json' => 'Line taxes must be a valid JSON string.',
            
            'lines.*.section.string' => 'Line section must be a string.',
            'lines.*.note.string' => 'Line note must be a string.',
            'lines.*.option_pro_id.exists' => 'Optional product ID must exist in the products table.',
            
            'lines.*.option_description.string' => 'Optional product description must be a string.',
            'lines.*.option_quantity.numeric' => 'Optional product quantity must be an number.',
            'lines.*.option_unit_price.numeric' => 'Optional product unit price must be a number.',
        ];
    }
}
