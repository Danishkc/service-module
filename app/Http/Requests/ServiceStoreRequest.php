<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceStoreRequest extends FormRequest
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
            'cus_id' => 'required|exists:customers,id',
            'pro_id' => 'nullable|exists:products,id',
            'return' => 'nullable|string',
            'product_quantity' => 'nullable|numeric|min:1',
            'warranty' => 'nullable|boolean',
            'scheduled_date' => 'required|date',
            'responsible' => 'nullable|exists:users,id',
            'tags' => 'nullable|string|max:255',
            'repair_notes' => 'nullable|string',
            'operation_type' => 'nullable|string|max:255',
            
            'lines' => 'nullable|array',
            'lines.*.type' => 'nullable|string|in:add,remove,recycle',
            'lines.*.product_id' => 'nullable|exists:products,id',
            'lines.*.demand' => 'nullable|numeric|min:1',
            'lines.*.done' => 'nullable|numeric|min:0',
            'lines.*.used' => 'nullable|boolean',
            'lines' => 'product_id_required_if_fields_filled',
        ];
    }

    public function messages()
    {
        return [
            'lines.product_id_required_if_fields_filled'=> 'Product is required or delete.',
            'cus_id.required' => 'The customer is required.',
            'cus_id.exists' => 'The selected customer is invalid.',
            'scheduled_date.required' => 'The scheduled date field is required.',
            'scheduled_date.date' => 'The scheduled date is not a valid date format.',
            'pro_id.exists' => 'The selected product is invalid.',

            'product_quantity.numeric' => 'The product quantity must be an numeric.',
            'product_quantity.min' => 'The product quantity must be at least :min.',

            'return.string' => 'The return field must be a string.',
            'warranty.boolean' => 'The warranty field must be true or false.',
            'responsible.exists' => 'The selected responsible person is invalid.',
            
            'tags.string' => 'The tags field must be a string.',
            'tags.max' => 'The tags field may not be greater than 255 characters.',
            'repair_notes.string' => 'The repair notes field must be a string.',
            'operation_type.string' => 'The operation type field must be a string.',
            'operation_type.max' => 'The operation type field may not be greater than 255 characters.',
            'lines.array' => 'The lines field must be an array.',
            'lines.*.type.string' => 'Each line type must be a string.',
            'lines.*.type.in' => 'Each line type must be one of the following: add, remove, recycle.',
            'lines.*.product_id.exists' => 'Each selected product is invalid.',
            
            'lines.*.demand.numeric' => 'Each line demand must be an numeric.',
            'lines.*.demand.min' => 'Each line demand must be at least 1.',
            'lines.*.done.numeric' => 'Each line done must be an numeric.',
            'lines.*.done.min' => 'Each line done must be at least 0.',
            'lines.*.used.boolean' => 'Each line used field must be true or false.',
        ];
    }
}
