var rateList = {};
var commonList = {};
var supplierList = [];
var rateOptionHtml = '';

var quoteTableLoadingFlag = true;
var historyTableLoadingFlag = true;

function checkSelectRow() {
    var selectedIds = []
    $.each($('.quote-check'), function (index, item) {
        if ($(item).prop("checked"))
            selectedIds.push($(item).parents('tr').data('quoteId'));
    })
    return selectedIds
}

function drawCustomerInfo(rowInfo) {
    if (!rowInfo)
        return;
    $('#customer-info').data('customerInfo', rowInfo);
    $("#custmer-name").text(rowInfo.user_info.company_name);
    $("#customer-orders-cancel-num").text(rowInfo.ord_cal_time);
    if (Array.isArray(rowInfo.user_info.payment) && rowInfo.user_info.payment[0] && rowInfo.user_info.payment[0].common)
        $("#custmer-payment").text(rowInfo.user_info.payment[0].common.common_name);
    else
        $("#custmer-payment").text('');
    $("#customer-rank").text(rowInfo.user_info.rank);
    $("#customer-rfq-num").text(rowInfo.user_info.est_req_time);
    $("#customer-est-response-num").text(rowInfo.user_info.est_ans_time);
    $("#customer-orders-num").text(rowInfo.user_info.order_qty);
    $("#customer-order-amount").text(rowInfo.user_info.order_money);
    $("#customer-remarks textarea").text(rowInfo.user_info.message1);
}

function getQuoteRowByIndex(pos) {
    var target = $("#quote-table").find('tr:eq(' + (pos) + ')');
    var targetData = target.data('rowInfo');

    var updatedData = [];
    $.each(editableIndexs, function (index, item) {
        if (target.find('td:eq(' + item + ') input').length > 0 || target.find('td:eq(' + item + ') select').length > 0)
            updatedData.push(target.find('td:eq(' + item + ')').val().toString());
        else
            updatedData.push(target.find('td:eq(' + item + ')').text().toString());
    });
    // 13,14,22,23,26,27,28,29,31
    var rateProfit = updatedData[2];
    var sellQty = updatedData[3];
    var sellingUnitPrice = updatedData[6];
    var sellingAmount = updatedData[7];

    if (rateProfit == '') rateProfit = '0';
    if (sellQty == '') sellQty = '0';
    if (sellingUnitPrice == '') sellingUnitPrice = '0';
    if (sellingAmount == '') sellingAmount = '0';

    var ajaxData = {
        id: targetData.id,
        quote_prefer: updatedData[0],
        deadline_quote: updatedData[1],
        rate_profit: parseFloat(rateProfit.normalize('NFKC')).toFixed(2),
        sell_qty: parseFloat(sellQty.normalize('NFKC')),
        unit_sell: updatedData[4],
        type_money_sell: updatedData[5],
        selling_unit_price: parseFloat(sellingUnitPrice.normalize('NFKC')).toFixed(2),
        selling_amount: parseFloat(sellingAmount.normalize('NFKC')),
        comment_bus: updatedData[9],
        profit: target.find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[23])) + ')').text(),
        unit_price: target.find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[24])) + ')').text(),
    }
    return ajaxData;
}

