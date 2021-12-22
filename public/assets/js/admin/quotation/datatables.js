var quoteFlag = false;
var historyFlag = false;
var historyTable = null;

var columnsQuoteData = {
    "受付番号": 0,
    "受付日": "receive_date",
    "見積番号": 1,
    "見積日": "date_send",
    "客先": "customer.user_info.company_name",
    "担当": "user_res",
    "メーカー": "maker",
    "型番": "katashiki",
    "DC": "dc",
    "Rohs": "rohs",
    "地域": "kbn2",
    "希望数量": "count_predict",
    "見積備考": "quote_prefer",
    "見積納期": "deadline_quote",
    "仕入先": "supplier.user_info.company_name",
    "買数量": "buy_quantity",
    "買単位": "unit_buy",
    "買通貨": "type_money_buy",
    "買単価": "money_buy",
    "買金額": "unit_price_buy",
    "送料": "fee_shipping",
    "粗利率": 5,
    "粗利": "profit",
    "見積単価": "price_quote",
    "売数量": 2,
    "売単位": "unit_sell",
    "売通貨": 6,
    "売単価": 3,
    "売金額": 4,
    "支払い条件": "cond_payment",
    // "購買メッセージ": "comment_bus",
};
var originalQuoteKeyArr = Object.keys(columnsQuoteData);
var columnQuoteDataArr = [
    {
        data: null,
        targets: 0,
        searchable: false,
        orderable: false,
        render: function (row, data) {
            return '<input type="checkbox" class="quote-check">';
        }
    }
];

getRatelist();

$.each(quoteColumns, function (index, item) {
    if (columnsQuoteData[item] == 0) {
        columnQuoteDataArr.push({
            data: null,
            name: "@lang('Reception number')",
            render: function (row, data) {
                return row.request_vendors.rfq_request.detail_id + ' - ' + row.request_vendors.rfq_request.child_index;
            }
        });
    } else if (columnsQuoteData[item] == 1) {
        columnQuoteDataArr.push({
            data: null,
            name: "@lang('Quote number')",
            render: function (row, data) {
                // if (row.is_sendmail == 1)
                return row.request_vendors.code_quote;
                // else
                //     return '';
            }
        });
    } else if (columnsQuoteData[item] == 2) {
        columnQuoteDataArr.push({
            data: null,
            name: "@lang('Sales quantity')",
            class: 'sell_quantity_td',
            render: function (row, data) {
                if (row.sell_quantity || row.sell_quantity == 0)
                    return row.sell_quantity;
                return row.sell_quantity_second;
            }
        });
    } else if (columnsQuoteData[item] == 3) {
        columnQuoteDataArr.push({
            data: null,
            name: "@lang('Selling unit price')",
            render: function (row, data) {
                if (row.unit_price_sell || row.unit_price_sell == 0)
                    return row.unit_price_sell;
                return row.unit_price_second;
            }
        });
    } else if (columnsQuoteData[item] == 4) {
        columnQuoteDataArr.push({
            data: null,
            name: "@lang('Selling amount')",
            render: function (row, data) {
                if (row.money_sell || row.money_sell == 0)
                    return row.money_sell;
                return row.money_sell_second;
            }
        });
    } else if (columnsQuoteData[item] == 5) {
        columnQuoteDataArr.push({
            data: null,
            class: 'rate_profit_td',
            name: "粗利率",
            render: function (row, data) {
                return row.rate_profit ? row.rate_profit : 0;
            }
        });
    } else if (columnsQuoteData[item] == 6) {
        columnQuoteDataArr.push({
            data: null,
            class: 'quote_currency_td',
            name: "売通貨",
            render: function (row, data) {
                return row.type_money_sell;
            }
        });
    } else if (item == '粗利') {
        columnQuoteDataArr.push({
            data: null,
            name: "粗利",
            render: function (row, data) {
                var price = ((row.money_buy*rateList[row.type_money_buy].buy_rate*row.buy_quantity)/(1-row.rate_profit)+row.fee_shipping)/(row.sell_quantity ? row.sell_quantity : row.sell_quantity_second)/rateList[row.type_money_sell].sale_rate;
                if (Number.isNaN(price) || !isFinite(price)) price = 0;
                price = Math.round(price * 100000) / 100000;
                
                var rate = (price*(row.sell_quantity ? row.sell_quantity : row.sell_quantity_second)*rateList[row.type_money_sell].sale_rate)-(row.money_buy*rateList[row.type_money_buy].buy_rate*row.buy_quantity)-row.fee_shipping;
                if (Number.isNaN(rate) || !isFinite(rate)) rate = 0;
                rate = Math.round(rate * 100000) / 100000;
                return row.profit ? row.profit : rate.toFixed(2);
            }
        });
    } else if (item == '見積単価') {
        columnQuoteDataArr.push({
            data: null,
            name: "見積単価",
            render: function (row, data) {
                var price = ((row.money_buy*rateList[row.type_money_buy].buy_rate*row.buy_quantity)/(1-row.rate_profit)+row.fee_shipping)/(row.sell_quantity ? row.sell_quantity : row.sell_quantity_second)/rateList[row.type_money_sell].sale_rate;
                if (Number.isNaN(price) || !isFinite(price)) price = 0;
                price = Math.round(price * 100000) / 100000;
                return row.price_quote ? row.price_quote : price.toFixed(2);
            }
        });
    } else {
        columnQuoteDataArr.push({
            data: columnsQuoteData[item], name: item
        });
    }
})

