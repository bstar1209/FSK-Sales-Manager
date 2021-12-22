<div id="shipment" class="account-tab mt-2 d-none" style="padding-right: 12px !important">
    <div class="row">
        <div class="col-4">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Ship Date')</span>
                </div>
                <input type="text" class="form-control ship_date">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Order Number')</span>
                </div>
                <input type="text" class="form-control ship_order_number">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Invoice number')</span>
                </div>
                <input type="text" class="form-control ship_invoice_number">
            </div>
        </div>
        <div class="offset-2 col-4">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Model number')</span>
                </div>
                <input type="text" class="form-control ship_model_number">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">@lang('Billing number')</span>
                </div>
                <input type="text" class="form-control ship_billing_number">
            </div>
            <select class="form-control ship_order_period">
                <option value="1">過去一ヶ月のデータ</option>
                <option value="3">過去三ヶ月のデータ</option>
            </select>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 table-responsive">
            <table class="table table-bordered table-striped table-sm mb-2" id="shipment-table" cellspacing="0"
                tabindex="0">
                <thead>
                    <tr>
                        <th>出荷日</th>
                        <th>注文番号</th>
                        <th>型番</th>
                        <th>メーカー</th>
                        <th>数量</th>
                        <th>単価</th>
                        <th>送り状番号</th>
                        <th>請求番号</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
