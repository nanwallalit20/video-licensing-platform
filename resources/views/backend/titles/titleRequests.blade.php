@extends('layouts.app')
@section('title','')
@section('content')
@php
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\RevenuePlanStatus;
@endphp
<div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>All Title Requests</h6>
          <!-- Filters -->
          <form action="{{ route('superadmin.titleRequests') }}" method="GET" class="row g-3 mt-3">
            <div class="col-md-2">
                <input type="text" class="form-control" name="name" placeholder="Search by name"
                    value="{{ request('name') }}">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="email" placeholder="Search by email"
                    value="{{ request('email') }}">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="order_status">
                    <option value="">Order Status</option>
                    @foreach($orderStatuses as $status)
                        <option value="{{ $status->value }}"
                            {{ request('order_status') == $status->value ? 'selected' : '' }}>
                            {{ $status->displayName() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="payment_status">
                    <option value="">Payment Status</option>
                    @foreach($paymentStatuses as $status)
                        <option value="{{ $status->value }}"
                            {{ request('payment_status') == $status->value ? 'selected' : '' }}>
                            {{ $status->displayName() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn bg-gradient-admin-blue">Filter</button>
                <a href="{{ route('superadmin.titleRequests') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">

            <table class="table align-items-center mb-0">
                <thead>
                <tr>
                    <th class="text-uppercase text-sm font-weight-bolder opacity-7">Order ID</th>
                    <th class="text-uppercase text-sm font-weight-bolder opacity-7 ps-2">Buyer</th>
                    <th class="text-uppercase text-sm font-weight-bolder opacity-7 ps-2">Price</th>
                    <th class="text-uppercase text-sm font-weight-bolder opacity-7">Order Status</th>
                    <th class="text-uppercase text-sm font-weight-bolder opacity-7">Payment Status</th>
                    <th class="text-uppercase text-sm font-weight-bolder opacity-7">Action</th>
                </tr>
                </thead>
                <tbody>
                    @if($orders->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center">No orders found</td>
                        </tr>
                    @else
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <p class="text-sm ms-3 mb-0">#{{ str_pad(1000 + $order->id, 6, '0', STR_PAD_LEFT) }}</p>
                                </td>
                                <td>
                                    <p class="text-sm mb-0">{{ $order->getBuyer->name }}</p>
                                    <p class="text-xs mb-0">{{ $order->getBuyer->email }}</p>
                                </td>
                                <td>
                                    <p class="text-sm mb-0">{{ $order->total_price . ' ' . $order->currency }}</p>
                                </td>
                                <td>
                                    @if ($order->order_status->value == OrderStatus::Approved->value)
                                    <span class="badge badge-sm ms-3 bg-gradient-success">{{ $order->order_status->displayName()}}</span>
                                    @elseif ($order->order_status->value == OrderStatus::Declined->value)
                                    <span class="badge badge-sm ms-3 bg-gradient-danger">{{ $order->order_status->displayName()}}</span>
                                    @else
                                    <span class="badge badge-sm ms-3 bg-gradient-warning">{{ $order->order_status->displayName()}}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->payment_status->value == PaymentStatus::Paid->value)
                                    <span class="badge badge-sm ms-3 bg-gradient-success">{{ $order->payment_status->displayName()}}</span>
                                    @elseif ($order->payment_status->value == PaymentStatus::Failed->value)
                                    <span class="badge badge-sm ms-3 bg-gradient-danger">{{ $order->payment_status->displayName()}}</span>
                                    @elseif ($order->payment_status->value == PaymentStatus::Pending->value)
                                    <span class="badge badge-sm ms-3 bg-gradient-warning">{{ $order->payment_status->displayName()}}</span>
                                    @elseif ($order->payment_status->value == PaymentStatus::Refunded->value)
                                    <span class="badge badge-sm ms-3 bg-gradient-info">{{ $order->payment_status->displayName()}}</span>
                                    @endif
                                </td>
                                </td>

                                <td class="align-middle text-center">
                                    <div class="border rounded-3 d-flex align-items-center px-2 py-1 me-3">
                                        <a class="me-2" href="{{ route('superadmin.titleRequests.view', ['id' => $order->id]) }}" title="View Order Details">
                                            <i class="fas fa-eye" style="color: #0ca8cb"></i>
                                        </a>
                                        @if($order->order_status->value == OrderStatus::Pending->value)
                                        <a href="{{route('superadmin.titleRequests.update', ['orderId' => $order->id, 'status' => OrderStatus::Approved->value])}}"
                                        class="px-2"
                                        data-confirm-message="Are you sure to approve this order?"
                                        data-method="post"
                                        data-class="ajaxForm"
                                        title="Approve">
                                            <i class="fa-solid fa-check" style="color: green;"></i>
                                        </a>
                                        <hr class="vr my-1 mx-2"/>
                                        <a href="{{route('superadmin.titleRequests.update', ['orderId' => $order->id, 'status' => OrderStatus::Declined->value])}}"
                                        class="px-2"
                                        data-confirm-message="Are you sure to decline this order?"
                                        data-method="post"
                                        data-class="ajaxForm"
                                        title="Decline">
                                            <i class="fa-solid fa-x" style="color: red;"></i>
                                        </a>
                                        @endif
                                        @if($order->payment_status->value != PaymentStatus::Paid->value)
                                        <a href="javascript:void(0);"
                                           class="me-2 payment-link-btn"
                                           data-order-id="{{$order->id}}"
                                           title="Send Payment Link">
                                            <i class="fas fa-link" style="color: #6c757d"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-center custom_admin_pagination">
            {{ $orders->links('pagination.bootstrap-5') }}
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Container -->
<div class="modal fade" id="paymentLinkModal" tabindex="-1" aria-labelledby="paymentLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="paymentLinkModalContent">

        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('.payment-link-btn').click(function() {
            var orderId = $(this).data('order-id');
            $('#loader').show();

            $.get("{{ route('superadmin.titleRequests.getPaymentModal', ['orderId' => ':orderId']) }}".replace(':orderId', orderId))
            .done(function (response) {
                $('#paymentLinkModalContent').html(response);
                $('#paymentLinkModal').modal('show');
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


