<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SportingGoodRequest extends FormRequest
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
        'name' => ['required', 'string', 'max:255'],
        'category' => ['required', 'in:バット,ラケット,ボール,グローブ,シューズ'],
        'brand' => ['required', 'string', 'max:100'],
        'price' => ['required', 'integer', 'min:0', 'max:100000'],
        'weight' => ['nullable', 'numeric', 'min:0.1', 'max:100'],
        'is_available' => ['required', 'boolean'],
        'stock' => ['required', 'integer', 'min:0'],
        'release_date' => ['required', 'date', 'before_or_equal:today'],
        'color' => ['nullable', 'string', 'max:50'],
        'sku' => ['required', 'string', 'unique:sporting_goods,sku'],
        ];
    }
}
