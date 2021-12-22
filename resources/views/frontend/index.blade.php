@inject('alert', 'App\Models\Alert')
@php
$today = date_create()->format('Y-m-d');
$notification = $alert
    ->whereDate('start_date', '<', $today)
    ->whereDate('end_date', '>', $today)
    ->orderBy('created_at')
    ->first();
@endphp

@extends('layouts.app')

@section('title', '会社概要画面')

@section('content')

    <div id="content-wrapper" class="container-fluid frontend-content" style="position: relative">
        <div class="d-flex justify-content-center logo" style="padding: 105px 0 40px;">
            <img src="{{ asset('images/logo.png') }}">
        </div>
        <div class="d-flex flex-column align-items-center">

            <div class="input-group search w-450">
                <div class="input-group mb-3">
                    <input type="text" id="model-number-search" class="form-control" placeholder="型番は3文字以上入力してください.">
                    <select id="search-type" class="form-control" style="max-width: 100px !important">
                        <option value="0">先頭一致</option>
                        <option value="1">部分一致</option>
                    </select>
                    <div class="input-group-prepend">
                        <button id="search-btn" class="btn btn-primary btn-sm" type="button"
                            style="min-width: 100px !important" disabled>@lang('Model number search')</button>
                    </div>
                </div>
            </div>

            @if ($notification)
                <div class="alert alert-primary notification w-450 mt-4">
                    <span class="text-danger">{!! $notification->title !!}</span>
                    <span>({!! $notification->end_date !!})</span>
                    {!! $notification->message !!}
                </div>
            @endif

            <div class="w-450 mt-4">
                （株）フォレスカイはお客様に代わって電子部品を調達するサービスを行っております. </br>
                お探しの部品がございましたら弊社ウェブサイトで検索してお見積り依頼ください. </br>
                弊社は法人向けサービスとなっております. </br>
                個人の方は申し訳ございませんがご利用になれません. </br>
            </div>

            <div class="mt-4">
                <a href="{{ route('frontend.overseas_manufacturer') }}" class="btn btn-primary">海外メーカー品調達依頼</a>
                <a href="{{ route('frontend.parts_mass_production') }}" class="btn btn-primary">量産向け部品調達依頼</a>
            </div>
        </div>
        @include('frontend.partials.login', ['type' => 0])
        @include('frontend.partials.user_info', ['type' => 0])
        @include('frontend.partials.toast')
        @include('frontend.modals.password_reset')
        @include('frontend.modals.member_register')
        @include('layouts.frontend.footer')
    </div>

@endsection

@section('custom_js')
    <script>
        var userProfile = null;
    </script>
    <script src="{{ asset('js/frontend/custom.js') }}"></script>
    <script src="{{ asset('js/frontend/client_shortkey.js') }}"></script>
    @yield('custom_script')
@endsection
