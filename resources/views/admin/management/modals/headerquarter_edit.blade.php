<div class="modal fade" id="headerquarter-edit-modal" tabindex="-1" role="dialog" aria-labelledby="transportEditModalLabel"
aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 500px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="transportEditModalLabel" class="modal-title text-warning">運送業者登録</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">@lang('Company name')(*)</span>
                        </div>
                        <input type="text" id="company-name" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    </div>
                    <div class="col-12 input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">TEL(*)</span>
                        </div>
                        <input type="text" id="company-tel" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    </div>
                    <div class="col-12 input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">@lang('Address')(*)</span>
                        </div>
                        <textarea id="company-address" rows="5" style="width: 100% !important">
                        </textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="company-address-edit-btn" class="btn btn-primary btn-sm">登録</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</div>
