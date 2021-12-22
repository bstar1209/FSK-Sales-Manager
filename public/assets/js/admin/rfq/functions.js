var editRFQ = false;
var customerInfoList = [];
var commonPaymentList = {};
var supplierList = [];
var searchCustomerId = null;
var commonList = {};
var makerList = {};
var selectSupplierIdInQuote = null;

var rfqTableLoadingFlag = true;
var historyTableLoadingFlag = true;
var quoteSupplierTableLoadingFlag = true;
var hitoryModelNumber = null;
var rohsOptionHtml = `
<option value="Rohs">Rohs</option>
<option value="鉛フリー">鉛フリー</option>
<option value="有鉛品">有鉛品</option>
<option value="未確認">未確認</option>
<option value="不明">不明</option>`;
var kbnOptionHtml = `
<option value="国内">国内</option>
<option value="北米">北米</option>
<option value="EU">EU</option>
<option value="中国">中国</option>
<option value="海外">海外</option>`;

function getCustomerLog(id) {
    $.ajax({
        url: getCustomerLogUrl,
        type: 'post',
        dataType: 'json',
        data: {
            id: id,
        },
        success: function (data) {
            drawCustomerLog(data);
        }
    });
}

function getRateList() {
    $.ajax({
        url: getCustomerLogUrl,
        type: 'post',
        dataType: 'json',
        data: {
            id: id,
        },
        success: function (data) {
            drawCustomerLog(data);
        }
    });
}

getRatelist();

function drawSupplierInfo(rowInfo) {
    $('#supplier-info').data('supplier', rowInfo);
    $("#supplier-id").text(rowInfo.user_info.company_name);
    // $("#supplier-rank select").val(rowInfo.user_info.rank);
    $("#supplier-rank").text(rowInfo.user_info.rank);
    $("#supplier-rfq-num").text(rowInfo.user_info.est_req_time);
    $("#supplier-est-response-num").text(rowInfo.user_info.est_ans_time);
    $("#supplier-orders-num").text(rowInfo.user_info.order_qty);
    $("#supplier-remarks textarea").text(rowInfo.user_info.message1);
    $("#supplier-purchase-price").text(rowInfo.user_info.order_money);
    $("#supplier-orders-cancel-num").text(rowInfo.cal_po_time);
    if (Array.isArray(rowInfo.user_info.payment) && rowInfo.user_info.payment[0] && rowInfo.user_info.payment[0].common)
        $("#supplier-pay-term").text(rowInfo.user_info.payment[0].common.common_name);
    else
        $("#supplier-pay-term").text('');
    $("#supplier-return-num").text(rowInfo.return_time);
    $("#supplier-sold-out").text(rowInfo.emp_ans_time);
}

function drawCustomerLog(data) {
    $.each($('#customer-log-table tbody').find('tr'), function (index, item) {
        switch (index) {
            case 0:
                $(item).find('td:eq(0)').text(data.log1[0].search_cout);
                $(item).find('td:eq(1)').text(data.log3[0].search_cout);
                $(item).find('td:eq(2)').text(data.log6[0].search_cout);
                $(item).find('td:eq(3)').text(data.all[0].search_cout);
                break;
            case 1:
                $(item).find('td:eq(0)').text(data.log1[0].answer_count);
                $(item).find('td:eq(1)').text(data.log3[0].answer_count);
                $(item).find('td:eq(2)').text(data.log6[0].answer_count);
                $(item).find('td:eq(3)').text(data.all[0].answer_count);
                break;
            case 2:
                $(item).find('td:eq(0)').text(data.log1[0].result_count);
                $(item).find('td:eq(1)').text(data.log3[0].result_count);
                $(item).find('td:eq(2)').text(data.log6[0].result_count);
                $(item).find('td:eq(3)').text(data.all[0].result_count);
                break;
            case 3:
                $(item).find('td:eq(0)').text(data.log1[0].order_qty);
                $(item).find('td:eq(1)').text(data.log3[0].order_qty);
                $(item).find('td:eq(2)').text(data.log6[0].order_qty);
                $(item).find('td:eq(3)').text(data.all[0].order_qty);
                break;
            case 4:
                $(item).find('td:eq(0)').text(data.log1[0].order_money);
                $(item).find('td:eq(1)').text(data.log3[0].order_money);
                $(item).find('td:eq(2)').text(data.log6[0].order_money);
                $(item).find('td:eq(3)').text(data.all[0].order_money);
                break;
        }
    });
}

function formatSupplierSection() {
    $('#supplier-info').data('supplier', '');
    $("#supplier-id").text('');
    // $("#supplier-rank select").val(rowInfo.user_info.rank);
    $("#supplier-rank").text('');
    $("#supplier-rfq-num").text('');
    $("#supplier-est-response-num").text('');
    $("#supplier-orders-num").text('');
    $("#supplier-remarks textarea").text('');
    $("#supplier-purchase-price").text('');
    $("#supplier-orders-cancel-num").text('');
    $("#supplier-pay-term").text('');
    $("#supplier-return-num").text('');
    $("#supplier-sold-out").text('');
}

function drawCustomerInfo(rowInfo) {
    $('#customer-info').data('customerInfo', rowInfo);
    $("#custmer-company-name").text(rowInfo.user_info.company_name);
    $("#customer-orders-cancel-num").text(rowInfo.ord_cal_time);
    if (Array.isArray(rowInfo.user_info.payment) && rowInfo.user_info.payment[0] && rowInfo.user_info.payment[0].common)
        $("#customer-pay-term").text(rowInfo.user_info.payment[0].common.common_name);
    // $("#customer-rank select").val(rowInfo.customer.user_info.rank);
    $("#customer-rank").text(rowInfo.user_info.rank);
    $("#customer-rfq-num").text(rowInfo.user_info.est_req_time);
    $("#customer-est-response-num").text(rowInfo.user_info.est_ans_time);
    $("#customer-orders-num").text(rowInfo.user_info.order_qty);
    $("#customer-order-amount").text(rowInfo.user_info.order_money);
    $("#customer-remarks textarea").text(rowInfo.user_info.message1);
}

