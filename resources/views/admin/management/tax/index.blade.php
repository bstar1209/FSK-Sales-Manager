<div class="row">
    <div class="col-md-4">
        <h6 class="font-weight-bold text-primary mb-3">@lang('Consumption tax setting')</h6>
        <table class="table table-bordered table-striped" cellspacing="0">
            <thead>
                <tr>
                    <th>@lang('Sale tax')</th>
                    <th>@lang('Update date')</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if ($tax)
                <tr id="tax-info-row">
                    <td>{{ $tax->tax }}%</td>
                    <td>{{ $tax->updated_at->format('Y-m-d') }}</td>
                    <td class="py-2"><button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit-tax-modal">@lang('Edit')</button></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-md-4 offset-md-2">
        <h6 class="font-weight-bold text-primary mb-3">@lang('History of consumption tax settings')</h6>
        <table class="table table-bordered table-striped" cellspacing="0" id="tax-log-table">
            <thead>
                <tr>
                    <th>@lang('Sale tax')</th>
                    <th>@lang('Update date')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tax_log as $log)
                <tr>
                    <td>{{ $log->tax }}%</td>
                    <td>{{ $log->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="edit-tax-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-warning">@lang('Sales tax edit')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="invalid-text text-danger mb-2"></div>
                <div class="form-group row mb-0">
                    <div class="col-3 col-form-label">@lang('Sale tax')</div>
                    <div class="col-9">
                        <input type="number" class="form-control" id="tax-input" min="0" />
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary btn-sm" id="btn-update-tax">@lang('Update')</button>
                <button type="button" class="btn btn-danger btn-sm ml-3" data-dismiss="modal">@lang('Cancel')</button>
            </div>
        </div>
    </div>
</div>
