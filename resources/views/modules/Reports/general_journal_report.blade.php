@extends('layouts.app')

@section('content')


    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Profit & Loss Report</h4>
            </div>

        </div>
        @include('layouts.partials.flash_message')

        <form action="{{route('module.'.$controllerName.'.search')}}" method="post">
            @csrf
            <div class="form-row">

                <div class="form-group col-md-3">
                    <label for="inputEmail4">From Date</label>

                    <input type="text" name="from_date"
                           class="form-control datepicker @error('from_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{old('from_date') ? old('from_date') : \App\Helpers\Helper::reqValue('from_date')}}"
                           autocomplete="off">
                    @error('from_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputEmail4">To Date</label>
                    <input type="text" name="to_date"
                           class="form-control datepicker @error('to_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{old('to_date') ? old('to_date') : \App\Helpers\Helper::reqValue('to_date')}}"
                           autocomplete="off">
                    @error('to_date')
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
    @if(!empty($months))
        <div class="card card-body">
            <div class="row">
                <div class="mb-3 col-12">
                    <div class="table-responsive">
                        <table class="table " id="example1">
                            <thead class="thead-primary">
                            <tr>
                                <th scope="col" width="100px"></th>
                                @foreach($months as $month)
                                    <th scope="col" width="10%"><strong>{{date('M Y',strtotime($month))}}</strong></th>
                                @endforeach
                                <th scope="col"><strong>Total</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="col" style="font-weight: 800"><strong>Income</strong></th>
                                @foreach($months as $month)
                                    <th scope="col" width="10%"></th>
                                @endforeach
                                <th scope="col"></th>
                            </tr>
                            <tr>
                                <td>Sales</td>
                                @foreach($data_all as $value)
                                    <td>{{!empty($value['sale']) ? $value['sale']: '-'}}</td>
                                @endforeach
                                <td >{{$total_all['saleTotal']}}</td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                @foreach($data_all as $value)
                                    <td class="tx-danger">{{!empty($value['discount']) ? $value['discount'] : '-'}}</td>
                                @endforeach
                                <td class="tx-danger">- {{$total_all['discountTotal']}}</td>
                            </tr>
                            <tr>
                                <td>Cost of Goods</td>
                                @foreach($data_all as $value)
                                    <td class="tx-danger">{{!empty($value['cost']) ? $value['cost'] : '-'}}</td>
                                @endforeach
                                <td class="tx-danger">{{$total_all['costTotal']}}</td>
                            </tr>
                            <tr>
                                <th scope="col" style="font-weight: 800"><strong>Gross Profit</strong></th>
                                @foreach($data_all as $value)
                                    <td class="{{$value['gross_profit'] < 0 ? 'tx-danger' :'tx-success'}}">{{!empty($value['gross_profit']) ? $value['gross_profit'] :'-'}}</td>
                                @endforeach
                                <td class="{{$total_all['grossProfitTotal'] < 0 ? 'tx-danger' :'tx-success'}}">{{$total_all['grossProfitTotal']}}</td>
                            </tr>
                            <tr>
                                <th>Less Expense</th>
                                @foreach($months as $month)
                                    <th scope="col" width="10%"></th>
                                @endforeach
                                <th scope="col"></th>
                            </tr>
                            <tr>
                                <td>Expenses</td>
                                @foreach($data_all as $value)
                                    <td>{{!empty($value['expense']) ? $value['expense'] : '-'}}</td>
                                @endforeach
                                <td>{{$total_all['expenseTotal']}}</td>
                            </tr>
                            <tr>
                                <th><strong>Expense Total</strong></th>
                                @foreach($data_all as $value)
                                    <td >{{!empty($value['expense']) ? $value['expense'] : '-'}}</td>
                                @endforeach
                                <td>{{$total_all['expenseTotal']}}</td>
                            </tr>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th scope="col" style="font-weight: 800"><strong>Net Profit</strong></th>
                                @foreach($data_all as $value)
                                    <td class="{{$value['net_profit'] < 0 ? 'tx-danger' :'tx-success'}}">{{$value['net_profit']}}</td>
                                @endforeach
                                <td>@price($total_all['netProfitTotal'])</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>


        </div>
    @endif
@endsection
@push('style')
    <style>
        table.table tr > *:nth-child(1) {
            background: #f1f1f1;
            padding: 10px 35px;
        }

        table {

            white-space: nowrap;
        }
        @if(!empty($months))
        @if(count($months) == 1)
        table.table tr > *:nth-child(2) {
            display: none;
        }
        table.table tr > *:nth-child(3) {
            width: 100%;
            text-align: right;
        }
        @endif
        @endif
    </style>
@endpush
@push('scripts')
    @if(!empty(\request()->toArray()))
        <script>
            $('#example1').DataTable({
                "paging":   false,
                "ordering": false,
                "info":     false,
                dom: 'lBrtip',
                buttons: [
                    { extend: 'excel', title: document.title},
                    { extend: 'pdf' ,title: 'Data export'},
                    { extend: 'print' ,title: 'Data export'},

                ],

            });
            setTimeout(() => {
                $('.ui button').attr('class', 'btn btn-primary mb-3')
            }, 100)
        </script>
    @endif
@endpush
