<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeasonRequest extends FormRequest {
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
        return [
            // Ensure the seasons array is provided and has at least one season.
            'seasons' => 'required|array|min:1',

            // For each season:
            'seasons.*.season_trailer' => 'required|string',
            'seasons.*.season_image' => 'required|string',
            'seasons.*.season_name' => 'required|string',
            'seasons.*.release_date' => 'required|date',

            // Episodes are optional but if provided they must be an array.
            'seasons.*.episodes' => 'nullable|array',

            // For each episode in a season:
            'seasons.*.episodes.*.main_video' => 'required|string',
            'seasons.*.episodes.*.episode_name' => 'required|string',
            'seasons.*.episodes.*.release_date' => 'required|date',

            'seasons.*.episodes.*.caption' => 'nullable|array',
            'seasons.*.episodes.*.caption.*.text' => 'required_with:seasons.*.episodes.*.caption|string',
            'seasons.*.episodes.*.caption.*.file' => 'required_with:seasons.*.episodes.*.caption|string',

            // Dubbed Language Validation - If exists, text and file should not be null
            'seasons.*.episodes.*.dubbed_language' => 'nullable|array',
            'seasons.*.episodes.*.dubbed_language.*.text' => 'required_with:seasons.*.episodes.*.dubbed_language|string',
            'seasons.*.episodes.*.dubbed_language.*.file' => 'required_with:seasons.*.episodes.*.dubbed_language|string',
        ];
    }

    public function messages() {
        return [
            'seasons.required' => 'At least one season is required.',
            'seasons.array' => 'The seasons data must be an array.',
            'seasons.min' => 'At least one season is required.',

            'seasons.*.season_trailer.required' => 'A season trailer is required for season :season_index.',
            'seasons.*.season_image.required' => 'A season image is required for season :season_index.',
            'seasons.*.season_name.required' => 'A season name is required for season :season_index.',
            'seasons.*.release_date.required' => 'A release date is required for season :season_index.',
            'seasons.*.release_date.date' => 'The release date for season :season_index must be a valid date.',

            'seasons.*.episodes.array' => 'The episodes must be provided as an array for season :season_index.',

            'seasons.*.episodes.*.main_video.required' => 'A main video is required for episode :episode_index of season :season_index.',
            'seasons.*.episodes.*.episode_name.required' => 'An episode name is required for episode :episode_index of season :season_index.',
            'seasons.*.episodes.*.episode_name.string' => 'The episode name must be a string for episode :episode_index of season :season_index.',
            'seasons.*.episodes.*.release_date.required' => 'A release date is required for episode :episode_index of season :season_index.',
            'seasons.*.episodes.*.release_date.date' => 'The release date must be a valid date for episode :episode_index of season :season_index.',

            // Custom messages for caption validation
            'seasons.*.episodes.*.caption.*.text.required_with' => 'The subtitle text is required for episode :episode_index of season :season_index.',
            'seasons.*.episodes.*.caption.*.file.required_with' => 'The subtitle file is required for episode :episode_index of season :season_index.',

            // Custom messages for dubbed language validation
            'seasons.*.episodes.*.dubbed_language.*.text.required_with' => 'The dubbed language text is required for episode :episode_index of season :season_index.',
            'seasons.*.episodes.*.dubbed_language.*.file.required_with' => 'The dubbed language file is required for episode :episode_index of season :season_index.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->setAttributeNames([]);  // Clear any existing attribute names

        // Add replacer for all validation messages that need index conversion
        $validator->addReplacer('required', function($message, $attribute, $rule, $parameters) {
            $parts = explode('.', $attribute);
            if (count($parts) >= 2) {
                $seasonIndex = is_numeric($parts[1]) ? ((int)$parts[1] + 1) : '';
                $episodeIndex = isset($parts[3]) && is_numeric($parts[3]) ? ((int)$parts[3] + 1) : '';

                return str_replace(
                    [':season_index', ':episode_index'],
                    [$seasonIndex, $episodeIndex],
                    $message
                );
            }
            return $message;
        });

        // Add replacer for date validation
        $validator->addReplacer('date', function($message, $attribute, $rule, $parameters) {
            $parts = explode('.', $attribute);
            if (count($parts) >= 2) {
                $seasonIndex = is_numeric($parts[1]) ? ((int)$parts[1] + 1) : '';
                $episodeIndex = isset($parts[3]) && is_numeric($parts[3]) ? ((int)$parts[3] + 1) : '';

                return str_replace(
                    [':season_index', ':episode_index'],
                    [$seasonIndex, $episodeIndex],
                    $message
                );
            }
            return $message;
        });

        // Keep existing required_with replacer
        $validator->addReplacer('required_with', function($message, $attribute, $rule, $parameters) {
            $parts = explode('.', $attribute);
            if (count($parts) >= 4) {
                $seasonIndex = is_numeric($parts[1]) ? ((int)$parts[1] + 1) : '';
                $episodeIndex = is_numeric($parts[3]) ? ((int)$parts[3] + 1) : '';

                return str_replace(
                    [':season_index', ':episode_index'],
                    [$seasonIndex, $episodeIndex],
                    $message
                );
            }
            return $message;
        });
    }
}
