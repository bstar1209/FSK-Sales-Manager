var rateList = {},
    commonList = {},
    supplierList = [],
    shipList = [],
    commonPaymentList = {},
    transportList = [];

var shipmentTableLoadingFlag = true;
var currentTime = +new Date();

function checkSelectRow() {
    var selectedIds = [];
    var selectedIndexs = [];
    var importGoodsIds = [];
    var orderHeaderIds = [];
    $.each($('.shipment-check:checked'), function (index, item) {
        selectedIds.push($(item).parents('tr').data('shipmentId'));
        selectedIndexs.push(index);
        importGoodsIds.push($(item).parents('tr').data('importGoodsId'));
        orderHeaderIds.push($(item).parents('tr').data('orderHeaderId'));
    })
    return {
        ids: selectedIds,
        indexs: selectedIndexs,
        importGoodsIds: importGoodsIds,
        orderHeaderIds: orderHeaderIds
    }
}

function removeEditalRow() {
    var target = $('#shipment-table').find('.shipment-edit-tr');
    var targetData = target.data('rowInfo');
    if (!targetData)
        return;

    var editableData = [
        targetData.sale_qty, targetData.sale_cost, targetData.import_goods.export_time,
        targetData.import_goods.out_tr, targetData.order_header.fee_shipping,
        targetData.order_header.fee_daibiki
    ];

    $.each(editableIndexs, function (index, item) {
        target.find('td:eq(' + item + ')').addClass('p-48').removeClass('p-0').text(editableData[index]);
    });

    target.removeClass('shipment-edit-tr');
}

function insertMultiShipmentRows(data) {
    $.each(data, function (index, item) {
        var originalData = {
            "ID": import_goods.order_id,
            "客先": quote_customer.customer.user_info.company_name,
            "担当": quote_customer.customer.representative,
            "入荷日": import_goods.import_date,
            "メーカー": import_goods.maker,
            "型番": import_goods.katashiki,
            "DC": import_goods.dc,
            "Rohs": import_goods.rohs,
            "粗利率": quote_customer.rate_profit,
            "売数量": sale_qty,
            "売単位": sale_unit,
            "売通貨": order_header.type_money,
            "売単価": sale_cost,
            "売金額": sale_money,
            "出荷日": import_goods.export_date,
            "顧客希望納期": order_header.expect_ship_date,
            "お届け予定日": import_goods.import_date_plan,
            "配達時間": import_goods.export_time,
            "請求日": order_header.date_invoice,
            "請求No": import_goods.invoice_code,
            "客先ID": quote_customer.customer_id,
            "OutTR#": import_goods.out_tr,
            "顧客注文番号": import_goods.user_code,
            "支払い条件": order_header.cond_payment,
            "送料": order_header.fee_shipping,
            "代引き手数料": order_header.fee_daibiki,
        };
        var child = `<tr role="row"><td class="p-48"><input type="checkbox" class="shipment-check"></td>`;

        $.each(shipmentColumns, function (index, item) {
            child += `<td class="p-48">` + originalData[item] + `</td>`;
        });
        child += `</tr>`;

        $('#shipment-table').append(child);
        var lastRow = $('#shipment-table tr:last');
        lastRow.data('rowInfo', item);
        lastRow.data('orderId', item.id);

        if (item.import_goods.is_send_mail == 1)
            $(row).find('td:eq(' + (shipmentColumns.indexOf(originalKeyArr[15]) + 1) + ')').addClass('tr-light-blue');
        if (item.import_goods.export_status == 1)
            $(row).addClass('tr-yellow');

        $.each(lastRow.find('td'), function (index, item) {
            $(item).attr('tabindex', 1);
            if ($(item).text() == "null")
                $(item).text('');
        });
    })
}

