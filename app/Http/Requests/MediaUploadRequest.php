<?php

namespace App\Http\Requests;

use App\Enums\MediaTypes;
use App\Enums\TitleType;
use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest {
    public function authorize() {
        return true;
    }

    public function rules() {
        // Skip validation if uploadId is present
        if ($this->has('uploadId')) {
            return [];
        }

        $mediaType = $this->route('mediaType') ?? $this->get('media_type');

        $rules = [];

        switch ($mediaType) {
            case MediaTypes::Image->value:
                $rules['file'] = 'required|file|mimes:jpg,jpeg,png|max:5120|dimensions:max_width=1080,max_height=1920';
                break;
            case MediaTypes::Image_Landscape->value:
                $rules['file'] = 'required|file|mimes:jpg,jpeg,png|max:5120|dimensions:max_width=1920,max_height=1080';
                break;
            case MediaTypes::MainVideo->value:
            case MediaTypes::Trailer->value:
                $rules['file'] = 'required|file|mimes:mp4,avi,mov,wmv,mkv';/*  */
                break;
            case MediaTypes::Caption->value:
                $rules['file'] = 'required|file|mimes:vtt,txt|max:2048';
                break;
            case MediaTypes::AdditionalLanguage->value:
                $rules['file'] = 'required|file|mimes:mp3,wav,m4a|max:2048';
                break;
            case MediaTypes::LegalDoc->value:
                $rules['file'] = 'required|file|mimes:pdf,doc,docx|max:5120'; // 5120 KB = 5 MB
                break;
            default:
                $rules['file'] = 'required|file|max:2048';
                break;
        }

        return $rules;
    }

    public function messages() {
        return [
            'file.required' => 'The file is required.',
            'file.file' => 'The file must be a valid file.',
            'file.mimes' => 'The file must be a file of type: :values.',
            'file.max' => 'The file may not be greater than :max kilobytes.',
            'title_id.required' => 'The title ID is required.',
            'file.dimensions' => 'The image dimensions not be greater than 1920x1080 pixels.',
        ];
    }
}