function autoQuoteSave() {
    var target = $("#quote-table").find('.quote-edit-tr');
    var targetData = target.data('rowInfo');

    if (!targetData) return

    var updatedData = [];
    $.each(editableIndexs, function (index, item) {
        var value = target.find('td:eq(' + item + ')').children().val();
        if (!value && value == undefined)
            updatedData.push('');
        else
            updatedData.push(value.toString());
    });
    // 13,14,22,23,26,27,28,29,31
    var rateProfit = updatedData[2];
    var sellQty = updatedData[3];
    var sellingUnitPrice = updatedData[6];
    var sellingAmount = updatedData[7];
    if (rateProfit == '') rateProfit = '0';
    if (sellQty == '') sellQty = '0';
    if (sellingUnitPrice == '') sellingUnitPrice = '0';
    if (sellingAmount == '') sellingAmount = '0';

    var ajaxData = {
        id: targetData.id,
        quote_prefer: updatedData[0],
        deadline_quote: updatedData[1],
        rate_profit: parseFloat(rateProfit.normalize('NFKC')).toFixed(2),
        sell_qty: parseFloat(sellQty.normalize('NFKC')),
        unit_sell: updatedData[4],
        type_money_sell: updatedData[5],
        selling_unit_price: parseFloat(sellingUnitPrice.normalize('NFKC')).toFixed(2),
        selling_amount: parseFloat(sellingAmount.normalize('NFKC')),
        comment_bus: updatedData[8],
        unit_price: target.find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[24])) + ')').text(),
        profit: target.find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[23])) + ')').text(),
    }

    $.ajax({
        url: "/admin/quotation/" + targetData.id,
        method: 'PUT',
        data: ajaxData,
        success: function (data) {
            var result = JSON.parse(data);
            var quotePrefer, sellingAmount, sellingUnitPrice, sellQty, deadlineQuote, rateProfit, unitSell, typeMoneySell, commentBus;

            if (result.money_sell) sellingAmount = result.money_sell;
            else if (result.money_sell_second) sellingAmount = result.money_sell_second;
            else sellingAmount = '';
            if (result.unit_price_sell) sellingUnitPrice = result.unit_price_sell;
            else if (result.unit_price_second) sellingUnitPrice = result.unit_price_second;
            else sellingUnitPrice = '';
            if (result.sell_quantity) sellQty = result.sell_quantity;
            else if (result.sell_quantity_second) sellQty = result.sell_quantity_second;
            else sellQty = '';
            if (result.quote_prefer) quotePrefer = result.quote_prefer; else quotePrefer = '';
            if (result.deadline_quote) deadlineQuote = result.deadline_quote; else deadlineQuote = '';
            if (result.rate_profit) rateProfit = result.rate_profit; else rateProfit = '';
            if (result.unit_sell) unitSell = result.unit_sell; else unitSell = '';
            if (result.type_money_sell) typeMoneySell = result.type_money_sell; else typeMoneySell = '';
            if (result.comment_bus) commentBus = result.comment_bus; else commentBus = '';

            var updatedData = [
                quotePrefer, deadlineQuote, rateProfit, sellQty, unitSell, typeMoneySell, sellingUnitPrice, sellingAmount, commentBus
            ];

            $.each(editableIndexs, function (index, item) {
                target.find('td:eq(' + item + ')').text(updatedData[index]);
            });
            target.data('rowInfo', result).removeClass('quote-edit-tr');
            historyTable.draw();
        }
    });
}

