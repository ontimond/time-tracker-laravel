<?php

namespace App\Http\Requests;

use App\Models\ProviderSlug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ProviderStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'slug' => [new Enum(ProviderSlug::class)],
            'config' => ['array'],
        ];
    }
}