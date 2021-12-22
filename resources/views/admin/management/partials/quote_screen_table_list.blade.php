@inject('table_config', 'App\Models\TableConfig')

@php
    $quote_info = $table_config->where('table_name', $table_config::$names[3])->first();
    $quote_columns = json_decode($quote_info->column_names);
    $quote_widths = json_decode($quote_info->column_info);

    $quote_history_info = $table_config->where('table_name', $table_config::$names[4])->first();
    $quote_history_columns = json_decode($quote_history_info->column_names);
    $quote_history_widths = json_decode($quote_history_info->column_info);
@endphp

<div class="row mt-5">
    <h6 class="col-12 m-0 font-weight-bold text-primary">未RFQ依頼</h6>
</div>
<div class="row table-responsive">
    <table class="table table-bordered table-striped table-sm mb-2" id="quote-table" cellspacing="0" tabindex="0">
        <thead>
            <tr>
                @foreach($quote_columns as $key=>$column)
                <th width="{{ convert_width($quote_widths[$key]) }}">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($quote_columns as $key=>$column)
                <td></td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<div class="row mt-5">
    <h6 class="col-12 m-0 font-weight-bold text-primary">見積履歴</h6>
</div>
<div class="row table-responsive">
    <table class="table table-bordered table-striped table-sm mb-2" id="quote-history-table" cellspacing="0">
        <thead>
            <tr>
                @foreach($quote_history_columns as $key=>$column)
                <th width="{{ convert_width($quote_history_widths[$key]) }}">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($quote_history_columns as $key=>$column)
                <td></td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<script>
    $(function() {
        drawFlexigrid('quote-table', 3);
        drawFlexigrid('quote-history-table', 4);
    })
</script>

