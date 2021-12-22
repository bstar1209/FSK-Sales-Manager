var orderShipFlag = false;

var columnsData = {
    "客先": 'quote_customer.customer.user_info.company_name',
    "担当": 'quote_customer.customer.representative',
    "受注日": 'order_header.receive_order_date',
    "メーカー": 'maker',
    "型番": 'katashiki',
    "DC": 'quote_customer.dc',
    "地域": 'supplier.user_info.address.country',
    "Rohs": 'quote_customer.rohs',
    "見積納期": 'deadline_send',
    "仕入先": 'supplier.user_info.company_name',
    "ランク": 'supplier.user_info.rank',
    "買数量": 'ship_quantity',
    "買通貨": 'type_money_ship',
    "買単価": 'unit_buy_ship',
    "買金額": 'price_ship',
    "粗利率": 'quote_customer.rate_profit',
    "発注日": 'send_date',
    "発注番号": 'code_send',
    "納入日": 'import_date_plan',
    "顧客希望納期": 'order_header.expect_ship_date',
    "仕入先備考": 'refer_vendor',
    "Ship To": 0,
    "Ship By": 1,
    "キャンセル 仕入先": 'cancel_date_vendor',
    "仕入先見積番号": 'quote_customer.quote_code',
};
var originalKeyArr = Object.keys(columnsData);
var columnDataArr = [
    {
        data: null,
        targets: 0,
        searchable: false,
        orderable: false,
        render: function (row, data) {
            return '<input type="checkbox" class="ship-order-check">';
        }
    }
]
$.each(shipOrderColumns, function (index, item) {
    if (item == 'Ship To') {
        columnDataArr.push({
            data: null,
            name: "Ship To",
            render: function (row, data) {
                if (row.ship_to_info)
                    return row.ship_to_info.comp_name;
                else
                    return '';
            }
        })
    } else if (item == "Ship By") {
        columnDataArr.push({
            data: null,
            name: "Ship By",
            render: function (row, data) {
                if (row.transport)
                    return row.transport.name;
                else
                    return '';
            }
        });
    } else {
        columnDataArr.push({
            data: columnsData[item], name: item
        });
    }
})

