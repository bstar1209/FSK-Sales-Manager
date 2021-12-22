
var rateList = {};
var commonList = {};
var supplierList = [];
var rateOptionHtml = '';
var makerList = {};

var orderTableLoadingFlag = true;

var katashikiList = [],
    saleQtyList = [],
    saleUnitList = [],
    saleCostList = [],
    orderIdList = [],
    orderNo = null,
    customerIdList = [],
    userName = null,
    userEmail = null,
    userTantou = null,
    subMeg = '';

function checkSelectRow() {
    var selectedIds = [];
    var selectedIndexs = [];
    $.each($('.order-check'), function (index, item) {
        if ($(item).prop("checked")) {
            selectedIds.push($(item).parents('tr').data('orderId'));
            selectedIndexs.push(index);
        }
    })
    return {
        ids: selectedIds,
        indexs: selectedIndexs
    }
}

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

function insertMultiOrderRows(data) {
    $.each(data, function (index, item) {
        var originalData = {
            "受注番号": item.order_header_id,
            "受注日": item.order_header.receive_order_date,
            "客先注文番号": item.order_no_by_customer,
            "支払い条件": item.order_header.cond_payment,
            "客先": item.quote_customer.customer.user_info.company_name,
            "担当": item.quote_customer.user_res,
            "見積日": item.quote_customer.quote_date,
            "見積番号": item.quote_customer.quote_code,
            "メーカー": item.maker,
            "型番": item.katashiki,
            "DC": item.dc,
            "Rohs": item.quote_customer.rohs,
            "国": item.supplier.user_info.address.country,
            "見積数": item.sale_qty,
            "見積単位": item.buy_unit,
            "見積備考": item.quote_customer.quote_prefer,
            "見積納期": item.quote_customer.deadline_quote,
            "粗利率": item.quote_customer.rate_profit,
            "粗利": item.quote_customer.profit,
            "売数量": item.sale_qty,
            "売単位": item.sale_unit,
            "売通貨": item.type_money_ship,
            "売単価": item.sale_cost,
            "売金額": item.sale_money,
            "客先希望納期": item.order_header.expect_ship_date,
            "キャンセル　客先": item.cancel_date_user,
        };

        var child = `<tr role="row"><td class="p-48"><input type="checkbox" class="order-check"></td>`;

        $.each(orderColumns, function (index, item) {
            child += `<td class="p-48">` + originalData[item] + `</td>`;
        });
        child += `</tr>`;

        $('#order-table').append(child);
        var lastRow = $('#order-table tr:last');
        lastRow.data('rowInfo', item);
        lastRow.data('orderId', item.id);

        if ($('input.all-quote-check').prop('checked'))
            lastRow.find('td:eq(0) input').prop('checked', true);

        if (item.cancel_date_user)
            lastRow.find('td:eq(' + (orderColumns.indexOf(originalKeyArr[5]) + 1) + ')').addClass('tr-grey');

        if (data.order_status == 1)
            $(row).addClass('tr-yellow');

        if (data.order_KBN == 1)
            $(row).find('td:eq(' + (orderColumns.indexOf(originalKeyArr[5]) + 1) + ')').addClass('tr-light-blue');

        if (data.order_status == 9)
            $(row).find('td:eq(' + (orderColumns.indexOf(originalKeyArr[10]) + 1) + ')').addClass('tr-grey');

        $.each(lastRow.find('td'), function (index, item) {
            $(item).attr('tabindex', 1);
            if ($(item).text() == "null")
                $(item).text('');
        });
    })
}

function drawCustomerInfo(rowInfo) {
    $('#customer-info').data('customerInfo', rowInfo);
    $('#customer-info .customer-name').text(rowInfo.user_info.company_name);
    if (Array.isArray(rowInfo.user_info.payment) && rowInfo.user_info.payment[0] && rowInfo.user_info.payment[0].common)
        $('#customer-info .customer-payment-terms').text(rowInfo.user_info.payment[0].common.common_name);
    else
        $('#customer-info .customer-payment-terms').text('');
    $('#customer-info .customer-rfq-num').text(rowInfo.user_info.est_req_time);
    $('#customer-info .customer-rank').text(rowInfo.user_info.rank);
    $('#customer-info .customer-est-num-of-responses').text(rowInfo.user_info.est_ans_time);
    $('#customer-info .customer-number-of-orders').text(rowInfo.user_info.order_qty);
    $('#customer-info .customer-order-amount').text(rowInfo.user_info.order_money);
    $('#customer-info .customer-remarks').text(rowInfo.user_info.message1);
}

function updatedByChangedOrderTable() {
    var rowInfo = $('#order-table').find('tr.selected').data("rowInfo");
    drawCustomerInfo(rowInfo.quote_customer.customer);
    if (rowInfo.quote_customer.request_vendors.messages[0])
        $('textarea.message-box').val(rowInfo.quote_customer.request_vendors.messages[0].content);
    else
        $('textarea.message-box').val('');
}

$(document).on('hidden.bs.modal', '.modal', function () {
    $('#order-table tbody').find('td:eq(1)').focus();
})
