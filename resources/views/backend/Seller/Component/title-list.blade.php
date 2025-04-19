@php
use \App\Enums\TitleStatus;
use \App\Enums\RevenuePlanStatus;
@endphp
<div class="card-body px-0 pt-0 pb-2">
    <div class="table-responsive p-0">
        <table class="table align-items-center mb-0 ">
            <thead>
            <tr>
                <th class="text-uppercase text-sm font-weight-bolder opacity-7">Title ID</th>
                <th class="text-uppercase text-sm font-weight-bolder opacity-7">Title Name</th>
                <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Initial Approval</th>
                <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Final Approval</th>
                <th class="text-center text-uppercase text-sm font-weight-bolder opacity-7">Platforms</th>
                <th class="text-uppercase text-sm font-weight-bolder opacity-7">Urls & Documents</th>
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
                    <td class="align-middle text-center text-sm">
                        <h6>-</h6>
                    </td>
                    <td class="text-sm px-4">
                        <a href="javascript:void(0);"
                            class="px-2 view_media_urls"
                            title="View Url"
                            data-route="{{ route('superadmin.seller.getMediaUrl',['slug'=>$title->slug]) }}">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="javascript:void(0);"
                            class="px-2 view_title_document_modal"
                            title="View Document"
                            data-view-doc-url="{{ route('superadmin.seller.viewDocument',['slug'=>$title->slug]) }}">
                            <i class="fa-solid fa-file-pdf"></i>
                        </a>
                        @if($title->getRevenuePlan && in_array($title->getRevenuePlan->status->value, [RevenuePlanStatus::InReview->value, RevenuePlanStatus::Final->value]))
                        <a href="javascript:void(0);" class="px-2 viewAgreementModal" title="View Agreement"
                        data-document-url="{{ route('title.viewAgreement', $title->slug) }}">
                            <i class="fa-solid fa-file text-info"></i>
                        </a>
                        @endif
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

