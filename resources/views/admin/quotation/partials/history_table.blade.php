<div class="row mt-3" style="position: relative;">
    <div class="col-12 d-flex align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">見積履歴 (ALT + 2) </h6>
        <input type="text" id="history-table_filter" class="col-2 form-control ml-3"> &nbsp;&nbsp;(ALT + 0)
    </div>
    <div class="col-12 table-responsive">
        <table class="table table-bordered table-striped table-sm mb-2" id="history-table" cellspacing="0">
            <thead>
                <tr>
                    @foreach ($quote_history_columns as $key => $column)
                        <th style="min-width: {{ $quote_history_widths[$key] }} !important">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="history-table-spin spin-background d-none"></div>
    </div>
    <div class="history-table-spin spin" data-spin />
</div>
