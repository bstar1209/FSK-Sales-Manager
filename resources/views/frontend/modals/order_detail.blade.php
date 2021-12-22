<div class="modal fade" id="order-detail-modal" tabindex="-1" role="dialog" aria-labelledby="OrderDetailModel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 800px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h6 id="OrderDetailModel" class="modal-title text-warning">

                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><span>注文番号:&nbsp;</span><span class="order-number"></span></p>
                <div class="table-responsive row mr-0 ml-0">
                    <div class="col-12 p-0">
                        <table class="table table-bordered table-striped table-sm mb-2" id="order-detail-table"
                            cellspacing="0" tabindex="0">
                            <thead>
                                <tr>
                                    <th>型番</th>
                                    <th>注文数</th>
                                    <th>単価</th>
                                    <th>小計</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-7">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang('Payment terms')</span>
                            </div>
                            <input type="text" class="form-control payment-cond" autocomplete="off" disabled>
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang('Delivery Address')</span>
                            </div>
                            <input type="text" class="form-control request-address" autocomplete="off" disabled>
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang('Billing Address')</span>
                            </div>
                            <input type="text" class="form-control send-address" autocomplete="off" disabled>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang('Total amount excluding tax')</span>
                            </div>
                            <input type="text" class="form-control total-excluding-tax" autocomplete="off" disabled>
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang('Shipping')</span>
                            </div>
                            <input type="text" class="form-control fee-shipping" autocomplete="off" disabled>
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang('Consumption tax')</span>
                            </div>
                            <input type="text" class="form-control sale_tax" autocomplete="off" disabled>
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang('Total amount including tax')</span>
                            </div>
                            <input type="text" class="form-control total-all-money" autocomplete="off" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                {{-- <button type="button" id="re-quote-btn" class="btn btn-primary btn-sm">はい</button> --}}
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>
