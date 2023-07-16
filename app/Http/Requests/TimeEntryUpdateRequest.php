<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeEntryUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'description' => ['string', 'max:255', 'nullable'],
            'start' => ['date', 'required'],
            'stop' => ['date', 'nullable'],
            'billable' => ['boolean', 'nullable'],
        ];
    }
}