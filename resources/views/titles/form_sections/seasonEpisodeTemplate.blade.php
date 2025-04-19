@php
    use App\Enums\MediaTypes;
@endphp
<template id="season-tab-template">
    <li class="nav-item" role="presentation">
        <a class="nav-link" data-bs-toggle="tab" role="tab" aria-controls="" aria-selected="false">
            Season Name
        </a>
    </li>
</template>
<template id="season-content-template">
    <div class="tab-pane fade" role="tabpanel" aria-labelledby="">

        <div class="session_tab_container mt-4">
            <input type="hidden" name="season_id" id="season_id" value="">
            <div class="form-group">
                <label for="season_name">Season Name<span class="text-danger">*</span></label>
                <input type="text" id="season_name" name="season_name" class="form-control season_name" placeholder="Season Name">
            </div>
            <div class="form-group">
                <label for="synopsis">Synopsis</label>
                <textarea id="synopsis" class="form-control" name="season_synopsis" placeholder="Synopsis" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="release_date">Original Release Date<span class="text-danger">*</span></label>
                <input type="date" id="release_date" name="release_date" class="form-control">
            </div>

            <div class="form-group">
                <label>Poster Upload(Portrait)<span class="text-danger">*  (jpg,jpeg,png)(MAX:1080x1920)</span></label>
                @include('components.file_upload', [
                    'name' => 'uploaded_season_image_portrait',
                    'url' => route('uploadFile',['mediaType' => MediaTypes::Image->value])
                ])
            </div>
            <div class="form-group ">
                <label for="season_trailer_upload">Trailer<span class="text-danger">* (mp4,avi,mov,wmv,mkv)</span></label>

                @include('components.file_upload', [
                    'name' => 'season_uploaded_trailer',
                    'url' => route('uploadFile',['mediaType' => MediaTypes::Trailer->value])
                ])
            </div>
            <div class="form-group">
                <label>Poster Upload(Landscape)<span class="text-danger">  (jpg,jpeg,png)(MAX:1920x1080)</span></label>
                @include('components.file_upload', [
                    'name' => 'uploaded_season_image_landscape',
                    'url' => route('uploadFile',['mediaType' => MediaTypes::Image_Landscape->value])
                ])
            </div>
        </div>
        <div class="custom-episode-accordion">
            <div class="custom_accordion_block mb-2">
                <div class="accordion"></div>
            </div>
            <div class="add_episode_button">
                <button type="button" class="border-0 fw-bold w-100 p-2 text-start rounded-2">
                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add Episode
                </button>
            </div>
        </div>
        <div class="delete_season_btn mt-4">
            <button type="button" class="btn btn-danger" >Delete Season</button>
        </div>



    </div>
</template>
<template id="episode-template">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" aria-expanded="false">
                Episode
            </button>
        </h2>
        <div class="accordion-collapse collapse">
            <div class="accordion-body custom-accordion-section">
                <div class="custom-accordion-block">
                    <input type="hidden" id="episode_id" name="episode_id" value="">
                    <div class="form-group">
                        <label>Episode Name<span class="text-danger">*</span></label>
                        <input type="text" id="episode-name" class="form-control" placeholder="Episode Name">
                    </div>
                    <div class="form-group">
                        <label>Synopsis</label>
                        <textarea class="form-control"  id="episode-synopsis" placeholder="Synopsis" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Original Release Date<span class="text-danger">*</span></label>
                        <input type="date" name="episode-release-date" id="episode-release-date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="episode_main_video_upload">Main Video<span class="text-danger">* (mp4,avi,mov,wmv,mkv)</span></label>
                        @include('components.file_upload', [
                            'name' => 'episode_uploaded_main_video',
                            'url' => route('uploadFile',['mediaType' => MediaTypes::MainVideo->value])
                        ])
                    </div>
                    <div class="form-group">
                        <div class="row row-cols-1">
                            <h6>Subtitles<span class="text-danger"> (vtt,txt)</span></h6>
                        </div>
                        <div class="row row-cols-1 row-cols-md-2 caption_container">
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
                    <button type="button" class="btn btn-danger">Delete Episode</button>
                </div>
            </div>
        </div>
    </div>
</template>
