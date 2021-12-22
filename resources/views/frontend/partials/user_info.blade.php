<div id="user-info-card" class="card card-cascade wider reverse f-card @if ($type=='0' ) card_pos @endif @guest d-none @endguest">
    <div class="card-body">
        <h5 class="card-title">@lang('Member information')</h5>
        <div class="row mb-2">
            <div class="col-5">@lang('Membership number'):</div>
            <div class="col-7 memebership_number">
                @auth
                    {{ auth()->user()->customer->id }}
                @endauth
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5">@lang('Company name'):</div>
            <div class="col-7 company_name">
                @auth
                    {{ auth()->user()->customer->user_info->company_name }}
                @endauth
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5">@lang('Name')</div>
            <div class="col-7 given_name">
                @auth
                    {{ auth()->user()->customer->representative }}
                @endauth
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12 d-flex justify-content-between">
                <a href="{{ route('frontend.account.index') }}" class="btn btn-primary btn-sm">マイアカウント</a>
                <a class="btn btn-sm btn-secondary" href="{{ route('logout') }}">ログアウト</a>
            </div>
        </div>
        <div class="form-group">
            <p class="mt-3 mb-0">見積もりカゴ</p>
            @include('frontend.partials.parts')
        </div>
    </div>
</div>
