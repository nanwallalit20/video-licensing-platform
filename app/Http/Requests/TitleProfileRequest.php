<?php

namespace App\Http\Requests;


use App\Enums\TitleType;
use Illuminate\Foundation\Http\FormRequest;

class TitleProfileRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'title_name' => 'required|string|max:255',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
            'rating' => 'required|array|min:1',
            'production_countries' => 'required|array|min:1',
            'licence_country' => 'required|array|min:1',
            'directors' => 'required|array|min:1',
            'tags' => 'required|array|min:1',
            'advisories' => 'required|array|min:1',
        ];

        if ($this->get('title_type') == TitleType::Movie->value) {
            $rules = array_merge($rules, [
                'release_date' => 'required|date',
                'duration' => 'required|numeric|min:1',
            ]);
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $actors = $this->input('actor', []);
            $characters = $this->input('character', []);

            foreach ($actors as $key => $value) {
                if (empty($value)) {
                    $validator->errors()->add('Actor', __('Actor  name is required'));
                }
            }

            foreach ($characters as $key => $value) {
                if (empty($value)) {
                    $validator->errors()->add('Character', __('Character name is required'));
                }
            }
        });
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'title_name.required' => 'Title name is required',
            'genres.required' => 'Genre is required',
            'genres.min' => 'At least one genre is required',
            'rating.required' => 'Rating is required',
            'production_countries.required' => 'Production country is required',
            'licence_country.required' => 'Licence country is required',
            'directors.required' => 'Director is required',
            'tags.required' => 'Tag is required',
            'advisories.required' => 'Advisory is required',
            'release_date.required' => 'Release date is required',
            'duration.required' => 'Duration is required',
        ];
    }
}
