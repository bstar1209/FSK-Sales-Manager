@extends('layouts.page')

@section('title', 'Order')

@section('custom_style')
    <link rel="stylesheet" href="//unpkg.com/bootstrap-select@1.12.4/dist/css/bootstrap-select.min.css" type="text/css" />
    <link rel="stylesheet" href="//unpkg.com/bootstrap-select-country@4.0.0/dist/css/bootstrap-select-country.min.css"
        type="text/css" />
@endsection

@section('header-container')
    <div class="row">
        @include('admin/ship_order/partials/search_area')
        <div class="col-7">
            <div class="row">
                @include('admin/ship_order/partials/supplier_info')
                @include('admin/ship_order/partials/actions')
            </div>
        </div>
    </div>
@endsection

@inject('table_config', 'App\Models\TableConfig')
@inject('template_info', 'App\Models\TemplateInfo')
@inject('header_quarter', 'App\Models\HeaderQuarter')
@inject('alert', 'App\Models\Alert')
@php
$ship_order_info = $table_config->where('table_name', $table_config::$names[6])->first();
$ship_order_columns = json_decode($ship_order_info->column_names);
$ship_order_widths = json_decode($ship_order_info->column_info);

$today = date_create()->format('Y-m-d');
$notification = $alert
    ->whereDate('start_date', '<', $today)
    ->whereDate('end_date', '>', $today)
    ->orderBy('created_at')
    ->first();

$order_templates_to_supplier_jp = $template_info->where('template_index', '=', $template_info::$template_type['Order mail to supplier jp'])->first();
$header_quarter_jp = $header_quarter->where('type', '=', $header_quarter::$language_type['JP'])->first();

$order_templates_to_supplier_en = $template_info->where('template_index', '=', $template_info::$template_type['Order mail to supplier en'])->first();
$header_quarter_en = $header_quarter->where('type', '=', $header_quarter::$language_type['EN'])->first();
@endphp

@section('table-container')
    @include('admin/ship_order/partials/ship_order_table')
@endsection

@section('other-container')
    @include('admin/modals/register_supplier')
    @include('admin/modals/add_new_payment_term')
    @include('admin/ship_order/modals/order_detail_change')
    @include('admin/ship_order/modals/send_mail_to_supplier')
    @include('admin/modals/update_customer_info')
    @include('admin/modals/billing_address')
    @include('admin/modals/confirm_message')
@endsection

