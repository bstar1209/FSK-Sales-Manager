@inject('table_config', 'App\Models\TableConfig')

@php
    $ship_order_info = $table_config->where('table_name', $table_config::$names[6])->first();
    $ship_columns = json_decode($ship_order_info->column_names);
    $ship_widths = json_decode($ship_order_info->column_info);
@endphp
<div class="row table-responsive mt-5">
    <table class="table table-bordered table-striped table-sm mb-2" id="ship-order-table" cellspacing="0" tabindex="0">
        <thead>
            <tr>
                @foreach($ship_columns as $key=>$column)
                <th width="{{ convert_width($ship_widths[$key]) }}">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($ship_columns as $key=>$column)
                <td></td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<script>
    $(function() {
        drawFlexigrid('ship-order-table', 6);
    })
</script>
