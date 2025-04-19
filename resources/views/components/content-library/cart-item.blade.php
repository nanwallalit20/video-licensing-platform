@php
    use App\Models\Title;
    use App\Enums\MediaTypes;
    use App\Helpers\FileUploadHelper;
@endphp
@foreach($cartItems as $item)
    @php
        $title = Title::find($item->title_id);
        if (!$title) continue;
    @endphp

    @if($item->season_id)
        @php
            $season = $title->getSeasons->where('id', $item->season_id)->first();
            if (!$season) continue;
            $image = $season->getMedia->where('file_type', MediaTypes::Image->value)->first();
            if($image){
                $mediaObject = FileUploadHelper::filePathUrl($image->file_name, $image->file_path);
                $imageUrl = $mediaObject
                ? $mediaObject->url
                : asset('assets/img/dummyTitle.jpg');
            }else{
                $imageUrl =asset('assets/img/dummyTitle.jpg');
            }

        @endphp
        <div class="d-flex mb-2">
            <div class="col-3">
                <img src="{{ $imageUrl }}" alt="{{ $title->name }}" class="avatar avatar-l me-3 cart-item-image">
            </div>
            <div class="col-6">
                <strong class="cart-item-name">{{ $title->name }} ({{ $season->name }})</strong><br>
            </div>
            <div class="col-3">
                <span class="cart-item-type">{{ $title->type->name }}</span>
            </div>
        </div>
    @else
        @php
            $image = $title->getMediaThroughMovie->where('file_type', MediaTypes::Image->value)->first();
            if($image){
                $mediaObject = FileUploadHelper::filePathUrl($image->file_name, $image->file_path);
                $imageUrl = $mediaObject
                ? $mediaObject->url
                : asset('assets/img/dummyTitle.jpg');
            }else{
                $imageUrl =asset('assets/img/dummyTitle.jpg');
            }
        @endphp
        <div class="d-flex mb-2">
            <div class="col-3">
                <img src="{{ $imageUrl }}" alt="{{ $title->name }}" class="avatar avatar-l me-3 cart-item-image">
            </div>
            <div class="col-6">
                <strong class="cart-item-name">{{ $title->name }}</strong><br>
            </div>
            <div class="col-3">
                <span class="cart-item-type">{{ $title->type->name }}</span>
            </div>
        </div>
    @endif
@endforeach
