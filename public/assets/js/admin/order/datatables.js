var orderFlag = false;

var columnsData = {
    "受注番号": "order_header_id",
    "受注日": "order_header.receive_order_date",
    "客先注文番号": "order_no_by_customer",
    "支払い条件": "order_header.cond_payment",
    "客先": "quote_customer.customer.user_info.company_name",
    "担当": "quote_customer.user_res",
    "見積日": "quote_customer.quote_date",
    "見積番号": "quote_customer.quote_code",
    "メーカー": "maker",
    "型番": "katashiki",
    "DC": "dc",
    "Rohs": "quote_customer.rohs",
    "国": "supplier.user_info.address.country",
    "見積数": "sale_qty",
    "見積単位": "buy_unit",
    "見積備考": "quote_customer.quote_prefer",
    "見積納期": "quote_customer.deadline_quote",
    "粗利率": "quote_customer.rate_profit",
    "粗利": "quote_customer.profit",
    "売数量": "sale_qty",
    "売単位": "sale_unit",
    "売通貨": "type_money_ship",
    "売単価": "sale_cost",
    "売金額": "sale_money",
    "客先希望納期": "order_header.expect_ship_date",
    "キャンセル　客先": "cancel_date_user",
};
var originalKeyArr = Object.keys(columnsData);
var columnDataArr = [
    {
        data: null,
        targets: 0,
        searchable: false,
        orderable: false,
        render: function (row, data) {
            return '<input type="checkbox" class="order-check">';
        }
    }
]
$.each(orderColumns, function (index, item) {
    columnDataArr.push({
        data: columnsData[item], name: item
    });
})

var orderTable = $('#order-table').DataTable({
    "processing": false,
    "serverSide": true,
    "searching": false,
    "lengthChange": false,
    "scrollY": "300px",
    "scrollX": true,
    "scrollCollapse": true,
    "paging": false,
    "bInfo": false,
    "autoWidth": true,
    'language': {
        "zeroRecords": "No data available in table",
        "loadingRecords": "&nbsp;",
        "processing": "読み込み中..."
    },
    "drawCallback": function (settings) {
        $('.order-table-spin.spin').spin('hide');
        $('.order-table-spin.spin-background').addClass('d-none');

        if (orderFlag) {
            var orderColumn = parseInt(localStorage.getItem('orderColumn'));
            var orderDir = localStorage.getItem('orderDir');
            if (orderColumn && orderDir) {
                orderFlag = false;
                var parentDiv = $('#order-table').parents('.dataTables_scroll').find('.dataTables_scrollHeadInner table');
                parentDiv.find('.sorting_desc').removeClass('sorting_desc').addClass('sorting');
                parentDiv.find('.sorting_asc').removeClass('sorting_asc').addClass('sorting');
                parentDiv.find('th:eq(' + orderColumn + ')').addClass('sorting_' + orderDir).removeClass('sorting');
            }
            if ($(':focus').parents('#search-area').length == 0)
                $('#order-table tbody').find('td:eq(1)').focus();
        }
    },
    "ajax": {
        url: getOrderListUrl,
        type: 'POST',
        data: function (data) {

            if (orderTable && orderTable.hasOwnProperty('context')) {
                localStorage.setItem('orderColumn', data.order[0].column);
                localStorage.setItem('orderDir', data.order[0].dir);
                orderFlag = false;
            } else {
                var orderColumn = parseInt(localStorage.getItem('orderColumn'));
                var orderDir = localStorage.getItem('orderDir');
                if (orderColumn && orderDir) {
                    data.order[0].column = orderColumn;
                    data.order[0].dir = orderDir;
                    orderFlag = true;
                }
            }

            $('.order-table-spin.spin').spin('show');
            $('.order-table-spin.spin-background').removeClass('d-none');
            data.customerName = $('#search-customer').val();
            data.maker = $('#search-maker').val();
            data.modelNumber = $('#search-model').val();
            data.estimateDate = $('#search-estimated-date').val();
            data.orderDate = $('#search-order-date').val();
            data.orderNumber = $('#search-order-number').val();
            data.quoteCode = $('#search-quote').val();
            data.searchStatus = $('#search-status').val();
            data.filterColumn = columnsData[orderColumns[data.order[0].column - 1]];
        },
        complete: function (data) {
            $('.all-order-check').prop('checked', false);
            orderTableLoadingFlag = true;
        }
    },
    'createdRow': function (row, data, dataIndex) {
        $(row).find('td').attr('tabindex', 1);
        $(row).data("orderId", data.id);
        $(row).data("rowInfo", data);
        if (dataIndex == 0) {
            $(row).addClass('tr-orange selected');
            drawCustomerInfo(data.quote_customer.customer);
        }
        if (data.order_KBN == 1)
            $(row).find('td:eq(' + (orderColumns.indexOf(originalKeyArr[5]) + 1) + ')').addClass('tr-light-blue');

        if (data.cancel_date_user)
            $(row).find('td:eq(' + (orderColumns.indexOf(originalKeyArr[5]) + 1) + ')').addClass('tr-grey');

        if (data.order_status == 1)
            $(row).addClass('tr-yellow');

        if (data.order_status == 9)
            $(row).find('td:eq(' + (columnsData.indexOf(originalKeyArr[10]) + 1) + ')').addClass('tr-grey');
    },
    columns: columnDataArr,
    order: [[1, "asc"]],
});
