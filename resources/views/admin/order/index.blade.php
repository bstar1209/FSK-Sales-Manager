@extends('layouts.page')

@section('title', 'Order')

@section('header-container')
<div class="row">
    @include('admin/order/partials/search_area')
    <div class="col-7">
        <div class="row">
            @include('admin/order/partials/customer_info')
            @include('admin/order/partials/actions')
        </div>
    </div>
</div>
@endsection

@inject('table_config', 'App\Models\TableConfig')

@php
    $order_info = $table_config->where('table_name', $table_config::$names[5])->first();
    $order_columns = json_decode($order_info->column_names);
    $order_widths = json_decode($order_info->column_info);
@endphp

@section('table-container')
    @include('admin/order/partials/order_table')
@endsection

@section('other-container')
    @include('admin/order/modals/order_detail_change')
    @include('admin/modals/update_customer_info')
    @include('admin/modals/billing_address')
    @include('admin/modals/confirm_message')
@endsection

@section('custom_script')
<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('vendor/ckeditor/adapters/jquery.js') }}"></script>
<script>
    var orderColumns = @json($order_columns);
    var orderWidths = @json($order_widths);

    originalIndexs = {
        "@lang('Order number')":orderColumns.indexOf("@lang('Order number')")+1,
        "@lang('Order date')":orderColumns.indexOf("@lang('Order date')")+1,
        "@lang('Customer order number')":orderColumns.indexOf("@lang('Customer order number')")+1,
        "@lang('Payment terms')":orderColumns.indexOf("@lang('Payment terms')")+1,
        "@lang('Customer')":orderColumns.indexOf("@lang('Customer')")+1,
        "@lang('Responsible')":orderColumns.indexOf("@lang('Responsible')")+1,
        "@lang('Estimated date')":orderColumns.indexOf("@lang('Estimated date')")+1,
        "@lang('Quote number')":orderColumns.indexOf("@lang('Quote number')")+1,
        "@lang('Manufacturer')":orderColumns.indexOf("@lang('Manufacturer')")+1,
        "@lang('Model number')":orderColumns.indexOf("@lang('Model number')")+1,
        "@lang('DC')":orderColumns.indexOf("@lang('DC')")+1,
        "@lang('Rohs')":orderColumns.indexOf("@lang('Rohs')")+1,
        "@lang('Country')":orderColumns.indexOf("@lang('Country')")+1,
        "@lang('Estimated number')":orderColumns.indexOf("@lang('Estimated number')")+1,
        "@lang('Estimated unit')":orderColumns.indexOf("@lang('Estimated unit')")+1,
        "@lang('Estimate Remarks')":orderColumns.indexOf("@lang('Estimate Remarks')")+1,
        "@lang('Estimated delivery date')":orderColumns.indexOf("@lang('Estimated delivery date')")+1,
        "@lang('Gross margin')":orderColumns.indexOf("@lang('Gross margin')")+1,
        "@lang('Gross profit')":orderColumns.indexOf("@lang('Gross profit')")+1,
        "@lang('Sales quantity')":orderColumns.indexOf("@lang('Sales quantity')")+1,
        "@lang('Selling unit')":orderColumns.indexOf("@lang('Selling unit')")+1,
        "@lang('Selling currency')":orderColumns.indexOf("@lang('Selling currency')")+1,
        "@lang('Selling price')":orderColumns.indexOf("@lang('Selling price')")+1,
        "@lang('Selling amount')":orderColumns.indexOf("@lang('Selling amount')")+1,
        "@lang('Customer desired delivery date')":orderColumns.indexOf("@lang('Customer desired delivery date')")+1,
        "@lang('Canceled customer')":orderColumns.indexOf("@lang('Canceled customer')")+1,
    };
