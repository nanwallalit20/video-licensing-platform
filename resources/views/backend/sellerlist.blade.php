@extends('layouts.app')
@section('content')
@section('title','')
<div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>All Sellers</h6>

          <!-- Add Filter Form -->
          <form method="GET" action="{{ route('superadmin.sellerlist') }}" class="mt-3">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" name="name" class="form-control" placeholder="Search by name" value="{{ request('name') }}">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" name="email" class="form-control" placeholder="Search by email" value="{{ request('email') }}">
                </div>
              </div>
              <div class="col-md-4">
                <button type="submit" class="btn bg-gradient-admin-blue">Filter</button>
                <a href="{{ route('superadmin.sellerlist') }}" class="btn btn-secondary">Reset</a>
              </div>
            </div>
          </form>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-sm font-weight-bolder opacity-7">Name</th>
                  <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Phone</th>
                  <th class="text-uppercase text-sm font-weight-bolder opacity-7 text-center ">Movie Count</th>
                  <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Action</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                @foreach($sellers as $seller)
                <tr>
                  <td >
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="{{asset('assets/img/dummyuser.webp')}}" class="avatar avatar-sm me-3" alt="poster">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{$seller->user->name}}</h6>
                        <p class="text-xs mb-0">{{ $seller->user->email }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="text-center">
                    <h6 class="mb-0 text-sm">{{$seller->phone}}</h6>
                  </td>
                  <td class="text-center">
                    <h6 class="mb-0 text-sm">{{$seller->getTitleCountAttribute()}}</h6>
                  </td>
                  <td class="align-middle text-center">
                    <a  class="me-2" href="{{ route('superadmin.seller-remove', $seller->user->id) }}" title="Delete">
                        <i class="fas fa-trash-alt" style="color: red"></i>
                    </a>

                    <!-- View Icon -->
                    <a class="me-2" href="{{ route('superadmin.seller-view', $seller->user->id) }}" title="View Profile">
                        <i class="fas fa-eye"></i>
                    </a>

                    <!-- Login Icon -->
                    <a class="me-2" href="{{ route('switchAccount', $seller->user->id) }}" title="Go to Seller Dashboard">
                        <i class="fas fa-sign-in-alt" style="color: #0ca7ca"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $sellers->links('pagination::bootstrap-5') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
