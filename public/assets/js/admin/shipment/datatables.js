var shipmentFlag = false;
var currentOrderIndex = 0;

var columnsData = {
    "ID": 'import_goods.order_id',
    "客先": 'quote_customer.customer.user_info.company_name',
    "担当": 'quote_customer.customer.representative',
    "入荷日": 'import_goods.import_date',
    "メーカー": 'import_goods.maker',
    "型番": 'import_goods.katashiki',
    "DC": 'import_goods.dc',
    "Rohs": 'import_goods.rohs',
    "粗利率": 'quote_customer.rate_profit',
    "売数量": 'sale_qty',
    "売単位": 'sale_unit',
    "売通貨": 'order_header.type_money',
    "売単価": 'sale_cost',
    "売金額": 'sale_money',
    "出荷日": 'import_goods.export_date',
    "顧客希望納期": 'order_header.expect_ship_date',
    "お届け予定日": 'import_goods.import_date_plan',
    "配達時間": 'import_goods.export_time',
    "請求日": 'order_header.date_invoice',
    "請求No": 'import_goods.invoice_code',
    "客先ID": 'quote_customer.customer_id',
    "OutTR#": 'import_goods.out_tr',
    "顧客注文番号": 'import_goods.user_code',
    "支払い条件": 'order_header.cond_payment',
    "送料": 'order_header.fee_shipping',
    "代引き手数料": 'order_header.fee_daibiki',
};
var originalKeyArr = Object.keys(columnsData);
var columnDataArr = [
    {
        data: null,
        targets: 0,
        searchable: false,
        orderable: false,
        render: function (row, data) {
            return '<input type="checkbox" class="shipment-check">';
        }
    }
]
$.each(shipmentColumns, function (index, item) {
    columnDataArr.push({
        data: columnsData[item], name: item
    });
})

var shipmentTable = $('#shipment-table').DataTable({
    "processing": false,
    "serverSide": true,
    "searching": false,
    "lengthChange": false,
    "scrollY": "350px",
    "scrollX": true,
    "scrollCollapse": true,
    "paging": false,
    // "ordering": false,
    "bInfo": false,
    "autoWidth": true,
    'language': {
        "zeroRecords": "No data available in table",
        "loadingRecords": "&nbsp;",
        "processing": "読み込み中..."
    },
    "drawCallback": function (settings) {
        $('.shipment-table-spin.spin').spin('hide');
        $('.shipment-table-spin.spin-background').addClass('d-none');

        updatedByChangedShipmentTable();

        if (shipmentFlag) {
            var shipmentDir = localStorage.getItem('shipmentDir');
            if (currentOrderIndex && shipmentDir) {
                shipmentFlag = false;
                var parentDiv = $('#shipment-table').parents('.dataTables_scroll').find('.dataTables_scrollHeadInner table');
                parentDiv.find('.sorting_desc').removeClass('sorting_desc').addClass('sorting');
                parentDiv.find('.sorting_asc').removeClass('sorting_asc').addClass('sorting');
                parentDiv.find('th:eq(' + currentOrderIndex + ')').addClass('sorting_' + shipmentDir).removeClass('sorting');
            }
            if ($(':focus').parents('#search-area').length == 0)
                $('#shipment-table .selected').find('td:eq(1)').focus();
        }
    },
    "ajax": {
        url: getShipmentListUrl,
        type: 'POST',
        data: function (data) {

            if (shipmentTable && shipmentTable.hasOwnProperty('context')) {
                localStorage.setItem('shipmentColumn', data.order[0].column);
                localStorage.setItem('shipmentDir', data.order[0].dir);
                shipmentFlag = false;
            } else {
                var shipmentColumn = parseInt(localStorage.getItem('shipmentColumn'));
                var shipmentDir = localStorage.getItem('shipmentDir');
                if (shipmentColumn && shipmentDir) {
                    data.order[0].column = shipmentColumn;
                    data.order[0].dir = shipmentDir;
                    shipmentFlag = true;
                    currentOrderIndex = shipmentColumn;
                }
            }

            $('.shipment-table-spin.spin').spin('show');
            $('.shipment-table-spin.spin-background').removeClass('d-none');
            data.customerName = $('#search-customer').val();
            data.invoiceNumber = $('#search-invoice-number').val();
            data.shipDate = $('#search-ship-date').val();
            data.id = $('#search-id').val();
            data.maker = $('#search-maker').val();
            data.billingNumber = $('#search-biling-number').val();
            data.model = $('#search-model').val();
            data.status = $('#search-status').val();
            data.filterColumn = columnsData[shipmentColumns[data.order[0].column - 1]]
        },
        complete: function (data) {
            $('.all-shipment-check').prop('checked', false);
            quoteTableLoadingFlag = true;
        }
    },
    'createdRow': function (row, data, dataIndex) {
        $(row).find('td').attr('tabindex', 1);
        $(row).data("shipmentId", data.id);
        $(row).data("importGoodsId", data.import_goods.id);
        $(row).data("orderHeaderId", data.order_header_id);
        $(row).data("rowInfo", data);

        if (dataIndex == 0)
            $(row).addClass('tr-orange selected');
        if (data.import_goods.is_send_mail == 1)
            $(row).find('td:eq(' + (shipmentColumns.indexOf(originalKeyArr[15]) + 1) + ')').addClass('tr-light-blue');
        if (data.import_goods.export_status == 1)
            $(row).addClass('tr-yellow');
    },
    columns: columnDataArr,
    order: [[1, "asc"]],
});
