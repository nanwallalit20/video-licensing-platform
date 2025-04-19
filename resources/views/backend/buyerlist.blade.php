@extends('layouts.app')
@section('title','')
@section('content')
@php
use App\Enums\SubscriptionStatus;
@endphp
<div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>All Buyers</h6>

          <!-- Add Filter Form -->
          <form method="GET" action="{{ route('superadmin.buyerlist') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Search by name" value="{{ request('name') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="email" class="form-control" placeholder="Search by email" value="{{ request('email') }}">
            </div>
            <div class="col-md-3">
                <select name="subscription" class="form-control">
                    <option value="">All Subscription Status</option>
                    <option value="{{SubscriptionStatus::SUBSCRIBED->value}}" {{ request('subscription') == SubscriptionStatus::SUBSCRIBED->value ? 'selected' : '' }}>{{ SubscriptionStatus::SUBSCRIBED->displayName() }}</option>
                    <option value="{{SubscriptionStatus::NOT_SUBSCRIBED->value}}" {{ request('subscription') == SubscriptionStatus::NOT_SUBSCRIBED->value ? 'selected' : '' }}>{{ SubscriptionStatus::NOT_SUBSCRIBED->displayName() }}</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn bg-gradient-admin-blue">Filter</button>
                <a href="{{ route('superadmin.buyerlist') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0 table-striped custom_table_admin">
                <thead>
                    <tr>
                        <th class="text-uppercase text-sm font-weight-bolder opacity-7">Name</th>
                        <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Email
                        </th>
                        <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Phone
                        </th>
                        <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Subscribed
                        </th>
                        <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($buyers) == 0)
                        <tr>
                            <td colspan="5" class="text-center">No data found</td>
                        </tr>
                    @endif
                    @foreach($buyers as $buyer)
                    <tr>
                      <td class="ps-4">
                        <h6 class="mb-0 text-sm">{{$buyer->user->name}}</h6>
                      </td>
                      <td class="text-center">
                        <h6 class="mb-0 text-sm">{{$buyer->user->email}}</h6>
                      </td>
                      <td class="text-center">
                        <h6 class="mb-0 text-sm">{{$buyer->country_code.$buyer->phone}}</h6>
                      </td>
                      <td class="text-center">
                        @if ($buyer->is_subscribed->value == SubscriptionStatus::SUBSCRIBED->value)
                        <span
                            class="badge badge-sm bg-gradient-success">{{ $buyer->is_subscribed->displayName()}}</span>
                        @else
                            <span
                                class="badge badge-sm bg-gradient-warning">{{ $buyer->is_subscribed->displayName()}}</span>
                        @endif
                      </td>
                      <td class="align-middle text-center">
                        @if ($buyer->is_subscribed == SubscriptionStatus::NOT_SUBSCRIBED)
                        <a href="{{ route('buyer.subscription.link', ['userId' => $buyer->user->id]) }}"
                            data-confirm-message="Are you sure to send the subscription link to the buyer?"
                            data-method="post"
                            data-class="ajaxForm">
                            <i class="fa-solid fa-envelope" style="color: #0caecb;"></i>
                         </a>
                        @endif
                      </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $buyers->links('pagination::bootstrap-5') }}
              </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
