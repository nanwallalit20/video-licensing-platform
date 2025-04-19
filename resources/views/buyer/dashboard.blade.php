@extends('layouts.app')
@section('title','Welcome to Movie Rites Content Library')
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
.custom-select-dropdown .dropdown .form-control > span{
	color: #6c757d;
}
.top-search-block{
	gap: 15px;
}
.top-search-block > div{
	flex: 1;
}
.top-search-block input:focus{
	box-shadow: none;
    outline: 0;
    border-color: #dee2e6;
}
.custom-select-dropdown{
	gap: 10px;
}
.sidebar.custom-sidebar ul > li > a:hover{
	background: #cb0c9f;
    color: #fff;
}
.custom-select-dropdown > label{
	white-space: nowrap;
}
.dropdown-filter{
	gap: 8px;
}
.dropdown-filter .dropdown-btn:after{
	content: "";
    position: absolute;
    border: solid #6c757d;
    border-width: 0 2px 2px 0;
    display: inline-block;
    padding: -18px;
    height: 8px;
    width: 8px;
    transform: rotate(45deg);
    -webkit-transform: rotate(45deg);
    top: 13px;
    right: 20px;
}
.dropdown-filter .dropdown-btn.show:after{
	border: solid #fff;
	border-width: 0 2px 2px 0;
}
.dropdown-filter .dropdown-btn:hover:after{
  border: solid #6c757d;
	border-width: 0 2px 2px 0;
}
.dropdown-filter .dropdown-btn.selected{
    background-image: linear-gradient(310deg, #cb0c9f, #cb0c9f);
    color: #fff;
}
.dropdown-filter .dropdown-btn.selected:after{
	display: none;
}
.resetfilter .reset-btn{
	display: none;
}
.dropdown-filter .close-icon {
  cursor: pointer;
  color: #fff;
  font-weight: bold;
  display: none;
  position: absolute;
  top: 4px;
  right: 20px;
  font-size: 20px;
}
.custom_table .table tbody > tr > td  img{
	max-height: 7rem;
    max-width: 100%;
}
.custom-select-dropdown .select-dropdown-btn:after{
	content: "";
    position: absolute;
    border: solid #6c757d;
    border-width: 0 2px 2px 0;
    display: inline-block;
    padding: -18px;
    height: 8px;
    width: 8px;
    transform: rotate(45deg);
    -webkit-transform: rotate(45deg);
    top: 13px;
    right: 20px;
}
.custom-select-dropdown .select-dropdown-btn.show:after,
.dropdown-filter .dropdown-btn.show:after{
	transform: rotate(226deg);
    -webkit-transform: rotate(226deg);
}
.custom-select-dropdown .dropdown .selected.form-control > span{
	color: #000;
}
.custom-select-dropdown .select-dropdown-btn.selected:after{
	border: solid #000;
	border-width: 0 2px 2px 0;
}
.custom_table .table td, .custom_table .table th{
  white-space: inherit;
}
.custom_table .table td, .custom_table p{
  font-size: 14px;
}
.dropdown-filter .dropdown button.btn:focus-visible{
  box-shadow: none !important;
}
.content-block-page{
  overflow-x: hidden;
}
.btn-close {
  background-color: black; /* Make the close button black */
  opacity: 1; /* Ensure full opacity */
}
.buyer_filter .dropdown ul{
    max-height: 200px;
    overflow: auto;
}
.buyer_filter .dropdown ul::-webkit-scrollbar{
    display: none;
}
</style>
@endsection
@section('content')
@php
use \App\Enums\TitleType;
@endphp
<div style="display: none" id="content-data-container" data-title-type=@json(TitleType::asArray()) data-content_library_page_route="{{ route("dashboard") }}" data-fetchTitleByIds_route = "{{route("fetchTitlesByIds")}}" data-updateSelectedTitles_route= "{{route("updateSelectedTitles")}}" data-toggle-cart-item-route="{{ url('/bu/toggle-cart-item') }}"
data-cart-items="{{$cartItems}}" >

</div>
<div class="ms-0 content-block-page">
    <div class="d-flex flex-column py-4">
      <!-- Dropdown Filters -->
      <div class="d-flex flex-wrap mb-3 dropdown-filter buyer_filter">
        <div class="col-md-2">
            <input type="text" name="title_name" id="title_name_filter" class="form-control me-2" placeholder="Search Title">
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-btn position-relative pe-5" type="button" data-bs-toggle="dropdown" data-default="Genres">
            <span class="button-text">Genres</span>
            <span class="close-icon">&times;</span>
          </button>
          <ul class="dropdown-menu">
            @foreach($genres as $genre)
            <li><a class="dropdown-item" href="#" data-value="{{ $genre->id }}">{{ $genre->name }}</a></li>
            @endforeach
          </ul>
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-btn position-relative pe-5" type="button" data-bs-toggle="dropdown" data-default="tags">
            <span class="button-text">Tags</span>
            <span class="close-icon">&times;</span>
          </button>
          <ul class="dropdown-menu">
            @foreach($tags as $tag)
                <li><a class="dropdown-item" href="#" data-value="{{ $tag->id }}">{{ $tag->name }}</a></li>
            @endforeach
          </ul>
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-btn position-relative pe-5" type="button" data-bs-toggle="dropdown" data-default="advisory">
            <span class="button-text">Advisory</span>
            <span class="close-icon">&times;</span>
          </button>
          <ul class="dropdown-menu">
            @foreach($advisories as $advisory)
            <li><a class="dropdown-item" href="#" data-value="{{ $advisory->id }}">{{ $advisory->name }}</a></li>
            @endforeach
          </ul>
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-btn position-relative pe-5" type="button" data-bs-toggle="dropdown" data-default="countries">
            <span class="button-text">Countries</span>
            <span class="close-icon">&times;</span>
          </button>
          <ul class="dropdown-menu">
            @foreach($countries as $country)
            <li><a class="dropdown-item" href="#" data-value="{{ $country->id }}">{{ $country->name }}</a></li>
            @endforeach
          </ul>
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-btn position-relative pe-5" type="button" data-bs-toggle="dropdown" data-default="licence">
            <span class="button-text">License Available</span>
            <span class="close-icon">&times;</span>
          </button>
          <ul class="dropdown-menu">
            @foreach($countries as $country)
            <li><a class="dropdown-item" href="#" data-value="{{ $country->id }}">{{ $country->name }}</a></li>
            @endforeach
          </ul>
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-btn position-relative pe-5" type="button" data-bs-toggle="dropdown" data-default="year">
            <span class="button-text">Year</span>
            <span class="close-icon">&times;</span>
          </button>
          <ul class="dropdown-menu">
            @for ($year = date('Y'); $year >= 2000; $year--)
            <li><a class="dropdown-item" href="#" data-value="{{ $year }}">{{ $year }}</a></li>
            @endfor
          </ul>
        </div>
        <div class="resetfilter">
           <button id="resetAllFilters" class="btn btn-secondary reset-btn">Reset All</button>
        </div>
        <div class="d-flex justify-content-end mb-3 ms-auto">
            <button id="viewCartBtn" class="btn btn-primary btn-sm" style="display: none;">
                <i class="fa-solid fa-cart-shopping" style="color: white;" title="View Cart"></i>
            </button> <!-- Initially hidden -->
        </div>
      </div>

      <!-- Table/List -->

      <div class="card">
        <!-- Add "Add to Cart" Button -->
        <div class="table-responsive">
          <table class="table align-items-center mb-0 content-table">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary font-weight-bolder opacity-7 w-30">Name</th>
                <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">Release Date</th>
                <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">Subtitles</th>
                <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">Platforms</th>
                <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">License Available</th>
                <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">Action</th>
              </tr>
            </thead>
            <tbody>
              @include('buyer.partials.title-list', ['titles' => $titles])
            </tbody>
          </table>
        </div>
        <!-- Add Pagination Links -->
        <div class="d-flex justify-content-end my-3 mx-3">
            {{ $titles->links() }}
        </div>
      </div>
    </div>
</div>

<!-- trailer Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color: black; color: white;">
        <h5 class="modal-title text-white" id="videoModalLabel">Video</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
      </div>
      <div class="modal-body p-0">
        <video id="videoPlayer" class="w-100 h-100" controls style="object-fit: cover;">
          <source id="videoSource" src="" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    </div>
  </div>
</div>

<!-- Cart Modal -->
<div class="modal fade" id="viewCartModal" tabindex="-1" aria-labelledby="viewCartModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="viewCartModalLabel">Your Cart</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="successMessage" class="alert alert-success" style="display: none;"></div>
            <!-- Table Heading -->
            <div class="d-flex mb-2">
              <div class="col-3"><strong>Image</strong></div>
              <div class="col-6"><strong>Name</strong></div>
              <div class="col-3"><strong>Type</strong></div>
            </div>

            <!-- Cart Items List will be populated here -->
            <div id="cartItemsList"></div>
          </div>
          <div class="modal-footer">
              <button id="requestBtn" class="btn btn-primary" disabled>Checkout</button>
          </div>
      </div>
  </div>
</div>

@endsection
@section('scripts')
    <script src="{{asset('js/content_library.js')}}"></script>
@endsection
