<div class="modal fade" id="cancel-rfq-modal" tabindex="-1" role="dialog" aria-labelledby="CancelRFQModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 800px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h6 id="CancelRFQModalLabel" class="modal-title text-warning">
                    この見積もり依頼を取り消してもいいですか.
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-3">
                    <div class="col-12 table-responsive">
                        <table class="table table-bordered table-striped table-sm mb-2" id="quote-wait-edit-table"
                            cellspacing="0" tabindex="0">
                            <thead>
                                <tr>
                                    <th>依頼日</th>
                                    <th>受付番号</th>
                                    <th>型番</th>
                                    <th>メーカー</th>
                                    <th>DC</th>
                                    <th>地域</th>
                                    <th>希望数</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="cancel-row">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" id="cancel-rfq-btn" class="btn btn-primary btn-sm">はい</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">いいえ</button>
            </div>
        </div>
    </div>
</div>
