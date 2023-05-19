<div class="content-header">
    <div class="content-search">

    </div>
    <nav class="nav">
        <div style="margin-right: 10px">
        @isSubscribed
        <div class="badge-warning " style="padding: 10px 15px 10px 15px;">
            <strong>
            Expire in {{\Illuminate\Support\Facades\Auth::user()->subscriptionLeft+1}} Days
            </strong>
        </div>
        @endisSubscribed
        @isNotSubscribed
        <div class="badge-danger " style="padding: 10px 15px 10px 15px;">
            <strong>
                Subscription Expired
            </strong>
        </div>
        @endisNotSubscribed
        </div>
<!--        <a href="" class="nav-link"><i data-feather="help-circle"></i></a>
        <a href="" class="nav-link"><i data-feather="grid"></i></a>-->
        <a style="margin-top: 10px;" href="{{url('logout')}}" class="nav-link"><i data-feather="log-out"></i></a>
    </nav>
</div><!-- content-header -->
