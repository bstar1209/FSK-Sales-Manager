<div id="before-login" class="card card-cascade wider reverse f-card d-none">
    <div class="card-body">
        <div class="form-group">
            <p class="mt-3 mb-0">見積もりカゴ</p>
            @include('frontend.partials.parts')
        </div>

        <div class="form-group row">
            <div class="col-12 d-flex justify-content-between">
                <a class="btn btn-primary btn-sm go-login-btn">@lang('Login')</a>
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#member-register-modal">@lang('Member registration')</a>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12 d-flex justify-content-end">
                <button id="quote-request-set-btn" class="btn btn-success btn-sm text-white">見積もり依頼</button>
            </div>
        </div>
    </div>
</div>
