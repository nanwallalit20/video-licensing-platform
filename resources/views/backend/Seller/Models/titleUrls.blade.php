@php
    use \App\Enums\TitleType;
    use \App\Enums\MediaTypes;
    use App\Helpers\FileUploadHelper;
@endphp
@if ($title->type == TitleType::Movie)
    <div class="overflow-auto" style="max-height: 400px;">
        @foreach($title->getTitleMediaMapping as $movie)
            @if($movie->getMedia->file_type != MediaTypes::LegalDoc)
                <div class="d-flex align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center flex-grow-1 gap-3">
                        <!-- Type Badge -->
                        <span class="badge bg-light text-dark text-uppercase">
                    {{ $movie->getMedia->file_type->displayName() }}
                </span>
                        @if($movie->text)
                            <span class="badge bg-secondary">
                    {{ $movie->text }}
                </span>
                        @endif

                        @php
                            $getFileInfo = FileUploadHelper::filePathUrl($movie->getMedia->file_name, $movie->getMedia->file_path);
                            $url = $getFileInfo->url ?? null;
//                            download Path = echo env('AWS_CLOUDFRONT_URL').'/'. $movie->getMedia->file_path.'/'.$movie->getMedia->file_name;
                        @endphp

                            <!-- URL -->
                        <div class="text-break flex-grow-1">{{ $url }}</div>

                        <!-- Copy Button -->
                        <button class="btn btn-outline-secondary btn-sm copy-btn me-3"
                                data-copy-text="{{ $url }}"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="Copy">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@else
    @foreach($title->getSeasons as $season)
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Season: {{$season->name}}</h5>
            </div>
            <div class="card-body">
                @if($season->getMedia->count() > 0)
                    @foreach($season->getMedia as $media)
                        <div class="d-flex align-items-center py-2 border-bottom">
                            <div class="d-flex align-items-center flex-grow-1 gap-3">
                                <span class="badge bg-light text-dark text-uppercase">
                                    {{ $media->file_type->displayName() }}
                                </span>

                                @php
                                    $url = $media->file_path .'/'.$media->file_name;
                                @endphp

                                <div class="text-break flex-grow-1">{{ $url }}</div>

                                <button class="btn btn-outline-secondary btn-sm copy-btn me-3"
                                        data-copy-text="{{ $url }}"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="bottom"
                                        title="Copy">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif

                @foreach($season->getEpisodes as $episode)
                    <div class="card mt-3 ms-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Episode: {{$episode->name}}</h6>
                        </div>
                        <div class="card-body">
                            @foreach($episode->getMedia as $media)
                                <div class="d-flex align-items-center py-2 border-bottom">
                                    <div class="d-flex align-items-center flex-grow-1 gap-3">
                                        <span class="badge bg-light text-dark text-uppercase">
                                            {{ $media->file_type->displayName() }}
                                        </span>
                                        @if($media->pivot->text)
                                            <span class="badge bg-secondary">
                                            {{ $media->pivot->text }}
                                        </span>
                                        @endif

                                        @php
                                            $url = $media->file_path .'/'.$media->file_name;
                                        @endphp

                                        <div class="text-break flex-grow-1">{{ $url }}</div>

                                        <button class="btn btn-outline-secondary btn-sm copy-btn me-3"
                                                data-copy-text="{{ $url }}"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="bottom"
                                                title="Copy">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@endif
