@extends('layouts.app')
@section('title', 'My Content')
@section('styles')
<style>
    body{
	padding: 0;
	margin: 0;
	box-sizing: border-box;
}
.sidebar.custom-sidebar {
  height: 100vh;
  position: fixed;
  background: #edf2f6;
}
.sidebar .logo-img img{
	max-height: 2rem;
	max-width: 100%;
}
main {
  margin-left: 16.6667%;
}
.sidebar.custom-sidebar ul > li > a{
	box-shadow: rgba(17, 17, 26, 0.1) 0px 1px 0px;
	color: #cb0c9f;
}
.sidebar.custom-sidebar ul > li > a:hover{
	background: #cb0c9f;
    color: #fff;
}
</style>
@endsection
@section('content')
@php
use App\Helpers\FileUploadHelper;
use App\Enums\TitleType;
use App\Enums\MediaTypes;
@endphp
<div class="ms-0 content-block-page">
    <div class="d-flex flex-column py-4">
      <div class="card">
        <div class="table-responsive">
          <table class="table align-items-center mb-0 my-content-table">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary font-weight-bolder opacity-7 w-30">Name</th>
                <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">Release Date</th>
                <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">Genres</th>
                <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">License Available</th>
                <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">Download</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($content as $item)
                    @foreach ($item->getOrderMetas as $meta)
                        @if ($meta->season_id && $meta->getTitle->type->value == TitleType::Series->value)
                        <tr>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div>
                                        @php
                                         $imageUrl = asset('assets/img/dummyTitle.jpg');
                                         $Image = $meta->getSeason->getMedia->first();
                                         if($Image){
                                            $imageUrl = FileUploadHelper:: filePathUrl($Image->file_name,$Image->file_path);
                                            if($imageUrl){
                                                $imageUrl = $imageUrl->url;
                                            }
                                         }
                                        @endphp
                                        <img
                                            src="{{ $imageUrl }}"
                                            alt="{{ $Image->file_name }}" class="avatar avatar-xxl me-3">
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-0 text-xl">{{ $meta->getTitle->name}} ({{ $meta->getSeason->name }})</h6>
                                        <p class="text-xs text-secondary mb-0">{{ $meta->getTitle->type->displayName() ?? "" }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="align-middle text-center text-sm">{{ $meta->getSeason->release_date }}</p>
                            </td>
                            <td>
                                <p class="align-middle text-center text-sm">{{ $meta->getTitle->getGenres->pluck('name')->implode(', ') }}</p>
                            </td>
                            <td>
                                <p class="align-middle text-center text-sm">{{ $meta->getTitle->getLicenceCountry->pluck('name')->implode(', ') ?? "" }}</p>
                            </td>
                            <td class="align-middle text-center">
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm p-2 generate-content-download-link" data-content-download-route="{{ route('buyer.download-content', $meta->id) }}">Generate Links</a>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div>
                                        @php

                                         $imageUrl = asset('assets/img/dummyTitle.jpg');
                                         $ImageMapping = $meta->getTitle->getTitleMediaMapping()->whereHas('getMedia',function($q){$q->where('file_type',MediaTypes::Image->value);})->first();
                                         $Image = $ImageMapping->getMedia;
                                         if($Image){
                                            $imageUrl = FileUploadHelper:: filePathUrl($Image->file_name,$Image->file_path);
                                            if($imageUrl){
                                                $imageUrl = $imageUrl->url;
                                            }
                                         }
                                        @endphp
                                        <img
                                            src="{{ $imageUrl }}"
                                            alt="{{ $Image->file_name }}" class="avatar avatar-xxl me-3">
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-0 text-xl">{{ $meta->getTitle->name }}</h6>
                                        <p class="text-xs text-secondary mb-0">{{ $meta->getTitle->type->displayName() ?? "" }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="align-middle text-center text-sm">{{ $meta->getTitle->getMovieMeta->release_date }}</p>
                            </td>
                            <td>
                                <span class="align-middle text-center text-sm">{{ $meta->getTitle->getGenres->pluck('name')->implode(', ') }}</span>
                            </td>
                            <td>
                                <span class="align-middle text-center text-sm">{{ $meta->getTitle->getLicenceCountry->pluck('name')->implode(', ') ?? "" }}</span>
                            </td>
                            <td class="align-middle text-center text-sm">
                               <a href="javascript:void(0)" class="btn btn-primary btn-sm p-2 generate-content-download-link" data-content-download-route="{{ route('buyer.download-content', $meta->id) }}">Generate Links</a>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>
@include('buyer.modals.content-download-link')
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.generate-content-download-link').click(function() {
            var contentDownloadRoute = $(this).data('content-download-route');
            $('.loader').show();
            $.get(contentDownloadRoute, function(response) {
                $('.loader').hide();
                $('#contentDownloadLinkContent').html(response);
                $('#contentDownloadLinkModal').modal('show');
            })
            .fail(function() {
                justify.customJustify('error', 'Error fetching content download links.');
                $('.loader').hide();
            });
        });
    });
</script>
@endsection
