@extends('layouts.page')

@section('title', 'shipment')

@section('header-container')
    <div class="row">
        @include('admin/shipment/partials/search_area')
        @include('admin/shipment/partials/actions')
    </div>
@endsection

@inject('table_config', 'App\Models\TableConfig')
@inject('template_info', 'App\Models\TemplateInfo')
@inject('header_quarter', 'App\Models\HeaderQuarter')
@inject('alert', 'App\Models\Alert')
@php
$shipment_info = $table_config->where('table_name', $table_config::$names[8])->first();
$shipment_columns = json_decode($shipment_info->column_names);
$shipment_widths = json_decode($shipment_info->column_info);

$today = date_create()->format('Y-m-d');
$notification = $alert
    ->whereDate('start_date', '<', $today)
    ->whereDate('end_date', '>', $today)
    ->orderBy('created_at')
    ->first();

$shipping_template = $template_info->where('template_index', '=', $template_info::$template_type['Shipping email'])->first();
$header_quarter_jp = $header_quarter->where('type', '=', $header_quarter::$language_type['JP'])->first();
@endphp

@section('table-container')
    @include('admin/shipment/partials/shipment_table')
@endsection

@section('other-container')
    @include('admin/shipment/modals/actual_slip')
    @include('admin/shipment/modals/send_mail_to_customer')
    @include('admin/shipment/modals/excel_form')
@endsection

