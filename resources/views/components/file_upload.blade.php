<div class="chunk_upload_container mb-4" data-url="{{ $url ?? route('uploadFile') }}">
    <div class="input-group">
        <input type="file" data-name="{{ $name }}" class="form-control rounded-2 border border-1 chunk_upload">
        <input type="hidden" class="fieldName" name="{{$name}}" value="{{$fileName ?? ''}}">
        <button type="button" class="btn btn-outline-secondary m-auto chunk_upload_cancel_button">Cancel Upload</button>
    </div>
    <div class="progress rounded-1" style="height: 15px;">
        <div class="progress-bar rounded-1 m-0 chunk_progress" role="progressbar"
             style="width: 100%; height: 15px;"
             aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{ $fileName ?? 'Pick File to upload' }}
        </div>
    </div>
</div>
