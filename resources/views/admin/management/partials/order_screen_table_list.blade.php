@inject('table_config', 'App\Models\TableConfig')

@php
    $order_info = $table_config->where('table_name', $table_config::$names[5])->first();
    $order_columns = json_decode($order_info->column_names);
    $order_widths = json_decode($order_info->column_info);
@endphp
<div class="row table-responsive mt-5">
    <table class="table table-bordered table-striped table-sm mb-2" id="order-table" cellspacing="0" tabindex="0">
        <thead>
            <tr>
                @foreach($order_columns as $key=>$column)
                <th width="{{ convert_width($order_widths[$key]) }}">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($order_widths as $key=>$column)
                <td></td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<script>
    $(function() {
        drawFlexigrid('order-table', 5);
    })
</script>