function drawSelectPaymentList() {
    $('#register-supplier-payment-term').empty();
    $.each(commonPaymentList, function (key, val) {
        $('#register-supplier-payment-term').append('<option value="' + key + '">' + val + '</option>');
    });
}

function makerAutoComplete() {
    $('.maker').autoComplete({
        resolver: 'custom',
        events: {
            search: function (qry, callback) {
                callback(makerList.filter(function (item) {
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

    $('.maker').on('autocomplete.select', function (evt, item) {
        $(this).focus();
    });
}

function quoteSupplierAutoComplete() {
    loadSupplierInfoList();
    $('.supplier-select').autoComplete({
        resolver: 'custom',
        events: {
            search: function (qry, callback) {
                callback(supplierList.filter(function (item) {
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
    })

    $('.supplier-select').on('autocomplete.select', function (evt, item) {
        selectSupplierIdInQuote = item.id;
        $(this).focus();
    });
}

// ------------------------------un rfq table---------------------------
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

function getRfqRowByIndex(pos) {
    var targetRow = $('#request-unrfq-table').find('tr:eq(' + (pos) + ')');
    var originalKeyArr = Object.keys(columnsRfqData);
    var countAspiration = targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[8]) + ')').text().toString();
    var priceAspiration = targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[9]) + ')').text().toString();
    if (countAspiration == '') countAspiration = '0';
    if (priceAspiration == '') priceAspiration = '0';

    var cancelDate = null, solveDate = null;
    if (targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[15]) + ')').find('input').length > 0)
        solvedDate = targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[15]) + ') input').val();
    else
        solvedDate = targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[15]) + ')').text();

    if (targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[14]) + ')').find('input').length > 0)
        cancelDate = targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[14]) + ') input').val();
    else
        cancelDate = targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[14]) + ')').text();

    var ajaxData = {
        id: targetRow.data('rowInfo').id,
        created_at: targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[0]) + ')').text(),
        customer_id: targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[1]) + ')').text(),
        compName: targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[3]) + ')').text(),
        maker: targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[5]) + ')').text(),
        dc: targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[6]) + ')').text(),
        katashiki: targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[7]) + ')').text(),
        countAspiration: parseFloat(countAspiration.normalize('NFKC')),
        priceAspiration: parseFloat(priceAspiration.normalize('NFKC')),
        kbn: targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[10]) + ')').text(),
        condition1: targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[11]) + ')').text(),
        condition2: targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[12]) + ')').text(),
        condition3: targetRow.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[13]) + ')').text(),
        solved_date: solvedDate,
        cancel_date: cancelDate,
        detail_id: targetRow.data('detailId')
    }
    return ajaxData;
}

function getSelectedRFQData() {
    var originalKeyArr = Object.keys(columnsRfqData);
    var countAspiration = $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[8]) + ') input').val().toString();
    var priceAspiration = $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[9]) + ') input').val().toString();
    if (countAspiration == '')
        countAspiration = '0';
    if (priceAspiration == '')
        priceAspiration = '0';

    var ajaxData = {
        created_at: $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[0]) + ') input').val(),
        customer_id: $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[1]) + ') input').val(),
        compName: $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[3]) + ') select').val(),
        // representative: $('.add-part-new-rfq').find('th:eq(4) input').val(),
        maker: $('.add-part-new-rfq').find('.maker').val(),
        dc: $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[6]) + ') input').val(),
        katashiki: $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[7]) + ') input').val(),
        countAspiration: parseFloat(countAspiration.normalize('NFKC')),
        priceAspiration: parseFloat(priceAspiration.normalize('NFKC')),
        kbn: $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[10]) + ') input').val(),
        condition1: $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[11]) + ') select').val(),
        condition2: $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[12]) + ') select').val(),
        condition3: $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[13]) + ') select').val(),
        // comment: $('.add-part-new-rfq').find('th:eq(16) textarea').val(),
        solved_date: $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[15]) + ') input').val(),
        cancel_date: $('.add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf(originalKeyArr[14]) + ') input').val(),
        detail_id: $('.add-part-new-rfq').data('detailId')
    }
    return ajaxData;
}

