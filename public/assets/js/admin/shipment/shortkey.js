var indexList = [
    shipmentColumns.indexOf(originalKeyArr[9]) + 1,
    shipmentColumns.indexOf(originalKeyArr[12]) + 1,
    // shipmentColumns.indexOf(originalKeyArr[17])+1,
    shipmentColumns.indexOf(originalKeyArr[21]) + 1,
    shipmentColumns.indexOf(originalKeyArr[24]) + 1,
    shipmentColumns.indexOf(originalKeyArr[25]) + 1,
];
indexList.sort(function (a, b) {
    return a - b;
});
$('#shipment-table').keydown(function (e) {
    if (!e.altKey) {
        switch (e.keyCode) {
            case 8: //backspace
                var curTd = $('#shipment-table').find(':focus');
                if (!indexList.includes(curTd.index()))
                    return;
                var curText = curTd.text();
                curTd.text(curText.slice(0, -1));
                curTd.parents('tr').addClass('direct-edit');
                break;
            case 113: // F2 shortkey
                var curText = $('#shipment-table').find(':focus').text();
                var curTd = $('#shipment-table').find(':focus');
                curTd.data('origin', curText);
                if (!indexList.includes(curTd.index()) || curTd.index() == 0)
                    return;

                curTd.removeAttr('tabindex');
                curTd.html('<input type="text" class="form-control form-control-sm indi-edit" value="' + curText + '">').removeClass('p-48').addClass('p-0');
                curTd.find('input').focus();
                break;
            case 46: //delete key
                var curTd = $('#shipment-table').find(':focus');
                if (!indexList.includes(curTd.index()) || curTd.index() == 0)
                    return;

                $('#shipment-table').find(':focus').text('');
                break;
            case 9: //tab
                e.preventDefault();

                if (!e.shiftKey) {
                    if ($('#shipment-table').find('.indi-edit').length > 0) {
                        var editInput = $('#shipment-table').find('.indi-edit');
                        var editTd = editInput.parents('td');
                        var editTr = editTd.parents('tr');

                        editTd.attr('tabindex', editTd.index() + 1).addClass('p-48').removeClass('p-0').html(editInput.val()).focus();

                        var nextIndex = editTd.index() + 1;
                        if (nextIndex >= editTr.find('td').length)
                            return;
                        editTr.find('td:eq(' + nextIndex + ')').focus();
                        editTr.addClass('direct-edit');
                        return;
                    }
                    var trElem = $('#shipment-table').find('tr.selected');
                    var tdElemIndex = trElem.find(':focus').parent('td').index();
                    var nextIndex = indexList.indexOf(tdElemIndex) + 1;
                    if (nextIndex >= indexList.length)
                        return;
                    else
                        var nextTdIndex = indexList[nextIndex];
                    trElem.find('td:eq(' + nextTdIndex + ')').children().focus();
                } else if (e.shiftKey) {
                    curElem = $(':focus');
                    tdElem = curElem.parents('td');
                    tdElemIndex = curElem.parents('td').index();
        
                    tdElem.text(tdElem.data('origin'));
                    var nextIndex = tdElemIndex + 1;
                    // if (nextIndex >= indexList.length)
                    //     return;
                    // else
                    //     var nextTdIndex = indexList[nextIndex];
                    var nextTD = $('#shipment-table .selected').find('td:eq('+nextIndex+')');
                    nextTD.focus();
                }
                break;
            case 37: // left
                if ($('#shipment-table').find('.indi-edit').length > 0)
                    return;
                e.preventDefault();
                var trElem = $('#shipment-table').find('tr.shipment-edit-tr');
                if (trElem.length > 0) {
                    var tdElemIndex = trElem.find(':focus').parent('td').index();
                    var nextIndex = indexList.indexOf(tdElemIndex) - 1;
                    if (nextIndex < 0)
                        return;
                    else
                        var nextTdIndex = indexList[nextIndex];
                    trElem.find('td:eq(' + nextTdIndex + ')').children().focus();
                    return;
                }

                var selectRow = $('#shipment-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    var curOne = selectRow.find(':focus');
                    if (curOne.hasClass('td-decoration')) {
                        curOne.removeClass('td-decoration');
                        if (curOne.is(':first-child')) {
                            selectRow.find('td:last-child').focus().addClass('td-decoration');
                            return;
                        }
                        curOne.prev().addClass('td-decoration').focus();
                    } else {
                        if (curOne.is(':first-child')) {
                            selectRow.find('td:last-child').focus();
                            return;
                        }
                        curOne.prev().focus();
                    }

                    if (curOne.is(':first-child')) {
                        $('#shipment-table').parents('.dataTables_scrollBody').scrollLeft(0);
                    }
                    return;
                }
                break;
            case 38: // up
                if ($('#shipment-table').find('.order-edit-tr').length > 0) {
                    var oldTdElme = $('#shipment-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                    autoSaveShipmentData();
                } else if ($('#shipment-table').find('.direct-edit').length > 0) {
                    if ($('#shipment-table').find(':focus').prop("tagName").toUpperCase() == 'TD')
                        var oldTdElme = $('#shipment-table').find(':focus');
                    else
                        var oldTdElme = $('#shipment-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                } else {
                    var oldTdElme = $('#shipment-table').find('tr.selected td:focus');
                    var oldTdFocusIndex = oldTdElme.index();
                }

                var index = $('#shipment-table').find('tr.selected').index();
                if (index >= 1) {
                    $('#shipment-table').find('tr.selected').removeClass('selected tr-orange');
                    $('#shipment-table').find('tr:eq(' + (index) + ')').addClass('selected tr-orange').focus();
                    if (oldTdFocusIndex != -1) {
                        var nextTdElem = $('#shipment-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')');
                        nextTdElem.focus();
                        updateTdElem(oldTdElme, nextTdElem);
                    } else {
                        $('#shipment-table').find('tr.selected td').first().focus();
                    }

                    var oneStepHeight = $('#shipment-table tbody').find('tr:eq(0)').height();
                    var trPos = oneStepHeight * ($('#shipment-table').find('tr.selected').index() + 1);
                    var mainPoint = trPos - oneStepHeight * 2;
                    $('#shipment-table').parents('.dataTables_scrollBody').scrollTop(mainPoint);

                    updatedByChangedshipmentTable();
                    e.preventDefault();
                }
                break;
            case 39: // right
                if ($('#shipment-table').find('.indi-edit').length > 0)
                    return;

                e.preventDefault();
                var trElem = $('#shipment-table').find('tr.shipment-edit-tr');
                if (trElem.length > 0) {
                    var tdElemIndex = trElem.find(':focus').parent('td').index();
                    var nextIndex = indexList.indexOf(tdElemIndex) + 1;
                    if (nextIndex >= indexList.length)
                        return;
                    else
                        var nextTdIndex = indexList[nextIndex];
                    trElem.find('td:eq(' + nextTdIndex + ')').children().focus();
                    return;
                }
                var selectRow = $('#shipment-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    var curOne = selectRow.find(':focus');
                    if (curOne.hasClass('td-decoration')) {
                        curOne.removeClass('td-decoration');
                        if (selectRow.find(':focus').is(':last-child')) {
                            selectRow.find('td:first-child').addClass('td-decoration').focus();
                            return;
                        }
                        selectRow.find(':focus').next().addClass('td-decoration').focus();
                        if (selectRow.find(':focus').is(':last-child')) {
                            $('#shipment-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                        }
                    } else {
                        if (selectRow.find(':focus').is(':last-child')) {
                            selectRow.find('td:first-child').focus();
                            return;
                        }
                        selectRow.find(':focus').next().focus();
                        if (selectRow.find(':focus').is(':last-child')) {
                            $('#shipment-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                        }
                    }
                    return;
                }
                break;
            case 40: // down
                if ($('#shipment-table').find('.order-edit-tr').length > 0) {
                    var oldTdElme = $('#shipment-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                    autoSaveShipmentData();
                } else if ($('#shipment-table').find('.direct-edit').length > 0) {
                    if ($('#shipment-table').find(':focus').prop("tagName").toUpperCase() == 'TD')
                        var oldTdElme = $('#shipment-table').find(':focus');
                    else
                        var oldTdElme = $('#shipment-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                } else {
                    var oldTdElme = $('#shipment-table').find('tr.selected td:focus');
                    var oldTdFocusIndex = oldTdElme.index();
                }
                var index = $('#shipment-table').find('tr.selected').index();
                if (index <= $('#shipment-table tbody').find('tr').length - 2) {
                    var curScrollPos = $('#shipment-table').parents('.dataTables_scrollBody').scrollTop();
                    var oneStepHeight = $('#shipment-table tbody').find('tr:eq(0)').height();
                    $('#shipment-table').find('tr.selected').removeClass('selected tr-orange');
                    $('#shipment-table').find('tr:eq(' + (index + 2) + ')').addClass('selected tr-orange').focus();
                    if (oldTdFocusIndex != -1) {
                        var nextTdElem = $('#shipment-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')');
                        nextTdElem.focus();
                        updateTdElem(oldTdElme, nextTdElem);
                    } else {
                        $('#shipment-table').find('tr.selected td').first().focus();
                    }

                    var trPos = oneStepHeight * $('#shipment-table').find('tr.selected').index();
                    var diff = trPos - curScrollPos;
                    if (diff < oneStepHeight * 3)
                        $('#shipment-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos);
                    else if (diff > oneStepHeight * 4) {
                        $('#shipment-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + diff);
                    } else
                        $('#shipment-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + oneStepHeight);
                    updatedByChangedshipmentTable();
                    e.preventDefault();
                }
                break;
            case 35: // end
                if ($('#shipment-table').find('.indi-edit').length > 0)
                return;
                e.preventDefault();
                
                var selectRow = $('#shipment-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    if (selectRow.find(':focus').is(':first-child')) {
                        selectRow.find('td:eq(26)').focus();
                        return;
                    }
                    selectRow.find('td:eq(26)').focus();
                    return;
                }
                break;
            case 36: // home
                if ($('#shipment-table').find('.indi-edit').length > 0)
                return;
                e.preventDefault();
                
                var selectRow = $('#shipment-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    if (selectRow.find(':focus').is(':first-child')) {
                        selectRow.find('td:eq(1)').focus();
                        return;
                    }
                    selectRow.find('td:eq(1)').focus();
                    return;
                }
                break;
            default:
                if ((47 < e.keyCode && e.keyCode < 58) || (64 < e.keyCode && e.keyCode < 91) || (95 < e.keyCode && e.keyCode < 106) || e.keyCode == 110 || e.keyCode == 130 || e.keyCode == 186 || e.keyCode == 187 || e.keyCode == 188 ||  e.keyCode == 189 ||e.keyCode == 191 ||   e.keyCode == 192 ||  e.keyCode == 219 || e.keyCode == 220 ||  e.keyCode == 221 || e.keyCode == 222) {
                    var curTd = $('#shipment-table').find(':focus');
                    if (!indexList.includes(curTd.index()))
                        return;

                    if (!curTd.hasClass('editing-td')) {
                        curTd.addClass('editing-td');
                        curTd.data('origin', curTd.text());
                        curTd.text('');
                    }

                    var trSelect = curTd.parents('tr');
                    if (!trSelect.hasClass('direct-edit'))
                        trSelect.addClass('direct-edit');
                    var curText = curTd.text();
                    curTd.text(curText + e.key);
                }
                break;
        }
    }
})

$(document).keydown(function (e) {
    $('.datepicker.datepicker-dropdown.dropdown-menu').remove();
    switch (e.keyCode) {
        //search key
        case 67: //c
            if (e.altKey) $('#search-customer').focus();
            break;
        case 83: //s
            if (e.altKey) $('#search-ship-date').focus();
            break;
        case 90: //z
            if (e.altKey) $('#search-model').focus();
            break;
        case 84: //t
            if (e.altKey) $('#search-maker').focus();
            break;
        case 75: //k
            if (e.altKey) $('#search-billing-number').focus();
            break;
        case 66: //b
            if (e.altKey) $('#search-invoice-number').focus();
            break;
        case 76: //l
            if (e.altKey) $('#search-status').focus();
            break;

        //clear key
        case 46: //delete
            if (e.altKey) $('#search-area-clear').click();
            break;

        //action key
        case 50: //2
            if (e.altKey) $('#actual-product-slip-btn').click();
            break;
        case 51: //3
            if (e.altKey) $('#export-excel-btn').click();
            break;
        case 52: //4
            if (e.altKey) $('#envelope-printing-btn').click();
            break;
        case 53: //5
            if (e.altKey) $('#import-excel-btn').click();
            break;
        case 54: //6
            if (e.altKey) $('#voucher-printing-btn').click();
            break;
        case 55: //7
            if (e.altKey) $('#shipment-btn').click();
            break;
        case 56: //8
            if (e.altKey) $('#status-change-btn').click();
            break;
        case 57: //9
            if (e.altKey) $('#update-fee-btn').click();
            break;
    }
    return;
})
