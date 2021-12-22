<div class="row" style="position: relative;">
    <h6 class="col-12 m-0 font-weight-bold text-primary">未RFQ依頼 (ALT + 1) </h6>
    <div class="col-12 table-responsive">
        <table class="table table-bordered table-striped table-sm mb-2" id="request-unrfq-table" cellspacing="0"
            tabindex="0">
            <thead>
                <tr>
                    @foreach ($rfq_columns as $key => $column)
                    <th style="min-width: {{ $rfq_widths[$key] }} !important; box-sizing: border-box !important">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="unrfq-table-spin spin-background d-none"></div>
    </div>
    <div class="unrfq-table-spin spin" data-spin></div>
</div>