</script>
<script src="{{ asset('js/admin/order/functions.js') }}"></script>
<script src="{{ asset('js/admin/order/datatables.js') }}"></script>
<script src="{{ asset('js/admin/order/shortkey.js') }}"></script>
<script>
$(function() {

    loadCommonList();
    loadCustomerInfoList();
    loadMakerList();
    $('.email_content').ckeditor();

    //search
    $('#search-customer').autoComplete({
        resolver: 'custom',
        events: {
            search: function (qry, callback) {
                callback(customerInfoList.filter(function(item) {
                    const matcher = new RegExp('^' + qry, 'i');
                    return matcher.test(item.user_info.company_name)
                        || matcher.test(item.user_info.company_name_kana)
                }));
            }
        },
        formatResult: function (item) {
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

    $('#search-maker').autoComplete({
        resolver: 'custom',
        events: {
            search: function (qry, callback) {
                callback(makerList.filter(function(item) {
                    const matcher = new RegExp('^' + qry, 'i');
                    return matcher.test(item.maker_name)
                }));
            }
        },
        formatResult: function (item) {
            return {
                value: item.id,
                text: item.maker_name,
                html: [
                    `${item.maker_name}`,
                ]
            };
        },
        noResultsText: '',
        minLength: 1
    })

    $('#search-maker').on('autocomplete.select', function(evt, item) {
        orderTable.draw();
    });

    $('#search-estimated-date, #search-order-date').datepicker({
        format: 'yyyy-mm-dd',
        inline: false,
    }).keydown(function(event) {
        var code = event.keyCode || event.which;
        // If key is not TAB
        var parts = $(this).val().split("/"),
            currentDate = new Date(parts[2], parts[0] - 1, parts[1]);
        switch (code) {
            case 27:
                $(this).datepicker('hide');
                return false;
                break;
            case 113:
                $(this).datepicker('show');
                return false;
                break;
            case 37:
                event.preventDefault();
                event.stopPropagation();
                currentDate.setDate(currentDate.getDate() - 1);
                break;
            case 38:
                event.preventDefault();
                event.stopPropagation();
                currentDate.setDate(currentDate.getDate() - 7);
                break;
            case 39:
                event.preventDefault();
                event.stopPropagation();
                currentDate.setDate(currentDate.getDate() + 1);
                break;
            case 40:
                event.preventDefault();
                event.stopPropagation();
                currentDate.setDate(currentDate.getDate() + 7);
                break;
        };
    });

    $(document).on('blur', '#search-estimated-date, #search-order-date', function(e) {
        var dateString = $(this).val();
        if (dateString == undefined || dateString == '' || !dateString) {
            orderTable.draw();
            return;
        }
        var formatCheck = validateDate(dateString);
        if (formatCheck)
            orderTable.draw();
        else
            toastr.warning('無効デートです。');
    })

    $(document).on('keypress', '#search-estimated-date, #search-order-date', function(e) {
        useList = '0123456789-';
        if (useList.search(e.key) == -1) {
            return false;
        }
    })

    $(document).on('keyup change', '#search-status, #search-customer, #search-maker, #search-order-number, #search-model, #search-quote', function() {
        orderTable.draw();
    })

    $('#search-area-clear').click(function() {
        $('#search-area').find('input').val('');
        $('#search-area').find('select').val('1');
        orderTable.draw();
    })

    //datatable
    $(document).on('click', 'th .all-order-check', function() {
        if ($(this).prop('checked'))
            $('.order-check').prop('checked', true);
        else
            $('.order-check').prop('checked', false);
    })

    $(document).on('click', '#order-table tbody tr', function() {
        if($(this).find('td.dataTables_empty').length != 0)
            return;
        $('#order-table').find('tr').removeClass('tr-orange selected');
        $(this).toggleClass('tr-orange').addClass('selected');
        updatedByChangedOrderTable();
    })

    $("#order-table").parents('.dataTables_scrollBody').scroll(function(event) {
        if (this.scrollTop != 0 && (this.scrollTop + this.clientHeight) - document.getElementById('order-table').querySelector('tbody').clientHeight > -1) {
            var currentLength = $('#order-table tbody').find('tr').length;
            if (currentLength == 1)
                return ;

            if (orderTableLoadingFlag) {
                $('.order-table-spin.spin').spin('show');
                $('.order-table-spin.spin-background').removeClass('d-none');
                orderTableLoadingFlag = false;
                $.ajax({
                    url: '{{ route("admin.order.get_order_more_list") }}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        currentLength : currentLength,
                        order : orderTable.context[0].oAjaxData.order,
                        customerName : $('#search-customer').val(),
                        maker : $('#search-maker').val(),
                        modelNumber : $('#search-model').val(),
                        estimateDate : $('#search-estimated-date').val(),
                        orderDate : $('#search-order-date').val(),
                        orderNumber : $('#search-order-number').val(),
                        quoteCode : $('#search-quote').val(),
                        searchStatus : $('#search-status').val(),
                        filterColumn : columnsData[orderColumns[orderTable.context[0].oAjaxData.order[0].column-1]],
                    },
                    success: function(data) {
                        $('.order-table-spin.spin').spin('hide');
                        $('.order-table-spin.spin-background').addClass('d-none');
                        insertMultiOrderRows(data);
                        if (data.length != 0)
                            orderTableLoadingFlag = true;
                    }
                });
            }
        }
    })

    //actions
    $('#to-order-btn').click(function() {
        var checkedStatus = checkSelectRow();
        if(checkedStatus.ids.length > 0) {
            $.ajax({
                url: "{{ route('admin.order.update_kbn') }}",
                type: 'post',
                data: {
                    'idList':checkedStatus.ids
                },
                success: function(data){
                    // searchReceiveOrder();
                    toastr.success('発注タブへ転送しました。');
                    orderTable.draw();
                }
            });
        } else
            toastr.warning('更新を行う為に、行を選択して下さい。');
    })

    $("#customer-update-btn").click(function() {
        var target = $('#order-table').find('tr.selected');
        if (target.length == 0)
            toastr.warning('更新対象顧客を選択してください。');
        else
            $('#customer-info-modal').modal('show');
    });

    $("#customer-info-modal").on('show.bs.modal', function() {
        if ($("#customer-info-modal").data("type") == "invalidConfirm") {
            return;
        }
        var customerInfo = $('#customer-info').data('customerInfo');
        $('#customer-info-modal').data('id', customerInfo.id);
        $('#customer-info-modal .customer-company-name').val(customerInfo.user_info.company_name);
        $('#customer-info-modal .customer-company-name-kana').val(customerInfo.user_info.company_name_kana);
        $('#customer-info-modal .customer-sales').val(customerInfo.representative_business);
        $('#customer-info-modal .customer-rank').val(customerInfo.user_info.rank);
        $('#customer-info-modal .customer-name').val(customerInfo.representative);
        $('#customer-info-modal .customer-email1').val(customerInfo.user_info.email1);
        $('#customer-info-modal .customer-email2').val(customerInfo.user_info.email2);
        $('#customer-info-modal .customer-email3').val(customerInfo.user_info.email3);
        $('#customer-info-modal .customer-email4').val(customerInfo.user_info.email4);
        if (customerInfo.user_info.address) {
            $('#customer-info-modal .customer-phone-number').val(customerInfo.user_info.address.tel);
            $('#customer-info-modal .customer-home-page').val(customerInfo.user_info.address.homepages);
            $('#customer-info-modal .customer-business-type').val(customerInfo.user_info.address.comp_type);
            $('#customer-info-modal .customer-fax-number').val(customerInfo.user_info.address.fax);
            $('#customer-info-modal .customer-department').val(customerInfo.user_info.address.part_name);
        }

        if (Array.isArray(customerInfo.user_info.payment) && customerInfo.user_info.payment[0]) {
            var payment = customerInfo.user_info.payment[0];
            if (payment.close_date != null && payment.send_date != null) {
                var closeDate = payment.close_date.split('-');
                var sendDate = payment.send_date.split('-');
                var currentDate = new Date();
                var currentMonth = currentDate.getMonth()+1;
                $("select.customer-close-date").val(closeDate[2]);
                $("select.customer-send-date").val(sendDate[2]);
                if(parseInt(currentMonth) === parseInt(sendDate[1])){
                    var index = "0";
                }else if((parseInt(sendDate[1]) - parseInt(currentMonth)) === 1) {
                    var index = "1";
                }else{
                    var index = "2";
                }
                $("select.customer-type-date").val(index);
            }
        }

        if($('form.payment-1').find('input').length == 0) {
            $.each(commonList, function(key, item) {
                if (item['type'] == 0)
                    $('form.payment-1').append(`<div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input payment-type" id="payment-type-`+key+`" name="inlineMaterialRadiosExample" data-commonId="`+key+`">
                                        <label class="form-check-label" for="payment-type-`+key+`">`+item.name+`</label>
                                    </div>`);
                else if (item['type'] == 1)
                    $('form.payment-2').append(`<div class="form-check form-check-inline">
                                        <input type="radio" id="payment-type-`+key+`" class="form-check-input"  name="inlineMaterialRadiosExample" data-commonId="`+key+`">
                                        <label class="form-check-label" for="payment-type-`+key+`">`+item.name+`</label>
                                    </div>`);
            })
        }

        if (customerInfo.user_info.payment && customerInfo.user_info.payment.length != 0) {
            for(var i=0; i<customerInfo.user_info.payment.length; i++) {
                $('#payment-type-'+customerInfo.user_info.payment[i].common_id).prop('checked', true);
            }
        }
        $('#customer-info-modal .customer-remarks').val(customerInfo.user_info.message1);
        getCustomerLog(customerInfo.id);
    });

    $('#update-customer-info').click(function() {
        $("#customer-info-modal").find('.invalid-feedback').remove();
        $("#customer-info-modal").find('.is-invalid').removeClass('is-invalid');

        var emails = {
            email1: {
                content: $('#customer-info-modal .customer-email1').val(),
                index: 1
            },
            email2: {
                content: $('#customer-info-modal .customer-email2').val(),
                index: 2
            },
            email3: {
                content: $('#customer-info-modal .customer-email3').val(),
                index: 3
            },
            email4: {
                content: $('#customer-info-modal .customer-email4').val(),
                index: 4
            },
        }
        var closeDay = $('#customer-info-modal .customer-close-date').val();
        var sendDay = $('#customer-info-modal .customer-send-date').val();
        var typeDate = $('#customer-info-modal .customer-type-date').val();
        var sendDate = new Date();
        sendDate.setDate(sendDay);
        var closeDate = new Date();
        closeDate.setMonth(closeDate.getMonth()+parseInt(typeDate));
        closeDate.setDate(closeDay);

        var storedData = {
            compName: $('#customer-info-modal .customer-company-name').val(),
            compNameKana: $('#customer-info-modal .customer-company-name-kana').val(),
            sales: $('#customer-info-modal .customer-sales').val(),
            rank: $('#customer-info-modal .customer-rank').val(),
            representative: $('#customer-info-modal .customer-name').val(),
            emails: emails,
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
            payment: [$("form.payment-1 input[type='radio']:checked").data('commonid'), $("form.payment-2 input[type='radio']:checked").data('commonid')],
        };

        $("#customer-info-modal").modal('hide');
        $("#confirm-modal").modal('show');
        $("#confirm-btn").data("type", "updateCustomerInfo");
        $("#confirm-cancel").data("type", "updateCustomerInfo");
        $("#confirm-btn").data("ajaxData", storedData);
        $("#confirm-btn").data("id", $("#customer-info-modal").data('id'));
    });

    $('#customer-info-modal .edit-billing-address').click(function() {
        $('#billing-address-modal').find('input').val('');
        var data = $('#customer-info').data('customerInfo');
        if ($(this).data('type') == 'address') {
            $('#billing-address-modal').find('h6').each(function(index) {
                $(this).text('請求先住所'+(index+1));
            });

            $.each(data.user_info.billing_address, function(index, item) {
                parentDiv =  $('#billing-address-'+(index+1));
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
                $(this).text('納品先住所'+(index+1));
            });
            $("#billing-address-modal").data('type', 'delivery');
            $("#billing-address-modal").data('userId', data.user_info.id);
            $.each(data.user_info.deliver_address, function(index, item) {
                parentDiv =  $('#billing-address-'+(index+1));
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
            elem = $('#billing-address-'+index);
            elem.find('.invalid-feedback').remove();
            elem.find('.is-invalid').removeClass('is-invalid');
        var id = elem.data('address_id'),
            url, method;

        if (id == undefined || id == null || id == '') {
            url = "{{route('admin.address.store')}}";
            method = "POST";
        } else {
            url = '/admin/address/'+id;
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
                    var errorElem = null, message = null;
                    switch(key) {
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
                        errorElem.parents('.input-group').append('<div class="invalid-feedback" style="display: block !important; margin-left: 100px">'+message+'</div>')
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
        orderTable.draw(true);
    });

    $('#confirm-btn').click(function() {
        var type = $(this).data('type'),
            ajaxData = $(this).data('ajaxData'),
            id = $(this).data('id'),
            method = "POST",
            url = null;

        switch(type) {
            case "updateCustomerInfo":
                method = "PUT";
                url = "/admin/customer/" + id;
                var email1 = $('#customer-info-modal .customer-email1').val();
                var email2 = $('#customer-info-modal .customer-email2').val();
                var email3 = $('#customer-info-modal .customer-email3').val();
                var email4 = $('#customer-info-modal .customer-email4').val();

                var checkDupplicate = validationEmails(email1, email2, email3, email4);
                if (checkDupplicate != 'success') {
                    let elem = $('#customer-info-modal .customer-email'+checkDupplicate[1]);
                    elem.parents('.input-group').append('<div class="invalid-feedback" style="display: block !important; margin-left: 100px">メールアドレスが重複されています.</div>')
                            .find('input').addClass('is-invalid');
                    $("#confirm-modal").modal('hide');
                    $('#customer-info-modal').data('type', 'invalidConfirm');
                    $('#customer-info-modal').modal('show');
                    return;
                }
                break;
        }

        $.ajax({
            url: url,
            method: method,
            data: ajaxData,
            success: function(data) {
                $("#confirm-modal").modal('hide');
                if (type == "updateCustomerInfo") {
                    $('#customer-info-modal').find('input').val('');
                    $('#customer-info-modal').find('textarea').val('');
                    $('#customer-info-modal').find('select').val('');
                    $("#customer-info-modal").data('type', '');
                    orderTable.draw(true);
                    toastr.success('顧客情報が更新されます。');
                }

            },
            error: function(xhr, status, error) {
                var errors = xhr.responseJSON.errors;
                toastr.error('正しく入力してください。');
                for (key in errors) {
                    var message = null, elem = null;
                    switch(key) {
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
                            elem = $('#customer-info-modal .customer-email2').parents('.input-group');
                            break;
                        case 'email3':
                            message = errors['email3'];
                            elem = $('#customer-info-modal .customer-email3').parents('.input-group');
                            break;
                        case 'email4':
                            message = errors['email4'];
                            elem = $('#customer-info-modal .customer-email4').parents('.input-group');
                            break;
                        case 'sales':
                            message = errors['sales'];
                            elem = $('#customer-info-modal .customer-sales').parents('.input-group');
                            break;
                        case 'tel':
                            message = errors['tel'];
                            elem = $('#customer-info-modal .customer-phone-number').parents('.input-group');
                            break;
                        case 'fax':
                            message = errors['fax'];
                            elem = $('#customer-info-modal .customer-fax-number').parents('.input-group');
                            break;
                    }
                    if (elem) {
                        elem.append('<div class="invalid-feedback" style="display: block !important; margin-left: 100px">'+message+'</div>')
                        .find('input').addClass('is-invalid');
                    }
                }
                $('#confirm-modal').modal('hide');
                if (type == 'updateCustomerInfo') {
                    $('#customer-info-modal').data('type', 'invalidConfirm');
                    $('#customer-info-modal').modal('show');
                }
            },
        });
    });

    $('#confirm-cancel').click(function() {
        var type = $(this).data("type");
        if (type == "updateCustomerInfo")
            $('#customer-info-modal').modal('show');

        $("#confirm-modal").modal('hide');
        return true;
    });

    $('#invoice-btn').click(function() {
        var checkedStatus = checkSelectRow();
        if (checkedStatus.ids.length <= 0) {
            toastr.warning('更新を行う為に、行を選択して下さい。');
            return ;
        }
        var firstData = $('#order-table tbody').find('tr:eq('+checkedStatus.indexs[0]+')').data('rowInfo');
        var itemNumber = firstData.order_header.item_number;
        // if (firstData.order_header.type_cond_pay != 3) {
        //     toastr.warning('前払い現金振り込み意外は処理しません。');
        //     return ;
        // }
        if (itemNumber != checkedStatus.ids.length) {
            toastr.warning('同オーダで、選択していない明細があります。');
            return ;
        }
        $('#orderHeaderId').val(firstData.order_header.id);
        $('#invoice_form').submit();
    })

    $('#change-status-btn').click(function() {
        var checkedStatus = checkSelectRow();
        if (checkedStatus.ids.length == 0) {
            toastr.warning('更新を行う為に、行を選択して下さい。');
            return;
        }
        $.ajax({
            url: "{{ route('admin.order.change_status') }}",
            method: 'POST',
            data: {
                orderIds : checkedStatus.ids
            },
            success: function(data) {
                orderTable.draw();
            }
        });
    })

    $("#order-cancel-btn").click(function() {
        var checkedStatus = checkSelectRow();
        if (checkedStatus.ids.length == 0) {
            toastr.warning('更新を行う為に、行を選択して下さい。');
            return;
        }
        $.ajax({
            url: "{{ route('admin.order.cancel_order') }}",
            method: 'POST',
            data: {
                cancelIds : checkedStatus.ids
            },
            success: function(data) {
                orderTable.draw();
            }
        });
    })

    $('#order-detail-change-btn').click(function () {
        var customerEmails = [],
            customerNames = [];
        userTantous = [];
        orderNoList = [];
        katashikiList = [];
        saleQtyList = [];
        saleUnitList = [];
        saleCostList = [];
        orderIdList = [];
        orderNo = null;
        customerIdList = [];
        userName = null;
        userEmail = null;
        userTantou = null;
        subMeg = '';

        $.each($('#order-table tr'), function(index, item) {
            if ($(item).find('.order-check').prop("checked")) {
                var data = $(item).data("rowInfo");
                orderIdList.push(data.id);
                customerIdList.push(data.quote_customer.customer.id);
                customerEmails.push(data.quote_customer.customer.user_info.email1);
                customerNames.push(data.quote_customer.customer.user_info.company_name);
                userTantous.push(data.quote_customer.user_res);
                katashikiList.push(data.katashiki);
                saleQtyList.push(data.sale_qty);
                saleUnitList.push(data.sale_unit);
                saleCostList.push(data.sale_cost);
                orderNoList.push(data.order_no_by_customer);
            }
        });
        var uniqueCustomer = customerIdList.filter((v, i, a) => a.indexOf(v) === i);
        if (uniqueCustomer.length != 1) {
            toastr.warning('異なる顧客が選択されています。');
            $('#order-to-modal').modal('hide');
            return ;
        }

        for (var i=0; i < katashikiList.length; i++) {
            subMeg += "型番:"+katashikiList[0]+"</br>数量:"+saleQtyList[0]+saleUnitList[0]+"</br> 単価:"+saleCostList[0]+"</br></br>";
        }

        userEmail = customerEmails[0];
        userName = customerNames[0];
        userTantou = userTantous[0];
        orderNo = orderNoList[0];

        $('#order-detail-change-modal').modal('show');
        $('#order-customer-email').val(userEmail);
        $('#cke_1_top').remove();
    })

    $('#order-detail-change-modal').on('hidden.bs.modal', function() {
        $('#order-detail-change-modal').find('input').val('');
        $('#order-detail-change-modal').find('select').val(0);
        $('#order-detail-change-modal').find('textarea').val('');
    });

    $('#order-confirm-btn').click(function() {
        var ajaxData = {
            email: $('#order-customer-email').val(),
            emailType: $('#order-change-type').val(),
            title: $("#order-change-title").val(),
            content: $("#order-detail-change-modal .email_content").val(),
            idList: orderIdList,
        };
        $.ajax({
            url: "{{ route('admin.mail.send_update_order') }}",
            method: 'POST',
            dataType: 'text',
            data: ajaxData,
            success: function(result) {
                $('#order-detail-change-modal').modal('hide');
                if (ajaxData.emailType == 1)
                    toastr.success('ご注文キャンセルのお知らせを顧客に送信しました。');
                else
                    toastr.success('ご注文内容変更のお知らせを顧客に送信しました。');
            },
            error: function(xhr, status, error) {
            },
        });
    })

    $(document).on('change', '#order-change-type', function() {
        var val = $(this).val();
        var $msg = "";
        var $title = "";
        if (val == 1) {
            $title = 'ご注文キャンセルのお知らせ　ご注文番号 ' + orderNo;
            $msg += '売り切れとの回答でした。<br>';
            $msg += '早急に別ルートで在庫がないか確認してご連絡いたします。<br>';
        } else if (val == 2) {
            $title = 'ご注文内容変更のお知らせ　ご注文番号《' + orderNo + '》';
            $msg += '数量　単価の変更となってしまいました。<br>';
            $msg += '再度見積メールを送りますのでそちらでよろしいかご確認お願いいたします。<br>';
        }

        $content = "";
        $content += userName + " 御中<br>";
        $content += userTantou + " 様<br>";
        $content += "いつもお世話になります。<br>";
        $content += "(株)フォレスカイの吉沼です。<br><br>";
        $content += "ご注文頂きました下記部品ですが、仕入先に手配を依頼したところ<br>";
        $content += $msg;
        $content += "このような事態になってしまい大変申し訳ございませんでした。<br><br>";
        $content += subMeg + "<br>";
        $content += "問い合わせ先：<br>";
        $content += "Mail：hajime@foresky.co.jp<br>";
        $content += "TEL ：04-2963-1276<br><br>";
        $content += "※お問合せに対する対応は下記営業時間内となります。<br>";
        $content += "営業時間：AM10:30-PM5:00(土・日曜日、祝祭日定休)<br><br>";
        $content += "よろしくお願いいたします。 <br>";
        $content += "--------------------------------<br>(株)フォレスカイ<br>吉沼　肇<br>埼玉県入間市久保稲荷4-6-4<br>";
        $content += "ハイム粕谷1-103<br>TEL:　04-2963-1276<br>FAX不可<br>Email: hajime@foresky.co.jp<br>http://www.新しいサイトのアドレス(未定)";

        $("#order-change-title").val($title);
        $("#order-detail-change-modal .email_content").val($content);
    })

    $('textarea.message-box').focusout(function() {
        var target = $('#order-table').find('tr.selected');
        if (target.length == 0) {
            $(this).val('');
            return;
        }
        else
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
})
</script>
@endsection
