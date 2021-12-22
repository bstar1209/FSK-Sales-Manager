<div id="order" class="account-tab mt-2 d-none" style="padding-right: 12px !important">
    <div class="row">
        <div class="col-4">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">注文日</span>
                </div>
                <input type="text" class="form-control order_date">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">注文番号</span>
                </div>
                <input type="text" class="form-control order_number">
            </div>
        </div>
        <div class="offset-2 col-4">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">型番</span>
                </div>
                <input type="text" class="form-control model_number">
            </div>
            <select class="form-control order_period">
                <option value="1">過去一ヶ月のデータ</option>
                <option value="3">過去三ヶ月のデータ</option>
            </select>
        </div>
    </div>
    <div class="order-spin spin" data-spin></div>
    <div class="row mt-3">
        <div class="col-12 table-responsive" id="account-order-area">
        </div>
    </div>
</div>
