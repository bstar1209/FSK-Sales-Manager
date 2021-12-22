<div class="modal fade" id="delivery-edit-modal" tabindex="-1" role="dialog" aria-labelledby="deliveryEditModalLabel"
aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 600px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="deliveryEditModalLabel" class="modal-title text-warning">納品先登録</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("Registered name")(*)</span>
                            </div>
                            <input type="text" class="form-control registered-name">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("Company name")(*)</span>
                            </div>
                            <input type="text" class="form-control company-name">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">TEL(*)</span>
                            </div>
                            <input type="text" class="form-control tel">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">FAX</span>
                            </div>
                            <input type="text" class="form-control fax">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("Country")(*)</span>
                            </div>
                            <input type="text" class="form-control country">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("Prefecture")</span>
                            </div>
                            <input type="text" class="form-control prefecture">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("City")(*)</span>
                            </div>
                            <input type="text" class="form-control city">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("Address")1(*)</span>
                            </div>
                            <input type="text" class="form-control address1">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("Address")2</span>
                            </div>
                            <input type="text" class="form-control address2">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("Zip code")</span>
                            </div>
                            <input type="text" class="form-control zip-code">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang("Person in charge")(*)</span>
                            </div>
                            <input type="text" class="form-control person-in-charge">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="delivery-edit-btn" class="btn btn-primary btn-sm">@lang('Registration')</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">@lang('Cancel')</button>
            </div>
        </div>
    </div>
</div>
<button class="btn btn-info btn-sm pre-btn preBtnModel d-none m-1"><span class="pre-name"></span><span class="badge">X</span></button>
