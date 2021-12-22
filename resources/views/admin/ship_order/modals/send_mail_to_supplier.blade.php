<div class="modal fade" id="send-mail-to-supplier-modal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 600px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="confirmModalLabel" class="modal-title text-warning">受注内容変更</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center w-100">
                    <div class="col-12 input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate" id="supplier-email-label">メールア</span>
                        </div>
                        <input type="text" id="order-supplier-email" class="form-control" aria-label="Small"
                            aria-describedby="inputGroup-sizing-sm" disabled>
                    </div>
                </div>

                <div class="row justify-content-center w-100">
                    <div class="col-12">
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate" id="supplier-order-content">本文</span>
                            </div>
                        </div>
                        <textarea id="supplier-mail-content" class="form-control mt-2" name="email_content"
                            style="font-size: 18px !important" rows="10"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer"
                style="padding-right: 55px !important; justify-content: space-between !important;">
                <a id="supplier-order-pdf" href="#" target="_blank" style="padding-left: 12px !important;">注文書.pdf</a>
                <button type="button" id="order-confirm-btn" class="btn btn-primary btn-sm">送信</button>
            </div>
        </div>
    </div>
</div>
