var quoteFromSupplierTable = null;
var historyTable = null;
var reqUnRFQFlag = false;
var quoteFlag = false;
var historyFlag = false;

var columnsRfqData = {
    "受付日": 'created_at',
    "顧客ID": 'customer_id',
    "受付番号": 0,
    "客先": 'customer.user_info.company_name',
    "担当": 'customer.representative',
    "メーカー": 'maker',
    "DC": 'dc',
    "型番": 'katashiki',
    "希望数量": 'count_aspiration',
    "希望単価": 'price_aspiration',
    "区分": 'kbn',
    "条件1": 'condition1',
    "条件2": 'condition2',
    "条件3": 'condition3',
    "キャンセル日付": 'cancel_date',
    "処理日付": 'solved_date',
};

var originalRfqKeyArr = Object.keys(columnsRfqData);

var columnRfqDataArr = [];
var columnRfqDefs = [];
$.each(rfqColumns, function (index, item) {
    if (columnsRfqData[item] == 0) {
        columnRfqDataArr.push({
            data: null,
            name: '受付番号',
            render: function (data, row) {
                return data.detail_id + ' - ' + data.child_index;
            }
        });
    } else {
        columnRfqDataArr.push({
            data: columnsRfqData[item], name: item
        });
    }

    columnRfqDefs.push({
        width: rfqWidths[index].replace('px', ''),
        targets: index,
    })
})

var reqUnRFQTable = $('#request-unrfq-table').DataTable({
    "processing": false,
    "serverSide": true,
    "searching": false,
    "lengthChange": false,
    "scrollY": "170px",
    "scrollX": true,
    "scrollCollapse": true,
    "paging": false,
    "bInfo": false,
    "autoWidth": false,
    "fixedColumns": true,
    "fixedHeader": {
        "header": true,
        "footer": true
    },
    "responsive": false,
    "scroller": true,
    'language': {
        "zeroRecords": "No data available in table",
        "loadingRecords": "&nbsp;",
        "processing": "読み込み中...",
        "search": ""
    },
    "drawCallback": function (settings) {
        $('.unrfq-table-spin.spin').spin('hide');
        $('.unrfq-table-spin.spin-background').addClass('d-none');
        if (reqUnRFQFlag) {
            var unRfqColumn = parseInt(localStorage.getItem('unRfqOrderColumn'));
            var unRfqDir = localStorage.getItem('unRfqOrderDir');
            if (unRfqDir) {
                reqUnRFQFlag = false;
                var parentDiv = $('#request-unrfq-table').parents('.dataTables_scroll').find('.dataTables_scrollHeadInner table');
                parentDiv.find('.sorting_desc').removeClass('sorting_desc').addClass('sorting');
                parentDiv.find('.sorting_asc').removeClass('sorting_asc').addClass('sorting');
                parentDiv.find('th:eq(' + unRfqColumn + ')').addClass('sorting_' + unRfqDir).removeClass('sorting');
            }
            if ($(':focus').parents('#search-area').length == 0)
                $('#request-unrfq-table tbody').find('td:eq(0)').focus();
        }
    },
    "ajax": {
        url: getRfqListUrl,
        type: 'POST',
        data: function (data) {
            if (reqUnRFQTable && reqUnRFQTable.hasOwnProperty('context')) {
                localStorage.setItem('unRfqOrderColumn', data.order[0].column);
                localStorage.setItem('unRfqOrderDir', data.order[0].dir);
                reqUnRFQFlag = false;
            } else {
                var unRfqColumn = parseInt(localStorage.getItem('unRfqOrderColumn'));
                var unRfqDir = localStorage.getItem('unRfqOrderDir');
                if (unRfqDir) {
                    data.order[0].column = unRfqColumn;
                    data.order[0].dir = unRfqDir;
                    reqUnRFQFlag = true;
                }
            }
            $('.unrfq-table-spin.spin').spin('show');
            $('.unrfq-table-spin.spin-background').removeClass('d-none');
            data.customerName = $('#search-customer').val();
            data.customerId = $('#search-customer-id').val();
            data.receptionDate = $('#search-reception-date').val();
            data.modelNumber = $('#search-model-number').val();
            data.rfqRequestId = $('#search-reception-number').val();
            data.searchStatus = $('#search-status').val();
            data.filterColumn = columnsRfqData[rfqColumns[data.order[0].column]];
        },
        complete: function (data) {
            formatSupplierSection();
            rfqTableLoadingFlag = true;
            searchCustomerId = null;
            if (!quoteFromSupplierTable)
                quoteFromSupplierTableDraw();
            else
                quoteFromSupplierTable.draw();

            if (!historyTable)
                historyTableDraw();
        }
    },
    'createdRow': function (row, data, dataIndex) {
        $(row).data('rowInfo', data);
        if (dataIndex == 0) {
            $(row).addClass('tr-orange selected');
            drawCustomerInfo(data.customer);
            $("#message-from-customer").text(data.comment);
        }

        if (data.is_cancel == 1)
            $(row).find('td:eq(' + (rfqColumns.indexOf(originalRfqKeyArr[0])) + ')').addClass('tr-grey');

        if (data.is_solved == 0) {
            $(row).addClass('tr-yellow');
            $(row).data('status', 0);
        } else {
            $(row).data('status', 1);
        }

        $.each($(row).find('td'), function (index, item) {
            if ($(this).text() == 'null')
                $(this).text('');
            $(item).attr('tabindex', index + 1);
        });
    },
    "columns": columnRfqDataArr,
    "columnDefs": columnRfqDefs
})

