<div class="modal fade" id="edit-template-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-warning"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 justify-content-center w-100">
                        <div class="row">
                            <div class="col-8 input-group input-group-sm mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-truncate" id="template-subject">件名</span>
                                </div>
                                <input type="text" id="template-name" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center w-100">
                    <div class="col-8">
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate" id="supplier-order-content">本文</span>
                            </div>
                        </div>
                        <textarea id="template-content" class="form-control mt-2" name="template_content" style="font-size: 18px !important" rows="10"></textarea>
                    </div>
                    <div class="col-4">
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate" style="width: 100% !important">情報をDBから取得する</span>
                            </div>
                        </div>
                        <select id="template-params" class="w-100" style="min-height: 200px !important" multiple>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary btn-sm" id="edit-template-btn">@lang('Update')</button>
                <button type="button" class="btn btn-danger btn-sm ml-3" data-dismiss="modal">@lang('Cancel')</button>
            </div>
        </div>
    </div>
</div>
