<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TitleContactRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        $rules = [
            'primary_name' => 'required|string|max:255',
            'primary_role' => 'required|string|max:255',
            'primary_email' => 'required|email|max:255',
            'primary_phone' => 'required|numeric|digits_between:1,20',
            'primary_whatsapp_number' => 'required|numeric|digits_between:1,20',
        ];

        if (!request()->input('sameContact')) {
            $rules = [
                ...$rules,
                'secondary_name' => 'required|string|max:255',
                'secondary_role' => 'required|string|max:255',
                'secondary_email' => 'required|email|max:255',
                'secondary_phone' => 'required|numeric|digits_between:1,20',
                'secondary_whatsapp_number' => 'required|numeric|digits_between:1,20',
            ];
        }

        return $rules;
    }
}
