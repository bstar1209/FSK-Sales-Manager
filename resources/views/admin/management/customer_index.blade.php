<div class="row">
    <h6 class="col-12 font-weight-bold text-warning mb-3">@lang('Customer management screen')</h6>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Customer id')</span>
            </div>
            <input type="text" id="search-membership-number" class="form-control" autocomplete="off" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Company name')</span>
            </div>
            <input type="text" id="search-compony-name" class="form-control" autocomplete="off" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Sales representative name')</span>
            </div>
            <input type="text" id="search-sales-representative-name" class="form-control" autocomplete="off" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Rank')</span>
            </div>
            <select id="search-rank" class="form-control">
                <option value=""></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Credit transaction')</span>
            </div>
            <select class="form-control" id="search-credit-transaction">
                <option></option>
                <option value='1'>@lang('Not applied')</option>
                <option value='2'>@lang('Applying')</option>
                <option value='3'>@lang('Apply for permission')</option>
                <option value='4'>@lang('Rejected')</option>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <button class="btn btn-sm btn-primary" id="clear-search-customer">@lang('Clear')</button>
        <button class="btn btn-sm btn-primary" id="register-customer">@lang('Register')</button>
    </div>
</div>

<table class="table table-bordered table-striped w-100" cellspacing="0" id="customer-management-table">
    <thead>
        <tr>
            <th>@lang('Customer id')</th>
            <th>@lang('Registration date')</th>
            <th>@lang('Company name')</th>
            <th>@lang('Company name (Kana)')</th>
            <th>@lang('Name of person in charge')</th>
            <th>@lang('Email')</th>
            <th>@lang('Sales representative name')</th>
            <th>@lang('Rank')</th>
            <th>@lang('Credit transaction')</th>
            <th>@lang('Management')</th>
        </tr>
    </thead>
</table>

