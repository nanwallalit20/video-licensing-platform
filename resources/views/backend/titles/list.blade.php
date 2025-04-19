@extends('layouts.app')
@section('title','')
@section('styles')
    <style>
        .disabled {
            pointer-events: none; /* Disables click events */
            opacity: 0.5; /* Reduces opacity to indicate it's disabled */
            cursor: not-allowed; /* Changes the cursor to indicate it's disabled */
        }

    </style>
@endsection
@section('content')
    @php
        use App\Enums\TitleStatus;
        use App\Enums\RevenuePlanStatus;
        use App\Enums\TitleType;
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>All Titles</h6>
                    <form method="GET" action="{{ route('titles') }}" class="mb-4">
                        <div class="row g-2 mb-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="title_name" class="form-control" placeholder="Title Name" value="{{ request('title_name') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="title_uuid" class="form-control" placeholder="Search by Title ID" value="{{ request('title_uuid') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="seller_email" class="form-control" placeholder="Seller Email" value="{{ request('seller_email') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="seller_name" class="form-control" placeholder="Seller Name" value="{{ request('seller_name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row g-2">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="approval_status" class="form-control">
                                        <option value="">Approval Status</option>
                                        <option value="{{ TitleStatus::Accepted->value }}" {{ request('approval_status') == TitleStatus::Accepted->value ? 'selected' : '' }}>{{TitleStatus::Accepted->displayName()}} </option>
                                        <option value="{{ TitleStatus::Rejected->value }}" {{ request('approval_status') == TitleStatus::Rejected->value ? 'selected' : '' }}>{{TitleStatus::Rejected->displayName()}}</option>
                                        <option value="{{ TitleStatus::Pending->value }}" {{ request('approval_status') == TitleStatus::Pending->value ? 'selected' : '' }}>{{TitleStatus::Pending->displayName()}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="title_type" class="form-control">
                                        <option value="">Select Title Type</option>
                                        <option value="{{ TitleType::Movie->value }}" {{ request('title_type') == TitleType::Movie->value ? 'selected' : '' }}>
                                            {{ TitleType::Movie->displayName() }}
                                        </option>
                                        <option value="{{ TitleType::Series->value }}" {{ request('title_type') == TitleType::Series->value ? 'selected' : '' }}>
                                            {{ TitleType::Series->displayName() }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex justify-content-start gap-2">
                                <button type="submit" class="btn btn-sm bg-gradient-admin-blue">Filter</button>
                                <a href="{{ route('titles') }}" class="btn btn-sm btn-secondary d-flex align-items-center">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 ">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-sm font-weight-bolder opacity-7">Title ID</th>
                                <th class="text-uppercase text-sm font-weight-bolder opacity-7">Name</th>
                                <th class="text-uppercase text-sm font-weight-bolder opacity-7 ps-2">Seller</th>
                                <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Initial Approval</th>
                                <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Final Approval</th>
                                <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($titles) == 0)
                                <tr>
                                    <td colspan="6" class="text-center">No data found</td>
                                </tr>
                            @endif
                            @foreach($titles as $title)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center ms-3">
                                            <p class="text-sm mb-0">#{{ substr($title->uuid, 0, 8) }}...</p>
                                            <button class="btn btn-link mb-0 p-0 ms-2 copy-btn"
                                                    data-copy-text="{{$title->uuid}}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom"
                                                    title="Copy">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{asset('assets/img/dummyTitle.jpg')}}"
                                                     class="avatar avatar-sm me-3" alt="poster">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <a href="javascript:void(0)" class="mb-0 text-sm view_title_profile"
                                                    title="View Title Profile"
                                                    data-title-profile-route="{{ route('title.profile', ['slug' => $title->uuid]) }}">
                                                    {{$title->name ?? 'New Title' }}</a>
                                                <p class="text-xs mb-0">{{ $title->type->displayName()}}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ $title->getUser->name }}</p>
                                        <p class="text-xs mb-0">{{ $title->getUser->email }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if ($title->status->value == TitleStatus::Accepted->value)
                                            <span
                                                class="badge badge-sm bg-gradient-success">{{ $title->status->displayName()}}</span>
                                        @elseif ($title->status->value == TitleStatus::Rejected->value)
                                            <span
                                                class="badge badge-sm bg-gradient-danger">{{ $title->status->displayName()}}</span>
                                        @else
                                            <span
                                                class="badge badge-sm bg-gradient-warning">{{ $title->status->displayName()}}</span>
                                        @endif
                                    </td>
                                    @if($title->getRevenuePlan)
                                    <td class="align-middle text-center text-sm">
                                        @if ($title->getRevenuePlan->status->value == RevenuePlanStatus::Final->value)
                                            <span
                                                class="badge badge-sm bg-gradient-success">{{ $title->getRevenuePlan->status->displayName()}}</span>
                                        @elseif ($title->getRevenuePlan->status->value == RevenuePlanStatus::InReview->value)
                                            <span
                                                class="badge badge-sm bg-gradient-danger">{{ $title->getRevenuePlan->status->displayName()}}</span>
                                        @else
                                            <span
                                                class="badge badge-sm bg-gradient-warning">{{ $title->getRevenuePlan->status->displayName()}}</span>
                                        @endif
                                    </td>
                                    @else
                                    <td class="align-middle text-center">
                                        <span
                                            class="badge badge-sm bg-gradient-warning">{{ RevenuePlanStatus::Pending->displayName()}}</span>
                                    </td>
                                    @endif
                                    <td class="align-middle text-center">
                                        <div class="font-weight-bold d-flex justify-content-center align-items-center">
                                            @if($title->status->value == TitleStatus::Pending->value)
                                                <div class="border rounded-3 d-flex align-items-center px-2 py-1 me-3">
                                                    <i class="fa-solid fa-info px-2" title="Status"
                                                       style="color: green;"></i>
                                                    <span class="mx-2">:</span>
                                                    <a href="javascript:void(0);"
                                                       class="px-2 approve-btn" data-title-id="{{ $title->id }}"
                                                       data-title-status="{{ TitleStatus::Accepted->value }}"
                                                       title="Approve"
                                                       data-route="{{ route('superadmin.sellers.title.updateStatus') }}">
                                                        <i class="fa-solid fa-check" style="color: green;"></i>
                                                    </a>
                                                    <hr class="vr my-1 mx-2"/>
                                                    <a href="javascript:void(0);"
                                                       class="px-2 decline-btn" data-title-id="{{ $title->id }}"
                                                       data-title-status="{{ TitleStatus::Rejected->value }}"
                                                       title="Decline"
                                                       data-route="{{ route('superadmin.sellers.title.updateStatus') }}">
                                                        <i class="fa-solid fa-x" style="color: red;"></i>
                                                    </a>
                                                </div>
                                            @endif
                                            @if($title->status->value == TitleStatus::Rejected->value)
                                                <a href=""
                                                   class="me-2 info-btn" data-decline-reason="{{$title->reason}}">
                                                    <i class="fa-solid fa-info" style="color: #71717a;"></i>
                                                </a>
                                            @endif
                                            {{-- <a href="" class="me-2 more-info-btn"
                                               data-route="{{ route('superadmin.sellers.title.addMessage',['slug'=>$title->slug]) }}"
                                               data-seller-name="{{ $title->getUser->name }}"
                                               title="more-info">
                                                <i class="fa-solid fa-envelope" style="color: #0caecb;"></i>
                                            </a> --}}
                                            @if($title->getRevenuePlan && in_array($title->getRevenuePlan->status->value, [RevenuePlanStatus::InReview->value, RevenuePlanStatus::Final->value]))
                                                <div
                                                    class="border rounded-3 d-flex align-items-center px-2 py-1 ms-3 revenue_plan_actions">
                                                    <a href="" class="px-2 viewAgreementModal" title="Show Agreement"
                                                       data-document-url="{{ route('title.viewAgreement', $title->slug) }}">
                                                        <i class="fa-solid fa-file text-info"></i>
                                                    </a>
                                                    @if($title->getRevenuePlan->status->value == RevenuePlanStatus::InReview->value)
                                                        <span class="mx-2">:</span>
                                                        <a href="{{ route('title.agreementStatus', [$title->slug, RevenuePlanStatus::Final->value]) }}"
                                                           data-method="post" data-class="ajaxForm"
                                                           class="px-2" title="Approve Agreement">
                                                            <i class="fa-solid fa-check text-success"></i>
                                                        </a>
                                                        <hr class="vr my-1 mx-2"/>
                                                        <a href="{{ route('title.agreementStatus', [$title->slug, RevenuePlanStatus::Pending->value]) }}"
                                                           data-method="post" data-class="ajaxForm"
                                                           class="px-2" title="Decline Agreement">
                                                            <i class="fa-solid fa-x text-danger"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $titles->links('pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.titles. models.request_more_info')
    <div class="modal fade" id="declineReason" tabindex="-1" role="dialog" aria-labelledby="declineReasonTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Decline Due To</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Reason:</label>
                        <textarea class="form-control" id="decline-reason-text"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="decline_reason" class="btn bg-gradient-admin-blue">Decline</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="decline-reason-model" tabindex="-1" role="dialog" aria-labelledby="modal-default"
         aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modal-title-default">Decline Reason:</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>

                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @include('titles.revenue.revenueDocViewModal')
@endsection
@section('scripts')
    <script>
        function agreementStatusUpdate(param) {
            let url = param.form[0].action
            let selector = $(document).find("a[href='" + url + "']")
            selector.closest('.revenue_plan_actions').remove()
        }

        $(document).ready(function () {

            $('.info-btn').on('click', function (e) {

                e.preventDefault();

                // Get the decline reason from the data attribute
                let reason = $(this).data('decline-reason');

                // Find the modal and update the paragraph content
                $('#decline-reason-model .modal-body p').text(reason);

                // Show the modal
                $('#decline-reason-model').modal('show');

            });

            // Handle click on "Approve" anchor
            $('.approve-btn').on('click', function (e) {
                e.preventDefault(); // Prevent default anchor behavior
                let titleId = $(this).data('title-id');
                let titleStatus = $(this).data('title-status');
                let url = $(this).data('route');
                let data = {
                    _token: '{{ csrf_token() }}',
                    id: titleId,
                    status: titleStatus
                };
                if (!confirm('Are you sure you want to approve this title?')) {
                    return;
                }
                $('#loader').show();
                sendAjaxRequest(data, url);
            });

            // Handle click on "Decline" anchor
            $('.decline-btn').on('click', function (e) {
                e.preventDefault(); // Prevent default anchor behavior
                let titleId = $(this).data('title-id');
                let titleStatus = $(this).data('title-status');
                let url = $(this).data('route');
                if (!confirm('Are you sure you want to decline this title?')) {
                    return;
                }

                $('#declineReason').modal('show');

                $('#decline_reason').off('click').on('click', function () {
                    let message = $('#decline-reason-text').val().trim();
                    if (!message) {
                        alert('You must provide a reason for declining.');
                        return;
                    }
                    $('#declineReason').modal('hide');
                    $('#loader').show();
                    let data = {
                        _token: '{{ csrf_token() }}',
                        id: titleId,
                        status: titleStatus,
                        message: message
                    };

                    sendAjaxRequest(data, url);
                });
            });

            // Handle click on "Request More Info" anchor
            $('.more-info-btn').on('click', function (e) {
                e.preventDefault(); // Prevent default anchor behavior
                let titleId = $(this).data('title-id');
                let url = $(this).data('route');
                let sellerName = $(this).data('seller-name');
                $('#request-model-title').text('New message to ' + sellerName);
                $('#request-more-info').modal('show');

                $('#send_message_seller').off('click').on('click', function () {
                    let message = $('#more-info-message').val().trim();
                    let Subject = $('#message_subject').val().trim();
                    if (!message) {
                        alert('You must provide additional information for the seller.');
                        return;
                    }
                    $('#request-more-info').modal('hide');
                    $('#loader').show();
                    let data = {
                        _token: '{{ csrf_token() }}',
                        id: titleId,
                        message: message,
                        subject: Subject
                    };

                    sendAjaxRequest(data, url);
                });
            });

            // Function to handle the AJAX request
            function sendAjaxRequest(data, url) {
                $('#loader').show(); // Ensure the loader is displayed while processing
                $.post(url, data)
                    .done(function (response) {
                        $('#loader').hide();
                        if (response.success) {
                            location.reload();
                        } else {
                            console.log('Failed to update status. Please try again.');
                        }
                    })
                    .fail(function (xhr, status, error) {
                        $('#loader').hide();
                        console.error('Error:', error);
                        console.log('An error occurred while updating the status. Please try again.');
                    });
            }

        });

    </script>
@endsection
