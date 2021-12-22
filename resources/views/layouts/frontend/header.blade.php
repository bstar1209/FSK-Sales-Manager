<div id="frontend-page-header"
    class="d-flex justify-content-center align-items-center bg-white topbar mb-4 static-top shadow"
    style="min-height: 52px !important">
    <a href="{{ route('frontend.index') }}"><img src="{{ asset('images/logo_small.png') }}" /></a>
    <div class="input-group search w-450">
        <div class="input-group">
            <input type="text" id="model-number-search" class="form-control" placeholder="型番は3文字以上入力してください.">
            <select id="search-type" class="form-control" style="max-width: 100px !important">
                <option value="0">先頭一致</option>
                <option value="1">部分一致</option>
            </select>
            <div class="input-group-prepend mr-0">
                <button id="search-btn" class="btn btn-primary btn-sm text-white" type="button"
                    style="min-width: 100px !important" disabled>@lang('Model number search')</button>
            </div>
        </div>
    </div>
</div>
