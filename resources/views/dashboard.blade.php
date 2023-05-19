@extends('layouts.app')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <h4 class="mg-b-0 tx-spacing--1">Welcome to Dashboard</h4>
        </div>
    </div>
    <div class="row row-xs">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Total Vendors</h6>
                <div class="d-flex d-lg-block d-xl-flex align-items-end">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{ $thirdBox['clientsCount'] }}</h3>
                </div>
            </div>
        </div><!-- col -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Total Users</h6>
                <div class="d-flex d-lg-block d-xl-flex align-items-end">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{ $thirdBox['userssCount'] }}</h3>
                </div>
            </div>
        </div><!-- col -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Total Vendor Services</h6>
                <div class="d-flex d-lg-block d-xl-flex align-items-end">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{ $thirdBox['vendorServiceCount'] }}</h3>
                </div>
            </div>
        </div><!-- col -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Total Courses|Sessions</h6>
                <div class="d-flex d-lg-block d-xl-flex align-items-end">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{ $thirdBox['serviceCount'] }}</h3>
                </div>
            </div>
        </div><!-- col -->
    </div>

    <div class="row row-xs">
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-body">
                        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Today Purchase</h6>
                        <div class="d-flex d-lg-block d-xl-flex align-items-end">
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">@price($thirdBox['purchaseSumToday'])</h3>
                            <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium {{$thirdBox['todayComp'] ? 'tx-success' : 'tx-danger' }}"> <i class="icon ion-md-arrow-{{$thirdBox['todayComp'] ? 'up' : 'down' }}"></i></span> @price($thirdBox['purchaseSumTodayLast']) Yesterday</p>
                        </div>

                    </div>
                </div><!-- col -->
                <div class="col-sm-6 col-lg-3 mg-t-10 mg-sm-t-0">
                    <div class="card card-body">
                        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Week Purchases</h6>
                        <div class="d-flex d-lg-block d-xl-flex align-items-end">
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">@price($thirdBox['purchaseSumWeek'])</h3>
                            <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium {{$thirdBox['weekComp'] ? 'tx-success' : 'tx-danger' }}"> <i class="icon ion-md-arrow-{{$thirdBox['weekComp'] ? 'up' : 'down' }}"></i></span> @price($thirdBox['purchaseSumWeekLast']) last week</p>
                        </div>

                    </div>
                </div><!-- col -->
                <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
                    <div class="card card-body">
                        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">{{date('F')}} purchases</h6>
                        <div class="d-flex d-lg-block d-xl-flex align-items-end">
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">@price($thirdBox['purchaseSumMonth'])</h3>
                            <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium {{$thirdBox['monthComp'] ? 'tx-success' : 'tx-danger' }}"> <i class="icon ion-md-arrow-{{$thirdBox['monthComp'] ? 'up' : 'down' }}"></i></span> @price($thirdBox['purchaseSumMonthLast']) last Month</p>
                        </div>

                    </div>
                </div><!-- col -->
                <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
                    <div class="card card-body">
                        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">{{date('Y')}} purchases</h6>
                        <div class="d-flex d-lg-block d-xl-flex align-items-end">
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">@price($thirdBox['purchaseSumYear'])</h3>
                            <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium {{$thirdBox['yearComp'] ? 'tx-success' : 'tx-danger' }}"> <i class="icon ion-md-arrow-{{$thirdBox['yearComp'] ? 'up' : 'down' }}"></i></span> @price($thirdBox['purchaseSumYearLast']) last Year</p>
                        </div>

                    </div>
                </div><!-- col -->
                <div class="col-lg-8 col-xl-7 mg-t-10">
                    <div class="card">
                        <div class="card-header pd-y-20 d-md-flex align-items-center justify-content-between">
                            <h6 class="mg-b-0">Courses &amp; Appointments Growth of {{date('Y')}}</h6>
                            <ul class="list-inline d-flex mg-t-20 mg-sm-t-10 mg-md-t-0 mg-b-0">

                                <li class="list-inline-item d-flex align-items-center mg-l-5">
                                    <span class="d-block wd-10 ht-10 bg-df-2 rounded mg-r-5"></span>
                                    <span class="tx-sans tx-uppercase tx-10 tx-medium tx-color-03">Appointments</span>
                                </li>
                                <li class="list-inline-item d-flex align-items-center mg-l-5">
                                    <span style="background: #001737" class="d-block wd-10 ht-10 bg-df-3 rounded mg-r-5"></span>
                                    <span class="tx-sans tx-uppercase tx-10 tx-medium tx-color-03">Courses</span>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body pd-20">
                            <div class="ht-250 ht-lg-300"><canvas id="chartBar1"></canvas></div>
                        </div>
                    </div><!-- card -->
                </div>
                <div class="col-lg-4 col-xl-5 mg-t-10">
                    <div class="card">
                        <div class="card-header pd-y-20 d-md-flex align-items-center justify-content-between">
                            <h6 class="mg-b-0">Purchase Comparison of {{date('F Y')}}</h6>

                        </div>
                        <div class="card-body pd-10">
                            <div class="card-body pd-lg-25">
                                <div class="chart-seven"><canvas id="chartDonut"></canvas></div>
                            </div><!-- card-body -->
                        </div><!-- card-body -->
                        <div class="card-footer pd-20">
                            <div class="row">
                                <div class="col-4">
                                    <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 mg-b-5">Purchase</p>
                                    <div class="d-flex align-items-center">
                                        <div class="wd-10 ht-10 rounded-circle bg-pink mg-r-5"></div>
                                        <h5 class="tx-normal tx-rubik mg-b-0">@price($secondBox['purchase_month_pie'])</h5>
                                    </div>
                                </div><!-- col -->
                            </div><!-- row -->
                        </div><!-- card-footer -->
                    </div><!-- card -->
                </div>
                <div class="col-lg-4 col-md-6 mg-t-10">
                    <div class="card">
                        <div class="card-body pd-y-20 pd-x-25">
                            <div class="row row-sm">
                                <div class="col-7">
                                    <h3 class="tx-normal tx-rubik tx-spacing--1 mg-b-5">@price($topBox['purchaseSum'])</h3>
                                    <h6 class="tx-12 tx-semibold tx-uppercase tx-spacing-1 tx-pink mg-b-5">Total Purchase</h6>
                                    <p class="tx-11 tx-color-03 mg-b-0">Total purchases you generate by systems</p>
                                </div>
                                <div class="col-5">
                                    <div class="chart-ten">
                                        <div id="purchaseSum" class="flot-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- card-body -->
                    </div><!-- card -->
                </div><!-- col -->

    </div><!-- row -->
