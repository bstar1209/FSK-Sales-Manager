<div id="login-card" class="card card-cascade wider reverse f-card @if ($type=='0') card_pos @endif @if ($type == 1) d-none @endif @auth d-none
@endauth">
<div class="card-body">
    <div class="form-group row">
        <label for="username" class="col-3 col-form-label text-primary font-weight-bold text-right px-0 ">ユーザー名</label>
        <div class="col-9">
            <input type="text" class="form-control username">
        </div>
    </div>
    <div class="form-group row">
        <label for="password"
            class="col-3 col-form-label text-primary font-weight-bold  text-right px-0 ">パスワード</label>
        <div class="col-9">
            <input type="password" class="form-control password">
        </div>
    </div>
    <div class="text-center mb-3">
        <a class="reset-password-btn" data-toggle="modal" data-target="#reset-modal"
            style="cursor: pointer">パスワードを忘れた場合</a>
    </div>
    <div class="form-group row">
        <div class="col-12 d-flex justify-content-between">
            <button id="login-customer-btn" class="btn btn-primary btn-sm">ログイン</button>
            <button class="btn btn-primary btn-sm" data-toggle="modal"
                data-target="#member-register-modal">会員登録</button>
        </div>
    </div>

    <div class="form-group">
        <p class="mt-3 mb-0">見積もりカゴ</p>
        @include('frontend.partials.parts')
    </div>
</div>
</div>
