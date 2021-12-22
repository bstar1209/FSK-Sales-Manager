<div class="modal fade" id="quote-request-modal" tabindex="-1" role="dialog" aria-labelledby="CancelRFQModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 1200px !important">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-3">
                    <div class="col-12 table-responsive" style="max-height: 500px !important; overflow-y: scroll">
                        <table id="quote-request-list-table" class="table table-bordered table-sm mb-2" cellspacing="0"
                            tabindex="0">
                            <thead>
                                <tr>
                                    <th>@lang('Model number')</th>
                                    <th>D/C</th>
                                    <th>@lang('Maker')</th>
                                    <th>@lang('Stock quantity')</th>
                                    <th>@lang('Hope number')</th>
                                    <th>@lang('Desired unit price')</th>
                                    <th>@lang('Area')</th>
                                    <th>削除</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-end">
                <a href="{{ route('frontend.terms.index') }}" class="btn btn-primary btn-sm" target="_blank">規約へ</a>
                <button type="button" id="quote-request-btn" class="btn btn-primary btn-sm">規約に同意して見積もり依頼</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">戻る</button>
            </div>
        </div>
    </div>
</div>
