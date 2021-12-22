<div class="row" style="position: relative;">
    <div class="col-12 table-responsive">
        <table class="table table-bordered table-striped table-sm mb-2" id="stock-table" cellspacing="0" tabindex="0">
            <thead>
                <tr>
                    <th><input type="checkbox" class="all-stock-check"></th>
                    @foreach ($stock_columns as $key => $column)
                        <th style="min-width: {{ $stock_widths[$key] }} !important">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="stock-table-spin spin-background d-none"></div>
    </div>
    <div class="stock-table-spin spin" data-spin></div>
</div>
