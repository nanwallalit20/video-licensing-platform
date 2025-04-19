@extends('layouts.app')
@section('title','')
@section('content')
@php
use App\Enums\TitleType;
use App\Helpers\FileUploadHelper;
use App\Enums\MediaTypes;
@endphp
<div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Order Details</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">

            <table class="table align-items-center mb-0">
                <thead>
                <tr>
                    <th class="text-uppercase text-sm font-weight-bolder opacity-7">Title</th>
                    <th class="text-uppercase text-sm font-weight-bolder opacity-7">Seller</th>
                    <th class="text-uppercase text-sm font-weight-bolder opacity-7">Licence</th>
                </tr>
                </thead>
                <tbody>
                    @if($order_details->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center">No orders details found</td>
                        </tr>
                    @else
                        @foreach($order_details as $item)

                            <tr>
                                <td>
                                    @if($item->getTitle->type->value == TitleType::Movie->value)
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            @php
                                            $imageUrl = asset('assets/img/dummyTitle.jpg');
                                            $imageFile = $item->getTitle->getMediaThroughMovie()->firstWhere('file_type', MediaTypes::Image->value);
                                            if($imageFile){
                                                $urlObject = FileUploadHelper::filePathUrl($imageFile->file_name, $imageFile->file_path);
                                                $imageUrl = $urlObject->url;
                                            }
                                            @endphp
                                            <img src="{{$imageUrl}}" alt="Poster Image" class="avatar avatar-xxl me-3">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{$item->getTitle->name ?? 'New Title' }}</h6>
                                            <p class="text-xs mb-0">{{ $item->getTitle->type->displayName()}}</p>
                                        </div>
                                    </div>
                                    @elseif($item->getTitle->type->value == TitleType::Series->value)
                                        <div class="d-flex px-2 py-1">

                                            <div>
                                                @php
                                                $imageUrl = asset('assets/img/dummyTitle.jpg');
                                                $imageFile = $item->getSeason->getMedia()->firstWhere('file_type', MediaTypes::Image->value);
                                                if($imageFile){
                                                    $urlObject = FileUploadHelper::filePathUrl($imageFile->file_name, $imageFile->file_path);
                                                    $imageUrl = $urlObject->url;
                                                }
                                                @endphp
                                                <img src="{{$imageUrl}}" alt="Season Image" class="avatar avatar-xxl me-3">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{$item->getTitle->name . "(" . $item->getSeason->name . ")" }}</h6>
                                                <p class="text-xs mb-0">{{ $item->getTitle->type->displayName()}}</p>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <p class="text-sm mb-0">{{ $item->getOrder->getBuyer->name }}</p>
                                    <p class="text-xs mb-0">{{ $item->getOrder->getBuyer->email }}</p>
                                </td>
                                <td>
                                    <p class="text-sm mb-0">{{ $item->getTitle->getLicenceCountry->pluck('name')->implode(', ') ?? ""}}</p>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
