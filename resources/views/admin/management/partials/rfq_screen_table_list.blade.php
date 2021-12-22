@inject('table_config', 'App\Models\TableConfig')

@php
    $rfq_info = $table_config->where('table_name', $table_config::$names[0])->first();
    $rfq_columns = json_decode($rfq_info->column_names);
    $rfq_widths = json_decode($rfq_info->column_info);

    $rfq_quote_info = $table_config->where('table_name', $table_config::$names[1])->first();
    $rfq_quote_columns = json_decode($rfq_quote_info->column_names);
    $rfq_quote_widths = json_decode($rfq_quote_info->column_info);

    $rfq_history_info = $table_config->where('table_name', $table_config::$names[2])->first();
    $rfq_history_columns = json_decode($rfq_history_info->column_names);
    $rfq_history_widths = json_decode($rfq_history_info->column_info);
@endphp

<div class="row mt-5">
    <h6 class="col-12 m-0 font-weight-bold text-primary">未RFQ依頼</h6>
</div>
<div class="row">
    <table class="table table-bordered table-striped table-sm mb-2" id="rfq-table" cellspacing="0" tabindex="0" style="overflow-x: scroll">
        <thead>
            <tr>
                @foreach($rfq_columns as $key=>$column)
                <th width="{{ convert_width($rfq_widths[$key]) }}">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($rfq_columns as $key=>$column)
                <td></td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<div class="row mt-5">
    <h6 class="col-12 m-0 font-weight-bold text-primary">仕入先からの見積もり</h6>
</div>
<div class="row">
    <table class="col-12 table table-bordered table-striped table-sm mb-2" id="rfq-quote-table" cellspacing="0" tabindex="0">
        <thead>
            <tr>
                @foreach($rfq_quote_columns as $key=>$column)
                <th width="{{ convert_width($rfq_quote_widths[$key]) }}">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($rfq_quote_columns as $key=>$column)
                <td></td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<div class="row mt-5">
    <h6 class="col-12 m-0 font-weight-bold text-primary">履歴</h6>
</div>
<div class="row">
    <table class="col-12 table table-bordered table-striped table-sm mb-2" id="rfq-history-table" cellspacing="0">
        <thead>
            <tr>
                @foreach($rfq_history_columns as $key=>$column)
                <th width="{{ convert_width($rfq_history_widths[$key]) }}">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($rfq_history_columns as $key=>$column)
                <td></td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<script>
$(function() {
    drawFlexigrid('rfq-table', 0);
    drawFlexigrid('rfq-quote-table', 1);
    drawFlexigrid('rfq-history-table', 2);
})
</script>
