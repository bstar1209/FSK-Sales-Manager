<div class="col-8" id="search-area">
    <div class="row">
        <div class="col-4">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Customer')(C)</span>
                </div>
                <input type="text" id="search-customer" class="form-control" autocomplete="off">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Invoice number')(B)</span>
                </div>
                <input type="text" id="search-invoice-number" class="form-control">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Ship date')(S)</span>
                </div>
                <input type="text" id="search-ship-date" class="form-control">
            </div>
        </div>
        <div class="col-4">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">ID(I)</span>
                </div>
                <input type="text" id="search-id" class="form-control" autocomplete="off">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Maker')(T)</span>
                </div>
                <input type="text" id="search-maker" class="form-control">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Billing number')(K)</span>
                </div>
                <input type="text" id="search-billing-number" class="form-control">
            </div>
        </div>
        <div class="col-4">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Model')(Z)</span>
                </div>
                <input type="text" id="search-model" class="form-control">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Status')(L)</span>
                </div>
                <select id="search-status" class="form-control">
                    <option value="1">@lang('Untreated')</option>
                    <option value="2">@lang('Processed')</option>
                    <option value="3">@lang('Both')</option>
                </select>
            </div>
            <div class="float-left">
                <button id="search-area-clear" class="btn btn-primary btn-sm btn-ellipsis"
                    type="button">@lang('Clear')</button>
            </div>
        </div>
    </div>
    <div class="row justify-content-center align-items-center" style="color: black; margin-top: 10px">
        <div class="col-7 text-right">
            メッセージ (J)
        </div>
        <div class="col-5">
            <textarea class="form-control w-100 message-box"></textarea>
        </div>
    </div>
</div>