var columnsQuoteData = {
    "RFQ依頼日": 'rfq_request.created_at',
    "ランク": 'vendor.user_info.rank',
    "仕入先": 'vendor.user_info.company_name',
    "メーカー": 'maker',
    "型番": 'katashiki',
    "買数量": 'quantity_buy',
    "買単位": 'unit_buy',
    "買通貨": 'type_money_buy',
    "買単価": 'unit_price_buy',
    "DC": 'dc',
    "地域": 'kbn2',
    "Rohs": 'rohs',
    "仕入納期": 'deadline_buy_vendor',
    "送料": 'fee_shipping',
    "仕入見積日": 'date_quote',
    "仕入先見積番号": 'code_quote',
};

var originalQuoteKeyArr = Object.keys(columnsQuoteData);

var columnQuoteDataArr = [];
var columnQuoteDefs = [];
$.each(rfqQuoteColumns, function (index, item) {
    if (item == "地域") {
        columnQuoteDataArr.push({
            data: null,
            name: "地域",
            render: function (row, data) {
                var str = row.kbn2 ? row.kbn2 : '国内';
                if(str.includes('国内'))
                {   
                    return '国内';
                } else if (str.includes('北米')) {
                    return '北米';
                } else if (str.includes('EU')) {
                    return 'EU';
                } else if (str.includes('中国')) {
                    return '中国';
                } else if (str.includes('海外')) {
                    return '海外';
                }
            }
        });
    } else {
        columnQuoteDataArr.push({
            data: columnsQuoteData[item], name: item
        });
    }
    
    columnQuoteDefs.push({
        width: rfqQuoteWidths[index].replace('px', ''),
        targets: index,
    })
})

