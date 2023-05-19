@extends('layouts.app')
@section('content')

    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Add Categories</h4>
            </div>
            <div>
                <a href="{{route('module.'.$moduleName.'.home')}}" class="btn btn-primary btn-icon">
                    <i data-feather="arrow-left"></i>
                </a>
            </div>
        </div>
        @include('layouts.partials.flash_message')

        <form action="{{route('module.'.$moduleName.'.create')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="inputEmail4">Category Name <span class="tx-danger">*</span></label>
                    <input type="text" name="name"  required class="form-control @error('name') is-invalid @enderror" id="inputEmail4" placeholder="Please enter category name">

                    @error('name')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Category Image</label>
                    <div class="custom-file">
                        <input type="file" accept="image/*" name="image" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    {!! Form::label('parent_id', 'Parent Category', ['class' => 'form-label']) !!}
                    {!! Form::select('parent_id', $categories??[], old('parent_id', null), ['class' => 'select2 form-control', 'placeholder' => 'Select Parent Category']) !!}
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <button type="submit" value="1" name="saveClose" class="btn btn-warning">Save & Close</button>
            <button type="reset" class="btn btn-light">Reset</button>
        </form>
    </div>

@endsection
