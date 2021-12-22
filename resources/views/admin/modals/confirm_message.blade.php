<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document" style="max-width: 500px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="confirmModalLabel" class="modal-title text-warning">通知</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" id="modal-warning">
                        このアクションを行いますか。
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="confirm-btn" class="btn btn-primary btn-sm">はい</button>
                <button type="button" id="confirm-cancel" class="btn btn-danger btn-sm">いいえ</button>
            </div>
        </div>
    </div>
</div>
