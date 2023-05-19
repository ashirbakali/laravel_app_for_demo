@extends('layouts.app')

@section('content')


    <div class="panel mb-5">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Purchase Order #{{$data['id']}}</h4>
            </div>
            <div>
                <a href="{{route('module.'.$moduleName.'.home')}}" class="btn btn-primary btn-icon">
                    <i data-feather="arrow-left"></i>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <h5>Supplier Info</h5>
                <p style="margin-bottom: 5px"><i class="fas fa-user"></i>&nbsp;&nbsp;{{$data['supplier']['name']}}</p>
                <p style="margin-bottom: 5px"><i class="fas fa-envelope"></i>&nbsp;&nbsp;{{$data['supplier']['email']}}
                </p>
                <p><i class="fas fa-phone"></i>&nbsp;&nbsp;{{$data['supplier']['phone']}}</p>
            </div>
            <div class="col-md-4 col-sm-12 ">
                <h5>Supplier Address</h5>
                <p>{{$data['supplier']['address']}}</p>
            </div>
            <div class="col-md-4 offset-md-1">
                <h5>Order Info</h5>
                <p style="margin-bottom: 5px"><i
                        class="fas fa-calendar"></i>&nbsp;&nbsp;{{date('m/d/Y',strtotime($data['order_date']))}}</p>
                <p style="margin-bottom: 5px">
                    <strong>Status:</strong> {{$data['status'] == 1 ? 'Confirmed' :'Pending' }}</p>
            </div>
        </div>
    </div>
    <div class="card card-body"
         style="padding-bottom: 5px;border-bottom-left-radius: unset;border-bottom-right-radius:unset ">
        <table class="table table-striped">
            <thead>
            <tr>
                <th width="50%">Item</th>
                <th width="10%">Cost</th>
                <th width="10%">Qty</th>
                <th width="10%">Total</th>
            </tr>
            </thead>
            <tbody>

            @foreach($orders as $order)
                <tr>
                    <td>{{$order['item']['name']}}</td>
                    <td>@price($order['unit_cost'])</td>
                    <td>{{$order['quantity']}}</td>
                    <td>@price($order['total'])</td>
                </tr>
            @endforeach
            </tbody>

        </table>

    </div>
    <div class="bottom-panel-td">
        <div class="row">
            <div class="col-md-12 pr-5">
                <div class="float-right"><span class="bottom-panel-text">Total</span>&nbsp;&nbsp;<span
                        class="bottom-panel-text"><strong>@price( $data['grand_total'])</strong></span></div>
            </div>
        </div>
    </div>

    @if($data['status'] == 2)
        <br>
        <div class="row ">
            <div class="col-md-6  col-xs-12 ">
                <form
                    id="statusForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="put"/>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <input type="text" name="description" value="{{ old('description') }}" class="form-control"
                                   id="description" placeholder="Enter description ...">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i>&nbsp;&nbsp;Confirm
                            </button>

                        </div>
                    </div>
                </form>
            </div>

        </div>
    @endif

@endsection
@push('scripts')
    @if($data['status'] == 2)
    <script>
        jQuery("#statusForm").submit(function(e){
            e.preventDefault();
            orderStatus({{$data['id']}},'{{route('module.'.$moduleName.'.status',[$data['id'],'status'])}}','{{ csrf_token() }}',this,{
                description:$('#description').val()
            },function(done) {
                if(done){
                    window.location = window.location;
                }
            });
        });
    </script>
    @endif
@endpush