var quoteTable = $('#quote-table').DataTable({
    "processing": false,
    "serverSide": true,
    "searching": false,
    "lengthChange": false,
    "scrollY": "170px",
    "scrollX": true,
    "scrollCollapse": true,
    "paging": false,
    "bInfo": false,
    // "autoWidth": true,
    'language': {
        "zeroRecords": "No data available in table",
        "loadingRecords": "&nbsp;",
        "processing": "読み込み中..."
    },
    "drawCallback": function (settings) {

        $('.quote-table-spin.spin').spin('hide');
        $('.quote-table-spin.spin-background').addClass('d-none');

        if (quoteFlag) {
            var quotationColumn = parseInt(localStorage.getItem('quotationOrderColumn'));
            var quotationDir = localStorage.getItem('quotationOrderDir');
            if (quotationColumn && quotationDir) {
                quoteFlag = false;
                var parentDiv = $('#quote-table').parents('.dataTables_scroll').find('.dataTables_scrollHeadInner table');
                parentDiv.find('.sorting_desc').removeClass('sorting_desc').addClass('sorting');
                parentDiv.find('.sorting_asc').removeClass('sorting_asc').addClass('sorting');
                parentDiv.find('th:eq(' + quotationColumn + ')').addClass('sorting_' + quotationDir).removeClass('sorting');
            }
            if ($(':focus').parents('#search-area').length == 0)
                $('#quote-table .selected').find('td:eq(1)').focus();
        }
    },
    "ajax": {
        url: getQuoteListUrl,
        type: 'POST',
        data: function (data) {
            if (quoteTable && quoteTable.hasOwnProperty('context')) {
                localStorage.setItem('quotationOrderColumn', data.order[0].column);
                localStorage.setItem('quotationOrderDir', data.order[0].dir);
                quoteFlag = false;
            } else {
                var quotationColumn = parseInt(localStorage.getItem('quotationOrderColumn'));
                var quotationDir = localStorage.getItem('quotationOrderDir');
                if (quotationColumn && quotationDir) {
                    data.order[0].column = quotationColumn;
                    data.order[0].dir = quotationDir;
                    quoteFlag = true;
                }
            }

            $('.quote-table-spin.spin').spin('show');
            $('.quote-table-spin.spin-background').removeClass('d-none');
            data.customerName = $('#search-customer').val();
            data.supplierName = $('#search-supplier').val();
            data.modelNumber = $('#search-model').val();
            data.estimateDate = $('#search-estimate').val();
            data.receptionDate = $('#search-reception-date').val();
            data.reception = $('#search-reception').val();
            data.quoteCode = $('#search-quote').val();
            data.customerId = $('#search-customer-id').val();
            data.searchStatus = $('#search-status').val();
            data.filterColumn = columnsQuoteData[quoteColumns[data.order[0].column - 1]];
        },
        complete: function (data) {
            $('.all-quote-check').prop('checked', false);
            quoteTableLoadingFlag = true;
            if (!historyTable)
                historyTableDraw();
            else
                historyTable.draw();
        }
    },
    'createdRow': function (row, data, dataIndex) {
        $(row).data("quoteId", data.id);
        $(row).data("customerId", data.customer_id);
        $(row).data("rowInfo", data);
        $(row).data('rfq_status', data.is_solved);
        if (data.is_solved == 1)
            $(row).addClass('tr-yellow');
        if (data.is_order == 1) {
            $(row).find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[5]) + 1) + ')').addClass('tr-green');
            $(row).find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[6]) + 1) + ')').addClass('tr-green');
        }
        if (data.is_sendmail == 1)
            $(row).find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[4]) + 1) + ')').addClass('tr-light-blue');

        if (data.is_delete == 1)
            $(row).find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[2]) + 1) + ')').addClass('tr-grey');

        if (data.request_vendors.rfq_request.is_solved == 0)
            $(row).find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[2]) + 1) + ')').addClass('tr-orange');

        if (dataIndex == 0) {
            $(row).addClass('tr-orange selected');
            drawCustomerInfo(data.customer);
            $('#history-table_filter').val(data.katashiki);
        }

        $(row).data('status', data.is_solved);

        $.each($(row).find('td'), function (index, item) {
            if ($(this).text() == 'null')
                $(this).text('');
            $(item).attr('tabindex', index + 1);
        });
    },
    columns: columnQuoteDataArr,
    order: [[2, "asc"]],
});