function addNewRfq() {
    var MakerListOptionHtml = '<option></option>';
    $.each(makerList, function (index, data) {
        MakerListOptionHtml += '<option value="' + data.maker_name + '">' + data.maker_name + '</option>';
    });

    var customerOptionList = '<option></option>';
    $.each(customerInfoList, function (index, item) {
        customerOptionList += `<option data-info='` + JSON.stringify(item) + `' class="` + item.user_info.company_name_kana + `">` + item.user_info.company_name + `</option>`
    })

    var trChild = `<tr role="row" class="add-part-new-rfq">`;
    var originalKeyArr = Object.keys(columnsRfqData);
    $.each(rfqColumns, function (index, item) {
        var originalIndex = originalKeyArr.indexOf(item);
        switch (originalIndex) {
            case 0:
                trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm" value="` + changeDateFormat(new Date()) + `" style="font-size: 12px !important" disabled></th>`;
                break;
            case 1:
                trChild += `<th class="p-0"><input type="number" class="form-control form-control-sm" disabled></th>`;
                break;
            case 2:
                trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm" disabled></th>`;
                break;
            case 3:
                // trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm unrfq-select-customer" autoComplete="off"></th>`;
                trChild += `<th class="p-0"><select class="form-control form-control-sm unrfq-select-customer select2">` + customerOptionList + `</select></th>`;
                break;
            case 4:
                trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm" disabled></th>`;
                break;
            case 5:
                // trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm maker-select" autoComplete="off"></th>`;
                trChild += `<th class="p-0"><select class="form-control form-control-sm maker select2">` + MakerListOptionHtml + `</select></th>`;
                break;
            case 6:
                trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm"></th>`;
                break;
            case 7:
                trChild += `<th class="p-0"><input type="text" id="add-rfq-katashiki" class="form-control form-control-sm"></th>`;
                break;
            case 8:
                trChild += `<th class="p-0"><input type="text" id="add-count-aspiration" class="form-control form-control-sm input-check-number"></th>`;
                break;
            case 9:
                trChild += `<th class="p-0"><input type="text" id="add-price-aspiration" class="form-control form-control-sm input-check-number"></th>`;
                break;
            case 10:
                trChild += `<th class="p-0"><input type="text" id="add-kbn" class="form-control form-control-sm"></th>`;
                break;
            case 11:
                trChild += `<th class="p-0"><select id="add-condition1" class="form-control form-control-sm"><option value="予算限定">予算限定</option><option value="納期優先">納期優先</option></select></th>`;
                break;
            case 12:
                trChild += `<th class="p-0"><select id="add-condition2" class="form-control form-control-sm"><option value="有鉛可">有鉛可</option><option value="Rohsのみ">Rohsのみ</option></select></th>`;
                break;
            case 13:
                trChild += `<th class="p-0"><select id="add-condition3" class="form-control form-control-sm"><option value="中国可">中国可</option><option value="海外可">海外可</option><option value="国内のみ">国内のみ</option></select></th>`;
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
    $('#request-unrfq-table thead').append(trChild);
    $('#request-unrfq-table .add-part-new-rfq').find('th:eq(' + rfqColumns.indexOf('型番') + ') input').focus();
    $('#request-unrfq-table .add-part-new-rfq').find('.unrfq-select-customer').select2(selectOptions);
    $('#request-unrfq-table .add-part-new-rfq').find('.maker').select2(selectOptions);
    // rfqCustomerAutoComplete('unrfq-select-customer');
    // makerAutoComplete();

    $('#request-unrfq-table').parents('.dataTables_scrollBody').scrollTop(0);
    $('.rfq-date-picker').datepicker({
        format: 'yyyy-mm-dd',
        inline: false,
        autoclose: true,
    }).keydown(function (e) {
        datepickerKeyDownHandler($(this), e);
    });
}

function updateRowRfqTable(index, data) {
    var trParent = $('#request-unrfq-table').find('tr:eq(' + (index + 1) + ')');
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[0]) + ')').text(data.created_at);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[1]) + ')').text(data.customer_id);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[2]) + ')').text(data.compName);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[3]) + ')').text(data.representative);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[4]) + ')').text(data.maker);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[5]) + ')').text(data.dc);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[6]) + ')').text(data.katashiki);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[7]) + ')').text(data.countAspiration);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[8]) + ')').text(data.priceAspiration);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[9]) + ')').text(data.kbn);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[10]) + ')').text(data.condition1);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[12]) + ')').text(data.condition2);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[13]) + ')').text(data.condition3);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[14]) + ')').text(data.solved_date);
    trParent.find('td:eq(' + rfqColumns.indexOf(originalKeyArr[15]) + ')').text(data.cancel_date);
    trParent.removeClass('d-none');
}

function addNewRowRfqTable(index, item, newRow) {
    var realData = JSON.parse(newRow);
    var originalData = {
        "受付日": item.created_at,
        "顧客ID": item.customer_id,
        "受付番号": realData.detail_id + ` - ` + realData.child_index,
        "客先": realData.customer.user_info.company_name,
        "担当": realData.customer.representative,
        "メーカー": item.maker,
        "DC": item.dc,
        "型番": item.katashiki,
        "希望数量": item.count_aspiration,
        "希望単価": item.price_aspiration,
        "区分": item.kbn,
        "条件1": item.condition1,
        "条件2": item.condition2,
        "条件3": item.condition3,
        "キャンセル日付": item.cancel_date,
        "処理日付": item.solved_date,
    };
    var trRow = `<tr role="row">`;
    $.each(rfqColumns, function (index, item) {
        trRow += `<td class="p-48">` + originalData[item] + `</td>`;
    })
    trRow += `</tr>`;
    $(trRow).data('rowInfo', realData);
    $(trRow).insertBefore($('#request-unrfq-table tbody tr:nth(' + (index + 1) + ')'));
}

