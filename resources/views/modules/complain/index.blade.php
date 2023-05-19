@extends('layouts.app')
@section('content')
    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Complains</h4>
            </div>
        </div>
        <table data-table="mainGrid" data-cols='' class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>User Email</th>
                    <th>User phone</th>
                    <th>User type</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($complains as $complain)
                    <tr>
                        <td>{{ $complain->id }}</td>
                        <td>{{ $complain->user->name }}</td>
                        <td>{{ $complain->user->email }}</td>
                        <td>{{ $complain->user->phone }}</td>
                        <td>{{ $complain->user->type=='CLIENT'?'Service Provider':$complain->user->type }}</td>
                        <td>{{ $complain->message }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn @if ($complain->complain_status == "PENDING") btn-primary @elseif($complain->complain_status == "RESOLVED") btn-success @else btn-danger @endif dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="openOtions(this)">
                                  {{$complain->complain_status}}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a class="dropdown-item" href="{{url('/complain/status?id='.$complain->id.'&complain_status=PENDING')}}">PENDING</a>
                                  <a class="dropdown-item" href="{{url('/complain/status?id='.$complain->id.'&complain_status=RESOLVED')}}">RESOLVED</a>
                                  <a class="dropdown-item" href="{{url('/complain/status?id='.$complain->id.'&complain_status=REJECT')}}">REJECT</a>
                                </div>
                              </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <th colspan="5" class="text-danger text-center">Record Not Found</th>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
<script>
    function openOtions(data)
    {
        $(data).parent().find('.dropdown-menu').toggleClass('show');
    }
</script>
