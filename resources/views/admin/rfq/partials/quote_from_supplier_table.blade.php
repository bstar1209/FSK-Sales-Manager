<div class="row" style="margin-top: 20px; position: relative;">
    <div class="col-12 d-flex justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">仕入先からの見積もり (ALT + 2)</h6>
        <button type="button" id="new-quote-btn" class="btn btn-primary btn-sm">見積もり追加 (B)</button>
    </div>
    <div class="col-12 table-responsive">
        <table class="table table-bordered table-striped table-sm mb-2" id="quote-from-supplier-table" cellspacing="0"
            tabindex="0">
            <thead>
                <tr>
                    @foreach ($rfq_quote_columns as $key => $column)
                        <th style="min-width: {{ $rfq_quote_widths[$key] }}!important">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="quote-from-supplier-table-spin spin-background d-none"></div>
    </div>
    <div class="quote-from-supplier-table-spin spin" data-spin></div>
</div>
