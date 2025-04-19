@php
    use App\Enums\MediaTypes;
    use App\Helpers\FileUploadHelper;
@endphp
<div class="season_custom_class">
    <div id="season-data-container" data-upload-url="{{route('uploadFile',['mediaType' => MediaTypes::Image->value])}}"></div>
    <form class="season_custom_class">
        <div class="d-flex justify-content-between mb-3">
            <button type="button" class="btn btn-outline-secondary mb-0" id="add_season">Add Season</button>
            <button type="button" class="btn btn-primary mb-0"
                    data-season_route={{route('titleEditSeason',['uuid' => $title->uuid])}} id="save-proress-season">Save
                Progress
            </button>
        </div>
        <ul class="nav nav-tabs" id="season-tabs" role="tablist">
            @php
                $seasonNumberLink = 1;
            @endphp
            @foreach($seasons as $season)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{$seasonNumberLink == 1 ? 'active' :'' }}" data-bs-toggle="tab"
                       role="tab" aria-controls="season-content-{{$seasonNumberLink}}"
                       aria-selected="true" id="season-tab-{{$seasonNumberLink}}"
                       href="#season-content-{{$seasonNumberLink}}">Season {{$seasonNumberLink}}</a>
                </li>
                @php
                    $seasonNumberLink++;
                @endphp
            @endforeach
        </ul>
        <div class="tab-content" id="season-content">
            @php
                $seasonNumber = 1;
            @endphp
            @foreach($seasons as $season)
                @php
                    $hasImage = false;
                @endphp
                <div class="tab-pane fade {{$seasonNumber == 1 ? 'active show' :'' }}" role="tabpanel"
                     aria-labelledby="season-tab-{{$seasonNumber}}"
                     id="season-content-{{$seasonNumber}}" data-season_id="{{$season->id}}">
                    <div class="session_tab_container mt-4">
                        <input type="hidden" name="season_id" id="season_id" value="{{$season->id}}">
                        <div class="form-group">
                            <label for="season_name">Season Name<span class="text-danger">*</span></label>
                            <input type="text" id="season_name" name="season_name"
                                   class="form-control season_name"
                                   placeholder="Season Name" value="{{$season->name}}"/>
                        </div>
                        <div class="form-group">
                            <label for="synopsis">Synopsis</label>
                            <textarea id="synopsis" class="form-control" name="season_synopsis"
                                      placeholder="Synopsis" rows="3">{{$season->synopsis}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="release_date">Original Release Date<span class="text-danger">*</span></label>
                            <input type="date" id="release_date" value="{{$season->release_date}}"
                                   name="release_date" class="form-control"/>
                        </div>
                        @php
                            $SeasonImagePortrait= $season->getMedia()->where('file_type', MediaTypes::Image->value)->first();
                            $SeasonImageLandscape = $season->getMedia()->where('file_type', MediaTypes::Image_Landscape->value)->first();
                            $SeasonTrailer = $season->getMedia()->where('file_type', MediaTypes::Trailer->value)->first();
                        @endphp
                        <div class="form-group">
                            <label>Poster Upload(Portrait)<span class="text-danger">*  (jpg,jpeg,png)(MAX:1080x1920)</span></label>
                            @include('components.file_upload', [
                                'name' => 'uploaded_season_image_portrait',
                                'fileName' => $SeasonImagePortrait->file_name ?? null,
                                'url' => route('uploadFile',['mediaType' => MediaTypes::Image->value])
                            ])
                        </div>
                        <div class="form-group ">
                            <label for="season_trailer_upload">Trailer<span class="text-danger">* (mp4,avi,mov,wmv,mkv)</span></label>

                            @include('components.file_upload', [
                                'name' => 'season_uploaded_trailer',
                                'fileName' => $SeasonTrailer->file_name ?? null,
                                'url' => route('uploadFile',['mediaType' => MediaTypes::Trailer->value])
                            ])
                        </div>
                        <div class="form-group">
                            <label>Poster Upload(Landscape)<span class="text-danger">  (jpg,jpeg,png)(MAX:1920x1080)</span></label>
                            @include('components.file_upload', [
                                'name' => 'uploaded_season_image_landscape',
                                'fileName' => $SeasonImageLandscape->file_name ?? null,
                                'url' => route('uploadFile',['mediaType' => MediaTypes::Image_Landscape->value])
                            ])
                        </div>
                    </div>
                    @php
                        $episodeNumber =1;
                    @endphp

                    <div class="custom-episode-accordion">
                        <div class="custom_accordion_block mb-2">
                            <div class="accordion">
                                @foreach ($season->getEpisodes as $episode)

                                    @php
                                        $episodeMainVideo = $episode->getMedia()->where('file_type', MediaTypes::MainVideo->value)->first();
                                        $episodeCaption = $episode->getMedia()->where('file_type', MediaTypes::Caption->value)->get();
                                        $episodeLanguages = $episode->getMedia()->where('file_type', MediaTypes::AdditionalLanguage->value)->get();


                                    @endphp
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    aria-expanded="false"
                                                    data-bs-target="#collapse{{$episodeNumber}}"
                                                    aria-controls="collapse{{$episodeNumber}}">
                                                Episode {{$episodeNumber}}
                                            </button>
                                        </h2>
                                        <div
                                            class="accordion-collapse collapse {{$episodeNumber ==1 ?'show' :''}}"
                                            id="collapse{{$episodeNumber}}">
                                            <div class="accordion-body custom-accordion-section">
                                                <div class="custom-accordion-block">
                                                    <input type="hidden" id="episode_id"
                                                           name="episode_id" value="{{$episode->id}}">
                                                    <div class="form-group">
                                                        <label>Episode Name<span class="text-danger">*</span></label>
                                                        <input type="text" id="episode-name"
                                                               class="form-control"
                                                               value="{{$episode->name}}"
                                                               placeholder="Episode Name"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Synopsis</label>
                                                        <textarea class="form-control"
                                                                  id="episode-synopsis"
                                                                  placeholder="Synopsis"
                                                                  rows="3">{{$episode->synopsis}}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Original Release Date<span class="text-danger">*</span></label>
                                                        <input type="date" name="episode-release-date"
                                                               id="episode-release-date"
                                                               value="{{$episode->release_date}}"
                                                               class="form-control"/>
                                                    </div>
                                                    <div class="form-group episode_main_video_upload_container">
                                                        <label
                                                            for="episode_main_video_upload">Main
                                                            Video<span class="text-danger">* (mp4,avi,mov,wmv,mkv)</span></label>

                                                        @include('components.file_upload', [
                                                            'name' => 'episode_uploaded_main_video',
                                                            'fileName' => $episodeMainVideo->file_name ?? null,
                                                            'url' => route('uploadFile',['mediaType' => MediaTypes::MainVideo->value])
                                                        ])
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row row-cols-1">
                                                            <h6>Subtitles<span class="text-danger"> (vtt,txt)</span></h6>
                                                        </div>
                                                        <div class="row row-cols-1 row-cols-md-2 caption_container">
                                                            @foreach($episodeCaption as $caption)
                                                                <div class="col caption_item">
                                                                    <div class="input-group mb-3">
                                                                        <input type="text" class="form-control" placeholder="Subtitle Name" name="uploaded_caption_text[]"
                                                                               value="{{ $caption->pivot->text }}">
                                                                        <button class="btn btn-outline-secondary mb-0 remove_caption_item" type="button" id="button-addon2">
                                                                            <i class="fa fa-remove"></i></button>
                                                                    </div>
                                                                    <div class="form-group fs-4">
                                                                        @include('components.file_upload', [
                                                                            'name' => 'uploaded_caption[]',
                                                                            'fileName' => $caption->file_name,
                                                                            'url' => route('uploadFile',['mediaType' => MediaTypes::Caption->value])
                                                                        ])
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <a href="javascript:void(0)" class="text-primary d-flex align-items-center add_new_caption">
                                                                    <i class="fas fa-plus-circle me-1"></i>
                                                                    <span>Add Subtitle</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row row-cols-1">
                                                            <h6>Dubbed Language<span class="text-danger"> (mp3,wav,m4a)</span></h6>
                                                        </div>
                                                        <div class="row row-cols-1 row-cols-md-2 language_container">
                                                            @foreach($episodeLanguages as $language)
                                                                <div class="col language_item">
                                                                    <div class="input-group mb-3">
                                                                        <input type="text" class="form-control" placeholder="Language Name" name="uploaded_language_text[]"
                                                                               value="{{ $language->pivot->text }}">
                                                                        <button class="btn btn-outline-secondary mb-0 remove_language_item" type="button"
                                                                                id="button-addon2">
                                                                            <i class="fa fa-remove"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="form-group fs-4">
                                                                        @include('components.file_upload', [
                                                                            'name' => 'uploaded_language[]',
                                                                            'fileName' => $language->file_name,
                                                                            'url' => route('uploadFile',['mediaType' => MediaTypes::AdditionalLanguage->value])
                                                                        ])
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <a href="javascript:void(0)" class="text-primary d-flex align-items-center add_new_language">
                                                                    <i class="fas fa-plus-circle me-1"></i>
                                                                    <span>Add Language</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="delete_episode_btn mt-4">
                                                    <button type="button"
                                                            data-episode_delete_url="{{route('seller.remove-episode',['id'=>$episode->id])}}"
                                                            class="btn btn-danger">
                                                        Delete Episode
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $episodeNumber++;
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                        <div class="add_episode_button">
                            <button type="button"
                                    class="border-0 fw-bold w-100 p-2 text-start rounded-2">
                                <svg stroke="currentColor" fill="none" stroke-width="2"
                                     viewBox="0 0 24 24" stroke-linecap="round"
                                     stroke-linejoin="round" aria-hidden="true" focusable="false"
                                     height="1em" width="1em"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Add Episode
                            </button>
                        </div>
                    </div>
                    <div class="delete_season_btn mt-4">
                        <button type="button"
                                data-season_delete_url="{{route('seller.remove-season',['id'=>$season->id])}}"
                                class="btn btn-danger">Delete Season
                        </button>
                    </div>
                </div>
                @php
                    $seasonNumber++;
                @endphp
            @endforeach
            <div class="hidden" id="seasonLinkList" data-season-number={{$seasonNumber}}></div>
        </div>
    </form>
</div>
<div id="chapter-template" class="form-group d-flex hidden">
    <input type="text" name="chapter_titles[]" value="" placeholder="Chapter" class="form-control">
    <input type="text" name="chapter_timestramps[]" value="" placeholder="HH:MM:SS:FF"
           class="form-control">
    <button class="form-control remove-chapter" type="button">Remove</button>
</div>
@include('titles.form_sections.seasonEpisodeTemplate')
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