@section('custom_script')
    <script>
        var shipmentColumns = @json($shipment_columns);
        var shipmentWidth = @json($shipment_widths);

        var shippingTemplate = @json($shipping_template);
        var headerQuarterJP = @json($header_quarter_jp);
        var notification = @json($notification);

        var editableIndexs = [
            shipmentColumns.indexOf("@lang('Sales quantity')") + 1,
            shipmentColumns.indexOf("@lang('Selling unit price')") + 1,
            shipmentColumns.indexOf("@lang('Delivery time')") + 1,
            shipmentColumns.indexOf("OutTR#") + 1,
            shipmentColumns.indexOf("@lang('Shipping')") + 1,
            shipmentColumns.indexOf("@lang('Cash on delivery fee')") + 1,
        ];
    </script>
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/adapters/jquery.js') }}"></script>

    <script src="{{ asset('js/admin/shipment/functions.js') }}"></script>
    <script src="{{ asset('js/admin/shipment/datatables.js') }}"></script>
    <script src="{{ asset('js/admin/shipment/shortkey.js') }}"></script>
    <script>
        $(function() {
            loadCustomerInfoList();
            $('#customer-mail-content').ckeditor();

            //search area
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

            $(document).on('keyup change',
                '#search-customer, #search-invoice-number, #search-id, #search-maker, #search-billing-number, #search-status, #search-model',
                function() {
                    shipmentTable.draw();
                })

            $('#search-ship-date').datepicker({
                format: 'yyyy-mm-dd',
                inline: false,
            }).keydown(function (e) {
                datepickerKeyDownHandler($(this), e);
            });

            $(document).on('keypress', 'input#search-ship-date', function(e) {
                useList = '0123456789-';
                if (useList.search(e.key) == -1) {
                    return false;
                }
            })

            $(document).on('blur', '#search-ship-date', function(e) {
                var dateString = $(this).val();
                if (dateString == undefined || dateString == '' || !dateString) {
                    shipmentTable.draw();
                    return;
                }

                var formatCheck = validateDate(dateString);
                if (formatCheck)
                    shipmentTable.draw();
                else
                    toastr.warning('無効デートです。');
            })

            $('#search-area-clear').click(function() {
                $('#search-area').find('input').val('');
                $('#search-area').find('select').val('1');
                shipmentTable.draw();
            })

            $(document).on('click', 'th .all-shipment-check', function() {
                if ($(this).prop('checked'))
                    $('.shipment-check').prop('checked', true);
                else
                    $('.shipment-check').prop('checked', false);
            })

            $(document).on('click', '#shipment-table tbody tr', function() {
                if ($(this).hasClass('shipment-edit-tr') || $(this).find('td.dataTables_empty').length > 0)
                    return;

                if ($("#shipment-table").find('.shipment-edit-tr').length != 0) {
                    autoSaveShipmentData();
                    return;
                }
                $('#shipment-table').find('tr').removeClass('tr-orange selected');
                $(this).toggleClass('tr-orange').addClass('selected');
                updatedByChangedShipmentTable();
            })

            $(document).on('dblclick', '#shipment-table tr', function() {
                if ($(this).hasClass('shipment-edit-tr') || $(this).find('td.dataTables_empty').length > 0)
                    return;

                if ($("#shipment-table").find('.shipment-edit-tr').length != 0) {
                    autoSaveShipmentData();
                    // return;
                }

                var target = $(this).addClass('shipment-edit-tr');
                var targetData = target.data('rowInfo');

                var editableData = [targetData.sale_qty, targetData.sale_cost, targetData.import_goods
                    .export_time, targetData.import_goods.out_tr, targetData.order_header.fee_shipping,
                    targetData.order_header.fee_daibiki
                ];

                $.each(editableIndexs, function(index, item) {
                    if (index == 2) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<input type="text" class="form-control form-control-sm shipment-date-picker" value="' +
                            editableData[index] + '">');
                    } else {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<input type="text" class="form-control form-control-sm input-check-number" value="' +
                            editableData[index] + '">');
                    }
                });

                $('.shipment-date-picker').datepicker({
                    format: 'yyyy-mm-dd',
                    inline: false,
                }).keydown(function (e) {
                    datepickerKeyDownHandler($(this), e);
                });

                target.find('input:not(:checkbox):eq(0)').focus();
                $.each($('#shipment-table .shipment-edit-tr').find('td input'), function(index, elem) {
                    if ($(elem).val() == 'null')
                        $(elem).val('');
                })
            })

            $(document).on('blur', '.shipment-edit-tr input', function(e) {

                if ($(e.target).hasClass('shipment-date-picker'))
                    return;

                if (!e.relatedTarget || e.relatedTarget.tagName.toLowerCase() == 'table') {
                    autoSaveShipmentData();
                }
            })

            $('#actual-product-slip-btn').click(function() {

                var selectedIds = checkSelectRow();
                if (selectedIds.importGoodsIds.length < 1) {
                    toastr.warning('現品票を印刷する行を選択してください。');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.stock.actual_slip') }}",
                    method: 'POST',
                    data: {
                        ids: selectedIds.importGoodsIds,
                    },
                    success: function(result) {
                        var htmlPDF = '<embed type="application/pdf" src="/storage/pdf/' +
                            result + '"';
                        htmlPDF += 'id="pdfDocument" width="100%" height="500"> </embed>';
                        $('#actual-slip-pdf-modal').find('.modal-body').html(htmlPDF);
                        $('#actual-slip-pdf-modal').modal('show');
                    }
                });
            });

            $('#envelope-printing-btn').click(function() {
                var possibleIds = [];
                var sendAddIds = [];
                var selectedCount = 0;

                $.each($('.shipment-check:checked'), function(index, item) {
                    selectedCount++;
                    var tr = $(item).parents('tr');
                    var trData = tr.data('rowInfo');
                    if (trData.request_address_id != trData.send_address_id) {
                        possibleIds.push(tr.data('importGoodsId'));
                        sendAddIds.push(trData.send_address_id);
                    }
                });

                if (possibleIds.length != selectedCount) {
                    toastr.warning('この処理は請求先と納品先が異なる明細しか対応しません。');
                    return;
                }

                if (selectedCount < 1) {
                    toastr.warning('封筒を印刷する行を選択してください。');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.shipment.envelope') }}",
                    method: 'POST',
                    data: {
                        ids: possibleIds,
                        sendIds: sendAddIds
                    },
                    success: function(result) {
                        var htmlPDF = '<embed type="application/pdf" src="/storage/pdf/' +
                            result + '"';
                        htmlPDF += 'id="pdfDocument" width="100%" height="500"> </embed>';
                        $('#actual-slip-pdf-modal').find('.modal-body').html(htmlPDF);
                        $('#actual-slip-pdf-modal').modal('show');
                    }
                });
            })

            $('#voucher-printing-btn').click(function() {
                var checkedIds = checkSelectRow();
                var importGoodsIdsByOrder = {};
                var orderHeaderIds = checkedIds.orderHeaderIds;
                if (checkedIds.importGoodsIds.length < 1) {
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                    return;
                }
                $.each(checkedIds.importGoodsIds, function(index, item) {
                    if (importGoodsIdsByOrder[orderHeaderIds[index]])
                        importGoodsIdsByOrder[orderHeaderIds[index]].push(item);
                    else
                        importGoodsIdsByOrder[orderHeaderIds[index]] = [item];
                })

                $.ajax({
                    url: "{{ route('admin.shipment.voucher') }}",
                    method: 'POST',
                    data: {
                        importGoodsIds: importGoodsIdsByOrder
                    },
                    success: function(result) {
                        var htmlPDF = '<embed type="application/pdf" src="/storage/pdf/' +
                            result + '"';
                        htmlPDF += 'id="pdfDocument" width="100%" height="500"> </embed>';
                        $('#actual-slip-pdf-modal').find('.modal-body').html(htmlPDF);
                        $('#actual-slip-pdf-modal').modal('show');
                    }
                });
            })

            $('#status-change-btn').click(function() {
                var checkedIds = checkSelectRow();
                if (checkedIds.importGoodsIds.length < 1) {
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.shipment.change_status') }}",
                    method: 'POST',
                    data: {
                        importGoodsIds: checkedIds.importGoodsIds,
                        orderIds: checkedIds.selectedIds,
                    },
                    success: function(result) {
                        if (result == 'processed')
                            toastr.success('処理済みにしました。');
                        else
                            toastr.success('処理済みを解除しました。');

                        shipmentTable.draw();
                    }
                });
            })

            $('#shipment-btn').click(function() {
                var checkedIds = checkSelectRow();
                if (checkedIds.importGoodsIds.length < 1) {
                    toastr.warning('出荷メールを送信する行を選択してください。');
                    return;
                }

                if (checkedIds.importGoodsIds.length > 1) {
                    toastr.warning('同時に複数明細の伝票印刷できません。');
                    return;
                }

                var targetData = $('#shipment-table tbody').find('tr:eq(' + checkedIds.indexs[0] + ')')
                    .data('rowInfo');
                var totalMoney = 0;
                var feeDaibiki = 0;
                var typeMoney = '';
                $.each(checkedIds.indexs, function(index, item) {
                    var itemData = $('#shipment-table tbody').find('tr:eq(' + item + ')').data(
                        'rowInfo');
                    if (itemData.order_header.type_money == 'JPY') {
                        typeMoney = '円';
                    } else if (itemData.order_header.type_money == 'USD') {
                        typeMoney = '$';
                    } else if (itemData.order_header.type_money == 'EUR') {
                        typeMoney = '€';
                    } else {
                        typeMoney = itemData.order_header.type_money;
                    }
                    totalMoney += itemData.sale_money;
                })

                var totalTax = totalMoney + targetData.order_header.fee_daibiki + targetData.order_header
                    .fee_shipping;
                var totalWithoutTax =
                    `{{ format_number(`+totalTax+`, `+itemData.order_header.type_money+`) }}`;
                var tax =
                    `{{ format_number(`+parseInt(totalTax)*targetData.tax.tax+`, `+itemData.order_header.type_money+`) }}`;
                var totalWithTax =
                    `{{ format_number(`+(parseInt(totalWithoutTax)+parseInt(tax))+`, `+itemData.order_header.type_money+`) }}`;

                var mailData = [
                    targetData.quote_customer.customer.user_info.company_name,
                    targetData.quote_customer.customer.representative,
                    (notification) ? notification.message : '',
                    targetData.import_goods.export_date,
                    targetData.import_goods.katashiki,
                    targetData.ship_quantity, targetData.unit_ship,
                    targetData.price_ship,
                    targetData.maker,
                    "《計算結果》",
                    targetData.order_header.fee_shipping,
                    targetData.order_header.fee_daibiki,
                    totalTax,
                    tax,
                    totalWithTax,
                    '',
                    targetData.quote_customer.customer.user_info.deliver_address[0].comp_type,
                    targetData.quote_customer.customer.user_info.deliver_address[0].part_name,
                    targetData.quote_customer.customer.user_info.deliver_address[0].customer_name,
                    targetData.quote_customer.customer.user_info.deliver_address[0].zip,
                    targetData.quote_customer.customer.user_info.deliver_address[0].address1,
                    targetData.quote_customer.customer.user_info.deliver_address[0].address2,
                    targetData.quote_customer.customer.user_info.deliver_address[0].address3,
                    targetData.quote_customer.customer.user_info.deliver_address[0].address4,
                    targetData.quote_customer.customer.user_info.deliver_address[0].tel,
                    targetData.quote_customer.customer.user_info.billing_address[0].comp_type,
                    targetData.quote_customer.customer.user_info.billing_address[0].part_name,
                    targetData.quote_customer.customer.user_info.billing_address[0].customer_name,
                    targetData.quote_customer.customer.user_info.billing_address[0].zip,
                    targetData.quote_customer.customer.user_info.billing_address[0].address1,
                    targetData.quote_customer.customer.user_info.billing_address[0].address2,
                    targetData.quote_customer.customer.user_info.billing_address[0].address3,
                    targetData.quote_customer.customer.user_info.billing_address[0].address4,
                    targetData.quote_customer.customer.user_info.billing_address[0].tel,
                    headerQuarterJP.company_name + '</br>' + headerQuarterJP.tel + '</br>' +
                    headerQuarterJP.address,
                    typeMoney
                ];

                var params = JSON.parse(shippingTemplate.template_params);
                var emailText = JSON.parse(shippingTemplate.template_content);
                $.each(params, function(index, item) {
                    emailText = emailText.replace(item, mailData[index]);
                });

                $('#customer-mail-content').val(emailText);
                $('#shipment-customer-email').val(targetData.quote_customer.customer.user_info.email1);
                $('#send-mail-to-customer-modal').modal('show');
                $('#shipment-customer-btn').data('orderIds', checkedIds.ids);
                $('#cke_1_top').remove();
            })

            $('#shipment-customer-btn').click(function() {
                var mailContent = $('#customer-mail-content').val();
                var idList = $(this).data('orderIds');
                if (mailContent == '') {
                    toastr.warning('内容は入力必須です。');
                    return;
                }
                $.ajax({
                    url: "{{ route('admin.mail.send_shipment_customer') }}",
                    method: 'POST',
                    data: {
                        content: JSON.stringify(mailContent),
                        email: $('#shipment-customer-email').val(),
                        idList: idList,
                    },
                    success: function(result) {
                        toastr.success('出荷メールを顧客に送信しました。');
                        $('#send-mail-to-customer-modal').modal('hide');
                        shipmentTable.draw();
                    }
                });
            })

            $('#update-fee-btn').click(function() {
                var checkedIds = checkSelectRow();
                if (checkedIds.importGoodsIds.length < 1) {
                    toastr.warning('出荷メールを送信する行を選択してください。');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.shipment.update_fee') }}",
                    method: 'POST',
                    data: {
                        idList: checkedIds.importGoodsIds
                    },
                    success: function(result) {
                        toastr.success('送料・代引き手数料を更新しました。');
                    }
                });
            })

            $('#export-excel-btn').click(function() {
                var checkedIds = checkSelectRow();
                if (checkedIds.importGoodsIds.length < 1) {
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                    return;
                }
                $('#excel_import_ids').val(checkedIds.importGoodsIds);
                $('#excel_form').submit();
            })

            $('#import-excel-btn').click(function() {
                $('#import-excel-browser').click();
            })

            $('#import-excel-browser').change(function() {
                if (this.files[0].type != "application/vnd.ms-excel" && this.files[0].type !=
                    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                    toastr.warning('ファイルの形式は必ずエクセルの形式になります。');
                    return;
                }
                if (this.files && this.files[0]) {
                    var excelName = document.getElementById('import-excel-browser');
                    var uploadFile = new FormData();
                    uploadFile.append("file", excelName.files[0]);

                    $.ajax({
                        url: "{{ route('admin.shipment.import_excel') }}",
                        contentType: false,
                        processData: false,
                        data: uploadFile,
                        type: 'post',
                        cache: false,
                        enctype: "multipart/form-data",
                        success: function(data) {
                            toastr.success('出荷情報を保存しました。');
                        }
                    });
                }
            })

            $("#shipment-table").parents('.dataTables_scrollBody').scroll(function(event) {
                if (this.scrollTop != 0 && (this.scrollTop + this.clientHeight) - document.getElementById(
                        'shipment-table').querySelector('tbody').clientHeight > -1) {
                    var currentLength = $('#shipment-table tbody').find('tr').length;
                    if (currentLength == 1)
                        return;

                    if (shipmentTableLoadingFlag) {
                        $('.shipment-table-spin.spin').spin('show');
                        $('.shipment-table-spin.spin-background').removeClass('d-none');
                        shipmentTableLoadingFlag = false;
                        $.ajax({
                            url: '{{ route('admin.shipment.get_more_list') }}',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                currentLength: currentLength,
                                customerName: $('#search-customer').val(),
                                invoiceNumber: $('#search-invoice-number').val(),
                                shipDate: $('#search-ship-date').val(),
                                id: $('#search-id').val(),
                                maker: $('#search-maker').val(),
                                billingNumber: $('#search-biling-number').val(),
                                model: $('#search-model').val(),
                                status: $('#search-status').val(),
                                filterColumn: columnsData[shipmentColumns[shipmentTable.context[0]
                                    .oAjaxData.order[0].column - 1]],
                            },
                            success: function(data) {
                                $('.shipment-table-spin.spin').spin('hide');
                                $('.shipment-table-spin.spin-background').addClass('d-none');
                                insertMultiShipmentRows(data);
                                if (data.length != 0)
                                    shipmentTableLoadingFlag = true;
                            }
                        });
                    }
                }
            })

            $('textarea.message-box').focusout(function() {
                var target = $('#shipment-table').find('tr.selected');
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

            $(document).on('hidden.bs.modal', '.modal', function() {
                $('#shipment-table tbody').find('td:eq(1)').focus();
            })

            $(document).on('blur', '#shipment-table tr', function() {
                if ($(this).hasClass('direct-edit')) {
                    $(this).removeClass('direct-edit');
                    var targetData = getShipmentRowByIndex($(this).index() + 1);

                    $.ajax({
                        url: autoShipmentOrderUrl,
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
