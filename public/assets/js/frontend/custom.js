$(function () {

    $('.order-to-desired').datepicker({
        format: 'yyyy-mm-dd',
        inline: false,
    }).keydown(function (event) {
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

    function customerLogUpdate() {
        $.ajax({
            url: customerUpdateLogUrl,
            method: 'POST',
            data: {},
            success: function (result) {
                console.log(result);
            }
        });
    }
    $('#waiting-toast').toast({
        delay: 5000
    });

    $(document).on('click', '#login-customer-btn', function () {
        $('#login-card').find('.invalid-feedback').remove();
        $.ajax({
            url: customerLoginUrl,
            method: 'POST',
            data: {
                email: $('#login-card .username').val(),
                password: $('#login-card .password').val(),
                role: 'customer'
            },
            success: function (result) {
                result = JSON.parse(result);
                if (!result.success) {
                    $('#login-card .username').removeClass('is-invalid');
                    $('#login-card .password').removeClass('is-invalid');

                    if (result.property == 'email') {
                        $('#login-card .username').parents('.col-9').append('<div class="invalid-feedback" style="display: block !important;">ユーザ名が不正です。</div>')
                        .find('input').addClass('is-invalid');

                        $('#login-card .password').parents('.col-9').append('<div class="invalid-feedback" style="display: block !important;">パスワードが正しくないです。</div>')
                        .find('input').addClass('is-invalid');
                    } else if (result.property == 'password') {
                        $('#login-card .password').parents('.col-9').append('<div class="invalid-feedback" style="display: block !important;">パスワードが正しくないです。</div>')
                        .find('input').addClass('is-invalid');
                    }
                } else if (result.success) {
                    loginStatus = true;
                    userProfile = result.user;

                    $('#user-info-card').removeClass('d-none');
                    $('#login-card').addClass('d-none');
                    $('#user-info-card').find('.memebership_number').text(userProfile.customer.id);
                    $('#user-info-card').find('.company_name').text(userProfile.customer.user_info.company_name);
                    $('#user-info-card').find('.given_name').text(userProfile.customer.representative);

                    $('.overseas_send_mail_btn').prop('disabled', false);
                    $('.parts_mass_production_btn').prop('disabled', false);

                    $.ajax({
                        url: getCardListUrl,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            uuid: localStorage.getItem('uuid'),
                        },
                        success: function(data) {
                            $('.carts-list-tbody').empty();
                            if (data.length == 0) {
                                $('.quote-request-btn').prop('disabled', true);
                                return;
                            }
                            $.each(data, function(index, elem) {
                                $('.carts-list-tbody').append(`<tr data-rowinfo='` + JSON.stringify(elem) +
                                    `' class="` + elem.part.katashiki + `">
                                    <td>` + elem.part.katashiki + `</td>
                                    <td><input class="form-control form-control-sm part-qty-input" value="` + elem.qty + `"></td>
                                    <td><a class="delete-cart" href="#"><img src="` + trashImg + `"></a></td>
                                </tr>`);
                            })
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                var errors = xhr.responseJSON.errors;
                for (key in errors) {
                    if (key == 'email') {
                        $('#login-card .username').parents('.col-9').append('<div class="invalid-feedback" style="display: block !important;">' + errors['email'] + '</div>')
                            .find('input').addClass('is-invalid');
                    } else if (key == 'password') {
                        $('#login-card .password').parents('.col-9').append('<div class="invalid-feedback" style="display: block !important;">' + errors['password'] + '</div>')
                            .find('input').addClass('is-invalid');
                    }
                }
            },
        });
    })

    $(document).on('click', '#reset-btn', function () {
        $.ajax({
            url: customerResetUrl,
            method: 'POST',
            data: {
                email: $('#reset-modal .email').val(),
            },
            success: function (result) {
                result = JSON.parse(result);
                if (result["success"]) { 
                    toastr.success(result["msg"]);
                    $('#reset-modal').modal('hide');
                } else {
                    toastr.warning(result["msg"]);
                }
            },
            error: function (xhr, status, error) {
                var errors = xhr.responseJSON.errors;
                for (key in errors) {
                    if (key == 'email') {
                        toastr.warning(errors[key][0]);
                    }
                }
            },
        });
    })

    $(document).on("click", "#agree-to-terms", function () {
        if ($("#agree-to-terms").prop('checked'))
            $('#member-register-btn').prop('disabled', false);
        else
            $('#member-register-btn').prop('disabled', true);
    })

    $(document).on('click', '#member-register-btn', function () {
        if (!$("#agree-to-terms").prop('checked'))
            return

        $('#member-register-modal').find('.is-invalid').removeClass('is-invalid');
        $('#member-register-modal').find('.invalid-feedback').remove();
        var zip = $('#member-register-modal .zip').val();
        $.ajax({
            url: memberRegistertUrl,
            method: 'POST',
            data: {
                company_name: $('#member-register-modal .company-name').val(),
                name: $('#member-register-modal .name').val(),
                email: $('#member-register-modal .email').val(),
                email_confirmation: $('#member-register-modal .email-confirm').val(),
                password: $('#member-register-modal .password').val(),
                password_confirmation: $('#member-register-modal .confirm-password').val(),
                zip: zip.toString().normalize('NFKC'),
            },
            success: function (result) {
                if (!result) {
                    $('#member-register-modal .zip').parents('.input-group').append('<div class="invalid-feedback" style="display: block !important;margin-left: 120px !important">郵便番号から住所を取得できません。</div>')
                        .find('input').addClass('is-invalid');
                    return;
                }
                $('#member-register-modal').modal('hide');
                $('#waiting-toast').toast('show');
                $('#waiting-toast').parent().removeClass('d-none');
                $('#waiting-toast .toast-body').html('自動で確認メールが送信されます。 </br>もし確認メールが届かない場合 は正常に見積もり依頼が送信されてない可能性がございます。 下記へご連絡ください。</br>  電話番号　04-2963-1276');
            },
            error: function (xhr, status, error) {
                var errors = xhr.responseJSON.errors;
                for (key in errors) {
                    switch (key) {
                        case 'company_name':
                            $('#member-register-modal .company-name').parents('.input-group').append('<div class="invalid-feedback" style="display: block !important;margin-left: 120px !important">' + errors['company_name'][0] + '</div>')
                                .find('input').addClass('is-invalid');
                            break;
                        case 'name':
                            $('#member-register-modal .name').parents('.input-group').append('<div class="invalid-feedback" style="display: block !important;margin-left: 120px !important">' + errors['name'][0] + '</div>')
                                .find('input').addClass('is-invalid');
                            break;
                        case 'email':
                            $('#member-register-modal .email').parents('.input-group').append('<div class="invalid-feedback" style="display: block !important;margin-left: 120px !important">' + errors['email'][0] + '</div>')
                                .find('input').addClass('is-invalid');
                            break;
                        case 'email_confirmation':
                            $('#member-register-modal .email-confirm').parents('.input-group').append('<div class="invalid-feedback" style="display: block !important;margin-left: 120px !important">' + errors['email_confirmation'][0] + '</div>')
                                .find('input').addClass('is-invalid');
                            break;
                        case 'password':
                            $('#member-register-modal .password').parents('.input-group').append('<div class="invalid-feedback" style="display: block !important;margin-left: 120px !important">' + errors['password'][0] + '</div>')
                                .find('input').addClass('is-invalid');
                            break;
                        case 'password_confirmation':
                            $('#member-register-modal .confirm-password').parents('.input-group').append('<div class="invalid-feedback" style="display: block !important;margin-left: 120px !important">' + errors['password_confirmation'][0] + '</div>')
                                .find('input').addClass('is-invalid');
                            break;
                        case 'zip':
                            $('#member-register-modal .zip').parents('.input-group').append('<div class="invalid-feedback" style="display: block !important;margin-left: 120px !important">' + errors['zip'][0] + '</div>')
                                .find('input').addClass('is-invalid');
                            break;
                    }
                }
            },
        });
    })

    $(document).on('change', '#model-number-search', function () {
        if ($(this).val().length > 1)
            $('#search-btn').prop('disabled', false);
        else
            $('#search-btn').prop('disabled', true);
    })

    $(document).on('keypress', '#model-number-search', function (e) {
        if (e.keyCode == 13)
            return true;
        var notUseList = '/-.#';
        if (notUseList.search(e.key) != -1) {
            return false;
        }

        if ($(this).val().length > 1)
            $('#search-btn').prop('disabled', false);
        else
            $('#search-btn').prop('disabled', true);
    })

    $(document).on('click', '#search-btn', function () {
        var searchKey = $('#model-number-search').val();
        if (searchKey.length > 2) {
            var searchType = $('#search-type').val();
            var halfSearchKey = searchKey.toString().normalize('NFKC');
            localStorage.setItem('searchKey', halfSearchKey);
            localStorage.setItem('searchType', searchType);
            $('#model-number-search').val(halfSearchKey);
            customerLogUpdate();

            $.ajax({
                url: modelSearchUrl,
                method: 'POST',
                data: {
                    model: searchKey,
                    type: searchType
                },
                success: function (result) {
                    window.location.replace(searchUrl);
                }
            });
        }
        return;
    })

    $(document).on('click', '.delete-cart', function () {
        tr = $(this).parents('tr');
        var trData = tr.data('rowinfo');

        if ($(this).parents('#order-list-table').length > 0) {
            var orderIds = $('#order-list-table').data('ids');
            const index = orderIds.indexOf(trData.id);
            if (index > -1)
                orderIds.splice(index, 1);
            $('#order-list-table').data('ids', orderIds);

            tr.remove();
            if (orderIds.length < 1)
                $('#purchase-btn').prop('disabled', true);
            return false;
        }

        $.ajax({
            url: destroyCartUrl,
            type: 'post',
            dataType: 'json',
            data: {
                id: trData.id
            },
            success: function (data) {
                $('.carts-list-tbody').find('.' + trData.part.katashiki).remove();
                return false;
            }
        });
    });

    $("#quote-request-modal").on('show.bs.modal', function (e) {
        var allData = [];
        var ids = [];

        $.each($('#user-info-card .carts-list-tbody').find('tr'), function (index, item) {
            allData.push($(item).data('rowinfo'));
        })

        $('#quote-request-list-table').find('tbody').empty();
        $.each(allData, function (index, item) {
            var data = null;
            if (typeof item == 'string')
                data = JSON.parse(item);
            else
                data = item;
            ids.push(data.id);
            $('#quote-request-list-table tbody').append(`
                <tr class="tr-bg part-main-`+ data.id + `" style="font-weight: 500" data-rowinfo="` + data + `" data-id=` + data.id + `>
                    <td>`+ data.part.katashiki + `</td>
                    <td>`+ data.part.dc + `</td>
                    <td>`+ data.part.maker + `</td>
                    <td>0</td>
                    <td class="p-0"><input type="number" class="form-control form-control-sm desired-number" min="0" step="any" value="`+ data.part.qty + `"></td>
                    <td class="p-0"><input type="number" class="form-control form-control-sm desired-unit-price" min="0" step="any"></td>
                    <td>`+ data.part.kubun2 + `</td>
                    <td><a class="delete-part" href="#"><img src="`+ trashImg + `"></a></td>
                </tr>
                <tr class="part-`+ data.id + `">
                    <td colspan="2">条件1</td>
                    <td colspan="6"><form class="row form-cond1-`+ data.id + `" style="margin-left: 30px;"><div class="col-4 form-check form-check-inline"><input type="radio" class="form-check-input payment-type" id="cond12-` + data.id + `" data-name="納期優先" name="cond1" checked><label class="form-check-label" for="cond12-` + data.id + `">納期優先</label></div><div class="col-4 form-check form-check-inline"><input type="radio" class="form-check-input payment-type" id="cond11-` + data.id + `" data-name="予算限定" name="cond1"><label class="form-check-label" for="cond11-` + data.id + `">予算限定(希望単価を必ず記入ください)</label></div></form></td>
                </tr>
                <tr class="part-`+ data.id + `">
                    <td colspan="2">条件2</td>
                    <td colspan="6"><form class="row form-cond2-`+ data.id + `" style="margin-left: 30px;"><div class="col-4 form-check form-check-inline"><input type="radio" class="form-check-input payment-type" id="cond21-` + data.id + `" data-name="有鉛可" name="cond2" checked><label class="form-check-label" for="cond21-` + data.id + `">有鉛品も可</label></div><div class="col-4 form-check form-check-inline"><input type="radio" class="form-check-input payment-type" id="cond22-` + data.id + `" data-name="Rohsのみ" name="cond2"><label class="form-check-label" for="cond22-` + data.id + `">Rohs・鉛フリーのみ可</label></div></form></td>
                </tr>
                <tr class="part-`+ data.id + `">
                    <td colspan="2">条件3</td>
                    <td colspan="6"><form class="row form-cond3-`+ data.id + `" style="margin-left: 30px;"><div class="col-4 form-check form-check-inline"><input type="radio" class="form-check-input payment-type" id="cond31-` + data.id + `" data-name="中国可" name="cond3" checked><label class="form-check-label" for="cond31-` + data.id + `">中国在庫も可</label></div><div class="col-4 form-check form-check-inline"><input type="radio" class="form-check-input payment-type" id="cond32-` + data.id + `" data-name="海外可" name="cond3"><label class="form-check-label" for="cond32-` + data.id + `">中国国外への海外在庫も可能です</label></div><div class="col-3 form-check form-check-inline"><input type="radio" class="form-check-input payment-type" id="cond33-` + data.id + `" data-name="国内のみ" name="cond3"><label class="form-check-label" for="cond33-` + data.id + `">国内在庫のみ可</label></div></form></td>
                </tr>
                <tr class="part-`+ data.id + `">
                    <td colspan="2">備考</td>
                    <td colspan="6"><textarea class="form-control remarks-`+ data.id + `" placeholder="お伝えしたいことをご記入ください.(この欄では追加の見積依頼は承れません.)"></textarea></td>
                </tr>
            `)
        });
        $('#quote-request-list-table').find('tbody').data('idArr', ids);
        $.each($('#quote-request-list-table').find('td'), function (index, item) {
            if ($(item).text() == 'null')
                $(item).text('');
        })
    });

    $(document).on('hidden.bs.modal', '.modal', function () {
        $(this).find('input').val('');
        $(this).find('input:checkbox').prop('checked', false);
        $(this).find('select').val(0);
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').remove();
    })

    $(document).on('click', '#quote-request-btn', function () {
        var quoteData = [];
        var arrIds = $('#quote-request-list-table').find('tbody').data('idArr');

        var errorCount = [0, 0];

        $.each(arrIds, function (index, item) {
            var row = $('.part-main-' + item);

            var qty = row.find('input:eq(0)').val();
            var unitPrice = row.find('input:eq(1)').val();

            if (isNaN(qty) || qty < 1) {
                row.find('.desired-number').addClass('is-invalid');
                errorCount[0]++;
            }

            if ((isNaN(unitPrice) || unitPrice < 1) && 
                $('.form-cond1-' + item).find('input[type="radio"]:checked').data('name') == "予算限定") {
                row.find('.desired-unit-price').addClass('is-invalid');
                errorCount[1]++;
            }

            var rowData = {
                id: item,   
                qty: qty,
                unitPrice: unitPrice,
                cond1: $('.form-cond1-' + item).find('input[type="radio"]:checked').data('name'),
                cond2: $('.form-cond2-' + item).find('input[type="radio"]:checked').data('name'),
                cond3: $('.form-cond3-' + item).find('input[type="radio"]:checked').data('name'),
                remarks: $('textarea.remarks-' + item).val()
            }
            quoteData.push(rowData);
        });

        if (errorCount[0] > 0 || errorCount[1] > 0) {
            if (errorCount[0] > 0) {
                toastr.warning('希望の番号を入力する必要があります。');
            }

            if (errorCount[1] > 0) {
                toastr.warning('ご希望の単価をご入力ください。');
            }
            return;
        }

        $.ajax({
            url: createRFQUrl,
            type: 'post',
            data: { data: quoteData },
            success: function (data) {
                $('#quote-request-modal').modal('hide');
                $('#waiting-toast').parent().removeClass('d-none');
                $('#waiting-toast').toast('show');
                $('#waiting-toast .toast-body').html('自動で確認メールが送信されます。 </br>もし確認メールが届かない場合は正常に会員登録がなされてない可能性がございます. 下記へご連絡ください。</br>  電話番号　04-2963-1276');
                $('#carts-list-table tbody').empty();
                $('.quote-request-btn').prop('disabled', true);
            }
        });
    })

    $(document).on('click', '.delete-part', function () {
        var trElem = $(this).parents('tr');
        var id = trElem.data('id');
        $.ajax({
            url: destroyCartUrl,
            type: 'post',
            dataType: 'json',
            data: { id: id },
            success: function (data) {
                trElem.remove();
                // $('#quote-request-list-table tbody').find('.part-'+id).slideDown('slow');
                $('#quote-request-list-table tbody').find('.part-' + id).remove();
                
                if ($('#quote-request-list-table tbody').find('tr').length == 0) {
                    $('#quote-request-modal').modal('hide');
                }
            }
        });
    })

    $(document).on('click', '.edit-address', function () {

        var index = $(this).data('index'),
            type = $(this).data('type'),
            customer = $(this).data('customer'),
            thisBtn = $(this);

        if (type == '0')
            elem = $('.billing-address-' + index);
        else
            elem = $('.delivery-address-' + index);

        $('#purchase-steps, #registration').find('.invalid-feedback').remove();
        $('#purchase-steps, #registration').find('.is-invalid').removeClass('is-invalid');

        var id = elem.data('address_id'),
            url, method;

        if (id == undefined || id == null || id == '') {
            url = createAddressUrl;
            method = "POST";
        } else {
            url = '/admin/address/' + id;
            method = "PUT";
        }
        $.ajax({
            url: url,
            method: method,
            data: {
                type: (type == '0') ? 'billing' : 'delivery',
                user_info_id: customer,
                address_index: index,
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
            success: function (data) {
                thisBtn.text('変更');
                toastr.success('正常に変更されました。');
            },
            error: function (xhr, status, error) {
                var errors = xhr.responseJSON.errors;
                toastr.error('正しく入力してください。');
                for (key in errors) {
                    var errorElem = null, message = null;
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
                        errorElem.parents('.input-group').append('<div class="invalid-feedback" style="display: block !important; margin-left: 100px">' + message + '</div>')
                            .find('input').addClass('is-invalid');
                    }
                }
            }
        });
    })

    $(document).on('keyup', '.part-qty-input', function () {
        var trElem = $(this).parents('tr');
        var trData = trElem.data('rowinfo');
        var data = null;
        if (typeof trData == 'string')
            data = JSON.parse(trData);
        else
            data = trData;
        $.ajax({
            url: updateCardUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                id: data.id,
                qty: $(this).val()
            },
            success: function (data) {
                trElem.data('rowinfo', null);
                trElem.data('rowinfo', JSON.stringify(data));
            }
        });
    })

    $(document).on('click', '#quote-request-set-btn', function () {        
        if (loginStatus) 
            $('#quote-request-modal').modal('show');
        else { 
            if ($('#user-info-card .carts-list-tbody').find('tr').length == 0) {
                $(this).attr("disabled", "disabled");
                return;
            }
            
            $('#before-login').addClass('d-none');
            $('#login-card').removeClass('d-none');
        }
    })

    $(document).on('shown.bs.modal', '.modal', function () {
        $(this).find('.modal-footer button').first().focus();
        return true;
    })

    $(document).on('focusout', '.billing-address-zip-code', function() {
        var elem = $(this);
        var postCode = elem.val();
        var elemParents = elem.parents(".input-group");
        elemParents.find('.invalid-feedback').remove().end().find('input');

        if (!postCode)
            return
        $.ajax({
            url: getAddressFromPostCodeUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                zip: postCode 
            },
            success: function (data) {
                if (!data) {
                    elem.parents('.input-group').append('<div class="invalid-feedback" style="display: block !important; margin-left: 100px">郵便番号から住所を取得できません。</div>').find('input').addClass('is-invalid');
                } else {
                    var addressTemplate = elem.parents('.address-template');
                    addressTemplate.find('.billing-address-zip-code').removeClass('is-invalid');
                    addressTemplate.find('.billing-address-prefecture').removeClass('is-invalid');
                    addressTemplate.find('.billing-address-municipality').removeClass('is-invalid');
                    addressTemplate.find('.billing-address-address').removeClass('is-invalid');
                    addressTemplate.find('.billing-address-zip-code').parent().find('.invalid-feedback').remove();
                    addressTemplate.find('.billing-address-prefecture').parent().find('.invalid-feedback').remove();
                    addressTemplate.find('.billing-address-municipality').parent().find('.invalid-feedback').remove();
                    addressTemplate.find('.billing-address-address').parent().find('.invalid-feedback').remove();
                    addressTemplate.find('.billing-address-prefecture').val(data.city);
                    addressTemplate.find('.billing-address-municipality').val(data.state);
                    addressTemplate.find('.billing-address-address').val(data.street);
                }
            }
        });
    })

    $(document).on('keydown', '.billing-address-tel', function(e) {
        if ((47 < e.keyCode && e.keyCode < 58) || (95 < e.keyCode && e.keyCode < 106 ) || e.keyCode == 189)
            return true;
        else
            return false;
    })
})
