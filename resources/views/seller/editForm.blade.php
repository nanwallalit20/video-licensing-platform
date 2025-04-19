@extends('layouts.app')
@section('title',"Seller Profile")
@section('content')
@php
use \App\Enums\TitleStatus;
use \App\Enums\TitleType;
@endphp
<div class="container-fluid p-0">

    <div class="card card-body blur shadow-blur">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <img src="{{asset('assets/img/dummyuser.webp')}}" alt="..." class="w-100 border-radius-lg shadow-sm">
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">
                        {{ $seller->name }}
                        <a class="ms-2" href="{{ route('switchAccount', $seller->id) }}" title="Go to Seller Dashboard">
                            <i class="fas fa-sign-in-alt" style="color: #0ca7ca"></i>
                        </a>
                    </h5>
                    <p class="mb-0 font-weight-bold text-sm">
                        {{ $seller->getUserProfile->role_id->displayName() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="title_edit_form_container">
        <div class="nav-wrapper position-relative mt-4">
            <ul class="nav nav-pills nav-fill bg-gray-400 p-1 seller_profile_nav" role="tablist">
                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="pill" href="#all_titles" id="all_titles_tab" role="tab"
                       aria-controls="all_titles" aria-selected="true">
                        All Titles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="pill" href="#documents" id="documents_tab" role="tab"
                       aria-controls="documents" aria-selected="true">
                        Documents
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="pill" href="#agreements" id="agreements_tab" role="tab"
                       aria-controls="agreements" aria-selected="true">
                        Agreements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="pill" href="#platforms" id="platforms_tab" role="tab"
                       aria-controls="platforms" aria-selected="true">
                        Platforms
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="pill" href="#analytics" id="analytics_tab" role="tab"
                       aria-controls="analytics" aria-selected="true">
                        Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="pill" id="basic_information_tab" href="#basic_information"
                       role="tab" aria-controls="basic_information" aria-selected="false">
                        Basic Info
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content rounded-4 mt-4 shadow-lg">
            <div class="tab-pane fade show active" id="all_titles" role="tabpanel" aria-labelledby="all_titles-tab">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>All Titles</h6>
                                <form method="GET" id="title-filter-form" action="{{ route('titles') }}" class="mb-4">
                                    <input type="hidden" name="seller_id" value="{{ $seller->id }}">
                                    <input type="hidden" name="isAjax" value="true">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" name="title_name" class="form-control" placeholder="Search by Title Name" value="{{ request('title_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" name="title_uuid" class="form-control" placeholder="Search by Title ID" value="{{ request('title_uuid') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-flex justify-content-start gap-2">
                                            <button class="btn btn-sm bg-gradient-admin-blue" id="title-filter-btn">Filter</button>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-secondary d-flex align-items-center" id="title-reset-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="title-list-container">
                                @include('backend.Seller.Component.title-list',['titles'=>$titles])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="agreements" role="tabpanel" aria-labelledby="agreements-tab">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Agreements</h6>
                            </div>
                            <div class="card-body">
                                <!-- Agreements content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Documents</h6>
                            </div>
                            <div class="card-body">
                                <!-- Agreements content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="platforms" role="tabpanel" aria-labelledby="platforms-tab">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Platforms</h6>
                            </div>
                            <div class="card-body">
                                <!-- Platforms content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
                <div class="row mb-4 p-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-3">
                                <form id="dateFilterForm" class="row align-items-center">
                                    <input type="hidden" name="isAjax" value="true">
                                    <input type="hidden" name="sellerId" value="{{ $seller->id }}">
                                    <div class="col-md-2">
                                        <label class="form-label">Date Range</label>
                                        <select class="form-control" id="dateRangeSelect" name="date_range">
                                            <option value="7" {{ request('date_range') == '7' ? 'selected' : '' }}>Last 7 Days</option>
                                            <option value="30" {{ (request('date_range') == '30' || !request('date_range')) ? 'selected' : '' }}>Last 30 Days</option>
                                            <option value="90" {{ request('date_range') == '90' ? 'selected' : '' }}>Last 3 Months</option>
                                            <option value="180" {{ request('date_range') == '180' ? 'selected' : '' }}>Last 6 Months</option>
                                            <option value="365" {{ request('date_range') == '365' ? 'selected' : '' }}>Last 12 Months</option>
                                            <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 custom-date-inputs" style="display: none;">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="startDate" name="start_date" value="{{ request('start_date') }}">
                                    </div>
                                    <div class="col-md-2 custom-date-inputs" style="display: none;">
                                        <label class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="endDate" name="end_date" value="{{ request('end_date') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Select Title</label>
                                        <select class="form-control" name="title_id">
                                            <option value="all">All Titles</option>
                                            @foreach($analyticsData['approvedTitles'] as $title)
                                                <option value="{{ $title->id }}">
                                                    {{ $title->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Select Platform</label>
                                        <select class="form-control" name="platform_id">
                                            <option value="all">All Platforms</option>
                                            @foreach($analyticsData['platforms'] as $platform)
                                                <option value="{{ $platform->id }}">
                                                    {{ $platform->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 mt-4">
                                        <button id="analytics-filter-btn" class="btn bg-gradient-admin-blue mb-0 text-white">Apply Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="analytics_container">
                    @include('backend.Seller.Component.analytics',['analyticsData'=>$analyticsData])
                </div>
            </div>
            <div class="tab-pane fade " id="basic_information" role="tabpanel" aria-labelledby="basic_information-tab">
                <div class="card">
                    <div class="card-header pb-0 px-3">
                        <h6 class="mb-0">Basic Info</h6>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.name" class="form-control-label">Name</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $seller->name }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.email" class="form-control-label">Email</label>
                                    <input type="email" class="form-control" placeholder="Email" value="{{ $seller->email }}" disabled >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Phone</label>
                                    <input type="text" class="form-control" placeholder="97XXXXXX" value="{{ $seller->getUserProfile->phone }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.location" class="form-control-label">Whatsapp Number</label>
                                    <input type="text" class="form-control" placeholder="97XXXXXX" value="{{ $seller->getUserProfile->whatsapp_number }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mediaUrlsModal" tabindex="-1" aria-labelledby="mediaUrlsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentLinkModalLabel">Media Urls</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times" style="color: black" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body" id="mediaUrlsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('backend.Seller.Models.documentViewModal')
@include('titles.revenue.revenueDocViewModal')
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle media URLs modal
        $('.view_media_urls').on('click', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const route = $btn.data('route');

            $('#loader').show()

            // Fetch media URLs
            $.get(route, function(response) {
                $('#mediaUrlsContent').html(response);
                $('#mediaUrlsModal').modal('show');
                $('#loader').hide()
            }).fail(function(xhr) {
                $('#loader').hide()
                console.error('Error loading media URLs:', xhr);
                $('#loader').hide();
            });
        });

        // Handle title document modal
        $('.view_title_document_modal').on('click', function(e) {
            e.preventDefault();
            let docModal = $('#view_title_document_modal');
            const $btn = $(this);
            const route = $btn.data('view-doc-url');

            $('.loader').show();

            $.get(route, function(mediaObject) {
                let document = mediaObject[0] ?? null;
                let iframeHtml;

                if (document && document.url) {
                    if (document.url.endsWith('.pdf')) {
                        iframeHtml = `<iframe id="doc_iframe" src="${document.url}" width="100%" height="100%"></iframe>`;
                    } else {
                        iframeHtml = `<iframe id="doc_iframe" src="https://docs.google.com/gview?url=${encodeURIComponent(document.url)}&embedded=true" width="100%" height="100%"></iframe>`;
                    }
                    docModal.find('.modal-body').html(iframeHtml);
                    docModal.modal('show');
                } else {
                    justify.customJustify('error', 'Document not found.');
                }
                $('.loader').hide();
            }).fail(function(xhr) {
                console.error('Error loading document:', xhr);
                $('.loader').hide();
            });

            setTimeout(() => {
                $('.loader').hide();
            }, 5000);
        });

        const $dateRangeSelect = $('#dateRangeSelect');
        const $customDateInputs = $('.custom-date-inputs');

        // Show/hide custom date inputs based on selection
        $dateRangeSelect.on('change', function() {
            const showCustomDates = $(this).val() === 'custom';
            $customDateInputs.toggle(showCustomDates);
        });

        // Initialize date inputs if custom is selected
        if ($dateRangeSelect.val() === 'custom') {
            $customDateInputs.show();
        }

        $('#analytics-filter-btn').on('click', function(e) {
            e.preventDefault();
            const $form = $(this).closest('form');
            const formData = $form.serialize();
            $('#loader').show();

            $.get("{{route('seller.analytics')}}", formData)
            .done(function (response) {
                $('#analytics_container').html('');
                $('#analytics_container').html(response);
                graphData();
                $('#loader').hide();
            })
            .fail(function (xhr, status, error) {
                $('#loader').hide();
                justify.customJustify('error', 'Error while displaying payment link modal.');
            })

        });

        $('#title-filter-btn').on('click', function(e) {
            e.preventDefault();
            const $form = $('#title-filter-form');
            const formData = $form.serialize();
            $('#loader').show();
            $.get("{{route('titles')}}", formData)
            .done(function (response) {
                $('#title-list-container').html('');
                $('#title-list-container').html(response);
                $('#loader').hide();
            })
            .fail(function (xhr, status, error) {
                $('#loader').hide();
                justify.customJustify('error', 'Error while displaying payment link modal.');
            })
        });

        $('#title-reset-btn').on('click', function(e) {
            e.preventDefault();
            const $form = $('#title-filter-form');
            const hiddenFields = $form.find('input[type="hidden"]').serialize();
            $('#loader').show();
            $.get("{{route('titles')}}", hiddenFields)
            .done(function (response) {
                $('#title-list-container').html('');
                $form.find('input:not([type="hidden"])').val('');
                $('#title-list-container').html(response);
                $('#loader').hide();
            })
            .fail(function (xhr, status, error) {
                $('#loader').hide();
                justify.customJustify('error', 'Error while displaying payment link modal.');
            })
        });
    });
</script>

@endsection
