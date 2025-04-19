@php
    use App\Enums\MediaTypes;
@endphp
<form id="video_form" class="ajaxForm" method="post"
      action="{{ route('titleEditDocument',['uuid' => $title->uuid]) }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="e_sign_dropzone">Upload E-Sign<span class="text-danger">* (pdf, doc, docx)</span></label>
                @include('components.file_upload', [
                    'name' => 'uploaded_e_sign',
                    'fileName' => $legal_doc->getMedia->file_name ?? null,
                    'url' => route('uploadFile',['mediaType' => MediaTypes::LegalDoc->value])
                ])
            </div>
        </div>
    </div>
    <div class="row mt-3 submit_btn_custom d-flex justify-content-end">
        <div class="col-md-3">
            <div class="form-group d-flex">
                <button type="button" class="form-control custom_submit_btn text-white back_tab">
                    Previous
                </button>
                <button type="submit" class="form-control custom_submit_btn text-white" id="submit_form">Save
                </button>
            </div>
        </div>
    </div>
</form>
