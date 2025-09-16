<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasAdminAccess();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sku' => 'required|string|max:255|unique:products,sku',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'barcode' => 'nullable|string|max:255|unique:products,barcode',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'sku.required' => 'SKU is required.',
            'sku.unique' => 'This SKU already exists.',
            'name.required' => 'Product name is required.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category does not exist.',
            'harga_beli.required' => 'Purchase price is required.',
            'harga_beli.numeric' => 'Purchase price must be a valid number.',
            'harga_beli.min' => 'Purchase price cannot be negative.',
            'harga_jual.required' => 'Selling price is required.',
            'harga_jual.numeric' => 'Selling price must be a valid number.',
            'harga_jual.min' => 'Selling price cannot be negative.',
            'stok.required' => 'Stock quantity is required.',
            'stok.integer' => 'Stock quantity must be a valid integer.',
            'stok.min' => 'Stock quantity cannot be negative.',
            'barcode.unique' => 'This barcode already exists.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);
    }
}