var shipOrderTable = $('#ship-order-table').DataTable({
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
        $('.ship-order-table-spin.spin').spin('hide');
        $('.ship-order-table-spin.spin-background').addClass('d-none');

        updatedByChangedShipOrderTable();

        if (orderShipFlag) {
            var shipOrderColumn = parseInt(localStorage.getItem('shipOrderColumn'));
            var shipOrderDir = localStorage.getItem('shipOrderDir');
            if (shipOrderColumn && shipOrderDir) {
                orderShipFlag = false;
                var parentDiv = $('#ship-order-table').parents('.dataTables_scroll').find('.dataTables_scrollHeadInner table');
                parentDiv.find('.sorting_desc').removeClass('sorting_desc').addClass('sorting');
                parentDiv.find('.sorting_asc').removeClass('sorting_asc').addClass('sorting');
                parentDiv.find('th:eq(' + shipOrderColumn + ')').addClass('sorting_' + shipOrderDir).removeClass('sorting');
            }
            if ($(':focus').parents('#search-area').length == 0)
                $('#ship-order-table .selected').find('td:eq(1)').focus();
        }
    },
    "ajax": {
        url: getShipOrderListUrl,
        type: 'POST',
        data: function (data) {

            if (shipOrderTable && shipOrderTable.hasOwnProperty('context')) {
                localStorage.setItem('shipOrderColumn', data.order[0].column);
                localStorage.setItem('shipOrderDir', data.order[0].dir);
                orderShipFlag = false;
            } else {
                var shipOrderColumn = parseInt(localStorage.getItem('shipOrderColumn'));
                var shipOrderDir = localStorage.getItem('shipOrderDir');
                if (shipOrderColumn && shipOrderDir) {
                    data.order[0].column = shipOrderColumn;
                    data.order[0].dir = shipOrderDir;
                    orderShipFlag = true;
                }
            }

            $('.ship-order-table-spin.spin').spin('show');
            $('.ship-order-table-spin.spin-background').removeClass('d-none');
            data.customerName = $('#search-customer').val();
            data.supplierName = $('#search-supplier-name').val();
            data.modelNumber = $('#search-model-number').val();
            data.maker = $('#search-maker').val();
            data.orderDate = $('#search-order-date').val();
            data.shipOrderDate = $('#search-ship-order-date').val();
            data.orderNumber = $('#search-order-number').val();
            data.status = $('#search-status').val();
            data.filterColumn = columnsData[shipOrderColumns[data.order[0].column - 1]]
        },
        complete: function (data) {
            $('.all-ship-order-check').prop('checked', false);
            shipOrderTableLoadingFlag = true;
        }
    },
    'createdRow': function (row, data, dataIndex) {
        $(row).find('td').attr('tabindex', 1);
        $(row).data("orderId", data.id);
        $(row).data("rowInfo", data);
        if (dataIndex == 0) {
            $(row).addClass('tr-orange selected');
            if (data.quote_customer)
                drawSupplierInfo(data.quote_customer.customer);
        }
        if (data.order_status == 1)
            $(row).addClass('tr-yellow');

        if (data.cancel_date_user && data.cancel_date_user != '')
            $(row).find('td:eq(' + (shipOrderColumns.indexOf(originalKeyArr[2]) + 1) + ')').addClass('tr-grey');

        if (data.cancel_date_vendor && data.cancel_date_vendor != '')
            $(row).find('td:eq(' + (shipOrderColumns.indexOf(originalKeyArr[9]) + 1) + ')').addClass('tr-grey');

        if (data.sent_ship && data.sent_ship == 1) {
            $(row).find('td:eq(' + (shipOrderColumns.indexOf(originalKeyArr[2]) + 1) + ')').addClass('tr-light-blue');
            $(row).find('td:eq(' + (shipOrderColumns.indexOf(originalKeyArr[9]) + 1) + ')').addClass('tr-light-blue');
        }

        $.each($(row).find('td'), function (index, elem) {
            if ($(elem).text() == 'null')
                $(elem).text('');
        })
    },
    columns: columnDataArr,
    order: [[4, "asc"]],
});

var paymentTable = $('#payment-term-table').DataTable({
    "searching": false,
    "lengthChange": false,
    "scrollY": "170px",
    "autoWidth": false,
    "responsive": true,
    "ordering": false,
    "paging": false,
    "bInfo": false,
    "bRetrieve": 'true',
    "destroy": "true",
    "autoWidth": false,
    'language': {
        "zeroRecords": "No data available in table",
        "loadingRecords": "&nbsp;",
        "processing": "読み込み中...",
        "search": ""
    },
    ajax: {
        url: getPaymentUrl,
        dataSrc: '',
        type: 'POST',
        complete: function (data) {
            data.responseJSON.forEach(function (item) {
                commonPaymentList[item.id] = item.common_name;
                commonList[item.id] = { name: item.common_name, type: item.common_type };
            });

        }
    },
    'createdRow': function (row, data, dataIndex) {
        $(row).data('id', data.id);
    },
    columns: [
        { data: 'common_name', name: '支払方法' },
        {
            data: null,
            name: '更新',
            render: function (data, row) {
                return `<a class="btn btn-sm payment-edit"><i class="fa fa-edit fa-sm"></i></a><a class="btn btn-sm payment-save d-none"><i class="fa fa-save fa-sm"></i></a>`;
            }
        },
        {
            data: null,
            name: '削除',
            render: function (data, row) {
                return `<a class="btn btn-sm payment-delete"><i class="fa fa-trash fa-sm"></i></a><a class="btn btn-sm payment-cancel d-none"><i class="fa fa-close fa-sm"></i></a>`;
            }
        },
    ]
})