<div class="modal fade" id="customer-delete-confirm" tabindex="-1" role="dialog"
aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-warning">通知</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                削除します。よろしいでしょうか?
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="btn-customer-delete-confirm" class="btn btn-primary btn-sm">OK</button>
                <button type="button" class="btn btn-danger btn-sm ml-3" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="customer-modal" tabindex="-1" role="dialog" aria-labelledby="customModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 500px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="confirmModalLabel" class="modal-title text-warning">通知</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" >
                        変更を適用しますか。
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="customer-btn" class="btn btn-primary btn-sm">はい</button>
                <button type="button" id="confirm-cancel" class="btn btn-danger btn-sm">いいえ</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    loadCommonList();
    loadCustomerInfoList();
    commonList = {};
    customerInfo = {};
    let billingAddress = [];
    let deliveryAddress = [];
    let firstUpdate = false;
    $('#search-membership-number, #search-compony-name, #search-sales-representative-name').keyup(function(e) {
        // if (e.keyCode === 13) {
        customerTable.ajax.reload();
        // }
    });

    $('#search-rank, #search-credit-transaction').change(function() {
        customerTable.ajax.reload();
    });

    $('#clear-search-customer').click(function() {
        $('#search-membership-number, #search-compony-name, #search-sales-representative-name, #search-rank, #search-credit-transaction').val('');
        customerTable.ajax.reload();
    });

    $('#search-sales-representative-name').autoComplete({
        resolver: 'custom',
        events: {
            search: function (qry, callback) {
                callback(customerInfoList.filter(function(item) {
                    const matcher = new RegExp('^' + qry, 'i');
                    return matcher.test(item.representative);
                }));
            }
        },
        formatResult: function (item) {
            return {
                value: item.id,
                text: item.representative,
            };
        },
        noResultsText: '',
        minLength: 1
    });

    const customerTable = $('#customer-management-table').DataTable({
        process: true,
        lengthChange: false,
        searching: false,
        info: false,
        pagingType: "full_numbers",
        language: {
            zeroRecords: "見つかりません",
            loadingRecords: "&nbsp;",
            processing: "読み込み中...",
            paginate: {
                first: "<< @lang('first')",
                previous: "< @lang('previous')",
                next: "@lang('next') >",
                last: "@lang('last') >>"
            }
        },
        dom: '<"row view-filter"<"col-md-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-md-12"<"d-flex justify-content-center"ip>>>',
        ajax: {
            url: "{{ route('admin.customer.ajax_list') }}",
            type: "post",
            dataSrc: "",
            data: function (data) {
                data.customer_id = $('#search-membership-number').val();
                data.company_name = $('#search-compony-name').val();
                data.representative = $('#search-sales-representative-name').val();
                data.rank = $('#search-rank').val();
                data.transaction = $('#search-credit-transaction').val();
            }
        },
        columns: [
            {
                data: 'id',
                name: 'customer.id'
            },
            {
                data: 'created_at',
                name: 'customer.created_at',
                render: function(data) {
                    return data.split('T')[0];
                }
            },
            {
                data: 'user_info.company_name',
                name: 'user_info.company_name',
            },
            {
                data: 'user_info.company_name_kana',
                name: 'user_info.company_name_kana',
            },
            {
                data: 'representative',
                name: 'customer.representative',
            },
            {
                data: 'user_info',
                render: function(data) {
                    var emails = '';
                    data.email1 ? emails += data.email1+'<br>': '';
                    data.email2 ? emails += data.email2+'<br>': '';
                    data.email3 ? emails += data.email3+'<br>': '';
                    data.email4 ? emails += data.email4+'<br>': '';
                    return emails;
                }
            },
            {
                data: 'representative_business',
                name: 'customer.representative_business',
                render: function(data, index, row) {
                    if(row.salesman && row.salesman.username_jap)
                        return row.salesman.username_jap;
                    return '';
                }
            },
            {
                data: 'user_info.rank',
                name: 'user_info.rank',
            },
            {
                data: 'user_info.payment',
                render: function(data) {
                    if (!data.length) {
                        return '';
                    // } else if (parseInt(data[0].common_id) != 4) {
                    //     return "@lang('Not applied')";
                    } else if (parseInt(data[0].payment_flag) == 1) {
                        return "@lang('Applying')";
                    } else if (parseInt(data[0].payment_flag) == 2) {
                        return "@lang('Apply for permission')";
                    } else if (parseInt(data[0].payment_flag) == 3) {
                        return "@lang('Rejected')";
                    }
                }
            },
            {
                data: null,
                render: function(data) {
                    return `<div class="d-flex justify-content-center" data-info='`+JSON.stringify(data)+`'><a class="btn btn-sm btn-primary update-customer-btn">@lang('Edit')</a><a class="btn btn-sm btn-danger delete-customer-btn ml-2">@lang('Delete')</a></div>`;
                }
            },
        ],
        order: [[1, 'desc']]
    });

    $('#register-customer').click(function() {
        $("#customer-info-modal").find('.invalid-feedback').remove();
        $("#customer-info-modal").find('.is-invalid').removeClass('is-invalid');

        action = 'create';
        $('#customer-management-table tr.selected').removeClass('selected');
        customerInfo = {};
        billingAddress = [];
        deliveryAddress = [];
        firstUpdate = true;
        $('#customer-info-modal').modal('show');
    });

    $('#customer-management-table').on('click', '.update-customer-btn', function() {
        action = 'update';
        $('#customer-management-table tr.selected').removeClass('selected');
        $(this).parents('tr').addClass('selected');
        customerInfo = $(this).parents('div').data('info');
        firstUpdate = true;
        $('#customer-info-modal').modal('show');
    });

    $('#customer-management-table').on('click', '.delete-customer-btn', function() {
        $('#customer-management-table tr.selected').removeClass('selected');
        $(this).parents('tr').addClass('selected');
        customerInfo = $(this).parents('div').data('info');
        $('#customer-delete-confirm').modal('show');
    });

    $("#customer-info-modal").on('show.bs.modal', function() {
        if (!firstUpdate) {
            $('#customer-info-modal modal-body').find('input').first().focus();
            return;
        }
        
        firstUpdate = false;
       
        $('#customer-info-modal').data('id', action == 'create' ? 0 : customerInfo.id);
        $('#customer-info-modal .customer-company-name').val(!customerInfo.user_info ? '' : customerInfo.user_info.company_name);
        $('#customer-info-modal .customer-company-name-kana').val(!customerInfo.user_info ? '' : customerInfo.user_info.company_name_kana);
        $('#customer-info-modal .customer-sales').val(customerInfo.salesman? customerInfo.salesman.id : '');
        $('#customer-info-modal .customer-rank').val(!customerInfo.user_info ? '' : customerInfo.user_info.rank);
        $('#customer-info-modal .customer-name').val(customerInfo.representative || '');
        $('#customer-info-modal .customer-email1').val(!customerInfo.user_info ? '' : customerInfo.user_info.email1);
        $('#customer-info-modal .customer-email2').val(!customerInfo.user_info ? '' : customerInfo.user_info.email2);
        $('#customer-info-modal .customer-email3').val(!customerInfo.user_info ? '' : customerInfo.user_info.email3);
        $('#customer-info-modal .customer-email4').val(!customerInfo.user_info ? '' : customerInfo.user_info.email4);
        $('#customer-info-modal .customer-email4').val(!customerInfo.user_info ? '' : customerInfo.user_info.email4);
        $('#customer-info-modal .customer-remarks').val(!customerInfo.user_info ? '' : customerInfo.user_info.remarks);
        if (customerInfo.user_info && customerInfo.user_info.address) {
            $('#customer-info-modal .customer-phone-number').val(customerInfo.user_info.address.tel || '');
            $('#customer-info-modal .customer-home-page').val(customerInfo.user_info.address.homepages || '');
            $('#customer-info-modal .customer-business-type').val(customerInfo.user_info.address.comp_type || '');
            $('#customer-info-modal .customer-fax-number').val(customerInfo.user_info.address.fax || '');
            $('#customer-info-modal .customer-department').val(customerInfo.user_info.address.part_name || '');
        } else {
            $('#customer-info-modal .customer-phone-number').val('');
            $('#customer-info-modal .customer-home-page').val('');
            $('#customer-info-modal .customer-business-type').val('');
            $('#customer-info-modal .customer-fax-number').val('');
            $('#customer-info-modal .customer-department').val('');
        }

        if (customerInfo.user_info && customerInfo.user_info.payment[0]) {
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
                    $('form.payment-1').append(`
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input payment-type" id="payment-type-${key}" name="inlineMaterialRadiosExample" data-commonId="${key}">
                            <label class="form-check-label" for="payment-type-${key}">${item.name}</label>
                        </div>
                    `);
                else if (item['type'] == 1)
                    $('form.payment-2').append(`
                        <div class="form-check form-check-inline">
                            <input type="radio" id="payment-type-${key}" class="form-check-input"  name="inlineMaterialRadiosExample" data-commonId="${key}">
                            <label class="form-check-label" for="payment-type-${key}">${item.name}</label>
                        </div>
                    `);
            })
        }

        if (action === 'update' && customerInfo.user_info.payment && customerInfo.user_info.payment.length != 0) {
            for(var i=0; i<customerInfo.user_info.payment.length; i++) {
                var payment_flag;
                $payment_flag = customerInfo.user_info.payment[i].payment_flag+3;
                $('#payment-type-'+ $payment_flag).prop('checked', true);
                $('#payment-type-'+customerInfo.user_info.payment[i].common_id).prop('checked', true);
            }
        }
        $('#customer-info-modal .customer-remarks').text(!customerInfo.user_info ? '' : (customerInfo.user_info.message1 || ''));
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
        $("#customer-modal").modal('show');
        
        $("#customer-btn").data("ajaxData", storedData);
        $("#customer-btn").data("id", $("#customer-info-modal").data('id'));
    });

    $('#customer-info-modal .edit-billing-address').click(function() {
        $('#billing-address-modal').find('input').val('');
        $('#billing-address-modal').find('.is-invalid').removeClass('is-invalid');
        $('#billing-address-modal').find('.invalid-feedback').remove();
        if (customerInfo.user_info) {
            if ($(this).data('type') == 'address') {
                $('#billing-address-modal').find('h6').each(function(index) {
                    $(this).text('請求先住所'+(index+1));
                });
                $.each(customerInfo.user_info.billing_address, function(index, item) {
                    parentDiv = $('#billing-address-'+(index+1));
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
                $("#billing-address-modal").data('userId', customerInfo.user_info.id);
                $('#billingAddressModalLabel').text('客先情報更新');
            } else {
                $('#billing-address-modal').find('h6').each(function(index) {
                    $(this).text('納品先住所'+(index+1));
                });
                $("#billing-address-modal").data('type', 'delivery');
                $("#billing-address-modal").data('userId', customerInfo.user_info.id);
                $.each(customerInfo.user_info.deliver_address, function(index, item) {
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
        }
            $("#billing-address-modal").modal('show');
    });

    $('.edit-address').click(function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);

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
            dataType: 'json',
            data: {
                type: $("#billing-address-modal").data('type'),
                user_info_id: action === 'create' ? 0 : $("#billing-address-modal").data('userId'),
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
                address_index: index

            },
            success: function(data) {
                thisElem.prop('disabled', false);
                if ($("#billing-address-modal").data('type') === 'billing') {
                    billingAddress[index] = data;
                } else {
                    deliveryAddress[index] = data;
                }
                toastr.success('正常に変更されました。');
            },
            error: function(xhr, status, error) {
                thisElem.prop('disabled', false);

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
    });

    $(document).on('click', '#customer-btn', function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);

        var ajaxData = $(this).data('ajaxData');
        ajaxData.id = $(this).data('id');

        if (action === 'create') {
            var method = "POST";
            var url = "{{ route('admin.customer.store') }}";
        }
        else {
            var method = "PUT";
            var url = "/admin/customer/" + $(this).data('id');
        }

        const email1 = $('#customer-info-modal .customer-email1').val();
        const email2 = $('#customer-info-modal .customer-email2').val();
        const email3 = $('#customer-info-modal .customer-email3').val();
        const email4 = $('#customer-info-modal .customer-email4').val();

        const checkDupplicate = validationEmails(email1, email2, email3, email4);
        if (checkDupplicate != 'success') {
            let elem = $('#customer-info-modal .customer-email'+checkDupplicate[1]);
            elem.parents('.input-group')
                .append('<div class="invalid-feedback" style="display: block !important; margin-left: 100px">メールアドレスが重複されています.</div>')
                .find('input')
                .addClass('is-invalid');
            $("#confirm-modal").modal('hide');
            $('#customer-info-modal').data('type', 'invalidConfirm');
            $('#customer-info-modal').modal('show');
            return;
        }
        if (action === 'create') {
            ajaxData.billingAddress = billingAddress;
            ajaxData.deliveryAddress = deliveryAddress;
        }
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name="csrf-token"]').attr('content') } 
        });

        $.ajax({
            url: url,
            method: method,
            data: ajaxData,
            success: function(data) {
                thisElem.prop('disabled', false);
                $("#customer-modal").modal('hide');
                $('#customer-info-modal').find('input').val('');
                $('#customer-info-modal').find('textarea').val('');
                $('#customer-info-modal').find('select').val('');
                customerTable.ajax.reload();
                if(action != 'create')
                {
                    toastr.success('顧客は更新完了しました。');
                }
            },
            error: function(xhr, status, error) {
                thisElem.prop('disabled', false);

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
                        case 'rank':
                            message = errors['rank'];
                            elem = $('#customer-info-modal .customer-rank').parents('.input-group');
                            break;
                    }
                    if (elem) {
                        elem.append('<div class="invalid-feedback" style="display: block !important; margin-left: 100px">'+message+'</div>')
                        .find('input').addClass('is-invalid');
                    }

                    if (key == 'katashiki') {
                        $('.add-part-new-rfq').find('th:eq(7) input').addClass('is-invalid');
                    } else if (key == 'countAspiration') {
                        $('.add-part-new-rfq').find('th:eq(8) input').addClass('is-invalid');
                    } else if (key == 'maker') {
                        $('.add-part-new-rfq').find('th:eq(5) select').addClass('is-invalid');
                        if ($('.add-part-new-rfq').find('th:eq(5) select').val() != '')
                            toastr.error(errors['maker']);
                    } else if(key == 'customer_id') {
                        $('.add-part-new-rfq').find('th:eq(3) input').addClass('is-invalid');
                    }
                }
                $('#customer-modal').modal('hide');
                $('#customer-info-modal').data('type', 'invalidConfirm');
                $('#customer-info-modal').modal('show');
            },
        });
    });

    $('#confirm-cancel').click(function() {
        $("#confirm-modal").modal('hide');
    });

    $("#confirm-modal").on('hidden.bs.modal', function() {
        $("#modal-change").empty().append('本当に削除しますか?');
    });


    $('#btn-customer-delete-confirm').click(function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);

        $.ajax({
            url: '/admin/customer/' + customerInfo.id,
            method: 'DELETE',
            success: function() {
                thisElem.prop('disabled', false);
                customerTable.draw(true);
                $('#customer-delete-confirm').modal('hide');
            }
        })
    });

    function validationEmails (email1, email2, email3, email4) {
        aaa = [email1, email2, email3, email4];
        var duplicate = [];
        var emails = [];

        if (email1 != "" && email1 != undefined) {
            if (emails.indexOf(email1) == -1)
                emails.push(email1);
        }

        if (email2 != "" && email2 != undefined) {
            if (emails.indexOf(email2) == -1)
                emails.push(email2);
            else {
                duplicate = [emails.indexOf(email2)+1, 2];
                return duplicate;
            }
        }

        if (email3 != "" && email3 != undefined) {
            if (emails.indexOf(email3) == -1)
                emails.push(email3);
            else {
                duplicate = [emails.indexOf(email3)+1, 3];
                return duplicate;
            }
        }

        if (email4 != "" && email4 != undefined) {
            if (emails.indexOf(email4) == -1)
                emails.push(email4);
            else {
                duplicate = [emails.indexOf(email4)+1, 4];
                return duplicate;
            }
        }
        return 'success';
    }

    $('.modal').on("hidden.bs.modal", function (e) { 
        if ($('.modal:visible').length) { 
            $('body').addClass('modal-open');
        }
    });
})
</script>