@section('custom_script')
    <script src="//unpkg.com/bootstrap-select@1.12.4/dist/js/bootstrap-select.min.js"></script>
    <script src="//unpkg.com/bootstrap-select-country@4.0.0/dist/js/bootstrap-select-country.min.js"></script>
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/adapters/jquery.js') }}"></script>

    <script>
        var shipOrderColumns = @json($ship_order_columns);
        var shipOrderWidth = @json($ship_order_widths);

        var orderTemplateJP = @json($order_templates_to_supplier_jp);
        var headerQuarterJP = @json($header_quarter_jp);

        var orderTemplateEN = @json($order_templates_to_supplier_en);
        var headerQuarterEN = @json($header_quarter_en);
        var notification = @json($notification);

        var editableIndexs = [
            shipOrderColumns.indexOf("@lang('Buy quantity')") + 1,
            shipOrderColumns.indexOf("@lang('Buying currency')") + 1,
            shipOrderColumns.indexOf("@lang('Purchase unit price')") + 1,
            shipOrderColumns.indexOf("@lang('Buy unit price')") + 1,
            shipOrderColumns.indexOf("@lang('Ship order number')") + 1,
            shipOrderColumns.indexOf("@lang('Ship delivery date')") + 1,
            shipOrderColumns.indexOf("@lang('Supplier remarks')") + 1,
            shipOrderColumns.indexOf("Ship To") + 1,
            shipOrderColumns.indexOf("Ship By") + 1,
            shipOrderColumns.indexOf("@lang('Canceled supplier')") + 1,
        ];
    </script>

    <script src="{{ asset('js/admin/ship_order/functions.js') }}"></script>
    <script src="{{ asset('js/admin/ship_order/datatables.js') }}"></script>
    <script src="{{ asset('js/admin/ship_order/shortkey.js') }}"></script>
    <script>
        $(function() {
            getShipAndTransportlist();
            loadCustomerInfoList();
            loadSupplierInfoList();
            loadCommonList();

            $('#supplier-mail-content').ckeditor();

            //search
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
                    return {
                        value: item.id,
                        text: item.user_info.company_name,
                        html: [
                            `仕入先 : ${item.user_info.company_name}`,
                            `<br> 担当 : ${item.representative}`
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

                    var companyKana = '';
                    if (item.user_info.company_name_kana)
                        companyKana = item.user_info.company_name_kana;

                    return {
                        value: item.id,
                        text: item.user_info.company_name,
                        html: [
                            `仕入先 : ${item.user_info.company_name}`,
                            `<br> 担当 : ${companyKana}`
                        ]
                    };
                },
                noResultsText: '',
                minLength: 1
            })

            $('#search-ship-order-date, #search-order-date').datepicker({
                format: 'yyyy-mm-dd',
                inline: false,
            }).keydown(function(e) {
                datepickerKeyDownHandler($(this), e);
            });

            $(document).on('keypress', '#search-ship-order-date, #search-order-date', function(e) {
                useList = '0123456789-';
                if (useList.search(e.key) == -1) {
                    return false;
                }
            })

            $(document).on('blur', '#search-ship-order-date, #search-order-date', function(e) {
                var dateString = $(this).val();
                if (dateString == undefined || dateString == '' || !dateString) {
                    shipOrderTable.draw();
                    return;
                }
                var formatCheck = validateDate(dateString);
                if (formatCheck)
                    shipOrderTable.draw();
                else
                    toastr.warning('無効デートです。');
            })

            $(document).on('keyup change', '#search-customer, #search-supplier-name, #search-model-number, #search-maker, #search-order-number, #search-status, #search-order-number',
                function() {
                    shipOrderTable.draw();
                })

            $('#search-area-clear').click(function() {
                $('#search-area').find('input').val('');
                $('#search-area').find('select').val('1');
                shipOrderTable.draw();
            })

            //datatable
            $(document).on('click', 'th .all-ship-order-check', function() {
                if ($(this).prop('checked'))
                    $('.ship-order-check').prop('checked', true);
                else
                    $('.ship-order-check').prop('checked', false);
            })

            $(document).on('click', '#ship-order-table tbody tr', function() {
                if ($(this).hasClass('order-edit-tr') || $(this).find('td.dataTables_empty').length != 0)
                    return;

                if ($("#ship-order-table").find('.order-edit-tr').length != 0) {
                    autoSaveOrderData();
                    return;
                }

                $('#ship-order-table').find('tr').removeClass('tr-orange selected');
                $(this).toggleClass('tr-orange').addClass('selected');
                updatedByChangedShipOrderTable();
            })

            $(document).on('dblclick', '#ship-order-table tr', function() {
                var target = $(this).addClass('order-edit-tr');
                targetData = target.data('rowInfo');

                var editableData = [
                    targetData.ship_quantity, targetData.type_money_ship, targetData.unit_buy_ship,
                    targetData.price_ship,
                    targetData.code_send, targetData.import_date_plan, targetData.refer_vendor, '', '',
                    targetData.cancel_date_vendor
                ];

                $.each(editableIndexs, function(index, item) {
                    var shipTo = '',
                        transport = '';
                    if (targetData.ship_to_info) shipTo = targetData.ship_to_info.id;
                    if (targetData.transport) transport = targetData.transport.id;

                    if (index == 0 || index == 2 || index == 3) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<input type="text" class="form-control form-control-sm input-check-number" value="' +
                            editableData[index] + '">');
                    } else if (index == 4 || index == 6) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<input type="text" class="form-control form-control-sm" value="' +
                            editableData[index] + '">');
                    } else if (index == 5 || index == 9) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<input type="text" class="form-control form-control-sm ship-order-date-picker" value="' +
                            editableData[index] + '">');
                    } else if (index == 7) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<select class="form-control form-control-sm">' + shipOptionHtml +
                            '</select>');
                        target.find('td:eq(' + item + ') select').val(shipTo);
                    } else if (index == 8) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<select class="form-control form-control-sm">' +
                            transportOptionHtml + '</select>');
                        target.find('td:eq(' + item + ') select').val(transport);
                    } else if (index == 1) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<select class="form-control form-control-sm">' + rateOptionHtml +
                            '</select>');
                        target.find('td:eq(' + item + ') select').val(editableData[index]);
                    }
                });

                $.each(target.find('input'), function(index, elem) {
                    if ($(elem).val() == 'null')
                        $(elem).val('');
                })

                target.find('input:not(:checkbox):eq(0)').focus();

                $('.ship-order-date-picker').datepicker({
                    format: 'yyyy-mm-dd',
                    inline: false,
                }).keydown(function(e) {
                    datepickerKeyDownHandler($(this), e);
                });
            })

            $("#ship-order-table").parents('.dataTables_scrollBody').scroll(function(event) {
                if (this.scrollTop != 0 && (this.scrollTop + this.clientHeight) - document.getElementById(
                        'ship-order-table').querySelector('tbody').clientHeight > -1) {
                    var currentLength = $('#ship-order-table tbody').find('tr').length;
                    if (currentLength == 1)
                        return;

                    if (shipOrderTableLoadingFlag) {
                        $('.ship-order-table-spin.spin').spin('show');
                        $('.ship-order-table-spin.spin-background').removeClass('d-none');
                        shipOrderTableLoadingFlag = false;
                        $.ajax({
                            url: '{{ route('admin.ship_order.get_more_list') }}',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                currentLength: currentLength,
                                order: shipOrderTable.context[0].oAjaxData.order,
                                customerName: $('#search-customer').val(),
                                supplierName: $('#search-supplier-name').val(),
                                modelNumber: $('#search-model-number').val(),
                                maker: $('#search-maker').val(),
                                orderDate: $('#search-order-date').val(),
                                shipOrderDate: $('#search-ship-order-date').val(),
                                orderNumber: $('#search-order-number').val(),
                                status: $('#search-status').val(),
                                filterColumn: columnsData[shipOrderColumns[shipOrderTable.context[0]
                                    .oAjaxData.order[0].column - 1]],
                            },
                            success: function(data) {
                                $('.ship-order-table-spin.spin').spin('hide');
                                $('.ship-order-table-spin.spin-background').addClass('d-none');
                                insertMultiShipOrderRows(data);
                                if (data.length != 0)
                                    shipOrderTableLoadingFlag = true;
                            }
                        });
                    }
                }
            })

            $('#change-status-btn').click(function() {
                var checkedStatus = checkSelectRow();
                if (checkedStatus.ids.length == 0) {
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                    return;
                }
                $.ajax({
                    url: "{{ route('admin.ship_order.change_status') }}",
                    method: 'POST',
                    data: {
                        orderIds: checkedStatus.ids
                    },
                    success: function(data) {
                        shipOrderTable.draw();
                    }
                });
            })

            $('#return-to-order-btn').click(function() {
                var checkedStatus = checkSelectRow();
                if (checkedStatus.ids.length == 0) {
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                    return;
                }
                $.ajax({
                    url: "{{ route('admin.ship_order.return_to_order') }}",
                    method: 'POST',
                    data: {
                        orderIds: checkedStatus.ids
                    },
                    success: function(data) {
                        shipOrderTable.draw();
                    }
                });
            })

            $(document).on('blur', '.order-edit-tr input, .order-edit-tr textarea, .order-edit-tr select', function(
                e) {
                if ($(e.target).hasClass('ship-order-date-picker'))
                    return;

                if (!e.relatedTarget || e.relatedTarget.tagName.toLowerCase() == 'table') {
                    autoSaveOrderData();
                }
            })

            $('#supplier-register-btn').click(function() {
                $("#supplier-register-modal").modal('show');
            })

            $('#order-btn').click(function() {
                var selectedStatus = checkSelectRow();
                if (selectedStatus.ids.length <= 0) {
                    toastr.warning('注文情報が選択されていません。');
                    return;
                }
                var firstData = $('#ship-order-table tbody').find('tr:eq(' + selectedStatus.indexs[0] + ')')
                    .data('rowInfo');

                var shipQty = firstData.ship_quantity;
                var typeMoneyShip = firstData.type_money_ship;
                var unitBuyShip = firstData.unit_buy_ship;
                var priceShip = firstData.price_ship;
                var codeSend = firstData.code_send;
                var shipTo = firstData.ship_to;
                var deadlineSend = firstData.deadline_send;
                var shipBy = firstData.ship_by;
                var importDatePlan = firstData.import_date_plan;

                if (shipQty == '' && shipQty == null) {
                    toastr.warning('買数量は空白を設定してはいけない。');
                    return;
                }
                if (typeMoneyShip == '' && typeMoneyShip == null) {
                    toastr.warning('買通貨は空白を設定してはいけない。');
                    return;
                }
                if (unitBuyShip == '' && unitBuyShip == null) {
                    toastr.warning('買単価は空白を設定してはいけない。');
                    return;
                }
                if (priceShip == '' && priceShip == null) {
                    toastr.warning('買金額は空白を設定してはいけない。');
                    return;
                }
                if (codeSend == '' && codeSend == null) {
                    toastr.warning('発注番号は空白を設定してはいけない。');
                    return;
                }
                if (shipBy == '' && shipBy == null) {
                    toastr.warning('Ship Byは空白を設定してはいけない。');
                    return;
                }
                if (shipTo == '' && shipTo == null) {
                    toastr.warning('Ship Toは空白を設定してはいけない。');
                    return;
                }
                if (deadlineSend == '' && deadlineSend == null) {
                    toastr.warning('見積納期は空白を設定してはいけない。');
                    return;
                }

                if (importDatePlan == '' && importDatePlan == null) {
                    toastr.warning('納入日は空白を設定してはいけません。');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.ship_order.generate_order_pdf') }}",
                    method: 'POST',
                    data: {
                        id: firstData.id,
                    },
                    success: function(result) {
                        $('#supplier-order-pdf').attr("href", '/storage/pdf/' + result);
                        $('#supplier-order-pdf').data("pdf", result);
                    }
                });

                $("#send-mail-to-supplier-modal").modal('show');
                $('#supplier-mail-content').ckeditor();
                $('#cke_1_top').remove();
                $('#order-supplier-email').val(firstData.supplier.user_info.email1);
                $('#order-confirm-btn').data('orderId', firstData.id);

                var supplierCountry = firstData.supplier.user_info.address.country;
                var estimationCode = firstData.quote_customer.quote_code;
                var transportName = '';
                if (firstData.transport)
                    transportName = firstData.transport.name;

                if (supplierCountry == 'JP') {
                    $('#supplier-email-label').text('Eメール');
                    $('#supplier-order-content').text('コンテンツ');

                    var mailData = [
                        firstData.supplier.user_info.company_name,
                        firstData.supplier.representative,
                        (notification) ? notification.message : '',
                        firstData.katashiki,
                        firstData.ship_quantity,
                        firstData.maker,
                        firstData.import_date_plan,
                        firstData.quote_customer.dc,
                        firstData.quote_customer.rohs,
                        supplierCountry,
                        transportName,
                        headerQuarterJP.company_name + '</br>' + headerQuarterJP.tel + '</br>' +
                        headerQuarterJP.address,
                    ];
                    var params = JSON.parse(orderTemplateJP.template_params);
                    var emailText = JSON.parse(orderTemplateJP.template_content);
                    $.each(params, function(index, item) {
                        emailText = emailText.replace(item, mailData[index]);
                    });

                    $("#supplier-mail-content").val(emailText);
                    $("#order-confirm-btn").text("送信");
                } else {
                    $('#supplier-email-label').text('Email');
                    $('#supplier-order-content').text('Content');

                    var mailData = [
                        firstData.supplier.user_info.company_name,
                        firstData.katashiki,
                        firstData.ship_quantity,
                        firstData.maker,
                        firstData.import_date_plan,
                        firstData.quote_customer.dc,
                        firstData.quote_customer.rohs,
                        supplierCountry,
                        headerQuarterEN.company_name + '</br>' + headerQuarterEN.tel + '</br>' +
                        headerQuarterEN.address,
                    ];

                    var params = JSON.parse(orderTemplateEN.template_params);
                    var emailText = JSON.parse(orderTemplateEN.template_content);
                    $.each(params, function(index, item) {
                        emailText = emailText.replace(item, mailData[index]);
                    });

                    $("#supplier-mail-content").val(emailText);
                    $("#order-confirm-btn").text("Send");
                }
            });

            $(document).on('click', '#order-confirm-btn', function() {
                var ajaxData = {
                    'id': $('#order-confirm-btn').data('orderId'),
                    'content': $('#supplier-mail-content').val(),
                    'pdf': $('#supplier-order-pdf').data('pdf'),
                };

                $.ajax({
                    url: "{{ route('admin.mail.send_order_to_supplier') }}",
                    method: 'POST',
                    data: ajaxData,
                    success: function(result) {
                        toastr.success('発注メールを仕入先に送信しました。');
                        $("#send-mail-to-supplier-modal").modal('hide');
                        shipOrderTable.draw();
                    }
                });
            })

            $('textarea.message-box').focusout(function() {
                var target = $('#ship-order-table').find('tr.selected');
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

            $('#supplier-register-modal').on('show.bs.modal', function() {
                $("#supplier-register-modal").find('.invalid-feedback').remove();
                $("#supplier-register-modal").find('.is-invalid').removeClass('is-invalid');
                $('#supplier-register-modal').find('input').val('');
                $('#supplier-register-modal').find('select').val(0);
                $('#supplier-register-modal').find('textarea').val('');
                $('#supplier-register-modal').find('input[type=checkbox]').prop('checked', false);
                var vendor = $('#supplier-info').data('supplier');
                $('#register-supplier-country').data('selectpicker').destroy();
                var checkedStatus = checkSelectRow();
                if (checkedStatus.ids.length != 0 && vendor) {
                    $('#register-supplier-country').val(vendor.user_info.address.country);
                    $('#register-supplier-country').data('default', vendor.user_info.address.country);
                    $("#register-supplier").data('registerType', 'update');
                    $("#register-supplier").data('registerId', vendor.id);
                    $('#register-supplier-company-name').val(vendor.user_info.company_name);
                    $('#register-postal-code').val(vendor.user_info.address.zip);
                    $('#register-person-in-charge').val(vendor.representative);
                    $('#register-supplier-phone-number').val(vendor.user_info.address.tel);
                    $('#register-supplier-email1').val(vendor.user_info.email1);
                    $('#register-supplier-email2').val(vendor.user_info.email2);
                    $('#register-supplier-email3').val(vendor.user_info.email3);
                    $('#register-supplier-email4').val(vendor.user_info.email4);
                    $('#register-supplier-remarks').val(vendor.user_info.message1);
                    $('#register-supplier-payment-term').val(vendor.user_info.payTerm);
                    $('#register-supplier-company-name-kana').val(vendor.user_info.company_name_kana);
                    $('#register-supplier-rank').val(vendor.user_info.rank);
                    $('#register-supplier-prefectures').val(vendor.district);
                    $('#register-supplier-address').val(vendor.user_info.address.address1);
                    $('#register-supplier-fax').val(vendor.user_info.address.fax);
                    if (vendor.daily_rfq == 1)
                        $('#daily-RFQ').prop('checked', true);
                    else
                        $('#daily-RFQ').prop('checked', false);
                } else {
                    $('#register-supplier').data('registerType', 'create');
                }
                $('#register-supplier-country').selectpicker();
                $('#register-supplier-country').countrypicker();
                drawSelectPaymentList();
            });

            $("#register-supplier").click(function() {
                $("#supplier-register-modal").find('.invalid-feedback').remove();
                $("#supplier-register-modal").find('.is-invalid').removeClass('is-invalid');
                var registerType = $(this).data('registerType');
                if (registerType == 'update') {
                    registerId = $(this).data('registerId');
                    var routeUrl = '/admin/supplier/' + registerId;
                    var method = "PUT";
                } else {
                    var routeUrl = "{{ route('admin.supplier.store') }}";
                    var method = "POST";
                }
                var email1 = $('#register-supplier-email1').val();
                var email2 = $('#register-supplier-email2').val();
                var email3 = $('#register-supplier-email3').val();
                var email4 = $('#register-supplier-email4').val();

                var checkDupplicate = validationEmails(email1, email2, email3, email4);
                if (checkDupplicate != 'success') {
                    let elem = $('#register-supplier-email' + (checkDupplicate[1]));
                    elem.parents('.input-group').append(
                            '<div class="invalid-feedback" style="display: block !important; margin-left: 100px">メールアドレスが重複されています.</div>'
                        )
                        .find('input').addClass('is-invalid')
                    return;
                }

                var storedData = {
                    compName: $('#register-supplier-company-name').val(),
                    country: $('#register-supplier-country').val(),
                    postalCode: $('#register-postal-code').val(),
                    personInCharge: $('#register-person-in-charge').val(),
                    phoneNumber: $('#register-supplier-phone-number').val(),
                    email1: $('#register-supplier-email1').val(),
                    email2: $('#register-supplier-email2').val(),
                    email3: $('#register-supplier-email3').val(),
                    email4: $('#register-supplier-email4').val(),
                    remarks: $('#register-supplier-remarks').val(),
                    payTerm: $('#register-supplier-payment-term').val(),
                    compNameKana: $('#register-supplier-company-name-kana').val(),
                    rank: $('#register-supplier-rank').val(),
                    prefectures: $('#register-supplier-prefectures').val(),
                    address: $('#register-supplier-address').val(),
                    fax: $('#register-supplier-fax').val(),
                    registerDate: $('#register-supplier-date').val(),
                    dailyRFQ: $('#daily-RFQ').val() == 'on' ? 1 : 0,
                };

                $.ajax({
                    url: routeUrl,
                    method: method,
                    data: storedData,
                    success: function(data) {
                        $("#supplier-register-modal").modal("hide");
                        $("#supplier-register-modal").find('input').val('');
                        toastr.success('仕入先は登録完了しました。');
                        shipOrderTable.draw();
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        for (key in errors) {
                            var errorElem = null;
                            var message = null;
                            switch (key) {
                                case 'compName':
                                    errorElem = $('#register-supplier-company-name');
                                    message = errors['compName'];
                                    break;
                                case 'compNameKana':
                                    errorElem = $('#register-supplier-company-name-kana');
                                    message = errors['compNameKana'];
                                    break;
                                case 'email1':
                                    errorElem = $('#register-supplier-email1');
                                    message = errors['email1'];
                                    break;
                                case 'email2':
                                    errorElem = $('#register-supplier-email2');
                                    message = errors['email2'];
                                    break;
                                case 'email3':
                                    errorElem = $('#register-supplier-email3');
                                    message = errors['email3'];
                                    break;
                                case 'email4':
                                    errorElem = $('#register-supplier-email4');
                                    message = errors['email'];
                                    break;
                                case 'country':
                                    errorElem = $('#register-supplier-country');
                                    message = errors['country'];
                                    break;
                                case 'address':
                                    errorElem = $('#register-supplier-address');
                                    message = errors['address'];
                                    break;
                                case 'payTerm':
                                    errorElem = $('#register-supplier-payment-term');
                                    message = errors['payTerm'];
                                    break;
                                default:
                                    errorElem = null;
                                    break;
                            }
                            if (errorElem) {
                                errorElem.parents('.input-group').append(
                                        '<div class="invalid-feedback" style="display: block !important; margin-left: 100px">' +
                                        message + '</div>')
                                    .find('input').addClass('is-invalid');
                            }
                        }
                    }
                });
            });

            $("#register-supplier-add-payment").click(function() {
                $('#add-new-payment-modal').modal('show');
                $('#supplier-register-modal').modal('hide');
            });

            $('#add-new-payment-modal').on('show.bs.modal', function() {
                paymentTable.draw(true);
            });

            paymentTable.on('click', '.payment-delete', function() {
                $('#payment-term-table').find('tr.selected').removeClass('selected');
                var id = $(this).parents('tr').addClass('selected').data('id');
                $("#confirm-btn").data("type", "deletePayment");
                $("#confirm-btn").data("id", id);
                $("#confirm-modal").modal('show');
                $("#add-new-payment-modal").modal('hide');
            })

            paymentTable.on('click', '.payment-cancel', function() {
                $('#payment-term-table').find('tr.selected').removeClass('selected');
                var trElem = $(this).parents('tr').addClass('selected');
                var id = trElem.data('id');
                trElem.find('td:eq(0)').empty().text($(this).data('old'));
                trElem.find('a.payment-cancel').addClass('d-none');
                trElem.find('a.payment-delete').removeClass('d-none');
                trElem.find('a.payment-edit').removeClass('d-none');
                trElem.find('a.payment-save').addClass('d-none');
            })

            paymentTable.on('click', '.payment-edit', function() {
                $('#payment-term-table').find('tr.selected').removeClass('selected');
                var trElem = $(this).parents('tr').addClass('selected');
                var id = trElem.data('id');
                var oldContent = trElem.find('td').eq(0).text();
                trElem.find('td').eq(0).empty().append(
                    '<input type="text" class="form-control form-control-sm" value="' + oldContent +
                    '">');
                trElem.find('a.payment-cancel').removeClass('d-none').data('old', oldContent);
                trElem.find('a.payment-delete').addClass('d-none');
                trElem.find('a.payment-edit').addClass('d-none');
                trElem.find('a.payment-save').removeClass('d-none');
            })

            paymentTable.on('click', '.payment-save', function() {
                var trElem = $(this).parents('tr').addClass('selected');
                var id = trElem.data('id');

                $("#add-new-payment-modal").find('.invalid-feedback').remove();
                $("#add-new-payment-modal").find('.is-invalid').removeClass('is-invalid');

                $.ajax({
                    url: "/admin/common/" + id,
                    method: 'PUT',
                    data: {
                        commonName: trElem.find('td:eq(0) input').val()
                    },
                    success: function(result) {
                        $('#register-new-common-name').val('');
                        $("#add-new-payment-modal").find('.invalid-feedback').remove();
                        $("#add-new-payment-modal").find('.is-invalid').removeClass(
                            'is-invalid');
                        var data = {
                            common_name: result.common_name,
                            '更新': '<a class="btn btn-sm"><i class="fas fa-edit fa-sm"></i>edit</a>',
                            '削除': '<a class="btn btn-sm"><i class="fas fa-edit fa-sm"></i>delete</a>'
                        }
                        paymentTable.row('.selected').remove().draw(false);
                        paymentTable.row.add(data).draw(false);
                        commonPaymentList[result.id] = result.common_name;
                        drawSelectPaymentList();

                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        for (key in errors) {
                            if (key == 'commonName') {
                                trElem.find('td:eq(0)').addClass('text-wrap').append(
                                        '<div class="invalid-feedback" style="display: block !important;">' +
                                        errors['commonName'] + '</div>')
                                    .find('input').addClass('is-invalid');
                            }
                        }
                    },
                });
            })

            $('#add-new-common-btn').click(function() {
                $("#add-new-payment-modal").find('.invalid-feedback').remove();
                $("#add-new-payment-modal").find('.is-invalid').removeClass('is-invalid');
                $.ajax({
                    url: "{{ route('admin.common.store') }}",
                    method: 'POST',
                    data: {
                        commonName: $('#register-new-common-name').val()
                    },
                    success: function(result) {
                        var data = {
                            common_name: result.common_name,
                            '更新': '<a class="btn btn-sm"><i class="fas fa-edit fa-sm"></i>edit</a>',
                            '削除': '<a class="btn btn-sm"><i class="fas fa-edit fa-sm"></i>delete</a>'
                        }
                        paymentTable.row.add(data).draw(true);
                        $('#register-new-common-name').val('');
                        commonPaymentList[result.id] = result.common_name;
                        drawSelectPaymentList();

                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        for (key in errors) {
                            if (key == 'commonName') {
                                $('#register-new-common-name').parents('.input-group').append(
                                        '<div class="invalid-feedback" style="display: block !important; margin-left: 100px">' +
                                        errors['commonName'] + '</div>')
                                    .find('input').addClass('is-invalid');
                            }
                        }
                    },
                });
            });

            $('#confirm-btn').click(function() {
                var type = $(this).data('type'),
                    ajaxData = $(this).data('ajaxData'),
                    id = $(this).data('id'),
                    method = "DELETE",
                    url = "/admin/common/" + id;

                $.ajax({
                    url: url,
                    method: method,
                    data: ajaxData,
                    success: function(data) {
                        $("#confirm-modal").modal('hide');
                        paymentTable.row('.selected').remove().draw(false);
                        paymentTable.draw();
                        delete commonPaymentList[id];
                    },
                });
            });

            $(document).on('hidden.bs.modal', '.modal', function() {
                $('#ship-order-table tbody').find('td:eq(1)').focus();
            })

            $(document).on('blur', '#ship-order-table tr', function() {
                if ($(this).hasClass('direct-edit')) {
                    $(this).removeClass('direct-edit');
                    var targetData = getShipOrderRowByIndex($(this).index() + 1);

                    $.ajax({
                        url: autoShipOrderUrl,
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
