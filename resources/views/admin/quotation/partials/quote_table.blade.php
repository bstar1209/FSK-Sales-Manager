<div class="row" style="position: relative;">
    <h6 class="col-12 m-0 font-weight-bold text-primary">見積もり (ALT + 1) </h6>
    <div class="col-12 table-responsive">
        <table class="table table-bordered table-striped table-sm mb-2" id="quote-table" cellspacing="0" tabindex="0">
            <thead>
                <tr>
                    <th><input type="checkbox" class="all-quote-check"></th>
                    @foreach ($quote_columns as $key => $column)
                        <th style="min-width: {{ $quote_widths[$key] }} !important">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="quote-table-spin spin-background d-none"></div>
    </div>
    <div class="quote-table-spin spin" data-spin></div>
</div>
