
<div class="row p-4">
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
                        <div class="icon icon-shape bg-gradient-admin-blue shadow text-center border-radius-md">
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
                        <div class="icon icon-shape bg-gradient-admin-blue shadow text-center border-radius-md">
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
                        <div class="icon icon-shape bg-gradient-admin-blue shadow text-center border-radius-md">
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
                        <div class="icon icon-shape bg-gradient-admin-blue shadow text-center border-radius-md">
                            <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row p-4">
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
</script>
<script src="{{ asset('js/profileAnalytics.js') }}"></script>