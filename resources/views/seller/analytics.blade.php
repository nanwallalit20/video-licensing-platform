@extends('layouts.app')
@section('title', 'Analytics')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <form id="dateFilterForm" class="row align-items-center">
                    <div class="col-md-2">
                        <label class="form-label">Date Range</label>
                        <select class="form-control" id="dateRangeSelect" name="date_range">
                            <option value="7" {{ request('date_range') == '7' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="30" {{ (request('date_range') == '30' || !request('date_range')) ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="90" {{ request('date_range') == '90' ? 'selected' : '' }}>Last 3 Months</option>
                            <option value="180" {{ request('date_range') == '180' ? 'selected' : '' }}>Last 6 Months</option>
                            <option value="365" {{ request('date_range') == '365' ? 'selected' : '' }}>Last 12 Months</option>
                            <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>
                    <div class="col-md-2 custom-date-inputs" style="display: none;">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="startDate" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2 custom-date-inputs" style="display: none;">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" id="endDate" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Select Title</label>
                        <select class="form-control" name="title_id">
                            <option value="all" {{ $analyticsData['selectedTitleId'] === 'all' ? 'selected' : '' }}>All Titles</option>
                            @foreach($analyticsData['approvedTitles'] as $title)
                                <option value="{{ $title->id }}" {{ $analyticsData['selectedTitleId'] == $title->id ? 'selected' : '' }}>
                                    {{ $title->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Select Platform</label>
                        <select class="form-control" name="platform_id">
                            <option value="all" {{ request('platform_id') === 'all' ? 'selected' : '' }}>All Platforms</option>
                            @foreach($analyticsData['platforms'] as $platform)
                                <option value="{{ $platform->id }}" {{ request('platform_id') == $platform->id ? 'selected' : '' }}>
                                    {{ $platform->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mt-4">
                        <button type="submit" class="btn bg-primary mb-0 text-white">Apply Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Views</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ number_format($analyticsData['totalViews']) }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-primary shadow text-center border-radius-md">
                            <i class="ni ni-chart-bar-32 text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Unique Views</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ number_format($analyticsData['totalUniqueViews']) }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-primary shadow text-center border-radius-md">
                            <i class="ni ni-user-run text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Watch Time (hrs)</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ number_format($analyticsData['totalWatchTime'], 1) }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-primary shadow text-center border-radius-md">
                            <i class="ni ni-time-alarm text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Completion Rate</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ number_format($analyticsData['avgCompletionRate'], 1) }}%
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-primary shadow text-center border-radius-md">
                            <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
     <!-- Monthly Trends Chart -->
    <div class="col-lg-12 mb-3">
        <div class="card z-index-2">
            <div class="card-header pb-0">
                <h6>Views overview</h6>
                <p class="text-sm">
                    <i class="fa fa-arrow-up text-success"></i>
                    <span class="font-weight-bold">Daily trend</span> of views
                </p>
            </div>
            <div class="card-body p-3">
                <div class="chart">
                    <canvas id="monthly-trends-chart" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Country Analytics Chart -->
    <div class="col-lg-12 mb-lg-0 mt-3 mb-4">
        <div class="card z-index-2">
            <div class="card-body p-3">
                <div class="bg-gradient-dark border-radius-lg py-3 pe-1 mb-3">
                    <div class="chart">
                        <canvas id="country-chart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
                <h6 class="ms-2 mt-4 mb-0">Country Distribution</h6>
                <p class="text-sm ms-2">Views and completion rate by country</p>
            </div>
        </div>
    </div>
     <!-- Watch Time Chart -->
     <div class="col-lg-7 mb-lg-0 mt-4">
        <div class="card z-index-2">
            <div class="card-body p-3">
                <div class="bg-gradient-dark border-radius-lg py-3 pe-1 mb-3">
                    <div class="chart">
                        <canvas id="watch-time-chart" class="chart-canvas" height="340"></canvas>
                    </div>
                </div>
                <h6 class="ms-2 mt-4 mb-0">Watch Time Trends</h6>
                <p class="text-sm ms-2">Hours watched per day</p>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    // Watch time data
    var monthLabels = {!! json_encode($analyticsData['dailyViews']->pluck('date')) !!};
    var watchTimeData = {!! json_encode($analyticsData['dailyViews']->pluck('watch_time_hours')) !!};
    var completionRateData = {!! json_encode($analyticsData['dailyViews']->pluck('completion_rate')) !!};

    // Views data
    var totalViews = {!! json_encode($analyticsData['dailyViews']->pluck('total_views')) !!};
    var uniqueViews = {!! json_encode($analyticsData['dailyViews']->pluck('unique_views')) !!};

     // Country chart data
     var countryLabels = {!! json_encode($analyticsData['countryData']->pluck('name')) !!};
    var countryViews = {!! json_encode($analyticsData['countryData']->pluck('total_views')) !!};
    var countryCompletionRates = {!! json_encode($analyticsData['countryData']->pluck('completion_rate')) !!};

    $(document).ready(function() {
    const $dateRangeSelect = $('#dateRangeSelect');
    const $customDateInputs = $('.custom-date-inputs');

    // Show/hide custom date inputs based on selection
    $dateRangeSelect.on('change', function() {
        const showCustomDates = $(this).val() === 'custom';
        $customDateInputs.toggle(showCustomDates);
    });

    // Initialize date inputs if custom is selected
    if ($dateRangeSelect.val() === 'custom') {
        $customDateInputs.show();
    }
});
</script>
<script src="{{ asset('js/videoAnalyticsCharts.js') }}"></script>
@endsection
