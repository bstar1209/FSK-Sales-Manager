@extends('layouts.page')

@section('title', 'RFQ')

@section('custom_style')
    <link rel="stylesheet" href="//unpkg.com/bootstrap-select@1.12.4/dist/css/bootstrap-select.min.css" type="text/css" />
    <link rel="stylesheet" href="//unpkg.com/bootstrap-select-country@4.0.0/dist/css/bootstrap-select-country.min.css"
        type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('header-container')
    <div class="row">
        @include('admin/rfq/partials/search_area')
        @include('admin/rfq/partials/customer_info')
        <div class="col-6">
            <div class="row">
                @include('admin/rfq/partials/supplier_info')
                @include('admin/rfq/partials/actions')
            </div>
        </div>
    </div>
@endsection

@inject('table_config', 'App\Models\TableConfig')
@inject('template_info', 'App\Models\TemplateInfo')
@inject('header_quarter', 'App\Models\HeaderQuarter')
@inject('alert', 'App\Models\Alert')
@php
$rfq_info = $table_config->where('table_name', $table_config::$names[0])->first();
$rfq_columns = json_decode($rfq_info->column_names);
$rfq_widths = json_decode($rfq_info->column_info);

$rfq_quote_info = $table_config->where('table_name', $table_config::$names[1])->first();
$rfq_quote_columns = json_decode($rfq_quote_info->column_names);
$rfq_quote_widths = json_decode($rfq_quote_info->column_info);

$rfq_history_info = $table_config->where('table_name', $table_config::$names[2])->first();
$rfq_history_columns = json_decode($rfq_history_info->column_names);
$rfq_history_widths = json_decode($rfq_history_info->column_info);

$today = date_create()->format('Y-m-d');
$notification = $alert
    ->whereDate('start_date', '<', $today)
    ->whereDate('end_date', '>', $today)
    ->orderBy('created_at')
    ->first();

$quote_templates_to_supplier_jp = $template_info->where('template_index', '=', $template_info::$template_type['Quotation request email to supplier jp'])->first();
$header_quarter_jp = $header_quarter->where('type', '=', $header_quarter::$language_type['JP'])->first();

$quote_templates_to_supplier_en = $template_info->where('template_index', '=', $template_info::$template_type['Quotation request email to supplier en'])->first();
$header_quarter_en = $header_quarter->where('type', '=', $header_quarter::$language_type['EN'])->first();
@endphp

@section('table-container')
    @include('admin/rfq/partials/request_unrfq_table')
    @include('admin/rfq/partials/quote_from_supplier_table')
    @include('admin/rfq/partials/history_table')
@endsection

@section('other-container')
    @include('admin/modals/update_customer_info')
    @include('admin/modals/register_supplier')
    @include('admin/modals/add_new_payment_term')
    @include('admin/modals/register_manufacturer')
    @include('admin/rfq/modals/send_supplier_email')
    @include('admin/modals/confirm_message')
    @include('admin/modals/billing_address')
@endsection