function quoteFromSupplierTableDraw() {
    quoteFromSupplierTable = $("#quote-from-supplier-table").DataTable({
        "processing": false,
        "serverSide": true,
        "searching": false,
        "lengthChange": false,
        "scrollY": "170px",
        "scrollX": true,
        "scrollCollapse": true,
        "paging": false,
        "bInfo": false,
        "autoWidth": false,
        "fixedColumns": true,
        "fixedHeader": {
            "header": true,
            "footer": true
        },
        "responsive": false,
        'language': {
            "zeroRecords": "No data available in table",
            "loadingRecords": "&nbsp;",
            "processing": "読み込み中...",
            "search": ""
        },
        "drawCallback": function (settings) {
            $('.quote-from-supplier-table-spin.spin').spin('hide');
            $('.quote-from-supplier-table-spin.spin-background').addClass('d-none');

            if (quoteFlag) {
                var quoteColumn = parseInt(localStorage.getItem('quoteColumn'));
                var quoteDir = localStorage.getItem('quoteDir');
                if (quoteColumn && quoteDir) {
                    quoteFlag = false;
                    var parentDiv = $("#quote-from-supplier-table").parents('.dataTables_scroll').find('.dataTables_scrollHeadInner table');
                    parentDiv.find('.sorting_desc').removeClass('sorting_desc').addClass('sorting');
                    parentDiv.find('.sorting_asc').removeClass('sorting_asc').addClass('sorting');
                    parentDiv.find('th:eq(' + quoteColumn + ')').addClass('sorting_' + quoteDir).removeClass('sorting');
                }
            }
        },
        "ajax": {
            url: getQuoteFromSupplierUrl,
            type: 'POST',
            // dataSrc: '',
            data: function (data) {
                $('.quote-from-supplier-table-spin.spin').spin('show');
                $('.quote-from-supplier-table-spin.spin-background').removeClass('d-none');
                var info = $('#request-unrfq-table').find('tr.selected').data('rowInfo');
                if (quoteFromSupplierTable && quoteFromSupplierTable.hasOwnProperty('context')) {
                    localStorage.setItem('quoteColumn', data.order[0].column);
                    localStorage.setItem('quoteDir', data.order[0].dir);
                } else {
                    var quoteColumn = parseInt(localStorage.getItem('quoteColumn'));
                    var quoteDir = localStorage.getItem('quoteDir');
                    if (quoteColumn && quoteDir) {
                        data.order[0].column = quoteColumn;
                        data.order[0].dir = quoteDir;
                        quoteFlag = true;
                    }
                }
                data.requestId = info ? info.id : null;
                data.filterColumn = columnsQuoteData[rfqQuoteColumns[data.order[0].column]];
            }
        },
        'createdRow': function (row, data, dataIndex) {
            $(row).data('rowInfo', data);
            if (dataIndex == 0) {
                $(row).addClass('tr-orange selected');
                if (data.messages[0])
                    $('.message-box').val(data.messages[0].content);
                else
                    $('.message-box').val('');
                if (data.vendor && data.vendor.user_info) {
                    $('#supplier-info').data('supplier', data.vendor);
                    drawSupplierInfo(data.vendor);
                }
            }

            $(row).find('td').attr('tabindex', 1);

            if (data.is_send_est == 1)
                $(row).find('td:eq(' + (rfqQuoteColumns.indexOf(originalQuoteKeyArr[2])) + ')').css('background', 'rgb(188, 247, 255)');

            if (data.is_sendmail == 1)
                $(row).find('td:eq(' + (rfqQuoteColumns.indexOf(originalQuoteKeyArr[0])) + ')').addClass('tr-orange');
        },
        columns: columnQuoteDataArr,
        columnDefs: columnQuoteDefs,
        order: [[4, "asc"]],
    });

    $("#quote-from-supplier-table").parents('.dataTables_scrollBody').scroll(function (event) {
        if (this.scrollTop != 0 && (this.scrollTop + this.clientHeight) - document.getElementById('quote-from-supplier-table').querySelector('tbody').clientHeight > -1) {
            var currentLength = $('#quote-from-supplier-table tbody').find('tr').length;
            if (currentLength == 1)
                return;

            if (quoteSupplierTableLoadingFlag) {
                $('.quote-from-supplier-table-spin.spin').spin('show');
                $('.quote-from-supplier-table-spin.spin-background').removeClass('d-none');
                quoteSupplierTableLoadingFlag = false;
                var info = $('#request-unrfq-table').find('tr.selected').data('rowInfo');
                var requestId = info ? info.id : null;
                $.ajax({
                    url: getQuoteFromSupplierMoreUrl,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        currentLength: currentLength,
                        order: quoteFromSupplierTable.context[0].oAjaxData.order,
                        requestId: requestId,
                        filterColumn: columnsQuoteData[rfqQuoteColumns[quoteFromSupplierTable.context[0].oAjaxData.order[0].column]]
                    },
                    success: function (data) {
                        if (data.length != 0)
                            quoteSupplierTableLoadingFlag = true;
                        $('.quote-from-supplier-table-spin.spin').spin('hide');
                        $('.quote-from-supplier-table-spin.spin-background').addClass('d-none');
                        insertMultiQuoteSupplierRows(data);
                    }
                });
            }
        }
    })
}