var columnsData = {
    "見積日": 'quote_date',
    "客先": 'customer_id',
    "メーカー": 'maker',
    "型番": 'katashiki',
    "売数量": 'sell_quantity',
    "売通貨": 'type_money_sell',
    "売単価": 'unit_price_sell',
    "DC": 'dc',
    "Rohs": 'rohs',
    "仕入先": 'supplier.user_info.company_name',
    "買単価": 'unit_price_buy',
    "買通貨": 'type_money_buy',
    "粗利": 'profit',
};
var columnHistoryDataArr = [];
$.each(quoteHistoryColumns, function (index, item) {
    columnHistoryDataArr.push({
        data: columnsData[item], name: item
    });
})

function historyTableDraw() {
    historyTable = $("#history-table").DataTable({
        "dom": '<"pull-left"f><"pull-right"l>tip',
        "lengthChange": false,
        "serverSide": true,
        "scrollY": "170px",
        "scrollX": true,
        "searching": false,
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
        "drawCallback": function (settings) {
            $('.history-table-spin.spin').spin('hide');
            $('.history-table-spin.spin-background').addClass('d-none');

            if (historyFlag) {
                var quoteHistoryColumn = parseInt(localStorage.getItem('quoteHistoryColumn'));
                var quoteHistoryDir = localStorage.getItem('quoteHistoryDir');
                if (quoteHistoryColumn && quoteHistoryDir) {
                    historyFlag = false;
                    var parentDiv = $('#history-table').parents('.dataTables_scroll').find('.dataTables_scrollHeadInner table');
                    parentDiv.find('.sorting_desc').removeClass('sorting_desc').addClass('sorting');
                    parentDiv.find('.sorting_asc').removeClass('sorting_asc').addClass('sorting');
                    parentDiv.find('th:eq(' + quoteHistoryColumn + ')').addClass('sorting_' + quoteHistoryDir).removeClass('sorting');
                }
            }
        },
        "ajax": {
            url: getQuoteCustomerHistoryUrl,
            type: 'POST',
            data: function (data) {
                if (historyTable && historyTable.hasOwnProperty('context')) {
                    localStorage.setItem('quoteHistoryColumn', data.order[0].column);
                    localStorage.setItem('quoteHistoryDir', data.order[0].dir);
                } else {
                    var quoteHistoryColumn = parseInt(localStorage.getItem('quoteHistoryColumn'));
                    var quoteHistoryDir = localStorage.getItem('quoteHistoryDir');
                    if (quoteHistoryColumn && quoteHistoryDir) {
                        data.order[0].column = quoteHistoryColumn;
                        data.order[0].dir = quoteHistoryDir;
                        historyFlag = true;
                    }
                }
                $('.history-table-spin.spin').spin('show');
                $('.history-table-spin.spin-background').removeClass('d-none');
                data.katashiki = $('#history-table_filter').val();
                data.filterColumn = columnsData[quoteHistoryColumns[data.order[0].column]];
            },
            complete: function (data) {
                historyTableLoadingFlag = true;
            }
        },
        'createdRow': function (row, data, dataIndex) {
            $(row).find('td').attr('tabindex', 1);
            $(row).find('td').attr('tabindex', 1);
        },
        "columns": columnHistoryDataArr,
        "columnDefs": [
            { "orderable": false, "targets": [9] }
        ]
    });

    $("#history-table").parents('.dataTables_scrollBody').scroll(function (event) {
        if (this.scrollTop != 0 && (this.scrollTop + this.clientHeight) - document.getElementById('history-table').querySelector('tbody').clientHeight >= 0) {
            var currentLength = $('#history-table tbody').find('tr').length;
            if (currentLength == 1)
                return;

            if (historyTableLoadingFlag) {
                $('.history-table-spin.spin').spin('show');
                $('.history-table-spin.spin-background').removeClass('d-none');
                historyTableLoadingFlag = false;
                var info = $('#request-unrfq-table').find('tr.selected').data('rowInfo');
                if ($('#history-table_filter').val())
                    katashiki = $('#history-table_filter').val();
                else
                    katashiki = info ? info.katashiki : null;
                $.ajax({
                    url: getQuoteCustomerHistoryMoreUrl,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        currentLength: currentLength,
                        order: historyTable.context[0].oAjaxData.order,
                        katashiki: katashiki,
                        filterColumn: columnsData[quoteHistoryColumns[historyTable.context[0].oAjaxData.order[0].column]],
                    },
                    success: function (data) {
                        if (data.length != 0)
                            historyTableLoadingFlag = true;
                        $('.history-table-spin.spin').spin('hide');
                        $('.history-table-spin.spin-background').addClass('d-none');
                        insertMultiHistoryRows(data);
                    }
                });
            }
        }
    })

    $(document).on('hidden.bs.modal', '.modal', function () {
        $('#quote-table tbody').find('td:eq(1)').focus();
    })
}
