<div class="row" style="position: relative;">
    <div class="col-12 table-responsive">
        <table class="table table-bordered table-striped table-sm mb-2" id="order-table" cellspacing="0" tabindex="0">
            <thead>
                <tr>
                    <th><input type="checkbox" class="all-order-check"></th>
                    @foreach ($order_columns as $key => $column)
                        <th style="min-width: {{ $order_widths[$key] }} !important">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="order-table-spin spin-background d-none"></div>
    </div>
    <div class="order-table-spin spin" data-spin></div>
</div>
