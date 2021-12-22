@extends('layouts.page')

@section('title', 'Quotation')

@section('header-container')
    <div class="row">
        @include('admin/quotation/partials/search_area')
        <div class="col-7">
            <div class="row">
                @include('admin/quotation/partials/customer_info')
                @include('admin/quotation/partials/supplier_info')
            </div>
        </div>
        @include('admin/quotation/partials/actions')
    </div>
@endsection

@inject('table_config', 'App\Models\TableConfig')
@inject('template_info', 'App\Models\TemplateInfo')
@inject('header_quarter', 'App\Models\HeaderQuarter')

@php
$quote_info = $table_config->where('table_name', $table_config::$names[3])->first();
$quote_columns = json_decode($quote_info->column_names);
$quote_widths = json_decode($quote_info->column_info);

$quote_history_info = $table_config->where('table_name', $table_config::$names[4])->first();
$quote_history_columns = json_decode($quote_history_info->column_names);
$quote_history_widths = json_decode($quote_history_info->column_info);

$quote_templates_to_customer = $template_info->where('template_index', '=', $template_info::$template_type['Quote email to customer'])->first();

$header_quarter_jp = $header_quarter->where('type', '=', $header_quarter::$language_type['JP'])->first();
@endphp

@section('table-container')
    @include('admin/quotation/partials/quote_table')
    @include('admin/quotation/partials/history_table')
@endsection

@section('other-container')
    @include('admin/quotation/modals/order_to')
    @include('admin/rfq/modals/send_supplier_email')
@endsection

