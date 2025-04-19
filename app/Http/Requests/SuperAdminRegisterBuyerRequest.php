<?php

namespace App\Http\Requests;

use App\Enums\AcquisitionPreference;
use App\Enums\ContentDuration;
use App\Enums\ContentType;
use App\Enums\ContentUsage;
use App\Models\Genre;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SuperAdminRegisterBuyerRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Store valid genres.
     */
    private array $validGenres;

    /**
     * Constructor to initialize valid genres.
     */
    public function __construct() {
        parent::__construct();
        $this->validGenres = Genre::pluck('slug')->toArray();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'full_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'email' => 'required|email|unique:buyers,email',
            'phone_number' => 'required|min:10|max:20',
            'whatsapp_number' => 'required|min:10|max:20',
            'content_type' => ['required', 'array', 'in:' . implode(',', $this->getEnumValues(ContentType::class))],
            'genre' => ['required', 'array', 'in:' . implode(',', $this->validGenres)],
            'content_usage' => ['required', 'integer', 'in:' . implode(',', $this->getEnumValues(ContentUsage::class))],
            'content_duration' => ['required', 'integer', 'in:' . implode(',', $this->getEnumValues(ContentDuration::class))],
            'acquisition_preferences' => ['required', 'integer', 'in:' . implode(',', $this->getEnumValues(AcquisitionPreference::class))],
            'target_audience' => 'required|string|max:255',
            'territories' => 'required|string|max:255',
            'budget' => 'required|numeric|min:1',
            'additional_details' => 'nullable|string',
            'terms_and_conditons' => 'required|boolean'
        ];
    }


    /**
     * Custom error messages for validation.
     */
    public function messages(): array {
        return [
            'genre.in' => 'Please choose a valid genre.',
            'content_type' => 'Please choose a valid content type.'
        ];
    }

    protected function failedValidation(Validator $validator) {
        response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 422)->send();

        exit;
    }

    /**
     * Extract the enum values dynamically.
     */
    private function getEnumValues(string $enumClass): array {
        return array_column($enumClass::cases(), 'value');
    }
}
