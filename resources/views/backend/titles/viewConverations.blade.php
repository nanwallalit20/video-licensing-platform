@extends('layouts.app')
@section('title','')
@section('content')
<div class="row">
    <div class="col-md-7 mt-4">
      <div class="card">
        <div class="card-header pb-0 px-3">
          <h6 class="mb-0">Title Conversation</h6>
        </div>
        <div class="card-body pt-4 p-3">
          <ul class="list-group">
            @foreach ($titleConversations as $conversation)
            <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                <div class="d-flex flex-column">
                  <h6 class="mb-3 text-sm">{{ $conversation->getTitle->getUser->name }} </h6>
                  <span class="mb-2 text-xs">Subject: <span class="text-dark font-weight-bold ms-sm-2">{{ $conversation->subject}}</span></span>
                  <span class="mb-2 text-xs">Message: <span class="text-dark ms-sm-2 font-weight-bold">{{ $conversation->message }}</span></span>
                </div>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>

  </div>
@endsection