function addPartUnRFQ() {
    var rowInfo = $('#request-unrfq-table').find('tr.selected').data('rowInfo');
    $('#request-unrfq-table').find('.add-part-new-rfq').remove();

    var trChild = `<tr role="row" class="add-part-new-rfq">`;
    var originalKeyArr = Object.keys(columnsRfqData);
    var MakerListOptionHtml = '<option></option>';
    $.each(makerList, function (index, data) {
        MakerListOptionHtml += '<option value="' + data.maker_name + '">' + data.maker_name + '</option>';
    });

    var customerOptionList = '<option></option>';
    $.each(customerInfoList, function (index, item) {
        customerOptionList += `<option data-info='` + JSON.stringify(item) + `' class="` + item.user_info.company_name_kana + `">` + item.user_info.company_name + `</option>`;
    })

    $.each(rfqColumns, function (index, item) {
        var originalIndex = originalKeyArr.indexOf(item);
        switch (originalIndex) {
            case 0:
                trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm" value="` + changeDateFormat(new Date()) + `" style="font-size: 12px !important" disabled></th>`;
                break;
            case 1:
                trChild += `<th class="p-0"><input type="number" class="form-control form-control-sm" value="` + rowInfo.customer_id + `" disabled></th>`;
                break;
            case 2:
                trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm" disabled></th>`;
                break;
            case 3:
                // trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm unrfq-select-customer" value="`+rowInfo.customer.user_info.company_name+`" autoComplete="off"></th>`;
                trChild += `<th class="p-0"><select class="form-control form-control-sm unrfq-select-customer select2">` + customerOptionList + `</select></th>`;
                break;
            case 4:
                trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm" value="` + rowInfo.customer.representative + `" disabled></th>`;
                break;
            case 5:
                // trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm maker-select" autoComplete="off"></th>`;
                trChild += `<th class="p-0"><select class="form-control form-control-sm maker select2">` + MakerListOptionHtml + `</select></th>`;
                break;
            case 6:
                trChild += `<th class="p-0"><input type="text" class="form-control form-control-sm"></th>`;
                break;
            case 7:
                trChild += `<th class="p-0"><input type="text" id="add-rfq-katashiki" class="form-control form-control-sm"></th>`;
                break;
            case 8:
                trChild += `<th class="p-0"><input type="text" id="add-count-aspiration" class="form-control form-control-sm input-check-number"></th>`;
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
    $('#request-unrfq-table').find('tr.selected').after(trChild);
    $('.add-part-new-rfq').find('select.condition1').val(rowInfo['condition1']);
    $('.add-part-new-rfq').find('select.condition2').val(rowInfo['condition2']);
    $('.add-part-new-rfq').find('select.condition3').val(rowInfo['condition3']);
    $('.add-part-new-rfq').find('select.unrfq-select-customer').select2(selectOptions);
    $('.add-part-new-rfq').find('select.maker').select2(selectOptions);
    $('.add-part-new-rfq').data('detailId', rowInfo.detail_id);
    // rfqCustomerAutoComplete('unrfq-select-customer');
    // makerAutoComplete();
    $('#add-rfq-katashiki').focus();

    $.each($('#request-unrfq-table .add-part-new-rfq').find('th'), function (index, elem) {
        if ($(elem).text() == 'null')
            $(elem).text('');
    })

    $('.rfq-date-picker').datepicker({
        format: 'yyyy-mm-dd',
        inline: false,
        autoclose: true,
    }).keydown(function (e) {
        datepickerKeyDownHandler($(this), e);
    });
}

function insertMultiRfqRows(data) {
    $.each(data, function (index, item) {
        var originalData = {
            "受付日": item.created_at,
            "顧客ID": item.customer_id,
            "受付番号": item['id'] + ` - ` + item['detail_id'],
            "客先": item.customer.user_info.company_name,
            "担当": item.customer.representative,
            "メーカー": item.maker,
            "DC": item.dc,
            "型番": item.katashiki,
            "希望数量": item.count_aspiration,
            "希望単価": item.price_aspiration,
            "区分": item.kbn,
            "条件1": item.condition1,
            "条件2": item.condition2,
            "条件3": item.condition3,
            "キャンセル日付": item.cancel_date,
            "処理日付": item.solved_date,
        };

        var trRow = `<tr role="row" data-index=` + index + `>`;
        $.each(rfqColumns, function (index, item) {
            trRow += `<td class="p-48">` + originalData[item] + `</td>`;
        })
        trRow += `</tr>`;

        $('#request-unrfq-table').append(trRow);
        var lastItem = $('#request-unrfq-table').find('tr:last');
        lastItem.data('rowInfo', item);
        if (item.is_cancel == 1)
            lastItem.find('td:eq(' + (rfqColumns.indexOf(originalRfqKeyArr[0])) + ')').addClass('tr-grey');

        if (item.is_solved == 0) {
            lastItem.addClass('tr-yellow');
            lastItem.data('status', 0);
        } else {
            lastItem.data('status', 1);
        }
        $.each(lastItem.find('td'), function (index, item) {
            $(item).attr('tabindex', 1);
            if ($(item).text() == 'null')
                $(item).text('');
        })
    })
}

function insertMultiHistoryRows(data) {
    $.each(data, function (index, item) {

        if (item.quote_customer && item.quote_customer.order_detail && item.quote_customer.order_detail.order_header) {
            var date = new Date(item.quote_customer.order_detail.order_header.receive_order_date);
            var receiveDate = changeDateFormat(date);
        } else
            var receiveDate = '';

        var originalData = {
            "仕入見積日": item.date_quote,
            "ランク": item.vendor.user_info.rank,
            "顧客": item.rfq_request.customer.user_info.company_name,
            "仕入先": item.vendor.user_info.company_name,
            "メーカー": item.maker,
            "型番": item.katashiki,
            "買数量": item.quantity_buy,
            "買単位": item.unit_buy,
            "買通貨": item.type_money_buy,
            "買単価": item.unit_price_buy,
            "売単価": (item.quote_customer) ? item.quote_customer.unit_price_sell : 0,
            "DC": item.dc,
            "仕入納期": item.deadline_buy_vendor,
            "Rohs": item.rohs,
            "受注日": receiveDate
        };

        var child = `<tr role="row">`;

        $.each(rfqHistoryColumns, function (index, item) {
            child += `<td class="p-48">` + originalData[item] + `</td>`;
        });
        child += `</tr>`;

        $('#history-table').append(child);

        $.each($('#history-table tr:last').find('td'), function (index, item) {
            $(item).attr('tabindex', 1);
            if ($(item).text() == 'null')
                $(item).text('');
        })
    })
}

function rfqCustomerAutoComplete(customerField) {
    $('.' + customerField).autoComplete({
        resolver: 'custom',
        events: {
            search: function (qry, callback) {
                callback(customerInfoList.filter(function (item) {
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
    })

    $('.' + customerField).on('autocomplete.select', function (evt, item) {
        $('.' + customerField).parents('tr').find('th:eq(' + rfqColumns.indexOf('顧客ID') + ') input').val(item.id);
        $('.' + customerField).parents('tr').find('th:eq(' + rfqColumns.indexOf('担当') + ') input').val(item.representative);
    });
}

function updateByUnrfqTable() {
    var rowInfo = $('#request-unrfq-table').find('tr.selected').data('rowInfo');
    if (!rowInfo)
        return;
    drawCustomerInfo(rowInfo.customer);
    $("#message-from-customer").val(rowInfo.comment);
    quoteFromSupplierTable.draw();
    hitoryModelNumber = rowInfo.katashiki;
    historyTable.draw();
}

function updatedByQuoteTable() {
    var rowInfo = $('#quote-from-supplier-table').find('tr.selected').data('rowInfo');
    if (!rowInfo)
        return;
    if (rowInfo.messages[0])
        $('.message-box').val(rowInfo.messages[0].content);
    else
        $('.message-box').val('');

    hitoryModelNumber = rowInfo.katashiki;
    historyTable.draw();
    if (rowInfo.vendor && rowInfo.vendor.user_info) {
        $('#supplier-info').data('supplier', rowInfo.vendor);
        drawSupplierInfo(rowInfo.vendor);
    }
}

function initialOrderDatatables() {
    var unRfqColumn = parseInt(localStorage.getItem('unRfqOrderColumn'));
    var unRfqDir = localStorage.getItem('unRfqOrderDir');
    if (unRfqColumn && unRfqDir)
        reqUnRFQTable.order([unRfqColumn, unRfqDir]).draw();
}

// ------------------------quote_table-------------------------------
function getQuoteRowByIndex(pos) {
    var originalKeyArr = Object.keys(columnsQuoteData);
    var target = $('#quote-from-supplier-table').find('tr:eq(' + (pos) + ')');
    var targetData = target.data('rowInfo');

    if (!targetData)
        return false;
    var targetId = targetData.id;

    var countAspiration = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[5]) + ')').text().toString();
    var feeShipping = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[13]) + ')').text().toString();
    var unitPriceBuy = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[8]) + ')').text().toString();

    if (countAspiration == '') countAspiration = '0';
    if (feeShipping == '') feeShipping = '0';
    if (unitPriceBuy == '') unitPriceBuy = '0';

    var supplierField = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[2]) + ')');
    if (supplierField.find('select').length == 0) {
        var supplierName = supplierField.text();
    } else {
        var supplierName = supplierField.find('select').val();
    }

    var makerField = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[3]) + ')');
    if (makerField.find('select').length == 0) {
        var makerName = makerField.text();
    } else {
        var makerName = makerField.find('select').val();
    }

    var typeMoneyType = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[7]) + ')').text().toUpperCase();
    target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[7]) + ')').text(typeMoneyType);

    if (target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[14]) + ')').find('input').length > 0)
        var dateQuote = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[14]) + ')').find('input').val()
    else
        var dateQuote = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[14]) + ')').text()
    
    var str = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[10]) + ')').text() ? target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[10]) + ')').text() : '国内';

    if(str.includes('国内'))
    {   
        str = '国内';
    } else if (str.includes('北米')) {
        str = '北米';
    } else if (str.includes('EU')) {
        str = 'EU';
    } else if (str.includes('')) {
        str = '中国';
    } else if (str.includes('海外')) {
        str = '海外';
    }

    var storedData = {
        id: targetId,
        supplier_id: getSupplierId(supplierName),
        maker: makerName,
        katashiki: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[4]) + ')').text(),
        count_aspiration: parseFloat(countAspiration.normalize('NFKC')),
        unit_buy: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[6]) + ')').text(),
        type_money_buy: typeMoneyType,
        unit_price_buy: parseFloat(unitPriceBuy.normalize('NFKC')),
        dc: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[9]) + ')').text(),
        kbn2: str,
        rohs: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[11]) + ')').text(),
        deadline_buy_vendor: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[12]) + ')').text(),
        fee_shipping: parseFloat(feeShipping.normalize('NFKC')),
        date_quote: dateQuote,
        code_quote: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[15]) + ')').text()
    };
    // quoteFromSupplierTable.draw();
    return storedData;
}

