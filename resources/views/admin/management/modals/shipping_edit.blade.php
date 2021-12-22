<div class="modal fade" id="shipping-edit-modal" tabindex="-1" role="dialog" aria-labelledby="shippingEditModalLabel"
aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 600px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="shippingEditModalLabel" class="modal-title text-warning">@lang('New shipping registration')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="col-12">
                    <table id="shipping-edit-table" class="table table-bordered table-striped" cellspacing="0">
                        <thead>
                            <tr>
                                <th>@lang('Area')</th>
                                <th>@lang('Shipping (excluding tax)')</th>
                                <th style="min-width: 270px">@lang('List of prefectures')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" class="form-control shipping-region"></td>
                                <td><input type="number" class="form-control shipping-fee" step="0.01"></td>
                                <td style="max-width: 270px"><div class="pre-list-div" style="display: flex; flex-wrap: wrap; justify-content: center;"></div><div style="position: relative;"><input id="pre-list-input" type="text" class="form-control" autoComplete="off"></div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="shipping-edit-btn" class="btn btn-primary btn-sm">@lang('Registration')</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">@lang('Cancel')</button>
            </div>
        </div>
    </div>
</div>
<button class="btn btn-info btn-sm pre-btn preBtnModel d-none m-1"><span class="pre-name"></span><span class="badge">X</span></button>
