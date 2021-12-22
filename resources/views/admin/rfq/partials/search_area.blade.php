<div class="col-2" id="search-area">
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">客先 (C)</span>
        </div>
        <input type="text" id="search-customer" class="form-control" autocomplete="off" aria-label="Small"
            aria-describedby="inputGroup-sizing-sm">
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">受付日 (P)</span>
        </div>
        <input type="text" id="search-reception-date" class="form-control">
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">型番 (Z)</span>
        </div>
        <input type="text" id="search-model-number" class="form-control">
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">顧客ID (I)</span>
        </div>
        <input type="text" id="search-customer-id" class="form-control">
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">受付番号 (N)</span>
        </div>
        <input type="text" id="search-reception-number" class="form-control">
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">ステータス (L)</span>
        </div>
        <select id="search-status" class="form-control">
            <option value="1">@lang('Untreated')</option>
            <option value="2">@lang('Processed')</option>
            <option value="3">@lang('Both')</option>
        </select>
    </div>
    <div class="float-right">
        <button id="search-area-clear" class="btn btn-primary btn-sm btn-ellipsis" type="button">クリア </button>
    </div>
</div>
