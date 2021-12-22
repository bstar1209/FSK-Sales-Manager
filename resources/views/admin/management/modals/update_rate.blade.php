<div class="modal fade" id="rate-update-modal" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <div class="col-12">
                        <div class="invalid-text text-danger mb-2"></div>
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th rowspan="2"></th>
                                    <th colspan="2">@lang('New rate')</th>
                                </tr>
                                <tr>
                                    <th>@lang('Buy rate')</th>
                                    <th>@lang('Selling rate')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input class="form-control text-center" type="text" id="input-type-money"></td>
                                    <td><input class="form-control text-center" type="number" id="input-buy-rate"></td>
                                    <td><input class="form-control text-center" type="number" id="input-sale-rate"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary btn-sm" id="btn-update-rate"></button>
                <button type="button" class="btn btn-danger btn-sm ml-3" data-dismiss="modal">@lang('Cancel')</button>
            </div>
        </div>
    </div>
</div>
