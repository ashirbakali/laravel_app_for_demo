@extends('layouts.app')
@section('content')

    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Edit Items</h4>
            </div>
            <div>
                <a href="{{route('module.'.$moduleName.'.home')}}" class="btn btn-primary btn-icon">
                    <i data-feather="arrow-left"></i>
                </a>
            </div>
        </div>
        <form action="{{route('module.'.$moduleName.'.update')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="put"/>
            <input type="hidden" name="id" value="{{$data['id']}}"/>
            <div class="form-row">
                <div class="form-group col-md-4">

                        <label for="inputEmail4">SKU <span class="tx-danger">*</span></label>
                        <div class="input-group mg-b-10">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="sku-field"><i class="fab fa-product-hunt"></i></span>
                            </div>
                            <input type="text" name="sku"  value="{{ $data['sku'] }}" class="form-control @error('sku') is-invalid @enderror" placeholder="Please enter item sku" required aria-describedby="sku-field">


                        </div>
                        @error('sku')
                        <div class="tx-danger">{{ $message }}</div>
                        @enderror

                </div>
            </div>
            <div class="form-row">

                <div class="form-group col-md-4">
                    <label for="inputEmail4">Categories <span class="tx-danger">*</span></label>
                    <select class="form-control select2  @error('category_id') is-invalid @enderror" name="category_id">
                        @foreach ($categories as $value)
                            <option value="{{$value['id']}}" {{$data['category_id'] == $value['id'] ? 'selected' : ''}}>{{$value['name']}}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-4">
                    <label for="inputEmail4">Item Name <span class="tx-danger">*</span></label>
                    <input type="text" name="name" value="{{$data['name']}}" required class="form-control"
                           id="inputEmail4" placeholder="Please enter item name">
                    @error('name')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group {{!empty($data['image']) ? 'col-md-3':'col-md-4'}}" id="image-field">
                    <label for="inputEmail4">Item Image</label>
                    <div class="custom-file">
                        <input type="file" accept="image/*" name="image" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                @if(!empty($data['image']))
                    <div class="form-group col-md-1 mt-md-3" id="image-box">
                        <a data-fancybox="gallery" href="{{url($data['image'] ?: '')}}">
                            <img class="rounded "src="{{url($data['image'] ?: '')}}" width="50" height="50"></a>&nbsp;&nbsp;
                        @php $deleteFun = "deleteFile(".$data["id"].",'".route('module.items.deleteFile',[$data["id"],'image'])."','".csrf_token()."','".$data['image']."')" @endphp
                        <a href="javascript:" onclick="{{$deleteFun}}"><i class="fas fa-trash center-form"style="color: red;"></i></a>
                    </div>
                @endif

            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Cost</label>
                    <input type="number" name="cost" value="{{ $data['cost'] }}" class="form-control" id="inputEmail4"
                           placeholder="Please enter item cost">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Price</label>
                    <input type="number" name="price" value="{{ $data['price'] }}" class="form-control" id="inputEmail4"
                           placeholder="Please enter item price">
                </div>
            </div>
            <div class="form-group">
                <label for="inputAddress">Description</label>
                <textarea class="form-control" name="description" cols="30"
                          rows="3">{{ $data['description'] }}</textarea>
            </div>


            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-light">Reset</button>
        </form>
    </div>

@endsection
