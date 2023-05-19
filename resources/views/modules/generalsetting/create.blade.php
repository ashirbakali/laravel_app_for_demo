@extends('layouts.app')
@section('content')

    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">General Settings</h4>
            </div>

        </div>
        @include('layouts.partials.flash_message')

        <form action="{{route('general-setting.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="about">About <span class="tx-danger">*</span></label>
                    <textarea rows="5" name="key[about]" class="form-control" id="about">{{ $generalSetting['about']??'' }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="privacy-policy">Privacy Policy <span class="tx-danger">*</span></label>
                    <textarea rows="5" name="key[privacy-policy]" class="form-control" id="privacy-policy">{{ $generalSetting['privacy-policy']??'' }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="terms-condition">Terms & Condition <span class="tx-danger">*</span></label>
                    <textarea rows="5" name="key[terms-condition]" class="form-control" id="terms-condition">{{ $generalSetting['terms-condition']??'' }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

@endsection