@section('custom_script')
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/adapters/jquery.js') }}"></script>
    <script>
        var quoteColumns = @json($quote_columns);
        var quoteWidths = @json($quote_widths);
        var quoteHistoryColumns = @json($quote_history_columns);
        var quoteHistoryWidths = @json($quote_history_widths);
        var quoteTemplate = @json($quote_templates_to_customer);
        var headerQuarter = @json($header_quarter_jp);
        var editableIndexs = [
            quoteColumns.indexOf("見積備考") + 1,
            quoteColumns.indexOf("見積納期") + 1,
            quoteColumns.indexOf("粗利率") + 1,
            quoteColumns.indexOf("売数量") + 1,
            quoteColumns.indexOf("売単位") + 1,
            quoteColumns.indexOf("売通貨") + 1,
            quoteColumns.indexOf("売単価") + 1,
            quoteColumns.indexOf("売金額") + 1,
            // quoteColumns.indexOf("購買メッセージ")+1,
        ];
    </script>
    <script src="{{ asset('js/admin/quotation/functions.js') }}"></script>
    <script src="{{ asset('js/admin/quotation/datatables.js') }}"></script>
    <script src="{{ asset('js/admin/quotation/shortkey.js') }}"></script>
    <script>
        $(function() {

            loadCommonList();
            loadCustomerInfoList();
            loadSupplierInfoList();
            getRatelist();

            var storageUrl = "/storage/pdf/";
            $('.email_content').ckeditor();

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

            $('#search-supplier').autoComplete({
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
            })

            $('#search-area-clear').click(function() {
                $('#search-area').find('input').val('');
                $('#search-area').find('select').val('1');
                quoteTable.draw();
            })

            $('#search-estimate, #search-reception-date, #order-to-desired').datepicker({
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

            $(document).on('keypress', '#search-estimate, #search-reception-date', function(e) {
                useList = '0123456789-';
                if (useList.search(e.key) == -1) {
                    return false;
                }
            })

            $(document).on('blur', '#search-estimate, #search-reception-date', function(e) {
                var dateString = $(this).val();
                if (dateString == undefined || dateString == '' || !dateString) {
                    quoteTable.draw();
                    return;
                }
                var formatCheck = validateDate(dateString);
                if (formatCheck)
                    quoteTable.draw();
                else
                    toastr.warning('無効デートです。');
            })

            $(document).on('focusout change', '#search-quote', function(e) {
                quoteTable.draw();
            })

            $(document).on('keyup', '#search-quote', function(e) {
                if (!e.altKey && ((e.keyCode > 47 && e.keyCode < 58) || (e.keyCode > 95 && e.keyCode <
                        106 || e.keyCode == 8))) {
                    quoteTable.draw();
                    return true;
                }
            })

            $(document).on('keyup change',
                '#search-status, #search-customer, #search-supplier, #search-model, #search-reception, #search-customer-id',
                function() {
                    quoteTable.draw();
                })

            //datatable

            $(document).on('click', 'th .all-quote-check', function() {
                if ($(this).prop('checked'))
                    $('.quote-check').prop('checked', true);
                else
                    $('.quote-check').prop('checked', false);
            })

            $(document).on('click', '#quote-table tr', function() {
                if ($(this).hasClass('quote-edit-tr') || $(this).find('td.dataTables_empty').length != 0)
                    return;

                if ($('#quote-table').find('.quote-edit-tr').length != 0) {
                    autoQuoteSave();
                }

                $('#quote-table').find('tr').removeClass('tr-orange selected');
                $(this).toggleClass('tr-orange').addClass('selected');
                updatedByChangedQuoteTable();
            })

            $(document).on('dblclick', '#quote-table tr', function(e) {

                var target = $(this).addClass('quote-edit-tr');
                var targetData = target.data('rowInfo');
                var sellingUnitPrice = 0;
                var sellingAmount = 0;
                var quotePrefer, sellingAmount, sellingUnitPrice, deadlinQuote, rateProfit, profit,
                    unitSell, typeMoneySell, commentBus, sellQty;

                if (targetData.money_sell) sellingAmount = targetData.money_sell;
                else if (targetData.money_sell_second) sellingAmount = targetData.money_sell_second;
                else sellingAmount = '';

                if (targetData.unit_price_sell) sellingUnitPrice = targetData.unit_price_sell;
                else if (targetData.unit_price_second) sellingUnitPrice = targetData.unit_price_second;
                else sellingUnitPrice = '';

                if (targetData.sell_quantity) sellQty = targetData.sell_quantity;
                else if (targetData.sell_quantity_second) sellQty = targetData.sell_quantity_second;
                else sellQty = '';

                if (targetData.quote_prefer) quotePrefer = targetData.quote_prefer;
                else quotePrefer = '';
                if (targetData.deadline_quote) deadlineQuote = targetData.deadline_quote;
                else deadlineQuote = '';
                if (targetData.rate_profit) rateProfit = targetData.rate_profit;
                else rateProfit = '';
                if (targetData.unit_sell) unitSell = targetData.unit_sell;
                else unitSell = '';
                if (targetData.type_money_sell) typeMoneySell = targetData.type_money_sell;
                else typeMoneySell = '';
                if (targetData.comment_bus) commentBus = targetData.comment_bus;
                else commentBus = '';

                var editableData = [
                    quotePrefer, deadlineQuote, rateProfit, sellQty, unitSell, rateOptionHtml,
                    sellingUnitPrice, sellingAmount, ''
                ];

                $.each(editableIndexs, function(index, item) {
                    if (index == 0 || index == 1 || index == 4) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<input type="text" class="form-control form-control-sm" value="' +
                            editableData[index] + '">');
                    } else if (index == 5) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<select class="form-control form-control-sm quote-currency">' +
                            editableData[index] + '</select>');
                    } else if (index == 2) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<input type="text" class="form-control form-control-sm rate_profit input-check-number" value="' +
                            editableData[index] + '">');
                    } else if (index == 3 || index == 6 || index == 7) {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<input type="text" class="form-control form-control-sm input-check-number" value="' +
                            editableData[index] + '">');
                    } else {
                        target.find('td:eq(' + item + ')').removeClass('p-48').addClass('p-0').html(
                            '<textarea class="form-control form-control-sm un-rfq-textarea"></textarea>'
                        );
                    }
                    target.find('td:eq(' + editableIndexs[0] + ') input').focus();
                    target.find('td:eq(' + editableIndexs[9] + ') textarea').val(commentBus);
                    target.find('td:eq(' + editableIndexs[6] + ') select').val(typeMoneySell);
                });
            })

            $(document).on('keyup', '.rate_profit', function(e) {
                var currValue = $(this).val();
                if (currValue > 1)
                    $(this).val(parseFloat(parseFloat(currValue) - parseInt(currValue)).toFixed(2))
                else if (currValue < 0)
                    $(this).val(0);
            })

            $(document).on('keydown', '.rate_profit', function(e) {
                if ((47 < e.keyCode && e.keyCode < 58) || (95 < e.keyCode && e.keyCode < 105) ||
                    e.keyCode == 130 || e.keyCode == 189 || e.keyCode == 190 || e.keyCode == 8) {
                    if (parseFloat($(this).val()) < 0 || parseFloat($(this).val()) > 1)
                        return false;
                }
            })

            $(document).on('focusout', '.sell_quantity_td, .rate_profit_td', function() {
                var target = $(this).parents('tr');
                autoCalculate(target);
            })

            $(document).on('focusout', '.rate_profit', function() {
                var target = $(this).parents('tr');
                autoCalculate(target);
            })

            $(document).on('change', 'select.quote-currency', function() {
                var currency = $(this).val();
                var target = $(this).parents('tr');
                var targetData = target.data('rowInfo');
                if (target.find('.rate_profit_td input').length > 0)
                    var rateProfit = target.find('.rate_profit_td input').val();
                else
                    var rateProfit = target.find('.rate_profit_td').text();

                if (target.find('.quote_currency_td select').length > 0)
                    var currency = target.find('select.quote-currency').val();
                else
                    var currency = target.find('td.quote_currency_td').text();
                
                var estimatedUnitPrice = ((targetData.money_buy * rateList[target.find('td:eq(18)').text()].buy_rate * targetData.buy_quantity) / (1 - targetData.rate_profit) + targetData.fee_shipping) / (targetData.sell_quantity * rateList[currency].sale_rate);
                if (Number.isNaN(estimatedUnitPrice) || !parseInt(estimatedUnitPrice) || !isFinite(
                        estimatedUnitPrice))
                    estimatedUnitPrice = 0;
                estimatedUnitPrice = Math.round(estimatedUnitPrice * 100000) / 100000;

                var profit = (estimatedUnitPrice * rateList[currency].sale_rate * targetData.sell_quantity) - (targetData.money_buy * rateList[target.find('td:eq(18)').text()].buy_rate * targetData.buy_quantity) - targetData.fee_shipping;
                if (Number.isNaN(profit) || !parseInt(profit) || !isFinite(
                        profit))
                    profit = 0;
                profit = Math.round(profit * 100000) / 100000;
                target.find('td:eq(' + quoteColumns.indexOf(originalQuoteKeyArr[23]) + ')').text(profit.toFixed(2));
                target.find('td:eq(' + quoteColumns.indexOf(originalQuoteKeyArr[24]) + ')').text(estimatedUnitPrice.toFixed(2));
            })

            $(document).on('blur', '.quote-edit-tr input, .quote-edit-tr textarea', function(e) {
                if (!e.relatedTarget || e.relatedTarget.tagName.toLowerCase() == 'table') {
                    autoQuoteSave();
                }
            })

            $("#quote-table").parents('.dataTables_scrollBody').scroll(function(event) {
                if (this.scrollTop != 0 && (this.scrollTop + this.clientHeight) - document.getElementById(
                        'quote-table').querySelector('tbody').clientHeight > -1) {
                    var currentLength = $('#quote-table tbody').find('tr').length;
                    if (currentLength == 1)
                        return;

                    if (quoteTableLoadingFlag) {
                        $('.quote-table-spin.spin').spin('show');
                        $('.quote-table-spin.spin-background').removeClass('d-none');
                        quoteTableLoadingFlag = false;
                        $.ajax({
                            url: '{{ route('admin.quotation.get_quote_more_list') }}',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                currentLength: currentLength,
                                order: quoteTable.context[0].oAjaxData.order,
                                customerName: $('#search-customer').val(),
                                supplierName: $('#search-supplier').val(),
                                modelNumber: $('#search-model').val(),
                                estimateDate: $('#search-estimate').val(),
                                receptionDate: $('#search-reception-date').val(),
                                reception: $('#search-reception').val(),
                                quoteCode: $('#search-quote').val(),
                                customerId: $('#search-customer-id').val(),
                                searchStatus: $('#search-status').val(),
                                filterColumn: columnsQuoteData[quoteColumns[quoteTable.context[0]
                                    .oAjaxData.order[0].column - 1]],
                            },
                            success: function(data) {
                                $('.quote-table-spin.spin').spin('hide');
                                $('.quote-table-spin.spin-background').addClass('d-none');
                                insertMultiQuoteRows(data);
                                if (data.length != 0)
                                    quoteTableLoadingFlag = true;
                            }
                        });
                    }
                }
            })

            $('#history-table_filter').attr('placeholder', '型番から検索 Enterで実行');

            $('#history-table_filter').keyup(function() {
                historyTable.draw();
            })

            //Actions event

            $('#re-investigation-btn').click(function() {
                var allFlag = false,
                    selectIds = [],
                    imposIds = [],
                    checkedFlag = false;
                if ($('.all-quote-check').prop('checked'))
                    allFlag = true;


                var solved_status;
                $solved_status = 1;

                $.each($('.quote-check'), function(index, item) {
                    if ($(item).prop("checked")) {
                        checkedFlag = true;
                        if ($(item).parents('tr').data('rowInfo').request_vendors.rfq_request.is_solved == 1)
                        {
                            $solved_status = 0;
                        }
                        else
                        {
                            if ($(item).parents('tr').data('status') == 1)
                                selectIds.push($(item).parents('tr').data('quoteId'));
                            else
                                imposIds.push($(item).parents('tr').data('quoteId'));
                        }
                    }

                })

                if (!checkedFlag) {
                    toastr.warning('再調査対象の行がまだ選択されていません。');
                    return;
                }

                if ($solved_status == 0) {
                    toastr.warning('RFQ画面で未処理になっていますので、再調査依頼できません。');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.quotation.re_investigation') }}",
                    method: 'POST',
                    data: {
                        ids: imposIds,
                    },
                    success: function(data) {
                        toastr.success('再見積へ依頼しました。');
                        quoteTable.draw(true);
                    }
                });
            })

            $('#change-quote-status-btn').click(function() {
                var ids = checkSelectRow();
                if (ids.length == 0)
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                else {
                    $.ajax({
                        url: "{{ route('admin.quotation.change_status') }}",
                        method: 'POST',
                        data: {
                            ids: ids
                        },
                        success: function(result) {
                            quoteTable.draw(true);
                            if (!result)
                                toastr.success("処理済みにしました。");
                            else
                                toastr.success("処理済みを解除しました。");
                        }
                    });
                }
            })

            $('#sold-out-btn').click(function() {
                var ids = checkSelectRow();
                if (ids.length == 0)
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                else {
                    $.ajax({
                        url: "{{ route('admin.quotation.sold_out') }}",
                        method: 'POST',
                        data: {
                            ids: ids
                        },
                        success: function(result) {
                            quoteTable.draw(true);
                            toastr.success("売り切れになりました");
                        }
                    });
                }
            })

            $('#duplicated-quote-btn').click(function() {
                var ids = checkSelectRow();
                if (ids.length == 0)
                    toastr.warning('更新を行う為に、行を選択して下さい。');
                else {
                    $.ajax({
                        url: "{{ route('admin.quotation.duplicated_quote') }}",
                        method: 'POST',
                        data: {
                            ids: ids
                        },
                        success: function(result) {
                            quoteTable.draw(true);
                            toastr.success("プロセスが完了しました.");
                        }
                    });
                }
            })

            $('#send-to-customer-btn').click(function() {
                var checkedIds = checkSelectRow();
                var customerIds = {};
                $.each($('#quote-table tr'), function(index, item) {
                    if ($(item).find('.quote-check').prop("checked")) {
                        if (customerIds[$(item).data('customerId')])
                            customerIds[$(item).data('customerId')].push($(item).data('quoteId'));
                        else
                            customerIds[$(item).data('customerId')] = [$(item).data('quoteId')];
                    }
                })

                if (checkedIds.length <= 0)
                {
                    toastr.warning('メールを配信する為、行を選択して下さい。');
                    return;
                }

                var target = $("#quote-table").find('tr.selected');
                var targetData = target.data("rowInfo");
                var type_money;
                if (targetData.type_money_sell == 'JPY')
                    $type_money = '円';
                else if (targetData.type_money_sell == 'USD')
                    $type_money = '$';
                else if (targetData.type_money_sell == 'EUR')
                    $type_money = '€';
                else
                    $type_money = targetData.type_money_sell;

                var mailData = [
                    targetData.customer.user_info.company_name, targetData.customer.representative, '',
                    targetData.katashiki, targetData.sell_quantity, targetData.unit_sell, targetData
                    .unit_price_sell, targetData.maker, targetData.deadline_quote, 
                    targetData.dc, targetData.rohs, targetData.kbn2, targetData.quote_prefer, targetData.money_sell, targetData.fee_shipping, targetData.fee_shipping, '', '', '', '', '', headerQuarter.company_name + '</br>' +
                    headerQuarter.tel + '</br>' + headerQuarter.address, $type_money
                ];

                var params = JSON.parse(quoteTemplate.template_params);
                var emailText = JSON.parse(quoteTemplate.template_content);
                $.each(params, function(index, item) {
                    emailText = emailText.replaceAll(item, mailData[index]);
                });

                $('#email-send-modal').find('.email_content').val(emailText);
                $('#email-send-modal').find('.email_title').val(targetData.customer.user_info.email1);
                $('#email-send-modal').modal('show');
                $('#email_quote_id').val(target.data('rowInfo').id);
                $('#cke_1_top').remove()
            })

            $('.send-mail-btn').click(function(){
                var checkedIds = checkSelectRow();
                var customerIds = {};
                $.each($('#quote-table tr'), function(index, item) {
                    if ($(item).find('.quote-check').prop("checked")) {
                        if (customerIds[$(item).data('customerId')])
                            customerIds[$(item).data('customerId')].push($(item).data('quoteId'));
                        else
                            customerIds[$(item).data('customerId')] = [$(item).data('quoteId')];
                    }
                })

                if (checkedIds.length <= 0) {
                    toastr.warning('メールを配信する為、行を選択して下さい。');
                } else {
                    $.ajax({
                        url: "{{ route('admin.mail.send_customer') }}",
                        type: 'POST',
                        dataType: 'text',
                        data: {
                            idList: checkedIds,
                            customerIds: customerIds
                        },
                        success: function(data) {
                            $('#email-send-modal').modal('hide');
                            toastr.success("見積メールを顧客に送信しました.");
                            quoteTable.draw();
                        }
                    });
                }
            })

            $('#email-send-modal').on('show.bs.modal', function() {
                toggleReadOnly();
            })

            CKEDITOR.on('instanceReady', function(ev) {
                editor = ev.editor;
            });

            function toggleReadOnly(isReadOnly) {
                // Change the read-only state of the editor.
                // https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_editor.html#method-setReadOnly
                editor.setReadOnly(isReadOnly);
            }

            $('#order-confirm-btn').click(function() {
                var checkedIds = checkSelectRow();
                var selling_buy_total = 0;
                var customerIds = [];
                var typeMoneyList = [];
                $.each($('#quote-table tr'), function(index, item) {
                    if ($(item).find('.quote-check').prop("checked")) {
                        if (!Number.isNaN(parseInt($(item).find('td:eq(' + quoteColumns.indexOf(
                                originalQuoteKeyArr[28]) + ')').text()))) {
                            selling_buy_total += parseInt($(item).find('td:eq(' + quoteColumns
                                .indexOf(originalQuoteKeyArr[-2]) + ')').text());
                            customerIds.push($(item).data('customerId'));
                            typeMoneyList.push($(item).find('td:eq(' + quoteColumns.indexOf(
                                originalQuoteKeyArr[18]) + ')').text());
                        }
                    }
                })

                var uniqueCustomer = customerIds.filter((v, i, a) => a.indexOf(v) === i);
                if (uniqueCustomer.length != 1) {
                    toastr.warning('異なる顧客が選択されています。');
                    $('#order-to-modal').modal('hide');
                    return;
                }

                ajaxData = {
                    idList: checkedIds,
                    payment: $("#order-to-modal form.payment-1 input[type='radio']:checked").data(
                        'commonid'),
                    orderDesired: $("#order-to-desired").val(),
                    orderYour: $("#order-to-your").val(),
                    sellingBuyTotal: selling_buy_total,
                    customerIds: uniqueCustomer,
                    typeMoneyList: typeMoneyList,
                    nouki: ''
                }
                $.ajax({
                    url: "{{ route('admin.quotation.order_to') }}",
                    type: 'post',
                    dataType: 'text',
                    data: ajaxData,
                    success: function(data) {
                        toastr.success('受注へ送りました。');
                        $('#order-to-modal').modal('hide');
                    }
                });
            });

            $('#quotation-issue-btn').click(function() {
                var checkedIds = checkSelectRow();
                var customerIds = {};
                $.each($('#quote-table tr'), function(index, item) {
                    if ($(item).find('.quote-check').prop("checked")) {
                        if (customerIds[$(item).data('customerId')])
                            customerIds[$(item).data('customerId')].push($(item).data('quoteId'));
                        else
                            customerIds[$(item).data('customerId')] = [$(item).data('quoteId')];
                    }
                })
                if (checkedIds.length <= 0) {
                    toastr.warning('メールを配信する為、行を選択して下さい。');
                } else {
                    $.ajax({
                        url: "{{ route('admin.mail.send_customer') }}",
                        type: 'POST',
                        dataType: 'text',
                        data: {
                            idList: checkedIds,
                            customerIds: customerIds,
                            is_pdf_only: true,
                        },
                        success: function(data) {
                            var pdfUrl = storageUrl + data;
                            window.open(pdfUrl, "_blank");
                        }
                    });
                }
            })

            $('#customer-update-btn').click(function() {
                window.location.assign("{{ route('admin.management.index', 1) }}");
            });

            $("#order-to-modal").on('hidden.bs.modal', function() {
                // $('#customer-info-modal').modal('show');
                $('#order-to-modal').find('input').val('');
                $('#order-to-modal').find('input[type=radio]:eq(0)').prop('checked', true);
                quoteTable.draw(true);
            });

            $('#to-order-btn').click(function() {
                var checkedIds = checkSelectRow();

                if (checkedIds.length <= 0) {
                    toastr.warning('メールを配信する為、行を選択して下さい。');
                    return;
                }

                var customerIds = [];
                $.each($('#quote-table tr'), function(index, item) {
                    if ($(item).find('.quote-check').prop("checked")) {
                        customerIds.push($(item).data('customerId'));
                    }
                })
                var uniqueCustomer = customerIds.filter((v, i, a) => a.indexOf(v) === i);
                if (uniqueCustomer.length != 1) {
                    toastr.warning('異なる顧客が選択されています。');
                    return;
                }

                $('#order-to-modal').modal('show');
                if ($('form.payment-1').find('input').length == 0) {
                    $.each(commonList, function(key, item) {
                        if (item['type'] == 0)
                            $('form.payment-1').append(`<div class="form-check form-check-block">
                                        <input type="radio" class="form-check-input payment-type" id="payment-type-` +
                                key + `" name="inlineMaterialRadiosExample" data-commonId="` + key + `">
                                        <label class="form-check-label" for="payment-type-` + key + `">` + item.name + `</label>
                                    </div>`);
                    });
                    $('form.payment-1').find('input[type=radio]:eq(0)').prop('checked', true);
                }
            });

            $('textarea.message-box').focusout(function() {
                var target = $('#quote-table').find('tr.selected');
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
                            id: targetData.request_vendors.id,
                            message: message
                        },
                        success: function(result) {
                            targetData.request_vendors.messages = [result];
                            target.data("rowInfo", targetData);
                        }
                    });
                }
            })

            $('#history-table tbody').on('click', 'tr', function(e) {
                if ($(this).find('td.dataTables_empty').length != 0)
                    return;

                $('#history-table').find('tr.selected').removeClass('selected');
                $('#history-table').find('tr').removeClass('tr-orange');
                $(this).toggleClass('tr-orange').addClass('selected');
            });

            $(document).on('hidden.bs.modal', '.modal', function() {
                $('#quote-table tbody').find('td:eq(0)').click();
            });

            $(document).on('blur', '#quote-table tr', function() {
                var targetElem = $(this);
                if ($(this).hasClass('direct-edit') && !$(this).hasClass('quote-edit-tr')) {
                    $(this).removeClass('direct-edit');
                    var targetData = getQuoteRowByIndex($(this).index() + 1);

                    $.ajax({
                        url: "/admin/quotation/" + targetData.id,
                        method: 'PUT',
                        data: targetData,
                        success: function(data) {
                            var jsonData = JSON.parse(data);
                            targetElem.data('rowInfo', jsonData);
                            targetElem.find('td:eq(' + quoteColumns.indexOf(originalQuoteKeyArr[
                                22]) + ')').text(jsonData['rate_profit']);
                        },
                        error: function(xhr, status, error) {
                            var errors = xhr.responseJSON.errors;
                            toastr.error('正しく入力してください。');
                            var targetData = targetElem.data("rowInfo")
                            var elem = addQuoteRow(targetData)
                            elem.insertBefore($('#quote-table tbody tr:eq(' + targetElem
                                .index() + ')'))
                            $('#quote-table tbody tr:eq(' + elem.index() + ')').data('rowInfo',
                                targetData);
                            targetElem.remove();
                        },
                    });
                }    
            })
        })
    </script>
@endsection
