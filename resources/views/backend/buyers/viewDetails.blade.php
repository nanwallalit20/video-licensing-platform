@extends('layouts.app')
@section('title','')
@section('content')
<div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">

          <h6><a href="{{ route('superadmin.buyerRequests') }}" class="d-inline">
            <h6><i class="fas fa-arrow-left"></i></h6>
        </a>Buyer Details</h6>
        </div>
        <div class="card-body ">
            <div class="row">
                <!-- Left Column -->
                <div class="col-6">
                    <p><strong>Full Name:</strong> {{ $buyer['full_name'] ?? 'N/A' }}</p>
                    <p><strong>Company Name:</strong> {{ $buyer['company_name'] ?? 'N/A' }}</p>
                    <p><strong>Job Title:</strong> {{ $buyer['job_title'] ?? 'N/A' }}</p>
                    <p><strong>WhatsApp Number:</strong> {{ $buyer['whatsapp_number'] ?? 'N/A' }}</p>
                    <p><strong>Terms and Conditions:</strong> {{ $buyer['terms_and_conditons'] ? 'Accepted' : 'Not Accepted' }}</p>
                    <p><strong>Content Type:</strong>@php
                        foreach ($buyer->getContentTypes as $contentType) {
                            echo $contentType->content_type->description().', ';
                        }
                    @endphp
                    </p>
                    <p><strong>Genre:</strong> {{ implode(', ', $genres ?? []) }}</p>
                    <p><strong>Email:</strong> {{ $buyer['email'] ?? 'N/A' }}</p>
                    <p><strong>Phone Number:</strong> {{ $buyer['phone_number'] ?? 'N/A' }}</p>
                </div>
                <!-- Right Column -->
                <div class="col-6">

                    <p><strong>Content Usage:</strong> {{ $buyer['content_usage']->description() ?? 'N/A' }}</p>
                    <p><strong>Content Duration:</strong> {{ $buyer['content_duration']->description() ?? 'N/A' }}</p>
                    <p><strong>Acquisition Preferences:</strong> {{ $buyer['acquisition_preferences']->description() ?? 'N/A' }}</p>
                    <p><strong>Target Audience:</strong> {{ $buyer['target_audience'] ?? 'N/A' }}</p>
                    <p><strong>Territories:</strong> {{ $buyer['territories'] ?? 'N/A' }}</p>
                    <p><strong>Budget:</strong> {{ $buyer['budget'] ?? 'N/A' }}</p>
                    <p><strong>Additional Details:</strong> {{ $buyer['additional_details'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
