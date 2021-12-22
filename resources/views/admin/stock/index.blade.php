@extends('layouts.page')

@section('title', 'In stock')

@section('header-container')
    <div class="row">
        @include('admin/stock/partials/search_area')
        <div class="col-7">
            <div class="row">
                @include('admin/stock/partials/supplier_info')
                @include('admin/stock/partials/actions')
            </div>
        </div>
    </div>
@endsection

@inject('table_config', 'App\Models\TableConfig')
@php
$stock_info = $table_config->where('table_name', $table_config::$names[7])->first();
$stock_columns = json_decode($stock_info->column_names);
$stock_widths = json_decode($stock_info->column_info);
@endphp

@section('table-container')
    @include('admin/stock/partials/stock_table')
@endsection

@section('other-container')
    @include('admin/stock/modals/actual_slip')
@endsection

@section('custom_script')
    <script>
        var stockColumns = @json($stock_columns);
        var stockWidth = @json($stock_widths);

        var editableIndexs = [
            stockColumns.indexOf("@lang('Buy quantity')") + 1,
            stockColumns.indexOf("@lang('Buying currency')") + 1,
            stockColumns.indexOf("@lang('Purchase unit price')") + 1,
            stockColumns.indexOf("@lang('Arrival day')") + 1,
            stockColumns.indexOf("@lang('Number of arrivals')") + 1,
            stockColumns.indexOf("@lang('Arrival unit price')") + 1,
            stockColumns.indexOf("CoO") + 1,
            stockColumns.indexOf("InTR#") + 1,
        ];
    </script>
    <script src="{{ asset('js/admin/stock/functions.js') }}"></script>
    <script src="{{ asset('js/admin/stock/datatables.js') }}"></script>
    <script src="{{ asset('js/admin/stock/shortkey.js') }}"></script>
    <script>
        $(function() {

            getShipAndTransportlist();
            loadCustomerInfoList();
            loadSupplierInfoList();
            loadCommonList();

            $('#search-customer').autoComplete({
                resolver: 'custom',
                events: {
                    search: function(qry, callback) {
                        callback(customerInfoList.filter(function(item) {
                            const matcher = new RegExp('^' + qry, 'i');
                            return matcher.test(item.user_info.company_name) ||
                                matcher.test(item.user_info.company_name_kana)
                        }));
                    }
                },
                formatResult: function(item) {
                    var representative = '';
                    if (item.representative)
                        representative = item.representative;

                    return {
                        value: item.id,
                        text: item.user_info.company_name,
                        html: [
                            `仕入先 : ${item.user_info.company_name}`,
                            `<br> 担当 : ${representative}`
                        ]
                    };
                },
                noResultsText: '',
                minLength: 1
            });

            $('#search-supplier-name').autoComplete({
                resolver: 'custom',
                events: {
                    search: function(qry, callback) {
                        callback(supplierList.filter(function(item) {
                            const matcher = new RegExp('^' + qry, 'i');
                            return matcher.test(item.user_info.company_name) ||
                                matcher.test(item.user_info.company_name_kana)
                        }));
                    }
                },
                formatResult: function(item) {
                    var comanyKana = '';
                    if (item.user_info.company_name_kana)
                        comanyKana = item.user_info.company_name_kana;
                    return {
                        value: item.id,
                        text: item.user_info.company_name,
                        html: [
                            `仕入先 : ${item.user_info.company_name}`,
                            `<br> 担当 : ${comanyKana}`
                        ]
                    };
                },
                noResultsText: '',
                minLength: 1
            })

            $(document).on('keyup change',
                '#search-order-number, #search-customer, #search-supplier-name, #search-model-number, #search-maker, #search-ship-order-number, #search-status',
                function() {
                    stockTable.draw();
                })

            $('#search-area-clear').click(function() {
                $('#search-area').find('input').val('');
                $('#search-area').find('select').val('1');
                stockTable.draw();
            })

            //datatable
            $(document).on('click', 'th .all-stock-check', function() {
                if ($(this).prop('checked'))
                    $('.stock-check').prop('checked', true);
                else
                    $('.stock-check').prop('checked', false);
            })

            $(document).on('click', '#stock-table tbody tr', function() {
                if ($(this).hasClass('stock-edit-tr') || $(this).find('td.dataTables_empty').length != 0)
                    return;
                if ($('#stock-table').find('.stock-edit-tr'))
                    autoSaveStockData();
                $('#stock-table').find('tr').removeClass('tr-orange selected');
                $(this).toggleClass('tr-orange').addClass('selected');
                updatedByChangedStockTable();
            })

            $(document).on('dblclick', '#stock-table tbody tr', function() {
                if ($(this).find('td.dataTables_empty').length != 0)
                    return;
                var target = $(this).addClass('stock-edit-tr');
                var targetData = target.data('rowInfo');

                var editableData = [
                    targetData.import_goods.ship_quantity, targetData.import_goods.type_money_ship,
                    targetData.import_goods.price_ship,
                    targetData.import_goods.import_date, targetData.import_goods.import_qty, targetData
                    .import_goods.import_unit_price,
                    targetData.import_goods.coo, targetData.import_goods.in_tr
                ];

                $.each(editableIndexs, function(index, item) {
                    if (index == 0) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<input type="text" class="form-control form-control-sm" value="' +
                            editableData[index] + '">');
                    } else if (index == 1) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<select class="form-control form-control-sm">' + rateOptionHtml +
                            '</select>');
                        target.find('td:eq(' + item + ') select').val(editableData[index]);
                    } else if (index == 3) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<input type="text" class="form-control form-control-sm stock-date-picker" value="' +
                            editableData[index] + '">');
                    } else if (index == 2 || index == 4 || index == 5) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<input type="text" class="form-control form-control-sm input-check-number" value="' +
                            editableData[index] + '">');
                    } else {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<input class="form-control form-control-sm" value="' +
                            editableData[index] + '">');
                    }
                });

                $.each($('#stock-table .stock-edit-tr').find('td input'), function(index, elem) {
                    if ($(elem).val() == 'null')
                        $(elem).val('');
                })

                target.find('td:eq(' + editableIndexs[0] + ') input').focus();

                $('.stock-date-picker').datepicker({
                    format: 'yyyy-mm-dd',
                    inline: false,
                }).keydown(function (e) {
                    datepickerKeyDownHandler($(this), e);
                });
            })

            $(document).on('blur', '.stock-edit-tr input, .stock-edit-tr textarea', function(e) {
                if ($(e.target).hasClass('stock-date-picker'))
                    return;

                if (!e.relatedTarget || e.relatedTarget.tagName.toLowerCase() == 'table') {
                    autoSaveStockData();
                }
            })

            $('#actual-product-slip-btn').click(function() {
                var selectedIds = [];
                var stockFlag = false;
                var checkedCount = 0;
                $.each($('.stock-check'), function(index, item) {
                    var tr = $(item).parents('tr');
                    var importQty = parseInt(tr.find('td:eq(18)').text());
                    if ($(item).prop("checked")) {
                        checkedCount++;
                        if (!isNaN(importQty) && importQty > 0)
                            selectedIds.push(tr.data('importGoodsId'));
                        else
                            stockFlag = true;
                    }
                })

                if (checkedCount > 0 && stockFlag) {
                    toastr.warning('入荷数が入力されてません。');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.stock.actual_slip') }}",
                    method: 'POST',
                    data: {
                        ids: selectedIds,
                    },
                    success: function(result) {
                        if (result == "success")
                            return;
                        var htmlPDF = '<embed type="application/pdf" src="/storage/pdf/' +
                            result + '"';
                        htmlPDF += 'id="pdfDocument" width="100%" height="500"> </embed>';
                        $('#actual-slip-pdf-modal').find('.modal-body').html(htmlPDF);
                        $('#actual-slip-pdf-modal').modal('show');
                    }
                });

            });

            $('#change-status-processed').click(function() {
                var checkedIds = checkSelectRow();
                var statusFlag = false;
                if (checkedIds.ids.length <= 0) {
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                    return;
                }

                $.each($('.stock-check'), function(index, item) {
                    var tr = $(item).parents('tr');
                    var trData = tr.data('rowInfo');
                    if ($(item).prop("checked") && trData.status_ship == 0 && (!trData.import_goods
                            .import_qty || trData.import_goods.import_qty < 1 || !trData
                            .import_goods.import_date))
                        statusFlag = true;
                })

                if (statusFlag) {
                    toastr.warning('入荷日と入荷数を入力してください');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.stock.change_status') }}",
                    method: 'POST',
                    data: {
                        ids: checkedIds.importGoodsIds,
                    },
                    success: function(result) {
                        if (result == 'processed')
                            toastr.success('処理済みにしました。');
                        else
                            toastr.success('処理済みを解除しました。');
                        stockTable.draw();
                    }
                });
            })

            $('#to-shipping-btn').click(function() {
                var dateFlag = false;
                var qtyFlag = false;
                var importIds = [];
                $.each($('.stock-check'), function(index, item) {
                    if ($(item).prop("checked")) {
                        var tr = $(item).parents('tr');
                        var trData = tr.data('rowInfo');
                        var importData = trData.import_goods.import_date;
                        var importQty = trData.import_goods.import_qty;
                        if (isNaN(importQty) || importQty < 0)
                            qtyFlag = true;

                        if (!importData || importData == '')
                            dateFlag = true;
                        importIds.push(tr.data('importGoodsId'));
                    }
                })

                if (importIds.length < 1) {
                    toastr.warning('更新を行う為に、行を選択して下さい');
                    return;
                }

                if (qtyFlag || dateFlag) {
                    toastr.warning('入荷数と入荷日を入力さしてから現品票を印刷してください。');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.stock.to_shipping') }}",
                    method: 'POST',
                    data: {
                        ids: importIds,
                    },
                    success: function(result) {
                        toastr.success('情報を出荷画面に送りました。');
                        stockTable.draw();
                    }
                });
            })

            $('#return-to-btn').click(function() {
                var supplierIds = [];
                $.each($('.stock-check'), function(index, item) {
                    if ($(item).prop("checked")) {
                        var tr = $(item).parents('tr');
                        var trData = tr.data('rowInfo');
                        var supplier_id = trData.supplier_id;
                        supplierIds.push(supplier_id);
                    }
                })

                if (supplierIds.length < 1) {
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.stock.return_to') }}",
                    method: 'POST',
                    data: {
                        ids: supplierIds,
                    },
                    success: function(result) {
                        toastr.success('返品処理を実行しました。');
                        stockTable.draw();
                    }
                });
            })

            $('#sold-out-btn').click(function() {
                var supplierIds = [];
                var importIds = [];
                $.each($('.stock-check'), function(index, item) {
                    if ($(item).prop("checked")) {
                        var tr = $(item).parents('tr');
                        var trData = tr.data('rowInfo');
                        supplierIds.push(trData.supplier_id);
                        importIds.push(tr.data('importGoodsId'));
                    }
                })

                if (supplierIds.length < 1) {
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.stock.sold_out') }}",
                    method: 'POST',
                    data: {
                        supplierIds: supplierIds,
                        importIds: importIds,
                    },
                    success: function(result) {
                        toastr.success('売切れ処理を実行しました。');
                        stockTable.draw();
                    }
                });
            })

            $('textarea.message-box').focusout(function() {
                var target = $('#stock-table').find('tr.selected');
                if (target.length == 0) {
                    $(this).val('');
                    return;
                } else
                    var targetData = target.data("rowInfo");
                var message = $(this).val();
                if (message || message != '') {
                    $.ajax({
                        url: "{{ route('admin.message.store') }}",
                        method: 'POST',
                        data: {
                            id: targetData.quote_customer.request_vendors.id,
                            message: message
                        },
                        success: function(result) {
                            targetData.quote_customer.request_vendors.messages = [result];
                            target.data("rowInfo", targetData);
                        }
                    });
                }
            });

            $("#stock-table").parents('.dataTables_scrollBody').scroll(function(event) {
                if (this.scrollTop != 0 && (this.scrollTop + this.clientHeight) - document.getElementById(
                        'stock-table').querySelector('tbody').clientHeight > -1) {
                    var currentLength = $('#stock-table tbody').find('tr').length;
                    if (currentLength == 1)
                        return;

                    if (stockTableLoadingFlag) {
                        $('.stock-table-spin.spin').spin('show');
                        $('.stock-table-spin.spin-background').removeClass('d-none');
                        stockTableLoadingFlag = false;
                        $.ajax({
                            url: '{{ route('admin.stock.get_more_list') }}',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                currentLength: currentLength,
                                order: stockTable.context[0].oAjaxData.order,
                                customerName: $('#search-customer').val(),
                                supplierName: $('#search-supplier-name').val(),
                                modelNumber: $('#search-model-number').val(),
                                maker: $('#search-maker').val(),
                                orderNumber: $('#search-order-number').val(),
                                shipOrderNumber: $('#search-ship-order-number').val(),
                                status: $('#search-status').val(),
                                filterColumn: columnsData[stockColumns[stockTable.context[0]
                                    .oAjaxData.order[0].column - 1]],
                            },
                            success: function(data) {
                                $('.stock-table-spin.spin').spin('hide');
                                $('.stock-table-spin.spin-background').addClass('d-none');
                                insertMultiStockOrderRows(data);
                                if (data.length != 0)
                                    stockTableLoadingFlag = true;
                            }
                        });
                    }
                }
            })

            $(document).on('hidden.bs.modal', '.modal', function() {
                $('#stock-table tbody').find('td:eq(1)').focus();
            })

            $(document).on('blur', '#stock-table tr', function() {
                if ($(this).hasClass('direct-edit')) {
                    $(this).removeClass('direct-edit');
                    var targetData = getStockRowByIndex($(this).index() + 1);

                    $.ajax({
                        url: autoStockOrderUrl,
                        method: 'POST',
                        data: targetData,
                        success: function(data) {},
                        error: function(xhr, status, error) {
                            var errors = xhr.responseJSON.errors;
                            toastr.error('正しく入力してください。');
                        },
                    });
                }
            })
        })
    </script>
@endsection