function updatedByChangedShipmentTable() {
    var rowInfo = $('#shipment-table tbody').find('tr.selected').data("rowInfo");
    // drawSupplierInfo(rowInfo.supplier);
    if (rowInfo) {
        if (rowInfo.quote_customer.request_vendors.messages[0])
            $('textarea.message-box').val(rowInfo.quote_customer.request_vendors.messages[0].content);
        else
            $('textarea.message-box').val('');
    } else {
        $('textarea.message-box').val('');
    }
}

function getShipmentRowByIndex(pos) {
    var target = $("#shipment-table").find('tr:eq(' + (pos) + ')');
    var targetData = target.data('rowInfo');

    if (!targetData)
        return;

    var updatedData = [];

    $.each(editableIndexs, function (index, item) {
        updatedData.push(target.find('td:eq(' + item + ')').val().toString());
    });

    var shipQty = updatedData[0];
    var saleCost = updatedData[1];
    var exportTime = updatedData[2];
    var outTr = updatedData[3];
    var feeShipping = updatedData[4];
    var feeDaibiki = updatedData[5];

    if (shipQty == '') shipQty = '0';
    if (saleCost == '') saleCost = '0';
    if (exportTime == '') exportTime = '0';
    if (outTr == '') outTr = '0';
    if (feeShipping == '') feeShipping = '0';
    if (feeDaibiki == '') feeDaibiki = '0';

    var ajaxData = {
        id: targetData.id,
        shipQty: parseFloat(shipQty.normalize('NFKC')),
        saleCost: parseFloat(saleCost.normalize('NFKC')),
        exportTime: exportTime,
        outTr: parseFloat(outTr.normalize('NFKC')),
        feeShipping: parseFloat(feeShipping.normalize('NFKC')),
        feeDaibiki: parseFloat(feeDaibiki.normalize('NFKC')),
    }
    return ajaxData;
}

function autoSaveShipmentData() {
    var target = $("#shipment-table").find('.shipment-edit-tr');
    var targetData = target.data('rowInfo');

    if (!targetData)
        return;

    var updatedData = [];

    $.each(editableIndexs, function (index, item) {
        updatedData.push(target.find('td:eq(' + item + ') input').val().toString());
    });

    var shipQty = updatedData[0];
    var saleCost = updatedData[1];
    var exportTime = updatedData[2];
    var outTr = updatedData[3];
    var feeShipping = updatedData[4];
    var feeDaibiki = updatedData[5];

    if (shipQty == '') shipQty = '0';
    if (saleCost == '') saleCost = '0';
    if (exportTime == '') exportTime = '0';
    if (outTr == '') outTr = '0';
    if (feeShipping == '') feeShipping = '0';
    if (feeDaibiki == '') feeDaibiki = '0';

    var ajaxData = {
        id: targetData.id,
        shipQty: parseFloat(shipQty.normalize('NFKC')),
        saleCost: parseFloat(saleCost.normalize('NFKC')),
        exportTime: exportTime,
        outTr: parseFloat(outTr.normalize('NFKC')),
        feeShipping: parseFloat(feeShipping.normalize('NFKC')),
        feeDaibiki: parseFloat(feeDaibiki.normalize('NFKC')),
    }

    $.ajax({
        url: autoShipmentOrderUrl,
        method: 'POST',
        data: ajaxData,
        success: function (data) {
            targetData.sale_qty = ajaxData.shipQty;
            targetData.sale_cost = ajaxData.saleCost;
            targetData.import_goods.export_time = ajaxData.exportTime;
            targetData.import_goods.out_tr = ajaxData.outTr;
            targetData.order_header.fee_shipping = ajaxData.feeShipping;
            targetData.order_header.fee_daibiki = ajaxData.feeDaibiki;
            target.data('rowInfo', targetData);
            removeEditalRow();
        }
    });
}

$(document).on('blur', 'td', function () {
    var indiInput = $('input.indi-edit');
    if (indiInput.length > 0) {
        var parentTd = indiInput.parents('td');
        var text = indiInput.val();
        parentTd.attr('tabindex', parentTd.index() + 1)
        parentTd.addClass('p-48').removeClass('p-0');
        parentTd.html(text);
    }
})
