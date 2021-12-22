<div class="modal fade" id="order-detail-change-modal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
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
                            <span class="input-group-text text-truncate">Email:</span>
                        </div>
                        <input type="text" id="order-customer-email" class="form-control" aria-label="Small"
                            aria-describedby="inputGroup-sizing-sm" disabled>
                    </div>
                </div>

                <div class="row justify-content-center w-100">
                    <div class="col-12 input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">@lang("Overview")</span>
                        </div>
                        <select id="order-change-type" class="form-control">
                            <option value="0"></option>
                            <option value="1">@lang('Sold select')</option>
                            <option value="2">@lang('Change')</option>
                        </select>
                    </div>
                </div>

                <div class="row justify-content-center w-100">
                    <div class="col-12 input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">@lang('Title')</span>
                        </div>
                        <input type="text" id="order-change-title" class="form-control" aria-label="Small"
                            aria-describedby="inputGroup-sizing-sm" disabled>
                    </div>
                </div>

                <div class="row justify-content-center w-100">
                    <div class="col-12">
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">本文</span>
                            </div>
                        </div>
                        <textarea class="form-control mt-2 email_content" name="email_content"
                            style="font-size: 18px !important" rows="15"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="padding-right: 55px !important">
                <button type="button" id="order-confirm-btn" class="btn btn-primary btn-sm">送信</button>
            </div>
        </div>
    </div>
</div>

<form id="invoice_form" action="{{ route('admin.order.invoice') }}" method="POST">
    @csrf
    <input type="hidden" name="orderHeaderId" id="orderHeaderId" />
</form>
