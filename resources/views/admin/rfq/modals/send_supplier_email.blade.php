<div class="modal fade" id="email-send-modal" tabindex="-1" role="dialog" aria-labelledby="sendSupplierEmailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 600px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="sendSupplierEmailModalLabel" class="modal-title text-warning">見積依頼メール</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="send-to-supplier-form" class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">宛先</span>
                            </div>
                            <input type="email" class="form-control email_title" name="email_title" disabled>
                        </div>
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">本文</span>
                            </div>
                        </div>
                        <textarea id="email_content" class="form-control mt-2 email_content" name="email_content"
                            style="width:87%; font-size: 18px !important" rows="15"></textarea>
                        <input type="hidden" id="email_quote_id" name='email_quote_id'>
                    </div>
                    <div class="col-4">
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary btn-sm send-mail-btn">送信</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</div>