var columnsQuoteData = {
    "RFQ依頼日": 'date_quote',
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

function drawQuoteData(item) {
    if (!item)
        return;

    var QuoteFromData = {
        "RFQ依頼日": item.rfq_request.created_at,
        "ランク": item.vendor.user_info.rank,
        "仕入先": item.vendor.user_info.company_name,
        "メーカー": item.maker,
        "型番": item.katashiki,
        "買数量": item.quantity_buy,
        "買単位": item.unit_buy,
        "買通貨": item.type_money_buy,
        "買単価": item.unit_price_buy,
        "DC": item.dc,
        "地域": item.kbn2,
        "Rohs": item.rohs,
        "仕入納期": item.deadline_buy_vendor,
        "送料": item.fee_shipping,
        "仕入見積日": item.date_quote,
        "仕入先見積番号": item.code_quote,
    };

    var child = `<tr role="row">`;
    $.each(rfqQuoteColumns, function (index, item) {
        if (!QuoteFromData[item])
            QuoteFromData[item] = '';
        if ( index == 10 )
        {
            var str = QuoteFromData[item] ? QuoteFromData[item] : '国内';
            if(str.includes('国内'))
            {   
                return child += `<td class="p-48">国内</td>`;
            } else if (str.includes('北米')) {
                return child += `<td class="p-48">北米</td>`;
            } else if (str.includes('EU')) {
                return child += `<td class="p-48">EU</td>`;
            } else if (str.includes('')) {
                return child += `<td class="p-48">中国</td>`;
            } else if (str.includes('海外')) {
                return child += `<td class="p-48">海外</td>`;
            }
        }
        else
            child += `<td class="p-48" tabindex="` + index + `">` + QuoteFromData[item] + `</td>`;
    });
    child += `</tr>`;
    return $(child);
}

function addQuoteTable(data) {
    if ($('#quote-from-supplier-table').find('.edit-quote').length != 0)
        return;

    if (!data) return;

    var target = $('#quote-from-supplier-table').find('tr.selected');
    var trChild = `<tr role="row" class="edit-quote" data-id="` + data.id + `">`;
    var originalKeyArr = Object.keys(columnsQuoteData);
    var MakerListOptionHtml = '';
    $.each(makerList, function (index, data) {
        MakerListOptionHtml += '<option value="' + data.maker_name + '">' + data.maker_name + '</option>';
    });
    var supplierOptionList = '';
    $.each(supplierList, function (index, item) {
        supplierOptionList += '<option class="' + item.user_info.company_name_kana + '">' + item.user_info.company_name + '</option>'
    })
    $.each(rfqQuoteColumns, function (index, item) {
        var originalIndex = originalKeyArr.indexOf(item);
        switch (originalIndex) {
            case 0:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm" style="font-size: 12px !important" value="` + data.rfq_request.created_at + `" disabled></td>`;
                break;
            case 1:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm" value="` + data.vendor.user_info.rank + `" disabled></td>`;
                break;
            case 2:
                trChild += `<td class="p-0"><select class="form-control form-control-sm supplier-select select2">` + supplierOptionList + `</select></td>`;
                break;
            case 3:
                trChild += `<td class="p-0"><select class="form-control form-control-sm maker select2">` + MakerListOptionHtml + `</select></td>`;
                break;
            case 4:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm" value="` + data.katashiki + `"></td>`;
                break;
            case 5:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm input-check-number" value="` + data.quantity_buy + `"></td>`;
                break;
            case 6:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm" value="pcs"></td>`;
                break;
            case 7:
                trChild += `<td class="p-0"><select class="form-control form-control-sm rate">` + rateOptionHtml + `</select></td>`;
                break;
            case 8:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm input-check-number" value="` + parseFloat(data.unit_price_buy) + `" step="any"></td>`;
                break;
            case 9:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm" value="` + data.dc + `"></td>`;
                break;
            case 10:
                trChild += `<td class="p-0"><select class="form-control form-control-sm kbn">` + kbnOptionHtml + `</select></td>`;
                break;
            case 11:
                trChild += ` <td class="p-0"><select class="form-control form-control-sm rohs">` + rohsOptionHtml + `</select></td>`;
                break;
            case 12:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm" value="` + data.deadline_buy_vendor + `"></td>`;
                break;
            case 13:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm input-check-number" value="` + data.fee_shipping + `" step="any"></td>`;
                break;
            case 14:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm quote-date-picker"></td>`;
                // trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm quote-date-picker" disabled></td>`;
                break;
            case 15:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm" value="` + data.code_quote + `"></td>`;
                break;
        }
    });
    trChild += `</tr>`;
    var elem = $(trChild);

    elem.find('select.supplier-select').val(data.vendor.user_info.company_name);
    elem.find('select.supplier-select').select2(selectOptions);

    elem.find('select.rate').val(data.type_money_buy);
    elem.find('select.kbn').val(data.kbn2);
    elem.find('select.maker').val(data.maker);
    elem.find('select.maker').select2(selectOptions);
    elem.find('select.rohs').val(data.rohs);
    elem.insertAfter($('#quote-from-supplier-table tbody tr:nth(' + target.index() + ')'));
    $('.quote-date-picker').datepicker({
        format: 'yyyy-mm-dd',
        inline: false,
    }).keydown(function (e) {
        datepickerKeyDownHandler($(this), e);
    });

    // quoteSupplierAutoComplete();
    // makerAutoComplete();
    target.addClass('d-none');
    $.each(elem.find('td'), function (index, item) {
        $(item).find('td').attr('tabindex', 1);
        if ($(item).text() == 'null')
            $(item).text('');
    })
    $.each(elem.find('input'), function (index, item) {
        if ($(item).val() == 'null')
            $(item).val('');
    })
    elem.find('.quote-date-picker').val(data.date_quote);
    elem.find('input:not([disabled])').first().focus();
}

function addNewQuoteToTable() {
    var rowData = $('#request-unrfq-table').find('tr.selected').data("rowInfo");
    var trChild = `<tr role="row" class="edit-quote">`;
    var originalKeyArr = Object.keys(columnsQuoteData);
    var makerOptions = '';
    $.each(makerList, function (index, item) {
        makerOptions += '<option class="' + item.id + '">' + item.maker_name + '</option>'
    })
    var supplierOptionList = '';
    var rateoption;
    $.each(supplierList, function (index, item) {
        supplierOptionList += '<option class="' + item.user_info.company_name_kana + '" title="'+ item.user_info.address.country +'">' + item.user_info.company_name + '</option>'
    })
    if(supplierList[0].user_info.address.country == 'JP')
        rateoption = 'JPY';
    else
        rateoption = 'USD';

    $.each(rfqQuoteColumns, function (index, item) {
        var originalIndex = originalKeyArr.indexOf(item);
        switch (originalIndex) {
            case 0:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm" style="font-size: 12px !important" disabled></td>`;
                break;
            case 1:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm" disabled></td>`;
                break;
            case 2:
                trChild += `<td class="p-0"><select class="form-control form-control-sm supplier-select select2">` + supplierOptionList + `</select></td>`;
                break;
            case 3:
                trChild += `<td class="p-0"><select class="form-control form-control-sm maker select2">` + makerOptions + `</select></td>`;
                break;
            case 4:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm" value="` + rowData.katashiki + `"></td>`;
                break;
            case 5:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm input-check-number" value="` + rowData.count_aspiration + `"></td>`;
                break;
            case 6:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm" value="pcs"></td>`;
                break;
            case 7:
                trChild += `<td class="p-0"><select class="form-control form-control-sm rate">${rateOptionHtml}</select></td>`;
                break;
            case 8:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm input-check-number"></td>`;
                break;
            case 9:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm"></td>`;
                break;
            case 10:
                trChild += `<td class="p-0"><select class="form-control form-control-sm kbn">` + kbnOptionHtml + `</select></td>`;
                break;
            case 11:
                trChild += ` <td class="p-0"><select class="form-control form-control-sm">` + rohsOptionHtml + `</select></td>`;
                break;
            case 12:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm"></td>`;
                break;
            case 13:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm input-check-number"></td>`;
                break;
            case 14:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm quote-date-picker"></td>`;
                // trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm quote-date-picker" disabled></td>`;
                break;
            case 15:
                trChild += `<td class="p-0"><input type="text" class="form-control form-control-sm"></td>`;
                break;
        }
    });
    trChild += `</tr>`;
    $('#quote-from-supplier-table thead').append(trChild);
    $('.rate').val(rateoption);
    $('.quote-date-picker').datepicker({
        format: 'yyyy-mm-dd',
        inline: false,
    }).keydown(function (e) {
        datepickerKeyDownHandler($(this), e);
    });
    // quoteSupplierAutoComplete();
    // makerAutoComplete();
    $('#quote-from-supplier-table thead').find('.edit-quote .supplier-select').select2(selectOptions);
    $('#quote-from-supplier-table thead').find('.edit-quote .maker').val(rowData.maker);
    $('select.maker').select2(selectOptions);
    $('#quote-from-supplier-table thead').find('.edit-quote input:enabled').eq(0).focus();

    $('.supplier-select').on('select2:closing', function (e) {
        if($('.supplier-select').select2('data')[0].title == 'JP')
            $('.rate').val('JPY');
        else
            $('.rate').val('USD');
    });
}

function insertMultiQuoteSupplierRows(data) {
    $.each(data, function (index, item) {
        var QuoteFromData = {
            "RFQ依頼日": item.rfq_request.created_at,
            "ランク": item.vendor.user_info.rank,
            "仕入先": item.vendor.user_info.company_name,
            "メーカー": item.maker,
            "型番": item.katashiki,
            "買数量": item.quantity_buy,
            "買単位": item.unit_buy,
            "買通貨": item.type_money_buy,
            "買単価": item.unit_price_buy,
            "DC": item.dc,
            "地域": item.kbn2,
            "Rohs": item.rohs,
            "仕入納期": item.deadline_buy_vendor,
            "送料": item.fee_shipping,
            "仕入見積日": item.date_quote,
            "仕入先見積番号": item.code_quote,
        };

        var child = `<tr role="row">`;
        $.each(rfqQuoteColumns, function (index, item) {
            if ( index == 10 )
            {
                var str = QuoteFromData[item] ? QuoteFromData[item] : '国内';
                if(str.includes('国内'))
                {   
                    return child += `<td class="p-48">国内</td>`;
                } else if (str.includes('北米')) {
                    return child += `<td class="p-48">北米</td>`;
                } else if (str.includes('EU')) {
                    return child += `<td class="p-48">EU</td>`;
                } else if (str.includes('')) {
                    return child += `<td class="p-48">中国</td>`;
                } else if (str.includes('海外')) {
                    return child += `<td class="p-48">海外</td>`;
                }
            }
            else
                child += `<td class="p-48">` + QuoteFromData[item] + `</td>`;
        });
        child += `</tr>`;

        $("#quote-from-supplier-table").append(child);
        var lastItem = $("#quote-from-supplier-table").find('tr:last');
        lastItem.data('rowInfo', item);
        if (item.is_send_est == 1)
            lastItem.find('td:eq(' + (rfqQuoteColumns.indexOf(originalQuoteKeyArr[2])) + ')').css('background', 'rgb(188, 247, 255)');
        if (item.is_sendmail == 1)
            lastItem.find('td:eq(' + (rfqQuoteColumns.indexOf(originalQuoteKeyArr[0])) + ')').addClass('tr-orange');

        $.each(lastItem.find('td'), function (index, item) {
            $(item).attr('tabindex', 1);
            if ($(item).text() == 'null')
                $(item).text('');
        })
    })
}

// get supplier id from supplier name
function getSupplierId(name) {
    supplierId = null
    $.each(supplierList, function (index, item) {
        if (name == item.user_info.company_name)
            supplierId = item.id;
    })
    return supplierId;
}

// auto update when focusing out
function autoEditedQuote(nextItem) {
    var originalKeyArr = Object.keys(columnsQuoteData);
    $('#quote-from-supplier-table').find('.is-invalid').removeClass('is-invalid');
    var target = $('#quote-from-supplier-table').find('.edit-quote');

    if (!target) return;

    target.removeClass('edit-quote');
    var targetId = target.data('id');
    if (targetId == undefined) {
        url = "/admin/request_quote_vendor";
        method = "POST";
        type = "create";
        var targetData = $('#request-unrfq-table').find('tr.selected').data('rowInfo');
        targetId = targetData.id;
    } else {
        url = "/admin/request_quote_vendor/" + targetId;
        method = "PUT";
        type = "update";
    }
    var countAspiration = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[5]) + ') input').val().toString();
    var feeShipping = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[13]) + ') input').val().toString();
    var unitPriceBuy = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[8]) + ') input').val().toString();

    if (countAspiration == '') countAspiration = '0';
    if (feeShipping == '') feeShipping = '0';
    if (unitPriceBuy == '') unitPriceBuy = '0';

    var supplierField = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[2]) + ')');
    if (supplierField.find('select').length == 0) {
        var supplierName = supplierField.text();
    } else {
        var supplierName = supplierField.find('select').val();
    }

    var makerField = target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[3]) + ')');
    if (makerField.find('select').length == 0) {
        var makerName = makerField.text();
    } else {
        var makerName = makerField.find('select').val();
    }

    var storedData = {
        id: targetId,
        supplier_id: getSupplierId(supplierName),
        maker: makerName,
        katashiki: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[4]) + ') input').val(),
        count_aspiration: parseFloat(countAspiration.normalize('NFKC')),
        unit_buy: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[6]) + ') input').val(),
        type_money_buy: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[7]) + ') select').val(),
        unit_price_buy: parseFloat(unitPriceBuy.normalize('NFKC')),
        dc: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[9]) + ') input').val(),
        kbn2: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[10]) + ') select').val(),
        rohs: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[11]) + ') select').val(),
        deadline_buy_vendor: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[12]) + ') input').val(),
        fee_shipping: parseFloat(feeShipping.normalize('NFKC')),
        date_quote: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[14]) + ') input').val(),
        code_quote: target.find('td:eq(' + rfqQuoteColumns.indexOf(originalKeyArr[15]) + ') input').val()
    };

    $.ajax({
        url: url,
        method: method,
        data: storedData,
        success: function (data) {
            $('#quote-from-supplier-table tbody tr.d-none').remove();
            var jsonData = JSON.parse(data);
            if (type == 'create') {
                var elem = drawQuoteData(jsonData);
                elem.insertBefore($('#quote-from-supplier-table tbody tr:eq(0)'));
                $('#quote-from-supplier-table tbody tr:eq(0)').data('rowInfo', jsonData);
            } else {
                var elem = drawQuoteData(jsonData);
                var elemIndex = target.index();
                elem.insertBefore($('#quote-from-supplier-table tbody tr:eq(' + elemIndex + ')'))
                $('#quote-from-supplier-table tbody tr:eq(' + elemIndex + ')').data('rowInfo', jsonData);

                if (jsonData.is_send_est == 1)
                    $('#quote-from-supplier-table tbody tr:eq(' + elemIndex + ')').find("td:eq(" + rfqQuoteColumns.indexOf(originalKeyArr[2]) + ")").css('background', 'rgb(188, 247, 255)');
            }
            $.each($('#quote-from-supplier-table tbody').find('td'), function (index, item) {
                if ($(item).text() == 'null')
                    $(item).text('');
            })
            target.remove();
            // quoteFromSupplierTable.draw();
            selectSupplierIdInQuote = null;
        },
        error: function (xhr, status, error) {
            // var errors = xhr.responseJSON.errors;
            // for (key in errors) {
            //     if (key == 'maker') {
            //         target.find('.maker-select').addClass('is-invalid');
            //     } else if (key == 'supplier_id') {
            //         target.find('.supplier-select').addClass('is-invalid');
            //     }
            //     toastr.error('正しく入力してください。');
            // }

            toastr.warning('仕入先が入力されていません。');
            $('#quote-from-supplier-table tbody tr.d-none').removeClass('d-none');
            nextItem.parents('tr').click().end().focus();
            target.remove();
        },
    });
}

