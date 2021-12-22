<div class="modal fade" id="add-new-payment-modal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 500px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="confirmModalLabel" class="modal-title text-warning">支払方法追加</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-9">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">支払方法</span>
                            </div>
                            <input type="type" id="register-new-common-name" class="form-control" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                    </div>
                    <div class="col-3 float-right">
                        <button type="button" id="add-new-common-btn" class="btn btn-primary btn-sm">新規追加</button>
                    </div>
                </div>

                <div class="row justify-content-center w-100">
                    <table id="payment-term-table" class="table table-bordered table-sm mb-2  w-100" cellspacing="0">
                        <thead>
                            <tr>
                                <th>支払方法</th>
                                <th>更新</th>
                                <th>削除</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer justify-content-start">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">戻る</button>
            </div>
        </div>
    </div>
</div>