@section('custom_script')
    <script src="//unpkg.com/bootstrap-select@1.12.4/dist/js/bootstrap-select.min.js"></script>
    <script src="//unpkg.com/bootstrap-select-country@4.0.0/dist/js/bootstrap-select-country.min.js"></script>
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/adapters/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var rfqColumns = @json($rfq_columns);
        var rfqWidths = @json($rfq_widths);
        var rfqQuoteColumns = @json($rfq_quote_columns);
        var rfqQuoteWidths = @json($rfq_quote_widths);
        var rfqHistoryColumns = @json($rfq_history_columns);
        var rfqHistoryWidths = @json($rfq_history_widths);

        var quoteTemplateJP = @json($quote_templates_to_supplier_jp);
        var headerQuarterJP = @json($header_quarter_jp);

        var quoteTemplateEN = @json($quote_templates_to_supplier_en);
        var headerQuarterEN = @json($header_quarter_en);
        var notification = @json($notification);
    </script>
    <script src="{{ asset('js/admin/rfq/functions.js') }}"></script>
    <script src="{{ asset('js/admin/rfq/datatables.js') }}"></script>
    <script src="{{ asset('js/admin/rfq/shortkey.js') }}"></script>
    <script>
        $(function() {

            loadCommonList();
            loadCustomerInfoList();
            loadSupplierInfoList();
            loadMakerList();

            $('.email_content').ckeditor();
            $('#history-table_filter').attr('placeholder', '型番から検索 Enterで実行');

            $('#history-table_filter').keyup(function() {
                historyTable.draw();
            })

            $('#search-reception-date').datepicker({
                format: 'yyyy-mm-dd',
                inline: false,
            }).keydown(function(e) {
                datepickerKeyDownHandler($(this), e);
            });

            $(document).on('keypress', 'input#search-reception-date', function(e) {
                useList = '0123456789-';
                if (useList.search(e.key) == -1) {
                    return false;
                }
            })

            $('#search-customer, #search-customer-id, #search-model-number, #search-reception-number, #search-status')
                .on('keyup change', function() {
                    reqUnRFQTable.draw();
                })

            $('#quote-from-supplier-table tbody').on('click', 'tr', function(e) {
                formatSupplierSection();
                if ($(this).hasClass('edit-quote') || $(this).find('td.dataTables_empty').length != 0)
                    return;

                if ($('#quote-from-supplier-table').find('.edit-quote').length != 0) {
                    var target = $(this).find('td:eq(' + rfqQuoteColumns.indexOf(originalQuoteKeyArr[2]) +
                        ')');
                    if (target.find('input').length > 1)
                        var value = target.find('input').val();
                    else
                        var value = target.text();
                    autoEditedQuote($(e.relatedTarget));
                    return;
                }

                $('#quote-from-supplier-table').find('tr.selected').removeClass('selected');
                $('#quote-from-supplier-table').find('tr').removeClass('tr-orange');
                $(this).toggleClass('tr-orange').addClass('selected');

                updatedByQuoteTable();
            });

            $('#search-area-clear').click(function() {
                $('#search-area').find('input').val('');
                $('#search-area').find('select').val('1');
                reqUnRFQTable.draw();
            });

            $("#update-customer-btn").click(function() {
                var target = $('#request-unrfq-table').find('tr.selected');
                if (target.length == 0)
                    toastr.warning('更新対象顧客を選択してください。');
                else
                    $('#customer-info-modal').modal('show');
            });

            $('#add-new-payment-modal').on('show.bs.modal', function() {
                paymentTable.draw(true);
            })

            $("#customer-info-modal").on('show.bs.modal', function() {

                if ($("#customer-info-modal").data("type") == "invalidConfirm") {
                    return;
                }
                var customerInfo = $('#customer-info').data('customerInfo');
                $('#customer-info-modal').data('id', customerInfo.id);
                $('#customer-info-modal .customer-company-name').val(customerInfo.user_info.company_name);
                $('#customer-info-modal .customer-company-name-kana').val(customerInfo.user_info
                    .company_name_kana);
                $('#customer-info-modal .customer-sales').val(customerInfo.representative_business);
                $('#customer-info-modal .customer-rank').val(customerInfo.user_info.rank);
                $('#customer-info-modal .customer-name').val(customerInfo.representative);
                $('#customer-info-modal .customer-email1').val(customerInfo.user_info.email1);
                $('#customer-info-modal .customer-email2').val(customerInfo.user_info.email2);
                $('#customer-info-modal .customer-email3').val(customerInfo.user_info.email3);
                $('#customer-info-modal .customer-email4').val(customerInfo.user_info.email4);
                if (customerInfo.user_info.address) {
                    $('#customer-info-modal .customer-phone-number').val(customerInfo.user_info.address
                    .tel);
                    $('#customer-info-modal .customer-home-page').val(customerInfo.user_info.address
                        .homepages);
                    $('#customer-info-modal .customer-business-type').val(customerInfo.user_info.address
                        .comp_type);
                    $('#customer-info-modal .customer-fax-number').val(customerInfo.user_info.address.fax);
                    $('#customer-info-modal .customer-department').val(customerInfo.user_info.address
                        .part_name);
                }

                if (Array.isArray(customerInfo.user_info.payment) && customerInfo.user_info.payment[0]) {
                    var payment = customerInfo.user_info.payment[0];
                    if (payment.close_date != null && payment.send_date != null) {
                        var closeDate = payment.close_date.split('-');
                        var sendDate = payment.send_date.split('-');
                        var currentDate = new Date();
                        var currentMonth = currentDate.getMonth() + 1;
                        $("select.customer-close-date").val(closeDate[2]);
                        $("select.customer-send-date").val(sendDate[2]);
                        if (parseInt(currentMonth) === parseInt(sendDate[1])) {
                            var index = "0";
                        } else if ((parseInt(sendDate[1]) - parseInt(currentMonth)) === 1) {
                            var index = "1";
                        } else {
                            var index = "2";
                        }
                        $("select.customer-type-date").val(index);
                    }
                }

                if ($('form.payment-1').find('input').length == 0) {
                    $.each(commonList, function(key, item) {
                        if (item['type'] == 0)
                            $('form.payment-1').append(`<div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input payment-type" id="payment-type-` +
                                key + `" name="inlineMaterialRadiosExample" data-commonId="` + key + `">
                                        <label class="form-check-label" for="payment-type-` + key + `">` + item.name + `</label>
                                    </div>`);
                        else if (item['type'] == 1)
                            $('form.payment-2').append(`<div class="form-check form-check-inline">
                                        <input type="radio" id="payment-type-` + key +
                                `" class="form-check-input"  name="inlineMaterialRadiosExample" data-commonId="` +
                                key + `">
                                        <label class="form-check-label" for="payment-type-` + key + `">` + item.name + `</label>
                                    </div>`);
                    })
                }

                if (customerInfo.user_info.payment && customerInfo.user_info.payment.length != 0) {
                    $('#payment-type-' + customerInfo.user_info.payment[0].common_id).prop('checked', true);
                    $('form.payment-2').find('input:eq(' + parseInt(customerInfo.user_info.payment[0]
                        .payment_flag - 1) + ')').prop('checked', true);
                }
                $('#customer-info-modal .customer-remarks').val(customerInfo.user_info.message1);
                getCustomerLog(customerInfo.id);
            });

            $("#customer-info-modal").on('hidden.bs.modal', function() {
                $("#customer-info-modal").find('.invalid-feedback').remove();
                $("#customer-info-modal").find('.is-invalid').removeClass('is-invalid');
            });

            $('#update-customer-info').click(function() {
                $("#customer-info-modal").find('.invalid-feedback').remove();
                $("#customer-info-modal").find('.is-invalid').removeClass('is-invalid');
                var closeDay = $('#customer-info-modal .customer-close-date').val();
                var sendDay = $('#customer-info-modal .customer-send-date').val();
                var typeDate = $('#customer-info-modal .customer-type-date').val();
                var sendDate = new Date();
                sendDate.setDate(sendDay);
                var closeDate = new Date();
                closeDate.setMonth(closeDate.getMonth() + parseInt(typeDate));
                closeDate.setDate(closeDay);

                var storedData = {
                    compName: $('#customer-info-modal .customer-company-name').val(),
                    compNameKana: $('#customer-info-modal .customer-company-name-kana').val(),
                    sales: $('#customer-info-modal .customer-sales').val(),
                    rank: $('#customer-info-modal .customer-rank').val(),
                    representative: $('#customer-info-modal .customer-name').val(),
                    email1: $('#customer-info-modal .customer-email1').val(),
                    email2: $('#customer-info-modal .customer-email2').val(),
                    email3: $('#customer-info-modal .customer-email3').val(),
                    email4: $('#customer-info-modal .customer-email4').val(),
                    tel: $('#customer-info-modal .customer-phone-number').val(),
                    homepage: $('#customer-info-modal .customer-home-page').val(),
                    businessType: $('#customer-info-modal .customer-business-type').val(),
                    fax: $('#customer-info-modal .customer-fax-number').val(),
                    department: $('#customer-info-modal .customer-department').val(),
                    message: $('#customer-info-modal .customer-remarks').val(),
                    sendDate: sendDate.toDateString(),
                    closeDate: closeDate.toDateString(),
                    payment: [$("form.payment-1 input[type='radio']:checked").data('commonid'), $(
                        "form.payment-2 input[type='radio']:checked").data('commonid')],
                };

                $("#customer-info-modal").modal('hide');
                $("#confirm-modal").modal('show');
                $("#confirm-btn").data("type", "updateCustomerInfo");
                $("#confirm-cancel").data("type", "updateCustomerInfo");
                $("#confirm-btn").data("ajaxData", storedData);
                $("#confirm-btn").data("id", $("#customer-info-modal").data('id'));
            });

            $("#register-maker").click(function() {
                $("#manufacturer-register-modal").find('.invalid-feedback').remove();
                $("#manufacturer-register-modal").find('.is-invalid').removeClass('is-invalid');
                $.ajax({
                    url: "{{ route('admin.maker.store') }}",
                    method: 'POST',
                    data: {
                        name: $('#register-maker-name').val()
                    },
                    success: function(data) {
                        $('#manufacturer-register-modal').modal('hide');
                        $('#register-maker-name').val('');
                        $("#manufacturer-register-modal").find('.invalid-feedback').remove();
                        $("#manufacturer-register-modal").find('.is-invalid').removeClass(
                            'is-invalid');
                        loadMakerList();
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        for (key in errors) {
                            if (key == 'name') {
                                $('#register-maker-name').parents('.input-group').append(
                                        '<div class="invalid-feedback" style="display: block !important; margin-left: 100px">' +
                                        errors['name'] + '</div>')
                                    .find('input').addClass('is-invalid');
                            }
                        }
                    },
                });
            });

            $('#supplier-register-modal').on('show.bs.modal', function() {
                drawSelectPaymentList();
                $("#supplier-register-modal").find('.invalid-feedback').remove();
                $("#supplier-register-modal").find('.is-invalid').removeClass('is-invalid');
                $('#supplier-register-modal').find('input').val('');
                $('#supplier-register-modal').find('select').val(0);
                $('#supplier-register-modal').find('textarea').val('');
                $('#supplier-register-modal').find('input[type=checkbox]').prop('checked', false);
                var vendor = $('#supplier-info').data('supplier');
                $('#register-supplier-country').data('selectpicker').destroy();
                if (vendor) {
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
                    if (vendor.user_info.payment && vendor.user_info.payment[0].common) {
                        $("#register-supplier-payment-term").val(parseInt(vendor.user_info.payment[0].common
                            .id));
                    } else {
                        $("#register-supplier-payment-term").val(0);
                    }
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
                    dailyRFQ: $('#daily-RFQ').prop('checked') ? 1 : 0,
                };

                $.ajax({
                    url: routeUrl,
                    method: method,
                    data: storedData,
                    success: function(data) {
                        $("#supplier-register-modal").modal("hide");
                        $("#supplier-register-modal").find('input').val('');
                        if (registerType == 'update') {
                            toastr.success('仕入先が更新完了しました。');
                        }
                        else
                            toastr.success('仕入先は登録完了しました。');
                        quoteFromSupplierTable.draw();
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

            $('#customer-info-modal .edit-billing-address').click(function() {
                $('#billing-address-modal').find('input').val('');
                var data = $('#customer-info').data('customerInfo');
                if ($(this).data('type') == 'address') {
                    $('#billing-address-modal').find('h6').each(function(index) {
                        $(this).text('請求先住所' + (index + 1));
                    });

                    $.each(data.user_info.billing_address, function(index, item) {
                        parentDiv = $('#billing-address-' + (index + 1));
                        parentDiv.data('address_id', item.id);
                        parentDiv.find('.billing-address-company-name').val(item.comp_type);
                        parentDiv.find('.billing-address-names').val(item.customer_name);
                        parentDiv.find('.billing-address-department-name').val(item.part_name);
                        parentDiv.find('.billing-address-zip-code').val(item.zip);
                        parentDiv.find('.billing-address-prefecture').val(item.address1);
                        parentDiv.find('.billing-address-municipality').val(item.address2);
                        parentDiv.find('.billing-address-building-name').val(item.address4);
                        parentDiv.find('.billing-address-address').val(item.address3);
                        parentDiv.find('.billing-address-tel').val(item.tel);
                        parentDiv.find('.billing-address-fax').val(item.fax);
                    })
                    $("#billing-address-modal").data('type', 'billing');
                    $("#billing-address-modal").data('userId', data.user_info.id);
                    $('#billingAddressModalLabel').text('客先情報更新');
                } else {
                    $('#billing-address-modal').find('h6').each(function(index) {
                        $(this).text('納品先住所' + (index + 1));
                    });
                    $("#billing-address-modal").data('type', 'delivery');
                    $("#billing-address-modal").data('userId', data.user_info.id);
                    $.each(data.user_info.deliver_address, function(index, item) {
                        parentDiv = $('#billing-address-' + (index + 1));
                        parentDiv.data('address_id', item.id);
                        parentDiv.find('.billing-address-company-name').val(item.comp_type);
                        parentDiv.find('.billing-address-names').val(item.customer_name);
                        parentDiv.find('.billing-address-department-name').val(item.part_name);
                        parentDiv.find('.billing-address-zip-code').val(item.zip);
                        parentDiv.find('.billing-address-prefecture').val(item.address1);
                        parentDiv.find('.billing-address-municipality').val(item.address2);
                        parentDiv.find('.billing-address-building-name').val(item.address4);
                        parentDiv.find('.billing-address-address').val(item.address3);
                        parentDiv.find('.billing-address-tel').val(item.tel);
                        parentDiv.find('.billing-address-fax').val(item.fax);
                    })
                    $('#billingAddressModalLabel').text('納品先住所');
                }
                $("#billing-address-modal").modal('show');
            });

            $('.edit-address').click(function() {
                var index = $(this).data('index');
                elem = $('#billing-address-' + index);
                elem.find('.invalid-feedback').remove();
                elem.find('.is-invalid').removeClass('is-invalid');
                var id = elem.data('address_id'),
                    url, method;

                if (id == undefined || id == null || id == '') {
                    url = "{{ route('admin.address.store') }}";
                    method = "POST";
                } else {
                    url = '/admin/address/' + id;
                    method = "PUT";
                }
                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        type: $("#billing-address-modal").data('type'),
                        user_info_id: $("#billing-address-modal").data('userId'),
                        compName: elem.find('.billing-address-company-name').val(),
                        addressNames: elem.find('.billing-address-names').val(),
                        department: elem.find('.billing-address-department-name').val(),
                        zip: elem.find('.billing-address-zip-code').val(),
                        prefecture: elem.find('.billing-address-prefecture').val(),
                        municipality: elem.find('.billing-address-municipality').val(),
                        buildingName: elem.find('.billing-address-building-name').val(),
                        address3: elem.find('.billing-address-address').val(),
                        tel: elem.find('.billing-address-tel').val(),
                        fax: elem.find('.billing-address-fax').val(),
                        address_index: index,
                    },
                    success: function(data) {
                        toastr.success('正常に変更されました。');
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        for (key in errors) {
                            var errorElem = null,
                                message = null;
                            switch (key) {
                                case 'compName':
                                    errorElem = elem.find('.billing-address-company-name');
                                    message = errors['compName'];
                                    break;
                                case 'zip':
                                    errorElem = elem.find('.billing-address-zip-code');
                                    message = errors['zip'];
                                    break;
                                case 'prefecture':
                                    errorElem = elem.find('.billing-address-prefecture');
                                    message = errors['perfecture'];
                                    break;
                                case 'municipality':
                                    errorElem = elem.find('.billing-address-municipality');
                                    message = errors['municipality'];
                                    break;
                                case 'address3':
                                    errorElem = elem.find('.billing-address-address');
                                    message = errors['address'];
                                    break;
                                case 'tel':
                                    errorElem = elem.find('.billing-address-tel');
                                    message = errors['tel'];
                                    break;
                                case 'fax':
                                    errorElem = elem.find('.billing-address-fax');
                                    message = errors['fax'];
                                    break;
                                default:
                                    errorElem = null;
                                    break;
                            }
                            if (errorElem) {
                                if (message == undefined || message == null)
                                    message = "スペースがあってはなりません.";
                                errorElem.parents('.input-group').append(
                                        '<div class="invalid-feedback" style="display: block !important; margin-left: 100px">' +
                                        message + '</div>')
                                    .find('input').addClass('is-invalid');
                            }
                        }
                    }
                });
            });

            $("#billing-address-modal").on('shown.bs.modal', function() {
                $('#customer-info-modal').modal('hide');
            });

            $("#billing-address-modal").on('hidden.bs.modal', function() {
                $('#customer-info-modal').modal('show');
                $('#billing-address-modal').find('.invalid-feedback').remove();
                $("#billing-address-modal").find('.is-invalid').removeClass('is-invalid');
                reqUnRFQTable.draw(true);
            });

            $("#add-new-payment-modal").on('hidden.bs.modal', function() {
                $('#supplier-register-modal').modal('show');
            });

            $('#add-part-rfq-btn').click(function() {
                var target = $('#request-unrfq-table').find('tr.selected');
                if (target.length == 0)
                    toastr.warning('商品を追加したい場合は、行を選択して下さい。');
                else
                    addPartUnRFQ();
                return;
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

            $('#new-quote-btn').click(function() {
                if ($('#quote-from-supplier-table').find('.edit-quote').length != 0) {
                    return;
                }
                $('#quote-from-supplier-table').parents('.dataTables_scrollBody').scrollTop(0);
                addNewQuoteToTable();
            })

            $('#add-new-rfq-btn').click(function() {
                if ($(this).hasClass('add-part-new-rfq'))
                    return;

                if ($('#request-unrfq-table').find('.add-part-new-rfq').length != 0) {
                    return;
                }
                addNewRfq();
            })

            $(document).on('click', '#update-rfq-btn', function() {
                var target = $('#request-unrfq-table').find('tr.selected');
                if (target.length == 0)
                    toastr.warning('更新対象顧客を選択してください。');
                else {
                    $('#request-unrfq-table').find('.add-part-new-rfq').remove();
                    $('#request-unrfq-table').find('.d-none').remove();
                    var targetData = target.data('rowInfo');
                    if (targetData.cancel_date == null)
                        targetData.cancel_date = '';
                    if (targetData.solved_date == null)
                        targetData.solved_date = '';
                    if (targetData.comment == null)
                        targetData.comment = '';

                    var MakerListOptionHtml = '<option></option>';
                    $.each(makerList, function(index, data) {
                        MakerListOptionHtml += '<option value="' + data.maker_name + '">' + data
                            .maker_name + '</option>';
                    });

                    var customerOptionList = '<option></option>';
                    $.each(customerInfoList, function(index, item) {
                        customerOptionList += `<option data-info='` + JSON.stringify(item) +
                            `' class="` + item.user_info.company_name_kana + `">` + item.user_info
                            .company_name + `</option>`;
                    })

                    var trChild = `<tr role="row" class="add-part-new-rfq" data-id=` + targetData.id + `>`;
                    var originalKeyArr = Object.keys(columnsRfqData);
                    $.each(rfqColumns, function(index, item) {
                        var originalIndex = originalKeyArr.indexOf(item);
                        switch (originalIndex) {
                            case 0:
                                trChild +=
                                    `<th class="p-0"><input type="text" class="form-control form-control-sm" value="` +
                                    targetData['created_at'] +
                                    `" style="font-size: 12px !important" disabled></th>`;
                                break;
                            case 1:
                                trChild +=
                                    `<th class="p-0"><input type="text" class="form-control form-control-sm" value="` +
                                    targetData['customer_id'] + `" disabled></th>`;
                                break;
                            case 2:
                                trChild +=
                                    `<th class="p-0"><input type="text" class="form-control form-control-sm" value="` +
                                    targetData['detail_id'] + `-` + targetData['child_index'] +
                                    `" disabled></th>`;
                                break;
                            case 3:
                                //trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm unrfq-select-customer" autoComplete="off"></th>`;
                                trChild +=
                                    `<th class="p-0"><select class="form-control form-control-sm unrfq-select-customer select2">` +
                                    customerOptionList + `</select></th>`;
                                break;
                            case 4:
                                trChild +=
                                    `<th class="p-0"><input type="text" class="form-control form-control-sm" value="` +
                                    targetData['customer']['representative'] + `" disabled></th>`;
                                break;
                            case 5:
                                //trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm maker-select" autoComplete="off"></th>`;
                                trChild +=
                                    `<th class="p-0"><select class="form-control form-control-sm maker select2">` +
                                    MakerListOptionHtml + `</select></th>`;
                                break;
                            case 6:
                                trChild +=
                                    `<th class="p-0"><input type="text" class="form-control form-control-sm" value="` +
                                    targetData['dc'] + `"></th>`;
                                break;
                            case 7:
                                trChild += `<th class="p-0"><input type="text" id="add-rfq-katashiki" class="form-control form-control-sm" value="` +
                                    targetData['katashiki'] + `"></th>`;
                                break;
                            case 8:
                                trChild += `<th class="p-0"><input type="text" id="add-count-aspiration" class="form-control form-control-sm input-check-number" value="` +
                                    targetData['quantity_aspiration'] + `"></th>`;
                                break;
                            case 9:
                                trChild += `<th class="p-0"><input type="text" id="add-price-aspiration" class="form-control form-control-sm input-check-number"></th>`;
                                break;
                            case 10:
                                trChild += `<th class="p-0"><input type="text" id="add-kbn" class="form-control form-control-sm"></th>`;
                                break;
                            case 11:
                                trChild += `<th class="p-0"><select class="form-control form-control-sm condition1"><option value="予算限定">予算限定</option><option value="納期優先">納期優先</option></select></th>`;
                                break;
                            case 12:
                                trChild += `<th class="p-0"><select class="form-control form-control-sm condition2"><option value="有鉛可">有鉛可</option><option value="Rohsのみ">Rohsのみ</option></select></th>`;
                                break;
                            case 13:
                                trChild += `<th class="p-0"><select class="form-control form-control-sm condition3"><option value="中国可">中国可</option><option value="海外可">海外可</option><option value="国内のみ">国内のみ</option></select></th>`;
                                break;
                            case 14:
                                trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm rfq-date-picker"></th>`;
                                break;
                            case 15:
                                trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm rfq-date-picker"></th>`;
                                break;
                        }
                    });
                    trChild += `</tr>`;

                    var elem = $(trChild);
                    elem.find('textarea.un-rfq-textarea').val(targetData['comment']);
                    elem.insertAfter($('#request-unrfq-table tbody tr:nth(' + target.index() + ')'));

                    //rfqCustomerAutoComplete('unrfq-select-customer');
                    //makerAutoComplete();
                    elem.find('.maker').val(targetData['maker']);
                    elem.find('.maker').select2(selectOptions);
                    elem.find('.unrfq-select-customer').val(targetData.customer.user_info.company_name);
                    elem.find('.unrfq-select-customer').select2(selectOptions);
                    target.addClass('d-none');
                    // target.remove();
                    $('#add-rfq-katashiki').focus();

                    $('.rfq-date-picker').datepicker({
                        format: 'yyyy-mm-dd',
                        inline: false,
                        autoclose: true,
                    }).keydown(function(e) {
                        datepickerKeyDownHandler($(this), e);
                    });

                    $.each($('.add-part-new-rfq').find('input'), function(index, elem) {
                        if ($(elem).val() == 'null')
                            $(elem).val('');
                    })
                }
            })

            $('#update-unRfq-status').click(function() {
                var target = $('#request-unrfq-table').find('tr.selected');
                var targetData = target.data("rowInfo");

                // if (target == null && targetData.is_solved == 1 && (targetData.quote_count == 0 || targetData.cancel_date)) {
                if (target.length == 0)
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                else {
                    if (target == null || targetData.is_cancel == 1) {
                        toastr.warning('フロントエンドにて削除されたので、更新することができません。');
                        return;
                    }
                
                    $.ajax({
                        url: "{{ route('admin.rfq.status.change') }}",
                        method: 'POST',
                        data: {
                            id: target.data("rowInfo").id
                        },
                        success: function(result) {
                            // reqUnRFQTable.draw(true);
                            var status = target.data("status");
                            UpdateProcessDate(status);
                            if (target.data("status") == 1)
                                toastr.success("処理済みにしました。");
                            else
                                toastr.success("処理済みを解除しました。");
                        }
                    });
                }
            })

            function UpdateProcessDate(status)
            {
                var solve_date;
                if(status == 1)
                {
                    solve_date = changeDateFormat(new Date());
                    $('#request-unrfq-table').find('tr.selected').find('td:eq(15)').text(changeDateFormat(new Date()));
                }
                else
                {
                    solve_date = null;
                    $('#request-unrfq-table').find('tr.selected').find('td:eq(15)').text('');
                }
                
                var target = $('#request-unrfq-table').find('tr.selected');
                var targetData = target.data("rowInfo");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('admin.rfq.date') }}",
                    method: "POST",
                    data: {
                        id : targetData.id,
                        solved_date : solve_date,
                    },
                    success: function(data) {
                        reqUnRFQTable.draw(true);
                    },
                });
            }

            $('#quote-send-btn').click(function() {
                var target = $("#quote-from-supplier-table").find('tr.selected');
                if (target.length == 0)
                    toastr.warning('不良が発生したため、作業を確認してください。');
                else {
                    var targetData = target.data("rowInfo");
                    if (targetData.is_send_est == 1) {
                        toastr.warning('この情報は既に送信されました。  ');
                        return;
                    }

                    $.ajax({
                        url: "/admin/rfq/send_quote",
                        method: 'POST',
                        data: {
                            id: targetData.id,
                        },
                        success: function(result) {
                            toastr.success('仕入先からの見積もりが見積もりタブへ送られました。');
                            targetData.is_send_est = 1;
                            targetData.date_quote = result
                            target.data("rowInfo", targetData);
                            target.find("td:eq(" + rfqQuoteColumns.indexOf(originalQuoteKeyArr[
                                2]) + ")").css('background', 'rgb(188, 247, 255)');
                            target.find("td:eq(" + rfqQuoteColumns.indexOf(originalQuoteKeyArr[
                                14]) + ")").text(result);
                        }
                    });
                }
            })

            $('#daily-rfq-btn').click(function() {
                var target = $('#request-unrfq-table').find('tr.selected');
                if (target.length == 0)
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                else {
                    var targetData = target.data("rowInfo");
                    $.ajax({
                        url: "{{ route('admin.rfq.daily') }}",
                        method: 'POST',
                        data: {
                            id: targetData.id,
                            date: targetData.created_at,
                            katashiki: targetData.katashiki,
                            countAspiration: targetData.count_aspiration,
                            maker: targetData.maker,
                        },
                        success: function(result) {
                            // reqUnRFQTable.draw(true);
                            quoteFromSupplierTable.draw(true);
                        }
                    });
                }
            })

            $('#email-send-btn').click(function() {
                $('#cke_1_top').remove();
                $('.cke_editable').css({
                    'font-size': '18px',
                    'font-weight': '500'
                })
                var target = $("#quote-from-supplier-table").find('tr.selected');
                if (target.length == 0)
                    toastr.warning('メールを送信するために行を選択してください。');
                else {
                    var targetData = target.data("rowInfo");

                    // if (targetData.is_sendmail == 1) {
                    //     toastr.warning('この情報はすでに送信されています。');]
                    //     return;
                    // } else {
                    if (targetData.vendor.user_info.address.country == 'JP') {
                        var mailData = [
                            targetData.vendor.user_info.company_name, targetData.rfq_request.customer
                            .representative,
                            (notification) ? notification.message : '',
                            targetData.katashiki, targetData.quantity_buy, targetData.maker, targetData
                            .date_quote,
                            targetData.dc, targetData.rohs, headerQuarterJP.company_name + '</br>' +
                            headerQuarterJP.tel + '</br>' + headerQuarterJP.address
                        ];
                        var params = JSON.parse(quoteTemplateJP.template_params);
                        var emailText = JSON.parse(quoteTemplateJP.template_content);
                        $.each(params, function(index, item) {
                            emailText = emailText.replaceAll(item, mailData[index]);
                        });
                    } else {
                        var mailData = [
                            targetData.vendor.user_info.company_name,
                            targetData.katashiki, targetData.quantity_buy,
                            targetData.maker,
                            targetData.date_quote,
                            targetData.dc,
                            targetData.rohs,
                            headerQuarterEN.company_name + '</br>' + headerQuarterEN.tel + '</br>' +
                            headerQuarterEN.address
                        ];
                        var params = JSON.parse(quoteTemplateEN.template_params);
                        var emailText = JSON.parse(quoteTemplateEN.template_content);
                        $.each(params, function(index, item) {
                            emailText = emailText.replaceAll(item, mailData[index]);
                        });
                    }

                    $('#email-send-modal').find('.email_content').val(emailText);
                    $('#email-send-modal').find('.email_title').val(targetData.vendor.user_info.email1);
                    $('#email-send-modal').modal('show');
                    $('#email_quote_id').val(target.data('rowInfo').id);
                    // }
                }
            })

            $('.send-mail-btn').click(function() {
                $('#email-send-modal').find('.invalid-feedback').remove();
                $('#email-send-modal').find('.is-invalid').removeClass('is-invalid');

                if ($('#email-send-modal').find('.email_title').val() == '') {
                    message = 'このアイテムは必須です.';
                    $('#email-send-modal').find('.email_title').parents('.input-group').append(
                            '<div class="invalid-feedback" style="display: block !important; margin-left: 100px">' +
                            message + '</div>')
                        .find('input').addClass('is-invalid');
                    return;
                }

                if ($('#email-send-modal .email-content').val() == '' || $('#email-send-modal .email-title')
                    .val() == '')
                    toastr.warning('データを入力してください。');

                $.ajax({
                    url: sendQuoteUrl,
                    method: 'POST',
                    data: {
                        id: $('#email_quote_id').val(),
                        email: $('#email-send-modal').find('.email_title').val(),
                        content: $('#email-send-modal').find('.email_content').val()
                    },
                    success: function(result) {
                        toastr.success('見積依頼メールを仕入先に送信しました。');
                        var target = $('#quote-from-supplier-table').find('tr.selected');
                        var targetData = target.data('rowInfo');
                        if (targetData.is_sendmail == 0) {
                            targetData.is_sendmail == 1;
                            target.data('rowInfo', targetData);
                            target.find("td:eq(0)").addClass('tr-orange');
                        }

                        $('#email-send-modal').modal('hide');
                    }
                });
            })

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

            $("input[type=search], #search-model-number").keypress(function(e) {
                var noUseList = '!@#$%^&*(){}[];<>';
                if (noUseList.search(e.key) != -1) {
                    e.preventDefault();
                }
            })

            $('#quote-from-supplier-table tbody').on('dblclick', 'tr', function() {
                if ($(this).find('td.dataTables_empty').length != 0)
                    return;
                var targetData = $(this).data('rowInfo');
                addQuoteTable(targetData);
            });

            $('#search-customer').on('autocomplete.select', function(evt, item) {
                searchCustomerId = item.id;
                reqUnRFQTable.draw();
            });

            $('#message-from-customer').focusout(function() {
                var target = $('#request-unrfq-table').find('tr.selected');
                if (target.length == 0) {
                    $(this).val('');
                    return;
                } else
                    var targetData = target.data("rowInfo");
                var message = $(this).val();
                if (message || message != '') {
                    $.ajax({
                        url: "{{ route('admin.rfq.message.rfq_customer') }}",
                        method: 'POST',
                        data: {
                            id: targetData.id,
                            message: message
                        },
                        success: function(result) {
                            targetData.comment = message;
                            target.data("rowInfo", targetData);
                        }
                    });
                }
            })

            $('textarea.message-box').focusout(function() {
                var target = $('#quote-from-supplier-table').find('tr.selected');
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
                            id: targetData.id,
                            message: message
                        },
                        success: function(result) {
                            targetData.messages.push(result);
                            target.data("rowInfo", targetData);
                        }
                    });
                }
            })

            $('#confirm-btn').click(function() {
                var type = $(this).data('type'),
                    ajaxData = $(this).data('ajaxData'),
                    id = $(this).data('id'),
                    method = "POST",
                    url = null;

                switch (type) {
                    case "updateCustomerInfo":
                        method = "PUT";
                        url = "/admin/customer/" + id;
                        var email1 = $('#customer-info-modal .customer-email1').val();
                        var email2 = $('#customer-info-modal .customer-email2').val();
                        var email3 = $('#customer-info-modal .customer-email3').val();
                        var email4 = $('#customer-info-modal .customer-email4').val();
                        ajaxData.id = id;

                        var checkDupplicate = validationEmails(email1, email2, email3, email4);
                        if (checkDupplicate != 'success') {
                            let elem = $('#customer-info-modal .customer-email' + checkDupplicate[1]);
                            elem.parents('.input-group').append(
                                    '<div class="invalid-feedback" style="display: block !important; margin-left: 100px">メールアドレスが重複されています.</div>'
                                    )
                                .find('input').addClass('is-invalid');
                            $("#confirm-modal").modal('hide');
                            $('#customer-info-modal').data('type', 'invalidConfirm');
                            $('#customer-info-modal').modal('show');
                            return;
                        }
                        break;
                    case "deletePayment":
                        method = "DELETE";
                        url = "/admin/common/" + id;
                        break;
                    case "addNewRfq":
                        $('.add-part-new-rfq').find('.is-invalid').removeClass('is-invalid');
                        method = "POST";
                        url = "{{ route('admin.rfq.store') }}";
                        var ajaxData = getSelectedRFQData();
                        break;
                    case "updateNewRfq":
                        $('.add-part-new-rfq').find('.is-invalid').removeClass('is-invalid');
                        method = "PUT";
                        url = "/admin/rfq/" + $('.add-part-new-rfq').data('id');
                        var ajaxData = getSelectedRFQData();
                        break;
                }

                $.ajax({
                    url: url,
                    method: method,
                    data: ajaxData,
                    success: function(data) {
                        $("#confirm-modal").modal('hide');
                        switch (type) {
                            case "updateCustomerInfo":
                                $('#customer-info-modal').find('input').val('');
                                $('#customer-info-modal').find('textarea').val('');
                                $('#customer-info-modal').find('select').val('');
                                $("#customer-info-modal").data('type', '');
                                reqUnRFQTable.draw(true);
                                toastr.success('顧客情報が更新されます。');
                                break;
                            case "deletePayment":
                                paymentTable.row('.selected').remove().draw(false);
                                paymentTable.draw();
                                delete commonPaymentList[id];
                                break;
                            case "addNewRfq":
                                paymentTable.draw();
                                // addNewRowRfqTable($('.add-part-new-rfq').index(), ajaxData, data);
                                toastr.success('新規RFQリクエストが追加できました。');
                                $.each($('#request-unrfq-table tbody').find('td'), function(
                                    index, item) {
                                    if ($(item).text() == 'null')
                                        $(item).text('');
                                })
                                reqUnRFQTable.draw(true);
                                break;
                            case "updateNewRfq":
                                // updateRowRfqTable($('.add-part-new-rfq').data('index'), ajaxData);
                                toastr.success('新規RFQリクエストが追加できました。');
                                $.each($('#request-unrfq-table tbody').find('td'), function(
                                    index, item) {
                                    if ($(item).text() == 'null')
                                        $(item).text('');
                                })
                                reqUnRFQTable.draw(true);
                                break;
                        }

                        $('.add-part-new-rfq').remove();
                        // reqUnRFQTable.draw(true);
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        if($('.add-part-new-rfq').find('#manufacture').val() == '')
                            toastr.error('メーカーがまだ登録されません。');
                        else
                            toastr.error('正しく入力してください。');
                        for (key in errors) {
                            var message = null,
                                elem = null;
                            switch (key) {
                                case 'compName':
                                    message = errors['compName'];
                                    elem = $('#customer-info-modal .customer-company-name').parents('.input-group');
                                    break;
                                case 'compNameKana':
                                    message = errors['compNameKana'];
                                    elem = $('#customer-info-modal .customer-company-name-kana').parents('.input-group');
                                    break;
                                case 'email1':
                                    message = errors['email1'];
                                    elem = $('#customer-info-modal .customer-email1').parents('.input-group');
                                    break;
                                case 'email2':
                                    message = errors['email2'];
                                    elem = $('#customer-info-modal .customer-email2').parents(
                                        '.input-group');
                                    break;
                                case 'email3':
                                    message = errors['email3'];
                                    elem = $('#customer-info-modal .customer-email3').parents(
                                        '.input-group');
                                    break;
                                case 'email4':
                                    message = errors['email4'];
                                    elem = $('#customer-info-modal .customer-email4').parents(
                                        '.input-group');
                                    break;
                                case 'sales':
                                    message = errors['sales'];
                                    elem = $('#customer-info-modal .customer-sales').parents(
                                        '.input-group');
                                    break;
                                case 'tel':
                                    message = errors['tel'];
                                    elem = $('#customer-info-modal .customer-phone-number')
                                        .parents('.input-group');
                                    break;
                                case 'fax':
                                    message = errors['fax'];
                                    elem = $('#customer-info-modal .customer-fax-number')
                                        .parents('.input-group');
                                    break;
                            }
                            if (elem) {
                                elem.append(
                                        '<div class="invalid-feedback" style="display: block !important; margin-left: 100px">' +
                                        message + '</div>')
                                    .find('input').addClass('is-invalid');
                            }
                            var originalKeyArr = Object.keys(columnsRfqData);
                            if (key == 'katashiki') {
                                $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(
                                    originalKeyArr[7]) + ') input').addClass('is-invalid');
                            } else if (key == 'countAspiration') {
                                $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(
                                    originalKeyArr[8]) + ') input').addClass('is-invalid');
                            } else if (key == 'maker') {
                                $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(
                                    originalKeyArr[5]) + ') input').addClass('is-invalid');
                            } else if (key == 'customer_id') {
                                $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(
                                    originalKeyArr[3]) + ') input').addClass('is-invalid');
                            } else if (key == 'compName') {
                                $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(
                                    originalKeyArr[3]) + ') input').addClass('is-invalid');
                            }
                        }
                        $('#confirm-modal').modal('hide');
                        if (type == 'updateCustomerInfo') {
                            $('#customer-info-modal').data('type', 'invalidConfirm');
                            $('#customer-info-modal').modal('show');
                        } else if (type == 'addNewRfq' || type == 'updateNewRfq') {
                            $('#confirm-modal').data('focusFlag', 'off');
                        }
                    },
                });
            });

            $('#confirm-cancel').click(function() {
                var type = $(this).data("type");
                switch (type) {
                    case "new-unrfq-cancel":
                        $('#request-unrfq-table').find('.add-part-new-rfq').remove();
                        $('#request-unrfq-table .d-none').removeClass('d-none');
                        break;
                    case "updateCustomerInfo":
                        $('#customer-info-modal').modal('show');
                        break;
                }
                $("#confirm-modal").modal('hide');
                return true;
            })

            $('#request-unrfq-table').parents('.dataTables_scrollBody').scroll(function(event) {
                if (this.scrollTop != 0 && (this.scrollTop + this.clientHeight) - document.getElementById(
                        'request-unrfq-table').querySelector('tbody').clientHeight > -1) {
                    if (rfqTableLoadingFlag) {

                        $('.unrfq-table-spin.spin').spin('show');
                        $('.unrfq-table-spin.spin-background').removeClass('d-none');
                        rfqTableLoadingFlag = false;

                        $.ajax({
                            url: '{{ route('admin.rfq.get_more_data') }}',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                currentLength: $('#request-unrfq-table tbody').find('tr').length,
                                order: reqUnRFQTable.context[0].oAjaxData.order,
                                customerName: $('#search-customer').val(),
                                customerId: $('#search-customer-id').val(),
                                receptionDate: $('#search-reception-date').val(),
                                modelNumber: $('#search-model-number').val(),
                                rfqRequestId: $('#search-reception-number').val(),
                                searchStatus: $('#search-status').val(),
                                filterColumn: columnsRfqData[rfqColumns[reqUnRFQTable.context[0]
                                    .oAjaxData.order[0].column]],
                            },
                            success: function(data) {
                                // if (data.length != 0)
                                rfqTableLoadingFlag = true;
                                $('.unrfq-table-spin.spin').spin('hide');
                                $('.unrfq-table-spin.spin-background').addClass('d-none');
                                insertMultiRfqRows(data);
                            }
                        });
                    }
                }
            });

            $(document).on('click', '#request-unrfq-table', function(e) {
                if ($(e.target).parents('.add-part-new-rfq').length != 0)
                    return;

                if ($('#request-unrfq-table').find('.add-part-new-rfq').length != 0) {
                    // $("#confirm-modal").modal('show');
                    if ($('.add-part-new-rfq').data('id'))
                    {
                        $("#confirm-btn").data("type", "updateNewRfq");
                    }
                    else
                        $("#confirm-btn").data("type", "addNewRfq");
                    $('#confirm-cancel').data("type", "new-unrfq-cancel");
                    return;
                }
            })

            $('#request-unrfq-table tbody').on('click', 'tr', function() {
                formatSupplierSection();
                if ($(this).hasClass('add-part-new-rfq') || $(this).find('td.dataTables_empty').length != 0)
                    return;
                if ($('#request-unrfq-table').find('.add-part-new-rfq').length != 0) {
                    return;
                }
                // if ($(this).hasClass('selected'))
                //     return;
                $('#request-unrfq-table').find('tr').removeClass('tr-orange selected');
                $(this).toggleClass('tr-orange').addClass('selected');
                updateByUnrfqTable();
            });

            $('#history-table tbody').on('click', 'tr', function(e) {
                if ($(this).find('td.dataTables_empty').length != 0)
                    return;

                $('#history-table').find('tr.selected').removeClass('selected');
                $('#history-table').find('tr').removeClass('tr-orange');
                $(this).toggleClass('tr-orange').addClass('selected');
            });

            $(document).on('hidden.bs.modal', '.modal', function() {
                if ($(this).data('focusFlag') == 'off') {
                    $(this).data('focusFlag', 'on');
                } else {
                    $('#request-unrfq-table tbody').find('tr:eq(0)').click();
                    $('#request-unrfq-table tbody').find('td:eq(0)').focus();
                }
            })

            // ---------------------------blur event--------------------------
            $(document).on('blur', '#search-reception-date', function(e) {
                var dateString = $(this).val();
                if (dateString == undefined || dateString == '' || !dateString) {
                    reqUnRFQTable.draw();
                    return;
                }
                var formatCheck = validateDate(dateString);
                if (formatCheck)
                    reqUnRFQTable.draw();
                else
                    toastr.warning('無効な日付です。');
            })

            // $(document).on('blur', '.add-part-new-rfq input, .add-part-new-rfq select, .add-part-new-rfq textarea', function(e) {
            //     console.log("replay");
            //     if ($(e.target).hasClass('rfq-date-picker'))
            //         return;

            //     if (!e.relatedTarget && !$(this).hasClass('unrfq-select-customer')) {
            //         validateForm();
                    
            //         if ($('.add-part-new-rfq').data('id'))
            //             $("#confirm-btn").data("type", "updateNewRfq");
            //         else
            //             $("#confirm-btn").data("type", "addNewRfq");
            //         $('#confirm-cancel').data("type", "new-unrfq-cancel");
            //     }
            // })

            $(document).on('blur', '#request-unrfq-table', function(e) {
                if ($(e.target).hasClass('rfq-date-picker'))
                    return;

                if (!e.relatedTarget && $('#request-unrfq-table').find('.add-part-new-rfq').length != 0) {
                    validateForm();
                    if($('#request-unrfq-table').find('.add-part-new-rfq').find('.unrfq-select-customer').val() && $('#request-unrfq-table').find('.add-part-new-rfq').find('#add-rfq-katashiki').val() && $('#request-unrfq-table').find('.add-part-new-rfq').find('#add-count-aspiration').val()) {
                        $("#confirm-modal").modal('show');
                    }
                    if ($('.add-part-new-rfq').data('id'))
                        $("#confirm-btn").data("type", "updateNewRfq");
                    else
                        $("#confirm-btn").data("type", "addNewRfq");
                    $('#confirm-cancel').data("type", "new-unrfq-cancel");
                    return;
                }
            })

            function validateForm() {
                if($('#request-unrfq-table').find('.add-part-new-rfq').find('.unrfq-select-customer').val() == "")
                {
                    $('#request-unrfq-table').find('.add-part-new-rfq').find('.unrfq-select-customer').next().find('.select2-selection').css('border-color', 'red');
                    toastr.warning('客先が入力されてません。');
                    return;
                }
                
                // if($('#request-unrfq-table').find('.add-part-new-rfq').find('.maker').val() == "")
                // {
                //     $('#request-unrfq-table').find('.add-part-new-rfq').find('.maker').next().find('.select2-selection').css('border-color', 'red');
                //     toastr.warning('メーカーがまだ登録されません。');
                //     return;
                // }
                if($('#request-unrfq-table').find('.add-part-new-rfq').find('#add-rfq-katashiki').val() == "")
                {
                    $('#request-unrfq-table').find('.add-part-new-rfq').find('#add-rfq-katashiki').addClass('is-invalid');
                    toastr.warning('型番が入力されてません。');
                    return;
                }
                
                if($('#request-unrfq-table').find('.add-part-new-rfq').find('#add-count-aspiration').val() == "")
                {
                    $('#request-unrfq-table').find('.add-part-new-rfq').find('#add-count-aspiration').addClass('is-invalid');
                    toastr.warning('希望数量が入力されてません。');
                    return;
                }
            }

            $(document).on('blur', '#request-unrfq-table tr', function(e) {
                var selectedTr = $(this);
                if (selectedTr.hasClass('direct-edit')) {
                    selectedTr.removeClass('direct-edit');
                    var targetData = getRfqRowByIndex(selectedTr.index() + 1);
                    $.ajax({
                        url: "/admin/rfq/" + targetData.id,
                        method: 'PUT',
                        data: targetData,
                        success: function(data) {
                            var jsonData = JSON.parse(data);
                            selectedTr.data('rowInfo', jsonData);
                            $('#request-unrfq-table').find('.indi-edit').removeClass(
                                'indi-edit');
                        },
                        error: function(xhr, status, error) {
                            var errors = xhr.responseJSON.errors;
                            toastr.error('正しく入力してください。');
                            $(e.relatedTarget).parents('tr').click().end().focus();
                        },
                    });
                }
            });

            // $(document).on('change', '.f-datepicker', function() {
            //     if ($(this).val() == '')
            //         return

            //     var targetElem = $(this).parents('tr');
            //     if (targetElem.hasClass('direct-edit')) {
            //         saveQuoteByIndexFun(targetElem)
            //     }
            // })

            $(document).on('blur', '#quote-from-supplier-table tr', function(e) {
                // if ($(e.relatedTarget).prop('tagName') == 'SPAN' ||
                //     $(e.target).hasClass('f-datepicker')) {
                //     return;
                // }
                if ($(this).find('select').length > 0) {
                    $.each($(this).find('select'), function(index, item) {
                        var tdElem = $(item).parents('td');
                        var value = $(item).val();
                        if ($(item).data('select2')) {
                            //$(item).select2('destroy');
                        }
                        //tdElem.text(value).prop('tabindex', 1);
                    })
                }
                var targetElem = $(this);
                if (targetElem.hasClass('direct-edit')) {
                    saveQuoteByIndexFun(targetElem)
                }
            })

            $(document).on('blur', '.edit-quote input, .edit-quote select', function(e) {
                if ($(e.target).hasClass('quote-date-picker'))
                    return;

                if (!e.relatedTarget) {
                    var supplier = $(this).parents('tr').find('td:eq(' + rfqQuoteColumns.indexOf(
                        originalQuoteKeyArr[2]) + ') select').val();
                    autoEditedQuote($(e.relatedTarget));
                }
            })

            $("#confirm-modal").on('show.bs.modal', function(e) {
                $("#modal-warning").text('このアクションを行いますか。');
            });

            $("#confirm-modal").on('hidden.bs.modal', function(e) {
                $("#modal-warning").text('このアクションを行いますか。');
            });
        });
    </script>
@endsection
