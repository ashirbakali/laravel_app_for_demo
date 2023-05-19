@extends('layouts.app')

@section('content')

    <form action="{{route('module.'.$moduleName.'.create')}}" method="post" enctype="multipart/form-data" id="purchaseOrderForm">

        <div class="panel mb-5">
            <div style="display: flex" class="mb-3">
                <div style="flex: 1">
                    <h4 id="section1" class="mg-b-10">Add Purchase Order</h4>
                </div>
                <div>
                    <a href="{{route('module.'.$moduleName.'.home')}}" class="btn btn-primary btn-icon">
                        <i data-feather="arrow-left"></i>
                    </a>
                </div>
            </div>
            @include('layouts.partials.flash_message')

            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Suppliers <span class="tx-danger">*</span></label>
                    <select class="form-control select2  @error('supplier_id') is-invalid @enderror" name="supplier_id">
                        <option label="Select Supplier"></option>
                        @foreach ($suppliers as $value)
                            <option value="{{$value['id']}}" {{old('supplier_id') == $value['id'] ? 'selected' : ''}}>{{$value['name']}}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="form-group col-md-4">
                    <label for="inputEmail4">Order Date <span class="tx-danger">*</span></label>
                    <input type="text" name="order_date"
                           class="form-control datepicker @error('order_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{date('m/d/Y')}}">
                    @error('order_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Status <span class="tx-danger">*</span></label>
                    <select class="form-control  @error('status') is-invalid @enderror" name="status">
                        <option label="Select Status"></option>
                        @foreach ($status as $key => $value)
                            <option value="{{$key}}" {{'2' == $key ? 'selected' : ''}}>{{$value}}</option>
                        @endforeach
                    </select>
                    @error('status')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror

                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputAddress">Description</label>
                    <input type="text" name="description" value="{{ old('description') }}" class="form-control" id="description" placeholder="Enter description ...">
                </div>
            </div>


        </div>

        <po-items items="{{ json_encode($items) }}" ></po-items>
        <br>
        <div class="row ">
            <div class="col-md-3  col-xs-12 ">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="submit" value="1" name="saveClose" class="btn btn-warning">Save & Close</button>
            </div>
            <div class="col-md-5 col-xs-12 offset-md-4">
                <span class="alert alert-warning float-right"><strong>Note </strong>: If you select confirm status item quantity will deduct automatically  </span>
            </div>
        </div>


    </form>
@endsection
