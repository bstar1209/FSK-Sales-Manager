@inject('table_config', 'App\Models\TableConfig')

@php
    $stock_info = $table_config->where('table_name', $table_config::$names[7])->first();
    $stock_columns = json_decode($stock_info->column_names);
    $stock_widths = json_decode($stock_info->column_info);
@endphp
<div class="row table-responsive mt-5">
    <table class="table table-bordered table-striped table-sm mb-2" id="stock-table" cellspacing="0" tabindex="0">
        <thead>
            <tr>
                @foreach($stock_columns as $key=>$column)
                <th width="{{ convert_width($stock_widths[$key]) }}">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($stock_columns as $key=>$column)
                <td></td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<script>
    $(function() {
        drawFlexigrid('stock-table', 7);
    })
</script>
