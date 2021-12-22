<div class="modal fade" id="sales-representative-edit-modal" tabindex="-1" role="dialog" aria-labelledby="salesRepresentativeEditModalLabel"
aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 600px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="salesRepresentativeEditModalLabel" class="modal-title text-warning">営業担当の情報編集</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("English name")(*)</span>
                            </div>
                            <input type="text" class="form-control english-name">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("Email")(*)</span>
                            </div>
                            <input type="text" class="form-control email">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">TEL</span>
                            </div>
                            <input type="text" class="form-control tel">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("Japanese name")(*)</span>
                            </div>
                            <input type="text" class="form-control japanese-name">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("Password")(*)</span>
                            </div>
                            <input type="password" class="form-control password">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">FAX</span>
                            </div>
                            <input type="text" class="form-control fax">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="sales-representative-edit-btn" class="btn btn-primary btn-sm">@lang('Registration')</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">@lang('Cancel')</button>
            </div>
        </div>
    </div>
</div>
<button class="btn btn-info btn-sm pre-btn preBtnModel d-none m-1"><span class="pre-name"></span><span class="badge">X</span></button>
