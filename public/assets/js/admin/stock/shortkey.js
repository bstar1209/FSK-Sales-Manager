var indexList = [
    stockColumns.indexOf(originalKeyArr[12]) + 1,
    stockColumns.indexOf(originalKeyArr[13]) + 1,
    stockColumns.indexOf(originalKeyArr[14]) + 1,
    stockColumns.indexOf(originalKeyArr[15]) + 1,
    stockColumns.indexOf(originalKeyArr[16]) + 1,
    stockColumns.indexOf(originalKeyArr[17]) + 1,
    stockColumns.indexOf(originalKeyArr[18]) + 1,
    stockColumns.indexOf(originalKeyArr[19]) + 1,
];
indexList.sort(function (a, b) {
    return a - b;
});

$('#stock-table').keydown(function (e) {
    if (!e.altKey) {
        switch (e.keyCode) {
            case 8: //backspace
                var curTd = $('#stock-table').find(':focus');
                if (!indexList.includes(curTd.index()))
                    return;
                var curText = curTd.text();
                curTd.text(curText.slice(0, -1));
                curTd.parents('tr').addClass('direct-edit');
                break;
            case 113: // F2 shortkey
                var curText = $('#stock-table').find(':focus').text();
                var curTd = $('#stock-table').find(':focus');
                curTd.data('origin', curText);
                if (!indexList.includes(curTd.index()) || curTd.index() == 0)
                    return;
                curTd.removeAttr('tabindex');
                if ((stockColumns.indexOf(originalKeyArr[13]) + 1) == curTd.index()) {
                    curTd.removeClass('p-48').addClass('p-0').html('<select class="form-control form-control-sm indi-edit">' + rateOptionHtml + '</select>');
                    curTd.find('select').val(curText);
                    curTd.find('select').focus();
                } else {
                    curTd.html('<input type="text" class="form-control form-control-sm indi-edit" value="' + curText + '">').removeClass('p-48').addClass('p-0');
                    curTd.find('input').focus();
                }
                break;
            case 46: //delete key
                var curTd = $('#stock-table').find(':focus');
                if (!indexList.includes(curTd.index()) || curTd.index() == 0)
                    return;

                $('#stock-table').find(':focus').text('');
                break;
            case 9: //tab
                e.preventDefault();
                if (!e.shiftKey) {
                    if ($('#stock-table').find('.indi-edit').length > 0) {
                        var editInput = $('#stock-table').find('.indi-edit');
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
                    var trElem = $('#stock-table').find('tr.selected');
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
                    var nextTD = $('#stock-table .selected').find('td:eq('+nextIndex+')');
                    nextTD.focus();
                }
                break;
            case 37: // left
                if ($('#stock-table').find('.indi-edit').length > 0)
                    return;
                e.preventDefault();
                var trElem = $('#stock-table').find('tr.stock-edit-tr');
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

                var selectRow = $('#stock-table').find('.selected').not('.d-none').first();
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
                        $('#stock-table').parents('.dataTables_scrollBody').scrollLeft(0);
                    }
                    return;
                }
                break;
            case 38: // up
                if ($('#stock-table').find('.indi-edit').length > 0)
                    return;

                if ($('#stock-table').find('.stock-edit-tr').length > 0) {
                    var oldTdElme = $('#stock-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                    autoSaveStockData();
                } else if ($('#stock-table').find('.direct-edit').length > 0) {
                    if ($('#stock-table').find(':focus').prop("tagName").toUpperCase() == 'TD')
                        var oldTdElme = $('#stock-table').find(':focus');
                    else
                        var oldTdElme = $('#stock-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index()
                } else {
                    var oldTdElme = $('#stock-table').find('tr.selected td:focus');
                    var oldTdFocusIndex = oldTdElme.index();
                }

                var index = $('#stock-table').find('tr.selected').index();
                if (index >= 1) {
                    $('#stock-table').find('tr.selected').removeClass('selected tr-orange');
                    $('#stock-table').find('tr:eq(' + (index) + ')').addClass('selected tr-orange').focus();
                    if (oldTdFocusIndex != -1) {
                        var nextTdElem = $('#stock-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')');
                        nextTdElem.focus();
                        updateTdElem(oldTdElme, nextTdElem);
                    } else {
                        $('#stock-table').find('tr.selected td').first().focus();
                    }

                    var oneStepHeight = $('#stock-table tbody').find('tr:eq(0)').height();
                    var trPos = oneStepHeight * ($('#stock-table').find('tr.selected').index() + 1);
                    var mainPoint = trPos - oneStepHeight * 2;
                    $('#stock-table').parents('.dataTables_scrollBody').scrollTop(mainPoint);
                    updatedByChangedStockTable();
                    e.preventDefault();
                }
                break;
            case 39: // right
                if ($('#stock-table').find('.indi-edit').length > 0)
                    return;

                e.preventDefault();
                var trElem = $('#stock-table').find('tr.stock-edit-tr');
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

                var selectRow = $('#stock-table').find('.selected').not('.d-none').first();
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
                            $('#stock-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                        }
                    } else {
                        if (selectRow.find(':focus').is(':last-child')) {
                            selectRow.find('td:first-child').focus();
                            return;
                        }
                        selectRow.find(':focus').next().focus();
                        if (selectRow.find(':focus').is(':last-child')) {
                            $('#stock-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                        }
                    }
                }
                break;
            case 40: // down
                if ($('#stock-table').find('.stock-edit-tr').length > 0) {
                    var oldTdElme = $('#stock-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                    autoSaveStockData();
                } else if ($('#stock-table').find('.direct-edit').length > 0) {
                    if ($('#stock-table').find(':focus').prop("tagName").toUpperCase() == 'TD')
                        var oldTdElme = $('#stock-table').find(':focus');
                    else
                        var oldTdElme = $('#stock-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index()
                } else {
                    var oldTdElme = $('#stock-table').find('tr.selected td:focus');
                    var oldTdFocusIndex = oldTdElme.index();
                }
                var index = $('#stock-table').find('tr.selected').index();
                if (index <= $('#stock-table tbody').find('tr').length - 2) {
                    var curScrollPos = $('#stock-table').parents('.dataTables_scrollBody').scrollTop();
                    var oneStepHeight = $('#stock-table tbody').find('tr:eq(0)').height();
                    $('#stock-table').find('tr.selected').removeClass('selected tr-orange');
                    $('#stock-table').find('tr:eq(' + (index + 2) + ')').addClass('selected tr-orange').focus();
                    if (oldTdFocusIndex != -1) {
                        var nextTdElem = $('#stock-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')');
                        nextTdElem.focus();
                        updateTdElem(oldTdElme, nextTdElem);
                    } else {
                        $('#stock-table').find('tr.selected td').first().focus();
                    }

                    var trPos = oneStepHeight * $('#stock-table').find('tr.selected').index();
                    var diff = trPos - curScrollPos;
                    if (diff < oneStepHeight * 3)
                        $('#stock-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos);
                    else if (diff > oneStepHeight * 4) {
                        $('#stock-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + diff);
                    } else
                        $('#stock-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + oneStepHeight);
                    updatedByChangedStockTable();
                    e.preventDefault();
                }
                break;
            case 35: // end
                if ($('#stock-table').find('.indi-edit').length > 0)
                return;
                e.preventDefault();
                
                var selectRow = $('#stock-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    if (selectRow.find(':focus').is(':first-child')) {
                        selectRow.find('td:eq(20)').focus();
                        return;
                    }
                    selectRow.find('td:eq(20)').focus();
                    return;
                }
                break;
            case 36: // home
                if ($('#stock-table').find('.indi-edit').length > 0)
                return;
                e.preventDefault();
                
                var selectRow = $('#stock-table').find('.selected').not('.d-none').first();
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
                    var curTd = $('#stock-table').find(':focus');
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
            if (e.altKey) $('#search-supplier-name').focus();
            break;
        case 90: //z
            if (e.altKey) $('#search-model-number').focus();
            break;
        case 84: //t
            if (e.altKey) $('#search-maker').focus();
            break;
        case 69: //e
            if (e.altKey) $('#search-order-number').focus();
            break;
        case 66: //b
            if (e.altKey) $('#search-ship-order-number').focus();
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
            if (e.altKey) $('#change-status-processed').click();
            break;
        case 52: //4
            if (e.altKey) $('#to-shipping-btn').click();
            break;
        case 53: //5
            if (e.altKey) $('#return-to-btn').click();
            break;
        case 54: //6
            if (e.altKey) $('#sold-out-btn').click();
            break;

        //message key
        case 74: //j
            if (e.altKey) $('textarea.message-box').focus();
            break;
    }
    return;
})
