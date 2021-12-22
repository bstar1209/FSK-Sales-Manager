<script>
function updateTableConfig(type, columnsName, columnsWidth) {
    $.ajax({
        url: tableConfigEditUrl,
        method: 'POST',
        data: {
            type: type,
            names: columnsName,
            width: columnsWidth
        },
        success: function(result) {
        }
    });
}
function getTableInfo(tableId, a, b, type) {
    var rfqTable = $('#'+tableId).parents('.flexigrid').find('.hDiv table');
    if(!isNaN(a) && !isNaN(b)) {
        var columnsName = [];
        var columnsWidth = [];
        $.each(rfqTable.find('thead th'), function(index, item) {
            columnsName.push($(item).text());
            columnsWidth.push($(item).width()+'px');
        });
        updateTableConfig(type, columnsName, columnsWidth);
    }
}
function drawFlexigrid(tableId, type) {
    $('#'+tableId).flexigrid({
        height:'auto',
        dataType : 'html',
        Sortable: 0,
        showToggleBtn: false,
        striped: false,
        minwidth: 60,
        autoResizeColumn: true,
        onDragCol:function(a, b, table, colName) {
            getTableInfo(tableId, a, b, type);
        },
        onResizeComplete:function(table,colName,newwidth) {
            getTableInfo(tableId, "", "", type);
        }
    });
}
</script>
<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="rfq-tab" data-toggle="tab" href="#rfq" role="tab" aria-controls="home"
                aria-selected="true">RFQ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="estimate-tab" data-toggle="tab" href="#estimate" role="tab" aria-controls="profile"
                aria-selected="false">@lang('Estimate')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="order-received-tab" data-toggle="tab" href="#order-received" role="tab" aria-controls="contact"
                aria-selected="false">@lang('Orders received')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="order-tab" data-toggle="tab" href="#order" role="tab" aria-controls="home"
                  aria-selected="true">@lang('Order')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="stock-tab" data-toggle="tab" href="#stock" role="tab" aria-controls="profile"
                aria-selected="false">@lang('In stock')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="shipment-tab" data-toggle="tab" href="#shipment" role="tab" aria-controls="contact"
                aria-selected="false">@lang('Shipment')</a>
            </li>
        </ul>
            <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="rfq" role="tabpanel" aria-labelledby="home-tab">
                @include('admin.management.partials.rfq_screen_table_list')
            </div>
            <div class="tab-pane fade" id="estimate" role="tabpanel" aria-labelledby="profile-tab">
                @include('admin.management.partials.quote_screen_table_list')
            </div>
            <div class="tab-pane fade" id="order-received" role="tabpanel" aria-labelledby="contact-tab">
                @include('admin.management.partials.order_screen_table_list')
            </div>
            <div class="tab-pane fade" id="order" role="tabpanel" aria-labelledby="contact-tab">
                @include('admin.management.partials.ship_order_screen_table_list')
            </div>
            <div class="tab-pane fade" id="stock" role="tabpanel" aria-labelledby="contact-tab">
                @include('admin.management.partials.stock_screen_table_list')
            </div>
            <div class="tab-pane fade" id="shipment" role="tabpanel" aria-labelledby="contact-tab">
                @include('admin.management.partials.shipment_screen_table_list')
            </div>
          </div>
    </div>
</div>
