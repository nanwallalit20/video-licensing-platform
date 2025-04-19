@extends('layouts.app')
@section('content')
    @section('title', "Master Dashboard")
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <a href="{{ route('superadmin.sellerlist') }}">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Sellers</p>
                                <div class="d-flex align-items-center">
                                    <h3 class="font-weight-bolder mb-0">
                                        {{ number_format($totalSellers) }}
                                    </h3>
                                </div>
                            </a>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-gradient-admin-blue shadow dashboard-card-icon text-center rounded-circle me-2 d-flex align-items-center justify-content-center">
                                <img src="{{asset('assets/img/SVG/sellersAdmin.svg')}}" alt="Sellers"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <a href="{{ route('titles') }}">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Titles</p>
                                <div class="d-flex align-items-center">
                                    <h3 class="font-weight-bolder mb-0">
                                        {{ number_format($titleStats['total']) }}
                                    </h3>
                                    <div class="ms-3">
                                        <div class="d-flex align-items-center">
                    <span class="text-xs text-error me-1">
                      <i class="fas fa-arrow-up"></i>
                    </span>
                                            <span class="text-sm text-danger font-weight-bolder">
                      +{{ number_format($titleStats['pendingApproval']) }}
                    </span>
                                        </div>
                                        <p class="text-xs text-muted mb-0">Pending Approval</p>
                                    </div>
                                    <div class="ms-3">
                                        <div class="d-flex align-items-center">
                      <span class="text-xs text-error me-1">
                        <i class="fas fa-arrow-up"></i>
                      </span>
                                            <span class="text-sm text-danger font-weight-bolder">
                        +{{ number_format($titleStats['pendingAgreement']) }}
                      </span>
                                        </div>
                                        <p class="text-xs text-muted mb-0">Pending Agreement</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-gradient-admin-blue shadow dashboard-card-icon text-center rounded-circle me-2 d-flex align-items-center justify-content-center">
                                <img src="{{asset('assets/img/SVG/adminTitles.svg')}}" alt="Titles"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Buyers</p>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('superadmin.buyerlist') }}">
                                    <h3 class="font-weight-bolder mb-0">
                                        {{ number_format($buyerStats['total']) }}
                                    </h3>
                                </a>
                                <a href="{{ route('superadmin.buyerRequests') }}">
                                    <div class="ms-3">
                                        <div class="d-flex align-items-center">
                        <span class="text-xs text-error me-1">
                          <i class="fas fa-arrow-up"></i>
                        </span>
                                            <span class="text-sm text-danger font-weight-bolder">
                          +{{ number_format($buyerStats['pending']) }}
                        </span>
                                        </div>
                                        <p class="text-xs text-muted mb-0">Pending Approval</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-gradient-admin-blue shadow dashboard-card-icon text-center rounded-circle me-2 d-flex align-items-center justify-content-center">
                                <img src="{{asset('assets/img/SVG/buyersAdmin.svg')}}" alt="Buyers"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <a href="{{ route('superadmin.titleRequests') }}">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Orders</p>
                                <div class="d-flex align-items-center">
                                    <h3 class="font-weight-bolder mb-0">
                                        {{ number_format($totalOrders) }}
                                    </h3>
                                    <div class="ms-3">
                                        <div class="d-flex align-items-center">
                      <span class="text-xs text-error me-1">
                        <i class="fas fa-arrow-up"></i>
                      </span>
                                            <span class="text-sm text-success font-weight-bolder">
                        +{{ number_format($orderPrice, 2) }} USD
                      </span>
                                        </div>
                                        <p class="text-xs text-muted mb-0">Total Price</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-gradient-admin-blue shadow dashboard-card-icon text-center rounded-circle me-2 d-flex align-items-center justify-content-center">
                                <img src="{{asset('assets/img/SVG/buyerRequests.svg')}}" alt="Orders"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/chartData.js') }}"></script>
@endsection