function drawSupplierInfo(rowInfo) {
    $('#supplier-info').data('supplier', rowInfo);
    $("#supplier-id").text(rowInfo.user_info.company_name);
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

function autoCalculate(target) {

    if (target.find('.rate_profit_td input').length > 0)
        var rateProfit = target.find('.rate_profit_td input').val();
    else
        var rateProfit = target.find('.rate_profit_td').text();
    if (target.find('.quote_currency_td select').length > 0)
        var currency = target.find('select.quote-currency').val();
    else
        var currency = target.find('td.quote_currency_td').text();
    if (target.find('.sell_quantity_td input').length > 0)
        var sellQty = target.find('.sell_quantity_td input').val();
    else
        var sellQty = target.find('.sell_quantity_td').text();

    if (Number.isNaN(sellQty) || !parseInt(sellQty) || !isFinite(sellQty)) sellQty = 0;

    if (!currency) return;

    var targetData = target.data('rowInfo');

    var estimatedUnitPrice = ((targetData.money_buy * rateList[target.find('td:eq(18)').text()].buy_rate * targetData.buy_quantity) / (1 - target.find('td:eq(22)').text()) + targetData.fee_shipping) / target.find('td:eq(25)').text()/rateList[currency].sale_rate;

    if (Number.isNaN(estimatedUnitPrice) || !isFinite(estimatedUnitPrice)) estimatedUnitPrice = 0;
    estimatedUnitPrice = Math.round(estimatedUnitPrice * 100000) / 100000;
    target.find('td:eq(' + quoteColumns.indexOf(originalQuoteKeyArr[24]) + ')').text(estimatedUnitPrice.toFixed(2));

    var profit = (estimatedUnitPrice * rateList[currency].sale_rate * target.find('td:eq(25)').text()) - (targetData.money_buy * rateList[target.find('td:eq(18)').text()].buy_rate * targetData.buy_quantity)-targetData.fee_shipping;

    if (Number.isNaN(profit) || !isFinite(profit)) profit = 0;
    profit = Math.round(profit * 100000) / 100000;
    var profitTD = target.find('td:eq(' + quoteColumns.indexOf(originalQuoteKeyArr[23]) + ')');
    if (profitTD.prop('tagName') == 'TD' && profitTD.find('input').length < 1)
        profitTD.text(profit.toFixed(2));
    else
        profitTD.find('input').val(profit.toFixed(2));
}

function insertMultiHistoryRows(data) {
    $.each(data, function (index, item) {
        if (item.quote_date == null)
            item.quote_date = '';
        if (item.profit == null)
            item.profit = '';

        var originalData = {
            "仕入見積日": item.quote_date,
            "客先": item.customer_id,
            "メーカー": item.maker,
            "型番": item.katashiki,
            "売数量": item.sell_quantity,
            "売通貨": item.type_money_sell,
            "売単価": item.unit_price_sell,
            "DC": item.dc,
            "Rohs": item.rohs,
            "仕入先": item.supplier.user_info.company_name,
            "買単価": item.unit_price_buy,
            "買通貨": item.type_money_buy,
            "粗利": item.profit,
        };

        var child = `<tr role="row"><td class="p-48">`;

        $.each(quoteHistoryColumns, function (index, item) {
            child += `<td class="p-48">` + originalData[item] + `</td>`;
        });
        child += `</tr>`;
        $('#history-table').append(child);
        var lastRow = $('#history-table tr:last');

        $.each(lastRow.find('td'), function (index, item) {
            $(item).attr('tabindex', 1);
            if ($(item).text() == "null")
                $(item).text('');
        });
    })
}

function addQuoteRow(data) {
    var price = ((data.money_buy*rateList[data.type_money_buy].buy_rate*data.buy_quantity)/(1-data.rate_profit)+data.fee_shipping)/(data.sell_quantity ? data.sell_quantity : data.sell_quantity_second)/rateList[data.type_money_sell].sale_rate;
    
    if (Number.isNaN(price) || !isFinite(price)) price = 0;
    price = Math.round(price * 100000) / 100000;
    
    var rate = (price*(row.sell_quantity ? row.sell_quantity : row.sell_quantity_second)*rateList[row.type_money_sell].sale_rate)-(row.money_buy*rateList[row.type_money_buy].buy_rate*row.buy_quantity)-row.fee_shipping;
    if (Number.isNaN(rate) || !isFinite(rate)) rate = 0;
    rate = Math.round(rate * 100000) / 100000;

    var originalData = {
        "受付番号": data.request_vendors.rfq_request.detail_id + ` - ` + data.request_vendors.rfq_request.child_index,
        "受付日": data.receive_date,
        "見積番号": (data.is_sendmail == 1) ? data.rank_quote : '',
        "見積日": data.date_send,
        "客先": data.customer.user_info.company_name,
        "担当": data.user_res,
        "メーカー": data.maker,
        "型番": data.katashiki,
        "DC": data.dc,
        "Rohs": data.rohs,
        "地域": data.kbn2,
        "希望数量": data.count_predict,
        "見積備考": data.quote_prefer,
        "見積納期": data.deadline_quote,
        "仕入先": data.supplier.user_info.company_name,
        "買数量": data.buy_quantity,
        "買単位": data.unit_buy,
        "買通貨": data.type_money_buy,
        "買単価": data.money_buy,
        "買金額": data.unit_price_buy,
        "送料": data.fee_shipping,
        "粗利率": data.rate_profit ? data.rate_profit : 0,
        "粗利": data.profit ? data.profit : rate.toFixed(2),
        "見積単価": data.price_quote ? data.price_quote : price.toFixed(2),
        "売数量": data.sell_quantity ? data.sell_quantity : data.sell_quantity_second,
        "売単位": data.unit_sell,
        "売通貨": data.type_money_sell,
        "売単価": data.unit_price_sell ? data.unit_price_sell : data.unit_price_second,
        "売金額": data.money_sell ? data.money_sell : data.money_sell_second,
        "支払い条件": data.cond_payment,
        // "購買メッセージ": item.comment_bus,
    };

    var child = `<tr role="row"><td class="p-48"><input type="checkbox" class="quote-check"></td>`;

    $.each(quoteColumns, function (index, item) {
        if (!originalData[item] || originalData[item] == undefined)
            child += `<td class="p-48" tabindex="` + index + `"></td>`;
        else
            child += `<td class="p-48" tabindex="` + index + `">` + originalData[item] + `</td>`;
    });
    child += `</tr>`;
    return $(child);
}

function insertMultiQuoteRows(data) {
    $.each(data, function (index, item) {
        var price = ((item.money_buy*rateList[item.type_money_buy].buy_rate*item.buy_quantity)/(1-item.rate_profit)+item.fee_shipping)/(item.sell_quantity ? item.sell_quantity : item.sell_quantity_second)/rateList[item.type_money_sell].sale_rate;

        var rate = ((item.price_quote ? item.price_quote : price)*(item.sell_quantity ? item.sell_quantity : item.sell_quantity_second)*rateList[item.type_money_sell].sale_rate)-(item.money_buy*rateList[item.type_money_buy].buy_rate*item.buy_quantity)-item.fee_shipping;
        var originalData = {
            "受付番号": item.request_vendors.rfq_request.detail_id + ` - ` + item.request_vendors.rfq_request.child_index,
            "受付日": item.receive_date,
            "見積番号": (item.is_sendmail == 1) ? item.rank_quote : '',
            "見積日": item.date_send,
            "客先": item.customer.user_info.company_name,
            "担当": item.user_res,
            "メーカー": item.maker,
            "型番": item.katashiki,
            "DC": item.dc,
            "Rohs": item.rohs,
            "地域": item.kbn2,
            "希望数量": item.count_predict,
            "見積備考": item.quote_prefer,
            "見積納期": item.deadline_quote,
            "仕入先": item.supplier.user_info.company_name,
            "買数量": item.buy_quantity,
            "買単位": item.unit_buy,
            "買通貨": item.type_money_buy,
            "買単価": item.money_buy,
            "買金額": item.unit_price_buy,
            "送料": item.fee_shipping,
            "粗利率": item.rate_profit ? item.rate_profit : 0,
            "粗利": item.profit ? item.profit : rate.toFixed(2),
            "見積単価": item.price_quote ? item.price_quote : price.toFixed(2),
            "売数量": item.sell_quantity ? item.sell_quantity : item.sell_quantity_second,
            "売単位": item.unit_sell,
            "売通貨": item.type_money_sell,
            "売単価": item.unit_price_sell ? item.unit_price_sell : item.unit_price_second,
            "売金額": item.money_sell ? item.money_sell : item.money_sell_second,
            "支払い条件": item.cond_payment,
            // "購買メッセージ": item.comment_bus,
        };

        var child = `<tr role="row"><td class="p-48"><input type="checkbox" class="quote-check"></td>`;

        $.each(quoteColumns, function (index, item) {
            child += `<td class="p-48">` + originalData[item] + `</td>`;
        });
        child += `</tr>`;

        $('#quote-table').append(child);
        var lastRow = $('#quote-table tr:last');

        if (item.is_solved == 1)
            lastRow.addClass('tr-yellow');
        if (item.is_order == 1) {
            lastRow.find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[5]) + 1) + ')').addClass('tr-green');
            lastRow.find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[6]) + 1) + ')').addClass('tr-green');
        }
        if (item.is_sendmail == 1)
            lastRow.find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[4]) + 1) + ')').addClass('tr-light-blue');

        if (item.is_delete == 1)
            lastRow.find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[2]) + 1) + ')').addClass('tr-grey');

        if (item.request_vendors.rfq_request.is_solved == 0)
            lastRow.find('td:eq(' + (quoteColumns.indexOf(originalQuoteKeyArr[2]) + 1) + ')').addClass('tr-orange');

        lastRow.find('td:eq(22)').addClass('rate_profit_td');
        lastRow.find('td:eq(25)').addClass('sell_quantity_td');
        lastRow.find('td:eq(27)').addClass('quote_currency_td');

        lastRow.data('quoteId', item.id);
        lastRow.data('customerId', item.customer_id);
        lastRow.data('rowInfo', item);
        lastRow.data('rfq_status', item.is_solved);
        lastRow.data('status', item.is_solved);

        if ($('input.all-quote-check').prop('checked'))
            lastRow.find('td:eq(0) input').prop('checked', true);

        $.each(lastRow.find('td'), function (index, item) {
            $(item).attr('tabindex', 1);
            if ($(item).text() == "null")
                $(item).text('');
        });
    })
}

function updatedByChangedQuoteTable() {
    var rowInfo = $('#quote-table').find('tr.selected').data("rowInfo");
    if (rowInfo) {
        if (rowInfo.katashiki)
            $('#history-table_filter').val(rowInfo.katashiki);
        else
            $('#history-table_filter').val('');
        drawCustomerInfo(rowInfo.customer);
        drawSupplierInfo(rowInfo.supplier);

        if (historyTable)
            historyTable.draw();

        if (rowInfo.request_vendors.messages[0])
            $('textarea.message-box').val(rowInfo.request_vendors.messages[0].content);
        else
            $('textarea.message-box').val('');
    }
}

// After pressing F2 keyboard, event
$(document).on('blur', 'td', function () {
    var indiInput = $('.indi-edit');
    if (indiInput.length > 0) {
        var parentTd = indiInput.parents('td');
        var text = indiInput.val();
        parentTd.attr('tabindex', parentTd.index() + 1)
        parentTd.addClass('p-48').removeClass('p-0');
        parentTd.html(text);
    }
})