@endsection

@push('scripts')
<script>
    var ctxLabel = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    let data = JSON.parse(@json($secondBox['month_data']));
    var course_data = data.courses;
    let appointment_data = data.appointments;
    var ctxData1 = $.map(course_data, function(value, ind){
        return [parseInt(value)];
    });
    // console.log(ctxData1)
    var ctxData2 = $.map(appointment_data, function(value, ind){
        return [parseInt(value)];
    });
    // console.log(ctxData2)
    var ctxColor1 = '#001737';
    var ctxColor2 = '#1ce1ac';
    var mini_val = Math.min(...ctxData1);
    var mini_val2 = Math.min(...ctxData2);
    var max_val = Math.max(...ctxData1);
    var max_val2 = Math.max(...ctxData2);
</script>
    <script>

        function getRndInteger(min, max) {
            return Math.floor(Math.random() * (max - min) ) + min;
        }
        var flotChartOption1 = {
            series: {
                shadowSize: 0,
                bars: {
                    show: true,
                    lineWidth: 0,
                    barWidth: .5,
                    fill: 1
                }
            },
            grid: {
                aboveData: true,
                color: '#e5e9f2',
                borderWidth: 0,
                labelMargin: 0
            },
            yaxis: {
                show: false,
                min: 0,
                max: 25
            },
            xaxis: {
                show: false
            }
        };
        let dataFraction = 100;
        let chartEnd = dataFraction/2;

         dataFraction = 10;
         chartEnd = dataFraction/2;

        var countTotal = $.plot('#purchaseSum', [{
            data: {!!$topBox['purchaseSumGraph']!!}.map((data, index) => [index, getRndInteger(data.amount/dataFraction,data.amount/chartEnd)]),
            color: '#e5e9f2'
        },{
            data: {!!$topBox['purchaseSumGraph']!!}.map((data, index) => [index, data.amount/dataFraction]),
            color: '#f10075'
        }], flotChartOption1);

         dataFraction = 20;
         chartEnd = dataFraction/2;

        var ctxColor1 = '#001737';
        var ctxColor2 = '#66a4fb';





        // Bar chart
        var ctx1 = document.getElementById('chartBar1').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ctxLabel,
                datasets: [{
                    data: ctxData1,
                    backgroundColor: ctxColor1
                }, {
                    data: ctxData2,
                    backgroundColor: ctxColor2
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false,
                    labels: {
                        display: false
                    }
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#e5e9f2'
                        },
                        ticks: {
                            beginAtZero:false,
                            fontSize: 10,
                            fontColor: '#182b49',
                            max: max_val>max_val2?max_val:max_val2,
                            min: mini_val<mini_val2?mini_val:mini_val2
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        barPercentage: 0.6,
                        ticks: {
                            beginAtZero:true,
                            fontSize: 11,
                            fontColor: '#182b49'
                        }
                    }]
                }
            }
        });

        // pie chart
        var datapie = {
            labels: ['Purchase','Sales','Expense'],
            datasets: [{
                data: [{{$secondBox['purchase_month_pie']}},{}, {}],
                backgroundColor: ['#f77eb9', '#7ebcff','#7ee5e5']
            }]
        };
        var optionpie = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false,
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        };
        var ctx2 = document.getElementById('chartDonut');
        var myDonutChart = new Chart(ctx2, {
            type: 'doughnut',
            data: datapie,
            options: optionpie
        });

    </script>
@endpush