$(document).on('blur', 'td, .select2', function (e) {
    if ($(e.relatedTarget).prop('id') == "quote-from-supplier-table" ||
        $(e.relatedTarget).hasClass('select2-selection') ||
        $(e.relatedTarget).prop('tagName') == 'SPAN') {
        return;
    }

    var indiInput = $('.indi-edit');
    if (indiInput.length > 0 && !$(e.target).hasClass('f-datepicker')) {
        // var selectElem = $(this).siblings('select');
        // if (selectElem.hasClass('select2') && selectElem.data('select2')) {
        //     selectElem.select2('destroy');
        // }
        // var parentTd = indiInput.parents('td');
        // var text = indiInput.val();
        // parentTd.attr('tabindex', parentTd.index()+1)
        // parentTd.addClass('p-48').removeClass('p-0');
        // parentTd.text(text);
    };
})

$(document).on('change', '.unrfq-select-customer', function () {
    var item = $(this).find(':selected').data('info');
    if (item) {
        $(this).parents('tr').find('th:eq(' + rfqColumns.indexOf('顧客ID') + ') input').val(item.id);
        $(this).parents('tr').find('th:eq(' + rfqColumns.indexOf('担当') + ') input').val(item.representative);
    }
});

function saveQuoteByIndexFun(targetElem) {
    var targetData = getQuoteRowByIndex(targetElem.index() + 1);
    if (!targetData)
        return false;
    targetElem.removeClass('direct-edit');
    $.ajax({
        url: "/admin/request_quote_vendor/" + targetData.id,
        method: 'PUT',
        data: targetData,
        success: function (data) {
            var jsonData = JSON.parse(data);
            targetElem.data('rowInfo', jsonData);
        },
        error: function (xhr, status, error) {
            var errors = xhr.responseJSON.errors;
            toastr.error('正しく入力してください。');

            var data = targetElem.data('rowInfo');
            var elem = drawQuoteData(data);
            var elemIndex = targetElem.index();
            elem.insertBefore($('#quote-from-supplier-table tbody tr:eq(' + elemIndex + ')'))
            $('#quote-from-supplier-table tbody tr:eq(' + elemIndex + ')').data('rowInfo', data);
            targetElem.remove();

            if (data.is_send_est == 1) {
                $('#quote-from-supplier-table tbody tr:eq(' + elemIndex + ')').find("td:eq(" + rfqQuoteColumns.indexOf(originalQuoteKeyArr[2]) + ")").css('background', 'rgb(188, 247, 255)');
            }

            // if($(e.relatedTarget).parents('tr').index() == -1) {
            //     $('#quote-from-supplier-table tbody tr:eq('+elemIndex+')').click();
            //     if ($(e.relatedTarget).index() != -1)
            //         $('#quote-from-supplier-table tbody tr:eq('+elemIndex+')').find("td:eq("+$(e.relatedTarget).index()+")").focus();
            //     else
            //         $('#quote-from-supplier-table tbody tr:eq('+elemIndex+')').find("td:eq("+$(e.target).index()+")").focus();
            // }
        },
    });
}

