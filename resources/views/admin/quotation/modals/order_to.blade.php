<div class="modal fade" id="order-to-modal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 600px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="confirmModalLabel" class="modal-title text-warning">支払方法追加</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend" style="border-radius: 0.2rem !important">
                                <span class="input-group-text text-truncate">支払い条件</span>
                            </div>
                            <form class="row justify-content-around payment-1 ml-2"
                                style="width: 50%; flex-direction: column;">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center w-100">
                    <div class="col-12 input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">@lang("Desired")(*)</span>
                        </div>
                        <input type="text" id="order-to-desired" class="form-control" aria-label="Small"
                            aria-describedby="inputGroup-sizing-sm">
                    </div>
                </div>

                <div class="row justify-content-center w-100">
                    <div class="col-12 input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">@lang("Your")(*)</span>
                        </div>
                        <input type="text" id="order-to-your" class="form-control" aria-label="Small"
                            aria-describedby="inputGroup-sizing-sm">
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" id="order-confirm-btn" class="btn btn-primary btn-sm">受注へ送る</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</div>
