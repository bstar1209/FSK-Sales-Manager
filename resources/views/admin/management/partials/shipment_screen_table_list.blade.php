@inject('table_config', 'App\Models\TableConfig')

@php
    $shipment_info = $table_config->where('table_name', $table_config::$names[8])->first();
    $shipment_columns = json_decode($shipment_info->column_names);
    $shipment_widths = json_decode($shipment_info->column_info);
@endphp

<div class="row table-responsive mt-5">
    <table class="table table-bordered table-striped table-sm mb-2" id="shipment-table" cellspacing="0" tabindex="0">
        <thead>
            <tr>
                @foreach($shipment_columns as $key=>$column)
                <th width="{{ convert_width($shipment_widths[$key]) }}">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($shipment_columns as $key=>$column)
                <td></td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<script>
    $(function() {
        drawFlexigrid('shipment-table', 8);
    })
</script>
