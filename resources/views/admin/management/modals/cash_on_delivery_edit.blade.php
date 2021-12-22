<div class="modal fade" id="cash-on-delivery-edit-modal" tabindex="-1" role="dialog" aria-labelledby="cashOnDeliveryModalLabel"
aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 800px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="cashOnDeliveryModalLabel" class="modal-title text-warning">@lang('New shipping registration')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="col-12">
                    <table id="shipping-edit-table" class="table table-bordered table-striped" cellspacing="0">
                        <thead>
                            <tr>
                                <th>最低金額 (円)</th>
                                <th>最高金額 (円)</th>
                                <th>手数料(税抜き)</th>
                                <th style="min-width: 300px">適用金額範囲</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="number" class="form-control fee-min" step="0.01"></td>
                                <td><input type="number" class="form-control fee-max" step="0.01"></td>
                                <td><input type="number" class="form-control fee" step="0.01"></td>
                                <td><input type="text" class="form-control fee-information"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="cash-edit-btn" class="btn btn-primary btn-sm">@lang('Registration')</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">@lang('Cancel')</button>
            </div>
        </div>
    </div>
</div>
