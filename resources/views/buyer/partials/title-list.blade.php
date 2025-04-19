@php
    use \App\Enums\TitleType;
    use \App\Enums\MediaTypes;
    use \App\Helpers\FileUploadHelper;
    use App\Models\Media;
    use App\Models\MapEpisodeMedia;
@endphp
@foreach ($titles as $title)
@php
    $title = $title->getTitle;
@endphp
    @if($title->type == TitleType::Series)
        @foreach ($title->getSeasons as $season )
        @php
            $captions = $season->getEpisodes()
            ->with(['getMedia' => function($query) {
                $query->where('file_type', MediaTypes::Caption->value);
            }])
            ->get()
            ->pluck('getMedia')
            ->flatten()
            ->pluck('pivot.text');
            $imageUrl = asset('assets/img/dummyTitle.jpg');
            $Image = $season->getMedia()->where('file_type', MediaTypes::Image->value)->first();
            if($Image){
                $imageUrl = FileUploadHelper:: filePathUrl($Image->file_name,$Image->file_path);
                if($imageUrl){
                    $imageUrl = $imageUrl->url;
                }
            }
        @endphp
            <tr>
                <td data-season-id={{$season->id}}>
                    <div class="d-flex px-2 py-1">
                        <div>
                            <img
                                src="{{ $imageUrl }}"
                                alt="{{ $Image->file_name ?? 'Poster Image' }}" class="avatar avatar-xxl me-3">
                        </div>
                        <div class="d-flex flex-column">
                            <h6 class="mb-0 text-xl">{{ $title->name }}</h6>
                            <h6 class="mb-0 text-xl">({{ $season->name }})</h6>
                            <p class="text-xs text-secondary mb-0">{{ $title->type->name ?? "" }}</p>
                        </div>
                    </div>
                </td>
                <td class="align-middle text-center text-sm">
                    <p class="mb-0">{{ $season->release_date ?? "No Date Available" }}</p>
                </td>
                <td class="align-middle text-center">
                    <span
                        class="font-weight-normal">{{ $captions->implode(', ') ?? "" }}</span>
                </td>
                <td class="align-middle text-center">
                    <span
                        class="font-weight-normal">-</span>
                </td>
                <td class="align-middle text-center">
                    <span
                        class="font-weight-normal">{{ $title->getLicenceCountry->pluck('name')->implode(', ') ?? "" }}</span>
                </td>
                <td class="text-center">
                    <a class="btn btn-secondary add-to-cart-btn"
                       href="{{route('buyer.toggle-cart-item', ['titleId' => $title->id, 'seasonId' => $season->id])}}"
                       data-class='ajaxForm'
                       title="Add to Cart"
                       data-season-id="{{$season->id}}"
                       data-id="{{$title->id}}"
                       data-method='post'>
                        <i class="fa-solid fa-cart-plus" data-fa-i2svg="false"></i>
                    </a>
                    <a href="javascript:void(0)" class="btn btn-secondary view_title_profile"
                        title="View More Details"
                        data-title-profile-route="{{ route('title.profile', ['slug' => $title->uuid, 'seasonSlug' => $season->uuid]) }}">
                        <i class="fa-solid fa-eye" data-fa-i2svg="false"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            @php
                $mediaInfoImage =  $title->getMediaThroughMovie()->where('file_type', MediaTypes::Image->value)->first();
                $filename = $mediaInfoImage->file_name ?? null;
                $filepath = $mediaInfoImage->file_path ?? null;
                $fileObjectImage = $filename && $filepath ? FileUploadHelper::filePathUrl($filename,$filepath) : null;
                $imageUrl = $fileObjectImage->url ?? asset('assets/img/dummyTitle.jpg');
                $captions = $title->getMediaThroughMovie()->where('file_type', MediaTypes::Caption->value)->pluck('text');
            @endphp
            <td>
                <div class="d-flex px-2 py-1">
                    <div>
                        <img
                            src="{{ $imageUrl }}"
                            alt="{{ $mediaInfoImage->file_name ?? 'Poster Image' }}" loading="lazy" class="avatar avatar-xxl me-3">
                    </div>
                    <div class="d-flex flex-column">
                        <h6 class="mb-0 text-xl">{{ $title->name ?? "" }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ $title->type->name ?? "" }}</p>
                    </div>
                </div>
            </td>
            <td class="align-middle text-center text-sm">
                <p class="mb-0">{{ $title->getMovieMeta->release_date ?? "No Date Available" }}</p>
            </td>
            <td class="align-middle text-center">
                <span
                    class="font-weight-normal">{{ $captions->implode(', ') ?? "" }}</span>
            </td>
            <td class="align-middle text-center">
                <span class="font-weight-normal">-</span>
            </td>
            <td class="align-middle text-center">
                <span
                    class="font-weight-normal">{{ $title->getLicenceCountry->pluck('name')->implode(', ') ?? "" }}</span>
            </td>
            <td class="text-center">
                <a class="btn btn-secondary add-to-cart-btn"
                   href="{{route('buyer.toggle-cart-item', ['titleId' => $title->id])}}"
                   data-class='ajaxForm'
                   data-id="{{$title->id}}"
                   data-method='post'>
                    <i class="fa-solid fa-cart-plus" data-fa-i2svg="false"></i>
                </a>
                <a href="javascript:void(0)" class="btn btn-secondary view_title_profile"
                    title="View More Details"
                    data-title-profile-route="{{ route('title.profile', ['slug' => $title->uuid]) }}">
                    <i class="fa-solid fa-eye" data-fa-i2svg="false"></i>
                </a>
            </td>
        </tr>
    @endif

@endforeach
