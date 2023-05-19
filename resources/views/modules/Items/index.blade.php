@extends('layouts.app')

@section('content')



            <div class="card card-body">
                <div style="display: flex" class="mb-3">
                    <div style="flex: 1">
                        <h4 id="section1" class="mg-b-10">Items</h4>
                    </div>
                    <div>
                        <a href="{{route('module.'.$moduleName.'.add')}}" class="btn btn-primary btn-icon">
                            <i data-feather="plus"></i>
                        </a>
                    </div>
                </div>
                <table data-table="mainGrid" data-url="{{route('module.'.$moduleName.'.datatable')}}" data-cols='{!! base64_encode($dataTableColumns) !!}' class="table table-hover">
                    <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="20%">Category</th>
                        <th width="20%">Name</th>
                        <th width="5%">Cost</th>
                        <th width="5%">Price</th>
                        <th width="5%">Status</th>
                        <th width="5%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>


@endsection

