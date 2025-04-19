@extends('layouts.app')
@section('styles')
    <style>
        .movies_form_nav a.nav-link.active {
            background: white;
        }

        .custom_submit_btn {
            background: #cb0c9f !important;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('title','')
@section('content')
    @php
        use \App\Enums\MediaTypes;
        use \App\Enums\TitleType;
    @endphp
    <div id="data-container"
         data-media-types=@json(MediaTypes::asArray()) data-title-types=@json(TitleType::asArray()) data-storage-path={{asset('storage') }} ></div>
    <div class="row d-flex justify-content-end">
        <div class="col-md-2 d-flex justify-content-end align-items-center">
            <a href="{{route('titleSubmitForReview',['uuid' => $title->uuid])}}" data-method="post"
               class="btn btn-sm p-2 btn-primary" data-class="ajaxForm">Submit For Review</a>
        </div>
    </div>
    <div class="title_edit_form_container">
        <div class="nav-wrapper position-relative end-0">
            <ul class="nav nav-pills nav-fill bg-gray-400 p-1 movies_form_nav" role="tablist">
                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="pill" id="profile_tab" href="#profile"
                       role="tab" aria-controls="profile" aria-selected="false">
                        Profile
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="pill" href="#contact" id="contact_tab" role="tab"
                       aria-controls="contact" aria-selected="true">
                        Contact
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="pill" href="#document" id="document_tab"
                       role="tab"
                       aria-controls="document" aria-selected="true">
                        Document
                    </a>
                </li>
                @if($title->type->value == TitleType::Movie->value)
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="pill" href="#video" id="video_tab" role="tab"
                           aria-controls="video" aria-selected="true">
                            Video
                        </a>
                    </li>
                @endif
                @if($title->type->value == TitleType::Series->value)
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="pill" href="#season" id="season_tab"
                           role="tab"
                           aria-controls="season" aria-selected="true">
                            Seasons
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <div id="data-container" data-unique-id="{{ $title->getActors->count() ?? 0 }}"></div>
        <div class="tab-content bg-white p-4 rounded-4 mt-4 shadow-lg">
            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                @include('titles.form_sections.profile')
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                @include('titles.form_sections.contact')
            </div>
            <div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="document-tab">
                @include('titles.form_sections.documents')
            </div>
            @if($title->type->value == TitleType::Movie->value)
                <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
                    @include('titles.form_sections.video')
                </div>
            @endif
            @if($title->type->value == TitleType::Series->value)
                <div class="tab-pane fade" id="season" role="tabpanel" aria-labelledby="season-tab">
                    @include('titles.form_sections.series')
                </div>
            @endif
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.1.0/resumable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script src="{{asset('/js/title.js')}}"></script>
@endsection
