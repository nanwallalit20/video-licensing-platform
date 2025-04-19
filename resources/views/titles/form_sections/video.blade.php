@php
    use App\Enums\MediaTypes;
@endphp
<form id="video_form" class="ajaxForm" method="post"
      action="{{ route('titleEditVideo',['uuid' => $title->uuid]) }}">
    @csrf
    <div class="row row-cols-1 row-cols-md-2">
        <div class="col">
            <div class="form-group">
                <label>Poster Upload(Portrait)<span class="text-danger">*  (jpg,jpeg,png)(MAX:1080x1920)</span></label>
                @include('components.file_upload', [
                    'name' => 'uploaded_poster',
                    'fileName' => $movies->poster->getMedia->file_name ?? null,
                    'url' => route('uploadFile',['mediaType' => MediaTypes::Image->value])
                ])
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label>Upload Trailer (<span>This will the primary trailer</span>)<span
                        class="text-danger">* (mp4,avi,mov,wmv,mkv)</span></label>
                @include('components.file_upload', [
                    'name' => 'uploaded_trailer',
                    'fileName' => $movies->trailer->getMedia->file_name ?? null,
                    'url' => route('uploadFile',['mediaType' => MediaTypes::Trailer->value])
                ])
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label>Poster Upload(Landscape)<span class="text-danger">*  (jpg,jpeg,png)(MAX:1920x1080)</span></label>
                @include('components.file_upload', [
                    'name' => 'uploaded_poster_landscape',
                    'fileName' => $movies->poster_landscape->getMedia->file_name ?? null,
                    'url' => route('uploadFile',['mediaType' => MediaTypes::Image_Landscape->value])
                ])
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label>Main Video<span class="text-danger">* (mp4,avi,mov,wmv,mkv)</span></label>
                @include('components.file_upload', [
                    'name' => 'uploaded_main_video',
                    'fileName' => $movies->main_video->getMedia->file_name ?? null,
                    'url' => route('uploadFile',['mediaType' => MediaTypes::MainVideo->value])
                ])
            </div>
        </div>
    </div>
    <div class="row row-cols-1">
        <h6>Subtitles<span class="text-danger"> (vtt,txt)</span></h6>
    </div>
    <div class="row row-cols-1 row-cols-md-2 caption_container">
        @foreach($movies->captions as $caption)
            <div class="col caption_item">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Subtitle Name" name="uploaded_caption_text[]"
                           value="{{ $caption->text }}">
                    <button class="btn btn-outline-secondary mb-0 remove_caption_item" type="button" id="button-addon2">
                        <i class="fa fa-remove"></i></button>
                </div>
                <div class="form-group fs-4">
                    @include('components.file_upload', [
                        'name' => 'uploaded_caption[]',
                        'fileName' => $caption->getMedia->file_name,
                        'url' => route('uploadFile',['mediaType' => MediaTypes::Caption->value])
                    ])
                </div>
            </div>
        @endforeach
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <a href="javascript:void(0)" class="text-primary d-flex align-items-center add_new_caption">
                <i class="fas fa-plus-circle me-1"></i>
                <span>Add Subtitle</span>
            </a>
        </div>
    </div>
    <div class="row row-cols-1">
        <h6>Dubbed Language<span class="text-danger"> (mp3,wav,m4a)</span></h6>
    </div>
    <div class="row row-cols-1 row-cols-md-2 language_container">
        @foreach($movies->languages as $language)
            <div class="col language_item">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Language Name" name="uploaded_language_text[]"
                           value="{{ $language->text }}">
                    <button class="btn btn-outline-secondary mb-0 remove_language_item" type="button"
                            id="button-addon2">
                        <i class="fa fa-remove"></i>
                    </button>
                </div>
                <div class="form-group fs-4">
                    @include('components.file_upload', [
                        'name' => 'uploaded_language[]',
                        'fileName' => $language->getMedia->file_name,
                        'url' => route('uploadFile',['mediaType' => MediaTypes::AdditionalLanguage->value])
                    ])
                </div>
            </div>
        @endforeach
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <a href="javascript:void(0)" class="text-primary d-flex align-items-center add_new_language">
                <i class="fas fa-plus-circle me-1"></i>
                <span>Add Language</span>
            </a>
        </div>
    </div>
    <div class="row d-flex justify-content-end">
        <div class="col-md-3">
            <div class="form-group d-flex">
                <button type="button" class="form-control custom_submit_btn text-white back_tab"> Previous</button>
                <button type="submit" class="form-control custom_submit_btn text-white" id="video_next">
                    Save & Next
                </button>
            </div>
        </div>
    </div>
</form>

{{--Hidden div--}}
<div class="d-none caption_fields">
    <div class="col caption_item">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Subtitle Name" name="uploaded_caption_text[]">
            <button class="btn btn-outline-secondary mb-0 remove_caption_item" type="button" id="button-addon2"><i
                    class="fa fa-remove"></i></button>
        </div>
        <div class="form-group fs-4">
            @include('components.file_upload', [
                'name' => 'uploaded_caption[]',
                'url' => route('uploadFile',['mediaType' => MediaTypes::Caption->value])
            ])
        </div>
    </div>
</div>
<div class="d-none language_fields">
    <div class="col language_item">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Language Name" name="uploaded_language_text[]">
            <button class="btn btn-outline-secondary mb-0 remove_language_item" type="button" id="button-addon2">
                <i class="fa fa-remove"></i>
            </button>
        </div>
        <div class="form-group fs-4">
            @include('components.file_upload', [
                'name' => 'uploaded_language[]',
                'url' => route('uploadFile',['mediaType' => MediaTypes::AdditionalLanguage->value])
            ])
        </div>
    </div>
</div>


