<div class="col-5" id="search-area">
    <div class="row">
        <div class="col-6">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Customer')(C)</span>
                </div>
                <input type="text" id="search-customer" class="form-control" autocomplete="off">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Supplier name')(S)</span>
                </div>
                <input type="text" id="search-supplier-name" class="form-control">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Model number')(Z)</span>
                </div>
                <input type="text" id="search-model-number" class="form-control">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Manufacturer')(T)</span>
                </div>
                <input type="text" id="search-maker" class="form-control">
            </div>
        </div>
        <div class="col-6">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Customer order number')(E)</span>
                </div>
                <input type="text" id="search-order-number" class="form-control">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Ship order number')(B)</span>
                </div>
                <input type="text" id="search-ship-order-number" class="form-control">
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
</div>
