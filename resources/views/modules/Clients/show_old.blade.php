@extends('layouts.app')
@section('content')

    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Service Providers Detail</h4>
            </div>

        </div>
        @include('layouts.partials.flash_message')

        <div class="card">
            <img src="{{ asset($data['banner_img']??'assets/img/placehold.jpg') }}" class="img-lg" style="height: 270px;" alt="{{ $data['name'] }}">
            <div class="overlay" style="margin-top: -80px; width: 100%">
                <div class="rounded" style="text-align:center;">
                    <img src="{{ asset($data['image']??'assets/img/placehold.jpg') }}" class="img-lg" style="border-radius: 50%; width: 250px; height: 185px" />
                </div>
            </div>
            <div class="card-header">
              Name: {{ $data['name'] }}
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Email: {{ $data['email'] }}</li>
              <li class="list-group-item">Phone: {{ $data['phone'] }}</li>
              <li class="list-group-item">
                <p>BIO:</p>
                {!! $data['bio'] !!}
              </li>
              <li class="list-group-item bg-black-1">
                <div class="flex">
                    <div class="col">
                        <p>License Images:</p>
                        <a href="{{ asset($data['license']) }}">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                    <div class="col">
                        <p>Insurance Coverage:</p>
                        <a href="{{ asset($data['insurance_coverage']) }}">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="flex row justify-content-end">
                    <div class="col text-right">
                        @if ($data['is_admin_approve'])
                        <button onclick="approve_user({{ $data['id'] }}, {{ route('module.clients.show', [$data['id'], 'is_admin_approve']) }}, csrf_token(), this)" class="btn btn-sm btn-danger">Disapprove</button>
                        @else
                        <button onclick="approve_user({{ $data['id'] }}, {{ route('module.clients.show', [$data['id'], 'is_admin_approve']) }}, csrf_token(), this)" class="btn btn-sm btn-primary">Approve</button>
                        @endif
                        <a href="{{ route('module.clients.home') }}" class="btn btn-sm btn-secondary">Back</a>
                    </div>
                </div>
              </li>
            </ul>
        </div>

    </div>

@endsection
