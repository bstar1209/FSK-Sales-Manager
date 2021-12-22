@extends('layouts.frontend.page')

@section('title', 'マイアカウント')

@section('header-container')
    <h4 class="mt-5" style="margin-left: 82px;">@lang('My Account')</h4>
    <div class="d-flex justify-content-around mt-3">
        <a class="btn btn-primary account-tab-btn" data-tab="quote-wait" style="min-width: 200px;">
            @lang('Waiting for a quote') <span id="wait-count" class="badge badge-warning ml-2">{{ $wait_count }}</span> </a>
        <a class="btn btn-primary account-tab-btn" data-tab="registration" style="min-width: 200px;">
            @lang('Change registration information')</a>
        <a class="btn btn-primary account-tab-btn" data-tab="estimate-answer" style="min-width: 200px;">
            @lang('Estimate answer') <span id="wait-count" class="badge badge-warning ml-2">{{ $estimate_count }}</span> </a>
    </div>
    <div class="order-spin spin" data-spin></div>
    <div class="d-flex justify-content-around mt-2">
        <a class="btn btn-primary account-tab-btn" data-tab="order" style="min-width: 200px;">@lang('Ordered') <span
                id="wait-count" class="badge badge-warning ml-2">{{ $order_detail_count }}</span> </a>
        <a class="btn btn-primary account-tab-btn" data-tab="shipment" style="min-width: 200px;">@lang('Shipped')<span
                id="wait-count" class="badge badge-warning ml-2">{{ $shipment_count }}</span></a>
        <a href="{{ route('logout') }}" class="btn btn-primary" style="min-width: 200px;">@lang('Logout')</a>
    </div>
    <hr class="mt-5 mb-4" style="border:1px solid">
@endsection

@section('main-container')
    @include('frontend.quote_wait')
    @include('frontend.estimate_answer')
    @include('frontend.registration')
    @include('frontend.ship')
    @include('frontend.order')
@endsection

@include('frontend.modals.cancel_rfq')
@include('frontend.modals.re_quote')
@include('frontend.modals.confirm_message')
@include("frontend.modals.password_change")
@include('frontend.modals.order_detail')
@include('frontend.modals.payment')

