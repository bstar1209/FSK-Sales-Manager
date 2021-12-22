var stockFlag = false;

var columnsData = {
    "客先": 'quote_customer.customer.user_info.company_name',
    "担当": 'quote_customer.customer.representative',
    "客先注文番号": 'import_goods.user_code',
    "納入予定日": 'import_goods.import_date_plan',
    "発注日": 'import_goods.send_date',
    "顧客希望納期": 'import_goods.expect_ship_date',
    "メーカー": 'import_goods.maker',
    "型番": 'import_goods.katashiki',
    "DC": 'import_goods.dc',
    "Rohs": 'import_goods.rohs',
    "地域": 'supplier.user_info.address.country',
    "仕入先": 'supplier.user_info.company_name',
    "買数量": 'import_goods.ship_quantity',
    "買通貨": 'import_goods.type_money_ship',
    "買単価": 'import_goods.price_ship',
    "入荷日": 'import_goods.import_date',
    "入荷数": 'import_goods.import_qty',
    "入荷単価": 'import_goods.import_unit_price',
    "CoO": 'import_goods.coo',
    "InTR#": 'import_goods.in_tr',
};

var originalKeyArr = Object.keys(columnsData);

var columnDataArr = [
    {
        data: null,
        targets: 0,
        searchable: false,
        orderable: false,
        render: function (row, data) {
            return '<input type="checkbox" class="stock-check">';
        }
    }
]
$.each(stockColumns, function (index, item) {
    columnDataArr.push({
        data: columnsData[item], name: item
    });
})

var stockTable = $('#stock-table').DataTable({
    "processing": false,
    "serverSide": true,
    "searching": false,
    "lengthChange": false,
    "scrollY": "350px",
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
        $('.stock-table-spin.spin').spin('hide');
        $('.stock-table-spin.spin-background').addClass('d-none');

        if (stockFlag) {
            var stockColumn = parseInt(localStorage.getItem('stockColumn'));
            var stockDir = localStorage.getItem('stockDir');
            if (stockColumn && stockDir) {
                stockFlag = false;
                var parentDiv = $('#stock-table').parents('.dataTables_scroll').find('.dataTables_scrollHeadInner table');
                parentDiv.find('.sorting_desc').removeClass('sorting_desc').addClass('sorting');
                parentDiv.find('.sorting_asc').removeClass('sorting_asc').addClass('sorting');
                parentDiv.find('th:eq(' + stockColumn + ')').addClass('sorting_' + stockDir).removeClass('sorting');
            }
            if ($(':focus').parents('#search-area').length == 0)
                $('#stock-table .selected').find('td:eq(1)').focus();
        }
    },
    "ajax": {
        url: getStockList,
        type: 'POST',
        data: function (data) {

            if (stockTable && stockTable.hasOwnProperty('context')) {
                localStorage.setItem('stockColumn', data.order[0].column);
                localStorage.setItem('stockDir', data.order[0].dir);
                stockFlag = false;
            } else {
                var stockColumn = parseInt(localStorage.getItem('stockColumn'));
                var stockDir = localStorage.getItem('stockDir');
                if (stockColumn && stockDir) {
                    data.order[0].column = stockColumn;
                    data.order[0].dir = stockDir;
                    stockFlag = true;
                }
            }

            $('.stock-table-spin.spin').spin('show');
            $('.stock-table-spin.spin-background').removeClass('d-none');
            data.customerName = $('#search-customer').val();
            data.supplierName = $('#search-supplier-name').val();
            data.modelNumber = $('#search-model-number').val();
            data.maker = $('#search-maker').val();
            data.orderNumber = $('#search-order-number').val();
            data.stockNumber = $('#search-stock-number').val();
            data.status = $('#search-status').val();
            currentTime = +new Date();
            data.filterColumn = columnsData[stockColumns[data.order[0].column - 1]]
        },
        complete: function (data) {
            $('.all-stock-check').prop('checked', false);
            stockTableLoadingFlag = true;
        }
    },
    'createdRow': function (row, data, dataIndex) {
        $(row).find('td').attr('tabindex', 1);
        $(row).data("stockId", data.id);
        $(row).data("importGoodsId", data.import_goods.id);
        $(row).data("rowInfo", data);
        if (dataIndex == 0) {
            $(row).addClass('tr-orange selected');
            drawSupplierInfo(data.supplier);
        }

        if (data.import_goods.import_status == 1)
            $(row).addClass('tr-yellow');

        if (data.import_goods.importKBN == 1) {
            $(row).find('td:eq(' + (stockColumns.indexOf(originalKeyArr[4]) + 1) + ')').addClass('tr-light-blue')
        }

        if (data.import_goods.import_date_plan) {
            var importTime = +new Date(data.import_goods.import_date_plan);
            if (currentTime - importTime > 86400000)
                $(row).find('td:eq(' + (stockColumns.indexOf(originalKeyArr[4]) + 1) + ')').addClass('font-red');
        }
    },
    columns: columnDataArr,
    columnDefs: [
        { "orderable": false, "targets": [0] }
    ],
    order: [[1, "asc"]],
});

var paymentTable = $('#payment-term-table').DataTable({
    "searching": false,
    "lengthChange": false,
    "scrollY": "170px",
    "scrollX": true,
    "scrollCollapse": true,
    "paging": false,
    "bInfo": false,
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
                return `<a class="btn btn-sm payment-edit"><i class="fa fa-edit fa-sm"></i></a>
                        <a class="btn btn-sm payment-save d-none"><i class="fa fa-save fa-sm"></i></a>`;
            }
        },
        {
            data: null,
            name: '削除',
            render: function (data, row) {
                return `<a class="btn btn-sm payment-delete"><i class="fa fa-trash fa-sm"></i></a>
                        <a class="btn btn-sm payment-cancel d-none"><i class="fa fa-close fa-sm"></i></a>`;
            }
        },
    ]
})
