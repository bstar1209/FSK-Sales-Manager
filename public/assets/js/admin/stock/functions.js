var rateList = {},
    commonList = {},
    supplierList = [],
    shipList = [],
    commonPaymentList = {},
    transportList = [];

var stockTableLoadingFlag = true;
var currentTime = +new Date();
var rateOptionHtml = '';

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
    var importGoodsIds = [];
    $.each($('.stock-check'), function (index, item) {
        if ($(item).prop("checked")) {
            selectedIds.push($(item).parents('tr').data('stockId'));
            selectedIndexs.push(index);
            importGoodsIds.push($(item).parents('tr').data('importGoodsId'));
        }
    })
    return {
        ids: selectedIds,
        indexs: selectedIndexs,
        importGoodsIds: importGoodsIds
    }
}

function removeEditalRow() {
    var target = $('#stock-table').find('.stock-edit-tr');
    var targetData = target.data('rowInfo');

    if (!targetData)
        return false;

    var editableData = [
        targetData.import_goods.ship_quantity, targetData.import_goods.type_money_ship,
        targetData.import_goods.price_ship, targetData.import_goods.import_date, targetData.import_goods.import_qty,
        targetData.import_goods.import_unit_price, targetData.import_goods.coo, targetData.import_goods.in_tr
    ];

    $.each(editableIndexs, function (index, item) {
        target.find('td:eq(' + item + ')').addClass('p-48').removeClass('p-0').text(editableData[index]);
    });
    target.removeClass('stock-edit-tr');
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

function insertMultiStockOrderRows(data) {
    $.each(data, function (index, item) {
        var originalData = [
            item.quote_customer.customer.user_info.company_name,
            item.quote_customer.customer.representative,
            item.import_goods.user_code,
            item.import_goods.import_date_plan,
            item.import_goods.send_date,
            item.import_goods.expect_ship_date,
            item.import_goods.maker,
            item.import_goods.katashiki,
            item.import_goods.dc,
            item.import_goods.rohs,
            item.supplier.user_info.address.country,
            item.supplier.user_info.company_name,
            item.import_goods.ship_quantity,
            item.import_goods.type_money_ship,
            item.import_goods.price_ship,
            item.import_goods.import_date,
            item.import_goods.import_qty,
            item.import_goods.import_unit_price,
            item.import_goods.coo,
            item.import_goods.in_tr,
        ];
        var child = `<tr role="row"><td class="p-48"><input type="checkbox" class="shipment-check"></td>`;

        $.each(originalIndexs, function (index, item) {
            child += `<td class="p-48">` + originalData[item] + `</td>`;
        });
        child += `</tr>`;

        $('#stock-table').append(child);
        var lastRow = $('#stock-table tr:last');
        lastRow.data('rowInfo', item);
        lastRow.data('orderId', item.id);

        if (item.import_goods.import_status == 1)
            $(row).addClass('tr-yellow');

        if (item.import_goods.importKBN == 1) {
            $(row).find('td:eq(' + (stockColumns.indexOf(originalKeyArr[4]) + 1) + ')').addClass('tr-light-blue')
        }

        if (item.import_goods.import_date_plan) {
            var importTime = +new Date(item.import_goods.import_date_plan);
            if (currentTime - importTime > 86400000)
                $(row).find('td:eq(' + (stockColumns.indexOf(originalKeyArr[4]) + 1) + ')').addClass('font-red');
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

function updatedByChangedStockTable() {
    var rowInfo = $('#stock-table').find('tr.selected').data("rowInfo");
    if (rowInfo) {
        drawSupplierInfo(rowInfo.supplier);
        if (rowInfo.quote_customer.request_vendors.messages[0])
            $('textarea.message-box').val(rowInfo.quote_customer.request_vendors.messages[0].content);
        else
            $('textarea.message-box').val('');
    }
}

function getStockRowByIndex(pos) {
    var target = $("#stock-table").find('tr:eq(' + (pos) + ')');
    var targetData = target.data('rowInfo');

    if (!targetData)
        return;

    var updatedData = [];
    $.each(editableIndexs, function (index, item) {
        if (index != 3)
            updatedData.push(target.find('td:eq(' + item + ')').text().toString());
        else
            updatedData.push(target.find('td:eq(' + item + ')').text());
    });

    var priceShip = updatedData[2];
    var importQty = updatedData[4];
    var importUnitPrice = updatedData[5];

    if (priceShip == '') priceShip = '0';
    if (importQty == '') importQty = '0';
    if (importUnitPrice == '') importUnitPrice = '0';

    var ajaxData = {
        id: targetData.import_goods.id,
        shipQty: updatedData[0],
        typeMoneyShip: updatedData[1],
        priceShip: parseFloat(priceShip.normalize('NFKC')),
        importDate: updatedData[3],
        importQty: parseFloat(importQty.normalize('NFKC')),
        importUnitPrice: parseFloat(importUnitPrice.normalize('NFKC')),
        coo: updatedData[6],
        inTr: updatedData[7],
    }
    return ajaxData;
}

function autoSaveStockData() {
    var target = $("#stock-table").find('.stock-edit-tr');
    var targetData = target.data('rowInfo');

    if (!targetData) return;

    var updatedData = [];
    $.each(editableIndexs, function (index, item) {
        if (index == 1) {
            var TdElem = target.find('td:eq(' + item + ')');
            if (TdElem.find('select').length > 0)
                var value = TdElem.find('select').val();
            else
                var value = TdElem.children().text();
            updatedData.push(value);
        }
        else if (index != 3)
            updatedData.push(target.find('td:eq(' + item + ') input').val().toString());
        else
            updatedData.push(target.find('td:eq(' + item + ') input').val());
    });

    var priceShip = updatedData[2];
    var importQty = updatedData[4];
    var importUnitPrice = updatedData[5];

    if (priceShip == '') priceShip = '0';
    if (importQty == '') importQty = '0';
    if (importUnitPrice == '') importUnitPrice = '0';

    var ajaxData = {
        id: targetData.import_goods.id,
        shipQty: updatedData[0],
        typeMoneyShip: updatedData[1],
        priceShip: parseFloat(priceShip.normalize('NFKC')),
        importDate: updatedData[3],
        importQty: parseFloat(importQty.normalize('NFKC')),
        importUnitPrice: parseFloat(importUnitPrice.normalize('NFKC')),
        coo: updatedData[6],
        inTr: updatedData[7],
    }

    if (ajaxData.importDate == '' || ajaxData.importQty == '') {
        toastr.warning('キャンセル 仕入先。');
        removeEditalRow();
        return;
    }

    $.ajax({
        url: autoStockOrderUrl,
        method: 'POST',
        data: ajaxData,
        success: function (data) {
            var result = JSON.parse(data);
            targetData.import_goods = result;
            target.data('rowInfo', targetData);
            removeEditalRow();
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
