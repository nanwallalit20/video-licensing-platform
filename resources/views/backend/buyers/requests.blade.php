@extends('layouts.app')
@section('title','')
@section('content')
@php
use App\Enums\BuyerStatus;
@endphp
<div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>All Buyers Requests</h6>

          <!-- Add Filter Form -->
          <form method="GET" action="{{ route('superadmin.buyerRequests') }}" class="mt-3">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" name="name" class="form-control" placeholder="Search by name" value="{{ request('name') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" name="email" class="form-control" placeholder="Search by email" value="{{ request('email') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select name="status" class="form-control">
                    <option value="">Select Status</option>
                    @foreach(BuyerStatus::cases() as $status)
                      <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                        {{ $status->displayName() }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn bg-gradient-admin-blue">Filter</button>
                <a href="{{ route('superadmin.buyerRequests') }}" class="btn btn-secondary">Reset</a>
              </div>
            </div>
          </form>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0 table-striped custom_table_admin">
              <thead>
                <tr>
                  <th class="text-uppercase text-sm font-weight-bolder opacity-7">Name</th>
                  <th class="text-uppercase text-sm font-weight-bolder opacity-7">Email</th>
                  <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Whatsapp Number</th>
                  <th class="text-uppercase text-sm font-weight-bolder opacity-7 text-center ">Budget</th>
                  <th class="text-uppercase text-sm font-weight-bolder opacity-7 text-center ">Content Duration</th>
                  <th class="text-uppercase text-sm font-weight-bolder opacity-7 text-center ">Status</th>
                  <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Action</th>
                </tr>
              </thead>
              <tbody>
                @if(count($buyers) == 0)
                    <tr>
                        <td colspan="7" class="text-center">No data found</td>
                    </tr>
                @endif
                @foreach($buyers as $buyer)
                <tr>
                  <td class="ps-4">
                    <h6 class="mb-0 text-sm">{{$buyer->full_name}}</h6>
                  </td>
                  <td class="text-center">
                    <h6 class="mb-0 text-sm">{{$buyer->email}}</h6>
                  </td>
                  <td class="text-center">
                    <h6 class="mb-0 text-sm">{{$buyer->whatsapp_number}}</h6>
                  </td>
                  <td class="text-center">
                    <h6 class="mb-0 text-sm">{{$buyer->budget}}</h6>
                  </td>
                  <td class="text-center">
                    <h6 class="mb-0 text-sm">{{$buyer->content_duration->description()}}</h6>
                  </td>
                  <td class="text-center">
                    @if ($buyer->status->value == BuyerStatus::Accepted->value)
                        <span
                            class="badge badge-sm bg-gradient-success">{{ $buyer->status->displayName()}}</span>
                    @elseif ($buyer->status->value == BuyerStatus::Rejected->value)
                        <span
                            class="badge badge-sm bg-gradient-danger">{{ $buyer->status->displayName()}}</span>
                    @else
                        <span
                            class="badge badge-sm bg-gradient-warning">{{ $buyer->status->displayName()}}</span>
                    @endif
                  </td>
                  <td class="align-middle text-center">
                    <a  class="me-2" href="{{ route('superadmin.buyerRequests.view',['id'=> $buyer->id]) }}" title="View">
                        <i class="fas fa-eye" style="color: #0ca8cb"></i>
                    </a>
                    @if ($buyer->status->value == BuyerStatus::Pending->value)
                    <a href="{{ route('buyer.request.status', ['buyer_id' => $buyer->id, 'status' => BuyerStatus::Accepted->value]) }}"
                        data-confirm-message="Are you sure you want to approve the buyer's request and send the subscription link?"
                        data-method="post"
                        data-class="ajaxForm">
                        <i class="fa-solid fa-check" style="color: green;"></i>
                     </a>
                     <hr class="vr my-1 mx-2"/>
                     <a href="{{ route('buyer.request.status', ['buyer_id' => $buyer->id, 'status' => BuyerStatus::Rejected->value]) }}"
                        data-confirm-message="Are you sure you want to decline the buyer's request."
                        data-method="post"
                        data-class="ajaxForm">
                        <i class="fa-solid fa-x" style="color: red;"></i>
                     </a>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4 custom_admin_pagination">
                {{ $buyers->links('pagination::bootstrap-5') }}
              </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
