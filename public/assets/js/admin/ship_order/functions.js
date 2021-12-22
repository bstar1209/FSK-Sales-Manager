var rateList = {},
    commonList = {},
    supplierList = [],
    shipList = [],
    commonPaymentList = {},
    transportList = [];

var rateOptionHtml = '',
    shipOptionHtml = '',
    transportOptionHtml = '';

var shipOrderTableLoadingFlag = true;

getRatelist();

function getShipAndTransportlist() {
    $.ajax({
        url: getShipAndTransportlistUrl,
        type: 'post',
        dataType: 'json',
        success: function (data) {
            shipOptionHtml = '';
            transportOptionHtml = '';
            shipList = data.ships;
            transportList = data.transports;
            $.each(shipList, function (index, item) {
                shipOptionHtml += '<option value="' + item.id + '">' + item.comp_name + '</option>';
            });

            $.each(transportList, function (index, item) {
                transportOptionHtml += '<option value="' + item.id + '">' + item.name + '</option>';
            });
        }
    });
}

function checkSelectRow() {
    var selectedIds = [];
    var selectedIndexs = [];
    $.each($('.ship-order-check'), function (index, item) {
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

function removeEditalRow(data) {
    var target = $('#ship-order-table').find('.order-edit-tr');
    if (data) targetData = data; else targetData = target.data('rowInfo');

    if (!targetData) return;
    var ship = ''; var transport = '';
    if (targetData.ship_to_info) ship = targetData.ship_to_info.comp_name;
    if (targetData.transport) transport = targetData.transport.name;

    var editableData = [
        targetData.ship_quantity, targetData.type_money_ship, targetData.unit_buy_ship, targetData.price_ship,
        targetData.code_send, targetData.import_date_plan, targetData.refer_vendor, ship, transport, targetData.cancel_date_vendor
    ];

    $.each(editableIndexs, function (index, item) {

        var elemValue = editableData[index];
        if (!elemValue || elemValue == 'null' || elemValue == undefined)
            elemValue = '';
        target.find('td:eq(' + item + ')').addClass('p-48').removeClass('p-0').text(elemValue);
    });

    target.removeClass('order-edit-tr');
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

function insertMultiShipOrderRows(data) {
    $.each(data, function (index, item) {
        var ship = '';
        var transport = '';

        if (item.ship_to_info)
            ship = item.ship_to_info.comp_name;
        if (item.transport)
            transport = item.transport.name;

        var originalData = {
            "客先": item.quote_customer.customer.user_info.company_name,
            "担当": item.quote_customer.customer.representative,
            "受注日": item.order_header.receive_order_date,
            "メーカー": item.maker,
            "型番": item.katashiki,
            "DC": item.quote_customer.dc,
            "地域": item.supplier.user_info.address.country,
            "Rohs": item.quote_customer.rohs,
            "見積納期": item.deadline_send,
            "仕入先": item.supplier.user_info.company_name,
            "ランク": item.supplier.user_info.rank,
            "買数量": item.ship_quantity,
            "買通貨": item.type_money_ship,
            "買単価": item.unit_buy_ship,
            "買金額": item.price_ship,
            "粗利率": item.quote_customer.rate_profit,
            "発注日": item.send_date,
            "発注番号": item.code_send,
            "納入日": item.import_date_plan,
            "顧客希望納期": item.order_header.expect_ship_date,
            "仕入先備考": item.refer_vendor,
            "Ship To": ship,
            "Ship By": transport,
            "キャンセル 仕入先": item.cancel_date_vendor,
            "仕入先見積番号": item.quote_customer.quote_code,
        };
        var child = `<tr role="row"><td class="p-48"><input type="checkbox" class="shipment-check"></td>`;

        $.each(shipOrderColumns, function (index, item) {
            child += `<td class="p-48">` + originalData[item] + `</td>`;
        });
        child += `</tr>`;

        $('#ship-order-table').append(child);
        var lastRow = $('#ship-order-table tr:last');
        lastRow.data('rowInfo', item);
        lastRow.data('orderId', item.id);

        if (item.cancel_date_user && item.cancel_date_user != '')
            lastRow.find('td:eq(' + (shipOrderColumns.indexOf(originalKeyArr[2]) + 1) + ')').addClass('tr-grey');

        if (item.cancel_date_vendor && item.cancel_date_vendor != '')
            lastRow.find('td:eq(' + (shipOrderColumns.indexOf(originalKeyArr[9]) + 1) + ')').addClass('tr-grey');

        if (item.sent_ship && item.sent_ship == 1) {
            lastRow.find('td:eq(' + (shipOrderColumns.indexOf(originalKeyArr[2]) + 1) + ')').addClass('tr-light-blue');
            lastRow.find('td:eq(' + (shipOrderColumns.indexOf(originalKeyArr[9]) + 1) + ')').addClass('tr-light-blue');
        }

        $.each(lastRow.find('td'), function (index, item) {
            $(item).attr('tabindex', 1);
            if ($(item).text() == "null")
                $(item).text('');
        });
    })
}

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
    // $("#supplier-pay-term").text(rowInfo.cond_payment);
    if (Array.isArray(rowInfo.user_info.payment) && rowInfo.user_info.payment[0] && rowInfo.user_info.payment[0].common)
        $("#supplier-pay-term").text(rowInfo.user_info.payment[0].common.common_name);
    else
        $("#supplier-pay-term").text('');
    $("#supplier-return-num").text(rowInfo.return_time);
    $("#supplier-sold-out").text(rowInfo.emp_ans_time);
}

function drawSelectPaymentList() {
    $('#register-supplier-payment-term').empty();
    $.each(commonPaymentList, function (key, val) {
        $('#register-supplier-payment-term').append('<option value="' + key + '">' + val + '</option>');
    });
}

function updatedByChangedShipOrderTable() {
    var rowInfo = $('#ship-order-table').find('tr.selected').data("rowInfo");
    if (rowInfo) {
        drawSupplierInfo(rowInfo.supplier);
        if (rowInfo.quote_customer.request_vendors.messages[0])
            $('textarea.message-box').val(rowInfo.quote_customer.request_vendors.messages[0].content);
        else
            $('textarea.message-box').val('');
    }
}

function getShipOrderRowByIndex(pos) {
    var target = $("#ship-order-table").find('tr:eq(' + (pos) + ')');
    var targetData = target.data('rowInfo');

    if (!targetData)
        return '';

    var updatedData = [];
    $.each(editableIndexs, function (index, item) {
        if (target.find('td:eq(' + item + ')').text() == undefined)
            updatedData.push('');
        else
            updatedData.push(target.find('td:eq(' + item + ')').text().toString());
    });

    var shipQty = updatedData[0];
    var unitBuyShip = updatedData[2];
    var priceShip = updatedData[3];

    if (shipQty == '') shipQty = '0';
    if (unitBuyShip == '') unitBuyShip = '0';
    if (priceShip == '') priceShip = '0';

    var ajaxData = {
        id: targetData.id,
        shipQty: parseFloat(shipQty.normalize('NFKC')),
        typeMoneyShip: updatedData[1],
        unitBuyShip: parseFloat(unitBuyShip.normalize('NFKC')),
        priceShip: parseFloat(priceShip.normalize('NFKC')),
        codeSend: updatedData[4],
        importDatePlan: updatedData[5],
        referVendor: updatedData[6],
        // shipTo: updatedData[7],
        // transport: updatedData[8],
        cancelDate: updatedData[9],
    };
    return ajaxData;
}

function autoSaveOrderData() {
    var target = $("#ship-order-table").find('.order-edit-tr');
    var targetData = target.data('rowInfo');

    if (!targetData)
        return '';

    var updatedData = [];
    $.each(editableIndexs, function (index, item) {
        if (index == 7 || index == 8 || index == 1) {
            var text = target.find('td:eq(' + item + ') select').val();
            if (!text && text == undefined)
                updatedData.push('');
            else
                updatedData.push(text);
        } else {
            if (target.find('td:eq(' + item + ') input').val() == undefined)
                updatedData.push('');
            else
                updatedData.push(target.find('td:eq(' + item + ') input').val().toString());
        }
    });

    var shipQty = updatedData[0];
    var unitBuyShip = updatedData[2];
    var priceShip = updatedData[3];

    if (shipQty == '') shipQty = '0';
    if (unitBuyShip == '') unitBuyShip = '0';
    if (priceShip == '') priceShip = '0';

    var ajaxData = {
        id: targetData.id,
        shipQty: parseFloat(shipQty.normalize('NFKC')),
        typeMoneyShip: updatedData[1],
        unitBuyShip: parseFloat(unitBuyShip.normalize('NFKC')),
        priceShip: parseFloat(priceShip.normalize('NFKC')),
        codeSend: updatedData[4],
        importDatePlan: updatedData[5],
        referVendor: updatedData[6],
        shipTo: updatedData[7],
        transport: updatedData[8],
        cancelDate: updatedData[9],
    }

    if (ajaxData.importDatePlan == '') {
        toastr.warning('キャンセル 仕入先。');
        removeEditalRow(null);
        return;
    }
    $.ajax({
        url: autoShipOrderUrl,
        method: 'POST',
        data: ajaxData,
        success: function (data) {
            var result = JSON.parse(data);
            target.data('rowInfo', null);
            target.data('rowInfo', result);
            removeEditalRow(result);
        }
    });
}

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
