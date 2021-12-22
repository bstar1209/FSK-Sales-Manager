<div class="row" style="position: relative;">
    <div class="col-12 table-responsive">
        <table class="table table-bordered table-striped table-sm mb-2" id="ship-order-table" cellspacing="0" tabindex="0">
            <thead>
                <tr>
                    <th><input type="checkbox" class="all-ship-order-check"></th>
                    @foreach ($ship_order_columns as $key => $column)
                        <th style="min-width: {{ $ship_order_widths[$key] }} !important">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="ship-order-table-spin spin-background d-none"></div>
    </div>
    <div class="ship-order-table-spin spin" data-spin></div>
</div>
