<div class="modal fade" id="password-change-modal" tabindex="-1" role="dialog"
    aria-labelledby="PasswordChangeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 400px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h6 id="PasswordChangeModalLabel" class="modal-title text-warning">
                    パスワードの変更をします
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="form-group col-12">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate"
                                style="min-width: 160px !important">@lang('Current password') *</span>
                        </div>
                        <input type="password" class="form-control cur-password" name="cur-password">
                    </div>
                </div>

                <div class="form-group col-12">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate" style="min-width: 160px !important">@lang('New password') *</span>
                        </div>
                        <input type="password" class="form-control new-password" name="cur-password">
                    </div>
                </div>

                <div class="form-group col-12">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate" style="min-width: 160px !important">@lang('New password (confirmation)') *</span>
                        </div>
                        <input type="password" class="form-control confirm-password" name="cur-password">
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" id="change-password-btn" class="btn btn-primary btn-sm">はい</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">いいえ</button>
            </div>
        </div>
    </div>
</div>
