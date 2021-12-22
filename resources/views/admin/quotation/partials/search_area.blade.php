<div class="col-4" id="search-area">
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
                    <span class="input-group-text text-truncate">@lang('Supplier')(S)</span>
                </div>
                <input type="text" id="search-supplier" class="form-control">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Model')(Z)</span>
                </div>
                <input type="text" id="search-model" class="form-control">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Estimated')(H)</span>
                </div>
                <input type="text" id="search-estimate" class="form-control">
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
        </div>
        <div class="col-6">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Reception date')(P)</span>
                </div>
                <input type="text" id="search-reception-date" class="form-control">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Reception')(N)</span>
                </div>
                <input type="text" id="search-reception" class="form-control">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Quote')(F)</span>
                </div>
                <input type="text" id="search-quote" class="form-control">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">顧客ID(I)</span>
                </div>
                <input type="text" id="search-customer-id" class="form-control">
            </div>
            <div class="float-left">
                <button id="search-area-clear" class="btn btn-primary btn-sm btn-ellipsis"
                    type="button">@lang('Clear')</button>
            </div>
        </div>
    </div>
</div>