var columnsHistoryData = {
    "見積日": 'date_quote',
    "ランク": 'vendor.user_info.rank',
    "顧客": 'rfq_request.customer.user_info.company_name',
    "仕入先": 'vendor.user_info.company_name',
    "メーカー": 'maker',
    "型番": 'katashiki',
    "買数量": 'quantity_buy',
    "買単位": 'unit_buy',
    "買通貨": 'type_money_buy',
    "買単価": 'unit_price_buy',
    "売単価": 0,
    "DC": 'dc',
    "仕入納期": 'deadline_buy_vendor',
    "Rohs": 'rohs',
    "受注日": 1
};
var columnHistoryDataArr = [];
var columnHistoryDefs = [{ "searchable": false, "targets": [0, 1, 2, 3, 5, 6, 7, 8, 9, 10, 11] }];
$.each(rfqHistoryColumns, function (index, item) {
    if (columnsHistoryData[item] == 0) {
        columnHistoryDataArr.push({
            data: null,
            name: "売単価",
            render: function (row, data) {
                if (row.quote_customer)
                    return row.quote_customer.unit_price_sell;
                else
                    return '';
            }
        });
    } else if (columnsHistoryData[item] == 1) {
        columnHistoryDataArr.push({
            data: null,
            name: "受注日",
            render: function (row, data) {
                if (row.quote_customer && row.quote_customer.order_detail && row.quote_customer.order_detail.order_header) {
                    var date = new Date(row.quote_customer.order_detail.order_header.receive_order_date);
                    return date;
                } else
                    return '';
            }
        });
    } else {
        columnHistoryDataArr.push({
            data: columnsHistoryData[item], name: item
        });
    }
    columnHistoryDefs.push({
        width: rfqHistoryWidths[index].replace('px', ''),
        targets: index,
    })
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
        "fixedColumns": true,
        "fixedHeader": {
            "header": true,
            "footer": true
        },
        "responsive": false,
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
                var historyColumn = parseInt(localStorage.getItem('historyColumn'));
                var historyDir = localStorage.getItem('historyDir');
                if (historyColumn && historyDir) {
                    historyFlag = false;
                    var parentDiv = $('#history-table').parents('.dataTables_scroll').find('.dataTables_scrollHeadInner table');
                    parentDiv.find('.sorting_desc').removeClass('sorting_desc').addClass('sorting');
                    parentDiv.find('.sorting_asc').removeClass('sorting_asc').addClass('sorting');
                    parentDiv.find('th:eq(' + historyColumn + ')').addClass('sorting_' + historyDir).removeClass('sorting');
                }
            }
        },
        "ajax": {
            url: getHistoryUrl,
            type: 'POST',
            data: function (data) {
                $('.history-table-spin.spin').spin('show');
                $('.history-table-spin.spin-background').removeClass('d-none');
                if (historyTable && historyTable.hasOwnProperty('context')) {
                    localStorage.setItem('historyColumn', data.order[0].column);
                    localStorage.setItem('historyDir', data.order[0].dir);
                } else {
                    var historyColumn = parseInt(localStorage.getItem('historyColumn'));
                    var historyDir = localStorage.getItem('historyDir');
                    if (historyColumn && historyDir) {
                        data.order[0].column = historyColumn;
                        data.order[0].dir = historyDir;
                        historyFlag = true;
                    }
                }
                if ($('#history-table_filter').val())
                    data.katashiki = $('#history-table_filter').val();
                else
                    data.katashiki = hitoryModelNumber;
                data.filterColumn = columnsHistoryData[rfqHistoryColumns[data.order[0].column]];
            },
            complete: function (data) {
                historyTableLoadingFlag = true;
            }
        },
        'createdRow': function (row, data, dataIndex) {
            $(row).find('td').attr('tabindex', 1);
        },
        "columns": columnHistoryDataArr,
        "columnDefs": columnHistoryDefs,
        order: [[3, "asc"]],
    });

    $("#history-table").parents('.dataTables_scrollBody').scroll(function (event) {
        if (this.scrollTop != 0 && (this.scrollTop + this.clientHeight) - document.getElementById('history-table').querySelector('tbody').clientHeight > -1) {
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
                    url: getHistoryMoreUrl,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        currentLength: currentLength,
                        order: historyTable.context[0].oAjaxData.order,
                        katashiki: katashiki,
                        filterColumn: columnsHistoryData[rfqHistoryColumns[historyTable.context[0].oAjaxData.order[0].column]],
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
}

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