@section('custom_script')
    <script>
        $(function() {
            var rateList = {};

            var storageUrl = "/storage/pdf/";

            function orderRender() {
                $('.order-spin').spin('show');
                $.ajax({
                    url: "{{ route('frontend.order.index') }}",
                    type: 'post',
                    data: {
                        orderDate: $('#order .order_date').val(),
                        orderNumber: $('#order .order_number').val(),
                        modelNumber: $('#order .model_number').val(),
                        period: $('#order .order_period').val()
                    },
                    success: function(data) {
                        $('#account-order-area').empty();
                        $('#account-order-area').append(data);
                        $('.order-spin').spin('hide');
                    }
                });
                return;
            }

            $(document).on('click', '.account-tab-btn', function() {
                $('.account-tab-btn').removeClass('text-warning');
                $(this).addClass('text-warning');

                var selectTab = $(this).data('tab');
                $('.account-tab').addClass('d-none');
                $('#' + selectTab).removeClass('d-none');

                if (selectTab == "estimate-answer") {
                    $('#order-list-table').removeClass('d-none');
                    $('#purchase-btn').removeClass('d-none').prop('disabled', true);
                    $('#user-info-card #carts-list-table').addClass('d-none');
                    $('.quote-request-btn').addClass('d-none');
                } else if (selectTab == 'order') {
                    orderRender();
                    return false;
                } else {
                    $('#order-list-table').addClass('d-none');
                    $('#user-info-card #carts-list-table').removeClass('d-none');
                    $('#quote-request-btn').removeClass('d-none');
                    $('#purchase-btn').addClass('d-none');
                }
            })

            // ----------------------quote-wait-table-------------------------

            var quoteWaitTable = $('#quote-wait-table').DataTable({
                "processing": true,
                "serverSide": false,
                "searching": false,
                "lengthChange": false,
                "scrollCollapse": true,
                "paging": true,
                "bInfo": false,
                "autoWidth": false,
                "responsive": true,
                'language': {
                    "zeroRecords": "テーブル内のデータなし.",
                    "loadingRecords": "&nbsp;",
                    "processing": "読み込み中...",
                    "search": "",
                    "paginate": {
                        "first": "<< @lang('first')",
                        "previous": "< @lang('previous')",
                        "next": "@lang('next') >",
                        "last": "@lang('last') >>"
                    }
                },
                "dom": '<"row view-filter"<"col-md-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-md-12"<"d-flex justify-content-center"ip>>>',
                "ajax": {
                    url: getQuoteWaitUrl,
                    type: 'POST',
                    dataSrc: '',
                    data: function(data) {
                        $('.order-spin').spin('show');
                        data.model = $('.quote-search').val();
                        data.month = $('select.quote-period').val();
                    },
                    complete: function(data) {
                        $('.order-spin').spin('hide');
                        if (Array.isArray(data.responseJSON))
                            $('#wait-count').text(data.responseJSON.length);
                        else
                            $('#wait-count').text(0);
                    }
                },
                'createdRow': function(row, data, dataIndex) {
                    $(row).data('rowInfo', data);
                },
                "columns": [{
                        data: 'created_at',
                        name: '受付日'
                    },
                    {
                        data: null,
                        name: '受付番号',
                        render: function(data, row) {
                            return data.detail_id + ' - ' + data.child_index;
                        }
                    },
                    {
                        data: 'katashiki',
                        name: '型番'
                    },
                    {
                        data: 'maker',
                        name: 'メーカー'
                    },
                    {
                        data: 'dc',
                        name: 'DC'
                    },
                    {
                        data: 'kbn2',
                        name: '地域'
                    },
                    {
                        data: 'quantity_aspiration',
                        name: '希望数',
                        render: function(data, row) {
                            return convertNumberFormat(data);
                        }
                    },
                    {
                        data: 'price_aspiration',
                        name: '希望単価',
                        render: function(data, row) {
                            return convertNumberFormat(data);
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        target: -1,
                        render: function() {
                            return `<a class="btn btn-sm btn-danger text-white update-maker-btn mr-2" data-toggle="modal" data-target="#cancel-rfq-modal">取り消し</a>`;
                        }
                    }
                ],
                // order: [[2, "asc"], [7, "asc"]],
            })

            $("#cancel-rfq-modal").on('show.bs.modal', function(e) {
                var targetTrData = $(e.relatedTarget).parents('tr').data('rowInfo');
                $('#quote-wait-edit-table .cancel-row').find('td:eq(0)').text(targetTrData.created_at);
                $('#quote-wait-edit-table .cancel-row').find('td:eq(1)').text(targetTrData.detail_id +
                    ' - ' + targetTrData.child_index);
                $('#quote-wait-edit-table .cancel-row').find('td:eq(2)').text(targetTrData.katashiki);
                $('#quote-wait-edit-table .cancel-row').find('td:eq(3)').text(targetTrData.maker);
                $('#quote-wait-edit-table .cancel-row').find('td:eq(4)').text(targetTrData.dc);
                $('#quote-wait-edit-table .cancel-row').find('td:eq(5)').text(targetTrData.kbn2);
                $('#quote-wait-edit-table .cancel-row').find('td:eq(6)').text(convertNumberFormat(
                    targetTrData.quantity_aspiration));
                $('#quote-wait-edit-table .cancel-row').find('td:eq(7)').text(convertNumberFormat(
                    targetTrData.price_aspiration));
                $('#cancel-rfq-btn').data('id', targetTrData.id);
            });

            $(document).on('click', '#cancel-rfq-btn', function() {
                $.ajax({
                    url: "{{ route('frontend.rfq.cancel') }}",
                    method: 'POST',
                    data: {
                        id: $(this).data('id')
                    },
                    success: function(result) {
                        $("#cancel-rfq-modal").modal('hide');
                        quoteWaitTable.ajax.reload();
                    }
                });
            })

            $(document).on('change', 'select.quote-period', function() {
                quoteWaitTable.ajax.reload();
            });

            $(document).on('keyup change', '.quote-search', function(e) {
                var noUseList = '!@#$%^&*(){}[];<>';
                if (noUseList.search(e.key) != -1) {
                    e.preventDefault();
                    return;
                }
                quoteWaitTable.ajax.reload();
            });

            $(document).on('keyup change', '.estimate-answer-search', function(e) {
                var noUseList = '!@#$%^&*(){}[];<>';
                if (noUseList.search(e.key) != -1) {
                    e.preventDefault();
                    return;
                }
                estimateAnswerTable.ajax.reload();
            })

            $(document).on('click', '.quote-search-btn', function() {
                quoteWaitTable.ajax.reload();
            })

            $(document).on('click', '.estimate-answer-search-btn', function() {
                estimateAnswerTable.ajax.reload();
            })

            $(document).on('change', 'select.estimate-answer-period', function() {
                estimateAnswerTable.ajax.reload();
            });

            // ---------------estimate_answer--------------------------------------

            function format(d) {
                var maker = (d.maker) ? d.maker : '';
                var dc = (d.dc) ? d.dc : '';
                var kbn2 = (d.kbn2) ? d.kbn2 : '';
                var deadline = d.deadline_quote ? d.deadline_quote : '';
                var rohs = d.rohs ? d.rohs : '';
                var comment = d.comment ? d.comment : '';
                return '<table class="table table-borderless" cellpadding="5" cellspacing="0" border="0" style="padding-left:250px; margin-left: 100px; max-width: 500px">' +
                    '<tr>' +
                    '<td class="text-left" style="width: 30%; font-weight: 600">メーカー:</td>' +
                    '<td class="text-left">' + maker + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td class="text-left" style="width: 30%; font-weight: 600">DC:</td>' +
                    '<td class="text-left">' + dc + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td class="text-left" style="width: 30%; font-weight: 600">地域:</td>' +
                    '<td class="text-left">' + kbn2 + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td class="text-left" style="width: 30%; font-weight: 600">めやす納期:</td>' +
                    '<td class="text-left">' + deadline + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td class="text-left" style="width: 30%; font-weight: 600">Rohs ステータス:</td>' +
                    '<td class="text-left">' + rohs + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td class="text-left" style="width: 30%; font-weight: 600">備考:</td>' +
                    '<td class="text-left">' + comment + '</td>' +
                    '</tr>' +
                    '</table>';
            }

            $.ajax({
                url: "{{ route('admin.rate.list') }}",
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    rateList = data;
                }
            });

            var estimateAnswerTable = $('#estimate-answer-table').DataTable({
                "processing": true,
                "serverSide": false,
                "searching": false,
                "lengthChange": false,
                "scrollCollapse": true,
                "paging": true,
                "bInfo": false,
                "autoWidth": false,
                "responsive": true,
                'language': {
                    "zeroRecords": "テーブル内のデータなし.",
                    "loadingRecords": "&nbsp;",
                    "processing": "読み込み中...",
                    "search": "",
                    "paginate": {
                        "first": "<< @lang('first')",
                        "previous": "< @lang('previous')",
                        "next": "@lang('next') >",
                        "last": "@lang('last') >>"
                    }
                },
                "dom": '<"row view-filter"<"col-md-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-md-12"<"d-flex justify-content-center"ip>>>',
                "ajax": {
                    url: getEstimateAnswerUrl,
                    type: 'POST',
                    dataSrc: '',
                    data: function(data) {
                        $('.order-spin').spin('show');
                        data.model = $('.estimate-answer-search').val();
                        data.month = $('.estimate-answer-period').val();
                    },
                    complete: function(data) {
                        $('.order-spin').spin('hide');
                        if (Array.isArray(data.responseJSON))
                            $('#estimate-count').text(data.responseJSON.length);
                        else
                            $('#estimate-count').text(0);
                    }
                },
                'createdRow': function(row, data, dataIndex) {
                    $(row).data('rowInfo', data);
                },
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {
                        data: 'created_at',
                        name: '見積日',
                        render: function(data, row) {
                            return changeDateFormat(new Date(data));
                        }
                    },
                    {
                        data: 'quote_code',
                        name: '見積番号'
                    },
                    {
                        data: 'katashiki',
                        name: '型番'
                    },
                    {
                        data: 'sell_quantity',
                        name: '見積数',
                        render: function(data, type) {
                            return convertNumberFormat(data);
                        }
                    },
                    {
                        data: 'unit_sell',
                        name: '見積単位'
                    },
                    {
                        data: 'unit_price_sell',
                        name: '単価',
                        render: function(row, type, data) {
                            var moneyType = '';
                            // var unitPrice = (!data.unit_sell) ? 0 : data.unit_sell;
                            if (data.type_money_sell == 'JPY')
                                moneyType = '円';
                            else if (data.type_money_sell == 'USD')
                                moneyType = '$';
                            else if (data.type_money_sell == 'EUR')
                                moneyType = '€';

                            var price = ((data.money_buy*(rateList[data.type_money_buy] ? rateList[data.type_money_buy].buy_rate : 1)*data.buy_quantity)/(1-data.rate_profit)+data.fee_shipping)/(data.sell_quantity ? data.sell_quantity : data.sell_quantity_second)/(rateList[data.type_money_sell] ? rateList[data.type_money_sell].sale_rate : 1);

                            return data.price_quote ? data.price_quote + ' ' + moneyType : price.toFixed(2)+ ' ' + moneyType;
                        }
                    },
                    {
                        data: null,
                        target: -1,
                        orderable: false,
                        render: function() {
                            // <a class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#re-quote-modal">再見積依頼</a>
                            return `<div class="d-flex flex-row">
                                <a class="btn btn-sm btn-primary mr-2 add-order-btn">買い物かごへ</a>
                                <a class="btn btn-sm btn-primary generate-pdf-btn mr-2">PDF見積書</a>
                                <a class="btn btn-sm btn-danger mr-2" data-toggle="modal" data-target="#confirm-modal">削除</a>
                            `;
                        }
                    }
                ],
                "columnDefs": [{
                    "targets": '_all',
                    "defaultContent": ""
                }],
                "order": [
                    [1, "asc"]
                ],
            });

            $('#estimate-answer-table').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = estimateAnswerTable.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });

            $(document).on('click', '.generate-pdf-btn', function() {
                var trData = $(this).parents('tr').data('rowInfo');
                var idArr = [trData.id];
                var ajaxData = {};
                ajaxData[trData.customer_id] = idArr;

                var price = ((trData.money_buy*(rateList[trData.type_money_buy] ? rateList[trData.type_money_buy].buy_rate : 1)*trData.buy_quantity)/(1-trData.rate_profit)+trData.fee_shipping)/(trData.sell_quantity ? trData.sell_quantity : trData.sell_quantity_second)/(rateList[trData.type_money_sell] ? rateList[trData.type_money_sell].sale_rate : 1);

                $.ajax({
                    url: "{{ route('admin.mail.send_customer') }}",
                    type: 'POST',
                    dataType: 'text',
                    data: {
                        customerIds: ajaxData,
                        is_pdf_only: true,
                        price: price.toFixed(2),
                    },
                    success: function(data) {
                        // toastr.success("見積メールを顧客に送信しました.");
                        var pdfUrl = storageUrl + data;
                        window.open(pdfUrl, "_blank");
                    }
                });
            })

            $("#re-quote-modal").on('show.bs.modal', function(e) {
                var targetTrData = $(e.relatedTarget).parents('tr').data('rowInfo');
                $('#re-quote-modal .model-number').val(targetTrData.katashiki);
                $('#re-quote-modal .quantity').val(targetTrData.sell_quantity);
                $('#re-quote-btn').data('id', targetTrData.id);
            });

            $("#confirm-modal").on('show.bs.modal', function(e) {
                var targetTr = $(e.relatedTarget).parents('tr');
                var targetTrData = targetTr.data('rowInfo');
                $('#confirm-btn').data('quoteId', targetTrData.id);
                $('#confirm-btn').data('type', 'quote-delete');
            });

            $(document).on('click', '#re-quote-btn', function() {
                var qty = $('#re-quote-modal .quantity').val();
                $.ajax({
                    url: "{{ route('frontend.re_quote_request') }}",
                    type: 'POST',
                    dataType: 'text',
                    data: {
                        id: $(this).data('id'),
                        qty: qty
                    },
                    success: function(data) {
                        estimateAnswerTable.ajax.reload();
                        $("#re-quote-modal").modal('hide');
                    }
                });
            });

            $(document).on('click', '#confirm-btn', function() {
                var quoteId = $(this).data('quoteId');
                var type = $(this).data('type');

                $.ajax({
                    url: "{{ route('frontend.quote_customer.delete') }}",
                    type: 'POST',
                    dataType: 'text',
                    data: {
                        id: quoteId,
                    },
                    success: function(data) {
                        $('#confirm-modal').modal('hide');
                    }
                });
            });

            $(document).on('click', '.add-order-btn', function() {
                var data = $(this).parents('tr').data('rowInfo');
                var unitPrice = (!data.unit_sell) ? 0 : data.unit_sell;
                var moneyType = '';
                if (data.type_money_sell == 'JPY') {
                    moneyType = '円';
                } else if (data.type_money_sell == 'USD') {
                    moneyType = '$';
                } else if (data.type_money_sell == 'EUR') {
                    moneyType = '€';
                } else {
                    moneyType = '円';
                }

                var orderIds = $('#order-list-table').data('ids');
                if (orderIds.includes(data.id)) {
                    toastr.warning('部品が買い物かごに存在しています。');
                    return false;
                } else {
                    orderIds.push(data.id);
                }

                var price = 0;
                price = ((data.money_buy*(rateList[data.type_money_buy] ? rateList[data.type_money_buy].buy_rate : 1)*data.buy_quantity)/(1-data.rate_profit)+data.fee_shipping)/(data.sell_quantity ? data.sell_quantity : data.sell_quantity_second)/(rateList[data.type_money_sell] ? rateList[data.type_money_sell].sale_rate : 1);

                $('#order-list-table tbody').append(`
                    <tr data-rowinfo='` + JSON.stringify(data) + `'><td>` + data.katashiki + `</td><td>` + convertNumberFormat(
                        data.sell_quantity) + `</td><td>` + (data.price_quote ? data.price_quote + ' ' + moneyType : price.toFixed(2)+ ' ' + moneyType) + `</td><td><a class="delete-cart" href="#"><img src="` + trashImg + `"></a></td></tr>`);
                $('#purchase-btn').prop('disabled', false);
            });

            function purchaseRender() {
                $('.order-spin').spin('show');
                $.ajax({
                    url: "{{ route('frontend.purchase.index') }}",
                    type: 'post',
                    success: function(data) {
                        $('.order-spin').spin('hide');
                        $('#main-card-wrapper').html(data);
                    }
                });
            }

            $(document).on('click', '.copy-to-delivery-address', function() {
                var index = $(this).data('index'),
                    type = $(this).data('type'),
                    customer = $(this).data('customer');

                $.ajax({
                    url: "{{ route('frontend.billing_address.copy') }}",
                    type: 'post',
                    data: {
                        index: index
                    },
                    success: function(data) {
                        toastr.success('内容を納品先住所' + index + 'へコピーしました。');
                        purchaseRender();
                        return;
                    }
                });

            });

            $(document).on('click', '#purchase-btn', function() {
                purchaseRender();
                // location.assign("{{ route('frontend.purchase.index') }}");
            })

            //---------------------registration-------------------------------------------

            @if (is_array(Auth::user()->customer->user_info->payment))
                var payment = "{{ Auth::user()->customer->user_info->payment }}";
                var close_date = "{{ Auth::user()->customer->user_info->payment[0]->close_date }}";
                var send_date = "{{ Auth::user()->customer->user_info->payment[0]->send_date }}"
            
                if (close_date != null && send_date != null) {
                var closeDate = close_date.split('-');
                var sendDate = send_date.split('-');
                var currentDate = new Date();
                var currentMonth = currentDate.getMonth()+1;
                $("select.customer-close-date").val(closeDate[2]);
                $("select.customer-send-date").val(sendDate[2]);
                if(parseInt(currentMonth) === parseInt(sendDate[1])){
                var index = "0";
                } else if((parseInt(sendDate[1]) - parseInt(currentMonth)) === 1) {
                var index = "1";
                } else {
                var index = "2";
                }
                $("select.customer-type-date").val(index);
                }
                }
            @endif

            $('.customer-business-type').val("{{ Auth::user()->customer->user_info->address->comp_type }}")
            $(document).on('click', '#basic-customer-update', function() {
                $('#registration').find('.invalid-feedback').remove();
                $('#registration').find('.is-invalid').removeClass('is-invalid');

                var closeDay = $('#payment-modal .customer-close-date').val();
                var sendDay = $('#payment-modal .customer-send-date').val();
                var typeDate = $('#payment-modal .customer-type-date').val();

                var sendDate = new Date();
                sendDate.setDate(sendDay);
                var closeDate = new Date();
                closeDate.setMonth(closeDate.getMonth() + parseInt(typeDate));
                closeDate.setDate(closeDay);

                var storedData = {
                    compName: $('#registration .customer-company-name').val(),
                    compNameKana: $('#registration .customer-name').val(),
                    sales: $('#registration .customer-sales').val(),
                    rank: '{{ Auth::user()->customer->user_info->rank }}',
                    representative: $('#registration .customer-name').val(),
                    email1: $('#registration .customer-email1').val(),
                    email2: $('#registration .customer-email2').val(),
                    email3: $('#registration .customer-email3').val(),
                    email4: $('#registration .customer-email4').val(),
                    tel: $('#registration .customer-phone-number').val(),
                    homepage: $('#registration .customer-home-page').val(),
                    businessType: $('#registration .customer-business-type').val(),
                    fax: $('#registration .customer-fax-number').val(),
                    department: $('#registration .customer-department').val(),
                    message: $('#registration .customer-remarks').val(),
                    sendDate: sendDate.toDateString(),
                    closeDate: closeDate.toDateString(),
                    payment: [$("#customer-payment-form input[type='radio']:checked").data('commonid'),
                        1
                    ],
                    id: "{{ Auth::user()->customer->id }}"
                };

                method = "PUT";
                url = "/frontend/customer/{{ Auth::user()->customer->id }}";
                var email1 = $('#registration .customer-email1').val();
                var email2 = $('#registration .customer-email2').val();
                var email3 = $('#registration .customer-email3').val();
                var email4 = $('#registration .customer-email4').val();

                var checkDupplicate = validationEmails(email1, email2, email3, email4);

                if (checkDupplicate != 'success') {
                    let elem = $('#registration .customer-email' + checkDupplicate[1]);
                    elem.parents('.input-group').append(
                            '<div class="invalid-feedback" style="display: block !important; margin-left: 100px">メールアドレスが重複されています.</div>'
                            )
                        .find('input').addClass('is-invalid');
                    return;
                }
                $.ajax({
                    url: url,
                    method: method,
                    data: storedData,
                    success: function(data) {
                        toastr.success('正常に変更されました。');
                        $('.given_name').empty().text($('#registration .customer-name').val());
                        $('.company_name').empty().text($('#registration .customer-company-name').val());
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        toastr.error('正しく入力してください。');
                        for (key in errors) {
                            var message = null,
                                elem = null;
                            switch (key) {
                                case 'compName':
                                    message = errors['compName'];
                                    elem = $('#registration .customer-company-name').parents(
                                        '.input-group');
                                    break;
                                case 'compNameKana':
                                    message = errors['compNameKana'];
                                    elem = $('#registration .customer-name')
                                        .parents('.input-group');
                                    break;
                                case 'email1':
                                    message = errors['email1'];
                                    elem = $('#registration .customer-email1').parents(
                                        '.input-group');
                                    break;
                                case 'email2':
                                    message = errors['email2'];
                                    elem = $('#registration .customer-email2').parents(
                                        '.input-group');
                                    break;
                                case 'email3':
                                    message = errors['email3'];
                                    elem = $('#registration .customer-email3').parents(
                                        '.input-group');
                                    break;
                                case 'email4':
                                    message = errors['email4'];
                                    elem = $('#registration .customer-email4').parents(
                                        '.input-group');
                                    break;
                                case 'sales':
                                    message = errors['sales'];
                                    elem = $('#registration .customer-sales').parents(
                                        '.input-group');
                                    break;
                                case 'tel':
                                    message = errors['tel'];
                                    elem = $('#registration .customer-phone-number').parents(
                                        '.input-group');
                                    break;
                                case 'fax':
                                    message = errors['fax'];
                                    elem = $('#registration .customer-fax-number').parents(
                                        '.input-group');
                                    break;
                            }
                            if (elem) {
                                elem.append(
                                        '<div class="invalid-feedback" style="display: block !important; margin-left: 115px">' +
                                        message + '</div>')
                                    .find('input').addClass('is-invalid');
                            }
                        }
                    },
                });
            })

            $(document).on('click', "#change-password-btn", function() {
                $('#password-change-modal').find('.is-invalid').removeClass('is-invalid');
                $('#password-change-modal').find('.invalid-feedback').remove();
                var old = $('#password-change-modal .cur-password').val();
                var password = $('#password-change-modal .new-password').val();
                var password_confirmation = $('#password-change-modal .confirm-password').val();

                $.ajax({
                    url: "{{ route('frontend.password.change') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        old: old,
                        password: password,
                        password_confirmation: password_confirmation
                    },
                    success: function(data) {
                        $('#password-change-modal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        var elem = $('#password-change-modal');
                        for (key in errors) {
                            var errorElem = null,
                                message = null;
                            switch (key) {
                                case 'old':
                                    errorElem = elem.find('.cur-password');
                                    message = errors['old'];
                                    break;
                                case 'password':
                                    errorElem = elem.find('.new-password');
                                    message = errors['password'];
                                    break;
                                case 'password_confirmation':
                                    errorElem = elem.find('.confirm-password');
                                    message = errors['password_confirmation'];
                                    break;
                                default:
                                    errorElem = null;
                                    break;
                            }
                            if (errorElem) {
                                if (message == undefined || message == null)
                                    message = "スペースがあってはなりません.";
                                errorElem.parents('.input-group').append(
                                        '<div class="invalid-feedback" style="display: block !important;">' +
                                        message + '</div>')
                                    .find('input').addClass('is-invalid');
                            }
                        }
                    }
                });
            })

            $(document).on('focus', '.customer-payment-terms', function() {
                $('#payment-modal').modal('show');
            })

            $(document).on('change', "#customer-payment-form", function() {
                var lastInput = $("#payment-type-radio").prop('checked');
                if (lastInput)
                    $('#payment-terms').removeClass('d-none');
                else
                    $('#payment-terms').addClass('d-none');
            });

            $(document).on('click', '#payment-choose-btn', function() {
                $('#payment-modal').modal('hide');
                var payment = $('#customer-payment-form input[type="radio"]:checked');
                $('.customer-payment-terms').val(payment.siblings('label').text());
                $('.customer-payment-terms').data('id', payment.data('commonid'));
            })

            // -----------------------shippment------------------------------

            $('.ship_date, .order_date').datepicker({
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

            $(document).on('keypress', '#shipment input', function() {
                shipmentTable.ajax.reload();
            });

            $(document).on('change', '#shipment select', function() {
                shipmentTable.ajax.reload();
            })

            var shipmentTable = $('#shipment-table').DataTable({
                "processing": true,
                "serverSide": false,
                "searching": false,
                "lengthChange": false,
                "scrollCollapse": true,
                "paging": true,
                "bInfo": false,
                "autoWidth": false,
                "responsive": true,
                'language': {
                    "zeroRecords": "テーブル内のデータなし.",
                    "loadingRecords": "&nbsp;",
                    "processing": "読み込み中...",
                    "search": "",
                    "paginate": {
                        "first": "<< @lang('first')",
                        "previous": "< @lang('previous')",
                        "next": "@lang('next') >",
                        "last": "@lang('last') >>"
                    }
                },
                "dom": '<"row view-filter"<"col-md-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-md-12"<"d-flex justify-content-center"ip>>>',
                "ajax": {
                    url: "{{ route('frontend.shipment.list') }}",
                    type: 'POST',
                    dataSrc: '',
                    data: function(data) {
                        $('.order-spin').spin('show');
                        data.ship_date = $('#shipment .ship_date').val();
                        data.order_number = $('#shipment .ship_order_number').val();
                        data.invoice_number = $('#shipment .ship_invoice_number').val();
                        data.model_number = $('#shipment .ship_model_number').val();
                        data.billing_number = $('#shipment .ship_billing_number').val();
                        data.order_period = $('#shipment .ship_order_period').val();
                    },
                    complete: function() {
                        $('.order-spin').spin('hide');
                    }
                },
                'createdRow': function(row, data, dataIndex) {
                    $(row).data('rowInfo', data);
                },
                "columns": [{
                        data: 'export_date',
                        name: "出荷日"
                    },
                    {
                        data: 'order_detail.order_no_by_customer',
                        name: "注文番号"
                    },
                    {
                        data: 'katashiki',
                        name: "型番"
                    },
                    {
                        data: 'maker',
                        name: "メーカー"
                    },
                    {
                        data: 'order_detail.sale_qty',
                        name: "数量"
                    },
                    {
                        data: 'order_detail.sale_cost',
                        name: "単価"
                    },
                    {
                        data: 'invoice_code',
                        name: "送り状番号"
                    },
                    {
                        data: 'out_tr',
                        name: "請求番号"
                    },
                    {
                        data: null,
                        orderable: false,
                        name: "",
                        render: function(index, row, data) {
                            return '<button class="btn btn-primary btn-sm shipment-pdf-download" data-id=' +
                                data.id + '>PDF請求書</button>';
                        }
                    },
                ],
            });

            $(document).on('click', '.shipment-pdf-download', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route('frontend.ship.pdf') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        var pdfUrl = storageUrl + data;
                        window.open(pdfUrl, "_blank");
                    }
                });
            })

            // --------------------------order-list-----------------------------
            $('#order-detail-modal').on('show.bs.modal', function(e) {
                var tr = $(e.relatedTarget).parents('tr');
                var trData = tr.data('rowinfo');
                $('.order-number').text(tr.data('order-num'));
                var tbodyData = '';
                var totalSaleMoney = 0;
                var typeMoney = trData.type_money;

                $.each(trData, function(index, item) {
                    var itemMoney = (parseInt(item.sale_qty) * parseInt(item.sale_cost ? item.sale_cost : 0));
                    if (!itemMoney && itemMoney == undefined && isNaN(itemMoney))
                        itemMoney = 0;

                    totalSaleMoney += itemMoney;
                    tbodyData += `<tr><td>` + item.katashiki + `</td><td>` + 
                        convertNumberFormat(item.sale_qty) + `</td><td>` + 
                        convertNumberFormat(item.sale_cost) + ` 円</td><td>` +
                        convertNumberFormat(itemMoney) + ` 円</td></tr>`;
                });

                var tax_money = totalSaleMoney * parseFloat(tr.data('tax'));
                var fee_shipping = tr.data('fee-shipping');


                $('#order-detail-table tbody').html(tbodyData);
                $('#order-detail-modal .payment-cond').val(tr.data('payment'));
                $('#order-detail-modal .send-address').val(tr.data('send-address'));
                $('#order-detail-modal .request-address').val(tr.data('request-address'));
                $('#order-detail-modal .total-excluding-tax').val(convertNumberFormat(totalSaleMoney) +
                    ' 円');
                $('#order-detail-modal .sale_tax').val(convertNumberFormat(tax_money) + ' 円');
                $('#order-detail-modal .fee-shipping').val(convertNumberFormat(fee_shipping));
                $('#order-detail-modal .total-all-money').val(convertNumberFormat(fee_shipping +
                    totalSaleMoney + tax_money) + ' 円');

                $.each($('#order-detail-table tbody').find('td'), function(index, item) {
                    if ($(item).text() == 'null')
                        $(item).text('');
                })
            });

            $(document).on('keyup', '.order_number, .model_number', function() {
                orderRender();
            })

            $(document).on('change', '.order_date, .order_period', function() {
                orderRender();
            })

            $(document).on('keyup',
                '.ship_order_number, .ship_invoice_number, .ship_model_number, .ship_billing_number',
                function() {
                    shipmentTable.ajax.reload();
                })

            $(document).on('change', '.ship_order_period, .ship_date', function() {
                shipmentTable.ajax.reload();
            })

        })
    </script>
@endsection
