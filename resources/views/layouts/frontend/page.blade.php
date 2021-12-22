@extends('layouts.app')

@section('custom_css')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @yield('custom_style')
@endsection

@section('content')
    @include('layouts.frontend.header')
    <div id="content-wrapper" class="pb-5 mb-5" style="position: relative">
        <div class="container">
            <div class="row" style="padding: 0 12px;">
                <div id="main-card-wrapper" class="col-9 card card-cascade wider reverse f-card"
                    style="padding-bottom: 20px; margin-left: -35px !important">
                    @yield('header-container')
                    @yield('main-container')
                </div>
                <div class="col-3">
                @section('left-side-container')
                    @include('frontend.partials.login', ['type' => 2])
                    @include('frontend.partials.user_info_other', ['type' => 1])
                    @include('frontend.partials.before_login')
                    @include('frontend.modals.password_reset')
                    @include('frontend.modals.member_register')
                @show
            </div>
        </div>
    </div>
</div>
@include('frontend.partials.toast')
@include('layouts.frontend.footer')
@include('frontend.modals.quote_request')
@endsection

@section('custom_js')
<script>
    var loginStatus = false;
    @auth
        loginStatus = true;
    @endauth
</script>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('js/frontend/custom.js') }}"></script>
<script src="{{ asset('js/frontend/client_shortkey.js') }}"></script>
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
@yield('custom_script')
@endsection
