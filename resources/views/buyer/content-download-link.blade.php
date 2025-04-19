@php
    use \App\Enums\MediaTypes;
    use \App\Enums\TitleType;
@endphp

@if($orderMeta->getTitle->type->value == TitleType::Series->value && $orderMeta->season_id)
   @php
       $season = $orderMeta->getSeason;
   @endphp
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
                        <button class="btn btn-outline-secondary btn-sm me-3"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="Download Now">
                            <i class="fas fa-download"></i>
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

                                <button class="btn btn-outline-secondary btn-sm me-3"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="bottom"
                                        title="Download Now">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@else
<div class="overflow-auto" style="max-height: 400px;">
    @foreach($orderMeta->getTitle->getTitleMediaMapping as $movie)
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

                <!-- Copy Button -->
                <button class="btn btn-outline-secondary btn-sm me-3"
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        title="Download Now">
                    <i class="fas fa-download"></i>
                </button>
            </div>
        </div>
        @endif
    @endforeach
</div>
@endif
