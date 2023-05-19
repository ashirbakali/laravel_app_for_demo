@extends('layouts.app')

@section('content')


    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Purchase Order Report</h4>
            </div>

        </div>
        <form action="{{route('module.'.$controllerName.'.home.search')}}" method="post">
            @csrf
            <div class="form-row">

                <div class="form-group col-md-2">
                    <label for="inputEmail4">From Date</label>

                    <input type="text" name="from_date"
                           class="form-control datepicker @error('from_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{\App\Helpers\Helper::reqValue('from_date')}}"
                           autocomplete="off">
                    @error('from_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-2">
                    <label for="inputEmail4">To Date</label>
                    <input type="text" name="to_date"
                           class="form-control datepicker @error('to_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{\App\Helpers\Helper::reqValue('to_date')}}"
                           autocomplete="off">
                    @error('to_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputEmail4">Status</label>
                    <select class="form-control  @error('status') is-invalid @enderror" name="status">
                        <option label="Select Status"></option>
                        @foreach ($status as $key => $value)
                            <option
                                value="{{$key}}" {{\App\Helpers\Helper::reqValue('status') == $key ? 'selected' : ''}}>{{$value}}</option>
                        @endforeach
                    </select>
                    @error('status')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Suppliers</label>
                    <select class="form-control select2  @error('supplier_id') is-invalid @enderror" name="supplier_id">
                        <option label="Select Supplier"></option>
                        @foreach ($suppliers as $value)
                            <option value="{{$value['id']}}" {{\App\Helpers\Helper::reqValue('supplier_id') == $value['id'] ? 'selected' : ''}}>{{$value['name']}}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="col-md-1">
                    <button style="margin-top: 28px;" type="submit" class="btn btn-primary btn-icon">
                        <i data-feather="search"></i> Search
                    </button>
                </div>

            </div>
        </form>
    </div>
    <br>
    @if(!empty(\request()->toArray()))
        <div class="row row-xs">
            <div class="col-lg-4 col-md-6 mg-t-10">
                <div class="card">
                    <div class="card-body pd-y-20 pd-x-25">
                        <div class="row row-sm">
                            <div class="col-7">
                                <h3 class="tx-normal tx-rubik tx-spacing--1 mg-b-5">{{$grand_total}}</h3>
                                <h6 class="tx-12 tx-semibold tx-uppercase tx-spacing-1 tx-primary mg-b-5">Total Amount</h6>
                                <p class="tx-11 tx-color-03 mg-b-0">No. of clicks to ad that consist of a single impression.</p>
                            </div>
                            <div class="col-5">
                                <div class="chart-ten">
                                    <div id="sumTotal" class="flot-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div><!-- card-body -->
                </div><!-- card -->
            </div><!-- col -->
            <div class="col-lg-4 col-md-6 mg-t-10">
                <div class="card">
                    <div class="card-body pd-y-20 pd-x-25">
                        <div class="row row-sm">
                            <div class="col-7">
                                <h3 class="tx-normal tx-rubik tx-spacing--1 mg-b-5">{{$count}}</h3>
                                <h6 class="tx-12 tx-semibold tx-uppercase tx-spacing-1 tx-pink mg-b-5">Total Items</h6>
                                <p class="tx-11 tx-color-03 mg-b-0">No. of clicks to ad that consist of a single impression.</p>
                            </div>
                            <div class="col-5">
                                <div class="chart-ten">
                                    <div id="countTotal" class="flot-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div><!-- card-body -->
                </div><!-- card -->
            </div><!-- col -->
            <div class="col-lg-4 col-md-6 mg-t-10">
                <div class="card">
                    <div class="card-body pd-y-20 pd-x-25">
                        <h6 class="tx-12 tx-semibold tx-uppercase tx-spacing-1 tx-primary mg-b-5">Total Orders by Status</h6>
                        <p class="tx-11 tx-color-03 mg-b-0">No. of clicks to ad that consist of a single impression.</p>
                        <div class="row row-sm mt-3">

                            <div class="col-sm-5 col-md-12 col-lg-6 col-xl-5 ">
                                <div class="media">
                                    <div class="wd-40 ht-40 rounded bd bd-2 bd-success d-flex flex-shrink-0 align-items-center justify-content-center op-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2 wd-20 ht-20 tx-success stroke-wd-3"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                                    </div>
                                    <div class="media-body mg-l-10">
                                        <h4 class="tx-normal tx-rubik tx-spacing--2 lh-1 mg-b-5">{{$cItem}}</h4>
                                        <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 tx-nowrap mg-b-0">Confirm</p>
                                    </div><!-- media-body -->
                                </div><!-- media -->
                            </div><!-- col -->
                            <div class="col-sm-5 col-md-12 col-lg-6 col-xl-5 mg-t-20 mg-sm-t-0 mg-md-t-20 mg-lg-t-0">
                                <div class="media">
                                    <div class="wd-40 ht-40 rounded bd bd-2 bd-warning d-flex flex-shrink-0 align-items-center justify-content-center op-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2 wd-20 ht-20 tx-warning stroke-wd-3"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                                    </div>
                                    <div class="media-body mg-l-10">
                                        <h4 class="tx-normal tx-rubik tx-spacing--2 lh-1 mg-b-5">{{$pItem}}</h4>
                                        <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 tx-nowrap mg-b-0">Pending</p>
                                    </div><!-- media-body -->
                                </div><!-- media -->
                            </div><!-- col -->
                        </div>
                    </div><!-- card-body -->
                </div><!-- card -->
            </div><!-- col -->


        </div>
        <br>
    @endif
    <div class="card card-body">

        <div style="display: flex" class="mb-3">

        </div>
        <table data-table="mainGrid" data-url="{{route('module.'.$controllerName.'.datatable',request()->toArray())}}"
               data-cols='{!! base64_encode((!empty($dataTableColumns) ? $dataTableColumns : '')) !!}'
               data-exportable="true"
               class="table table-hover">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="5%">Date</th>
                <th width="30%">Supplier Name</th>
                <th width="10%">Grand Total</th>
                <th width="5%">Items</th>
                <th width="5%">Status</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

@endsection
@push('scripts')
    @if(!empty(\request()->toArray()))

        <script>
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

            function getRndInteger(min, max) {
                return Math.floor(Math.random() * (max - min) ) + min;
            }

            let dataFraction = 50;
            let chartEnd = dataFraction/2;

            var sumTotal = $.plot('#sumTotal', [{
                data: {!!$sumTotalGraph!!}.map((data, index) => [index, getRndInteger(data.grand_total/dataFraction,data.grand_total/chartEnd)]),
                color: '#e5e9f2'
            },{
                data: {!!$sumTotalGraph!!}.map((data, index) => [index, data.grand_total/dataFraction]),
                color: '#66a4fb'
            }], flotChartOption1);


            dataFraction = 5;
            chartEnd = dataFraction/2;

            var countTotal = $.plot('#countTotal', [{
                data: {!!$sumCountGraph!!}.map((data, index) => [index, getRndInteger(data.count/dataFraction,data.count/chartEnd)]),
                color: '#e5e9f2'
            },{
                data: {!!$sumCountGraph!!}.map((data, index) => [index, data.count/dataFraction]),
                color: '#f10075'
            }], flotChartOption1);




        </script>
    @endif
@endpush
