@php
    use App\Enums\RevenuePlanType;
    use App\Enums\TitleType;
    use App\Enums\RevenuePlanStatus;
    use App\Enums\TitleStatus;
    use App\Enums\TitleCompletion;
@endphp
@extends('layouts.app')
@section('title','')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <h6>Search Titles</h6>
                <form method="GET" action="{{ route('titles') }}" class="mb-0">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="name" class="form-control" placeholder="Search Title Name" value="{{ request('name') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="type" class="form-control">
                                <option value="">Type</option>
                                <option value="{{ TitleType::Movie->value }}" {{ request('type') == TitleType::Movie->value ? 'selected' : '' }}>{{TitleType::Movie->displayName()}}</option>
                                <option value="{{ TitleType::Series->value }}" {{ request('type') == TitleType::Series->value ? 'selected' : '' }}>{{TitleType::Series->displayName()}}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">Admin Approval</option>
                                <option value="{{ TitleStatus::Accepted->value }}" {{ request('status') == TitleStatus::Accepted->value ? 'selected' : '' }}>{{TitleStatus::Accepted->displayName()}}</option>
                                <option value="{{ TitleStatus::Rejected->value }}" {{ request('status') == TitleStatus::Rejected->value ? 'selected' : '' }}>{{ TitleStatus::Rejected->displayName()}}</option>
                                <option value="{{TitleStatus::Pending->value}}" {{ request('status') == TitleStatus::Pending->value ? 'selected' : '' }}>{{TitleStatus::Pending->displayName()}}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="isSubmitted" class="form-control">
                                <option value="">Submission Status</option>
                                <option value="{{TitleCompletion::Completed->value}}" {{ request('isSubmitted') == TitleCompletion::Completed->value ? 'selected' : '' }}>{{TitleCompletion::Completed->displayName()}}</option>
                                <option value="{{TitleCompletion::Pending->value}}" {{ request('isSubmitted') == TitleCompletion::Pending->value ? 'selected' : '' }}>{{TitleCompletion::Pending->displayName()}}</option>
                            </select>
                        </div>
                        <div class="col-md-4 ">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('titles') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between mb-3">
                <h6>All Titles</h6>
                <div class="dropdown add-title-dropdown">
                    <button
                        class="btn btn-sm btn-primary mb-0 dropdown-toggle"
                        type="button"
                        id="dropdownMenuButton2"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Add Title
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('titleCreate', ['type' => TitleType::Movie->value]) }}">Add
                                Movie</a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('titleCreate', ['type' => TitleType::Series->value]) }}">Add
                                Show</a>
                        </li>
                    </ul>
                </div>

            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 table-striped custom_table_seller">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-sm font-weight-bolder opacity-7">Name</th>
                            <th class="text-uppercase text-sm font-weight-bolder opacity-7 ps-2">Type</th>
                            <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Approval
                                Status
                            </th>
                            <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Submission Status
                            </th>
                            <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($titles) == 0)
                            <tr>
                                <td colspan="5" class="text-center">No data found</td>
                            </tr>
                        @endif
                        @foreach($titles as $title)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{$title->name ?? 'New Title'}}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-xs mb-0">{{ $title->type->displayName() }}</h6>
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
                                <td class="align-middle text-center text-sm">
                                    @if ($title->isSubmitted->value == TitleCompletion::Completed->value)
                                    <span
                                        class="badge badge-sm bg-gradient-success">{{ $title->isSubmitted->displayName()}}</span>
                                    @else
                                    <span
                                        class="badge badge-sm bg-gradient-warning">{{ $title->isSubmitted->displayName()}}</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <div
                                        class="d-flex align-items-center justify-content-center">
                                        @if((!$title->getRevenuePlan || $title->getRevenuePlan->status->value != RevenuePlanStatus::Final->value) && $title->status != TitleStatus::Accepted)
                                            <a href="{{route('titleEdit',['uuid'=>$title->uuid])}}"><i
                                                    class="fas fa-edit"></i></a>
                                        @endif
                                        @if($title->status->value == TitleStatus::Accepted->value)
                                            @if(!$title->getRevenuePlan || (int) $title->getRevenuePlan->status->value == RevenuePlanStatus::Pending->value)
                                                <a href="{{ route('title.revenuePlan',[$title->slug]) }}"
                                                    class="btn my-0 ms-3 badge badge-sm btn-primary">Check Plan</a>
                                            @endif
                                        @endif
                                        @if($title->getRevenuePlan && in_array($title->getRevenuePlan->status->value, [RevenuePlanStatus::InReview->value, RevenuePlanStatus::Final->value]))
                                            <div
                                                class="border rounded-3 d-flex align-items-center px-2 py-1 ms-3 revenue_plan_actions">
                                                <a href="" class="px-2 viewAgreementModal" title="Show Agreement"
                                                    data-document-url="{{ route('title.viewAgreement', $title->slug) }}">
                                                    <i class="fa-solid fa-file text-info"></i>
                                                </a>
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
@include('titles.revenue.revenueDocViewModal')
@endsection
