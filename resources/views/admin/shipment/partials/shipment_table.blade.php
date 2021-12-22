<div class="row" style="position: relative; margin-top: 30px">
    <div class="col-12 table-responsive">
        <table class="table table-bordered table-striped table-sm mb-2" id="shipment-table" cellspacing="0" tabindex="0">
            <thead>
                <tr>
                    <th><input type="checkbox" class="all-shipment-check"></th>
                    @foreach ($shipment_columns as $key => $column)
                        <th style="min-width: {{ $shipment_widths[$key] }} !important">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="shipment-table-spin spin-background d-none"></div>
    </div>
    <div class="shipment-table-spin spin" data-spin></div>
</div>
