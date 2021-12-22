<div class="modal fade" id="reset-modal" tabindex="-1" role="dialog" aria-labelledby="ResetModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 500px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h6 id="ResetModalLabel" class="modal-title mx-auto">
                    パスワードを再設定するメールを送ります. 会員登録したメールアドレスをご記入ください.
                </h6>
            </div>
            <div class="modal-body row">
                <div class="form-group col-12">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">@lang('Email')*</span>
                        </div>
                        <input type="email" class="form-control email">
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" id="reset-btn" class="btn btn-primary btn-sm">送信</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</div>
