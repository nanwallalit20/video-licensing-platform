<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TitleVideoRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void {
        // Filter chapter_titles and chapter_timestramps to remove empty values
        $this->merge([
            'chapter_titles' => array_filter($this->input('chapter_titles', []), function ($value) {
                return !is_null($value) && $value !== '';
            }),
            'chapter_timestramps' => array_filter($this->input('chapter_timestramps', []), function ($value) {
                return !is_null($value) && $value !== '';
            }),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array {
        return [
            'uploaded_poster' => 'required',
            'uploaded_trailer' => 'required',
            'uploaded_main_video' => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $actors = $this->input('uploaded_caption', []);
            $characters = $this->input('uploaded_language', []);

            foreach ($actors as $key => $value) {
                if (empty($value)) {
                    $validator->errors()->add('Subtitle', __('Select subtitle or remove field'));
                }
            }

            foreach ($characters as $key => $value) {
                if (empty($value)) {
                    $validator->errors()->add('Dubbed Language', __('Select dubbed language or remove field'));
                }
            }
        });
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array {
        return [
            'uploaded_main_video.required' => 'The main video is required.'
        ];
    }
}
