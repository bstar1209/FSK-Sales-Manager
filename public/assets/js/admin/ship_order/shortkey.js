var indexList = [
    shipOrderColumns.indexOf(originalKeyArr[11]) + 1,
    shipOrderColumns.indexOf(originalKeyArr[12]) + 1,
    shipOrderColumns.indexOf(originalKeyArr[13]) + 1,
    shipOrderColumns.indexOf(originalKeyArr[14]) + 1,
    shipOrderColumns.indexOf(originalKeyArr[17]) + 1,
    shipOrderColumns.indexOf(originalKeyArr[18]) + 1,
    shipOrderColumns.indexOf(originalKeyArr[20]) + 1,
    shipOrderColumns.indexOf(originalKeyArr[21]) + 1,
    shipOrderColumns.indexOf(originalKeyArr[22]) + 1,
    shipOrderColumns.indexOf(originalKeyArr[23]) + 1,
    shipOrderColumns.indexOf(originalKeyArr[24]) + 1,
];
indexList.sort(function (a, b) {
    return a - b;
});
$('#ship-order-table').keydown(function (e) {
    if (!e.altKey) {
        switch (e.keyCode) {
            case 8: //backspace
                var curTd = $('#ship-order-table').find(':focus');
                if (!indexList.includes(curTd.index()))
                    return;
                var curText = curTd.text();
                curTd.text(curText.slice(0, -1));
                curTd.parents('tr').addClass('direct-edit');
                break;
            case 113: // F2 shortkey
                var curText = $('#ship-order-table').find(':focus').text();
                var curTd = $('#ship-order-table').find(':focus');
                curTd.data('origin', curText);
                if (!indexList.includes(curTd.index()) || curTd.index() == 0)
                    return;

                curTd.removeAttr('tabindex');
                if ((shipOrderColumns.indexOf(originalKeyArr[12]) + 1) == curTd.index()) {
                    curTd.html('<select class="form-control form-control-sm select-rate indi-edit">' + rateOptionHtml + '</select>');
                    $(".select-rate").val(curText);
                    $(".select-rate").focus();
                } else {
                    curTd.html('<input type="text" class="form-control form-control-sm indi-edit" value="' + curText + '">').removeClass('p-48').addClass('p-0');

                    if ((shipOrderColumns.indexOf(originalKeyArr[18]) + 1) == curTd.index() || (shipOrderColumns.indexOf(originalKeyArr[23]) + 1) == curTd.index()) {
                        curTd.find('input').addClass('f-datepicker');
                        curTd.find('.f-datepicker').datepicker({
                            format: 'yyyy-mm-dd',
                            inline: false,
                        }).keydown(function (e) {
                            datepickerKeyDownHandler($(this), e);
                        });
                        curTd.find('input.f-datepicker').focus();
                        setTimeout(() => {
                            curTd.find('input.f-datepicker').datepicker('show');
                        }, 150);
                    }
                }
                
                if (!curTd.find('input').hasClass('f-datepicker'))
                    curTd.find('input').focus();
                break;
            case 46: //delete key
                var curTd = $('#ship-order-table').find(':focus');
                if (!indexList.includes(curTd.index()) || curTd.index() == 0)
                    return;

                $('#ship-order-table').find(':focus').text('');
                break;
            case 9: //tab
                e.preventDefault();
                if (!e.shiftKey) {
                    if ($('#ship-order-table').find('.indi-edit').length > 0) {
                        var editInput = $('#ship-order-table').find('.indi-edit');
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
                    var trElem = $('#ship-order-table').find('tr.order-edit-tr');
                    var tdElemIndex = trElem.find(':focus').parent('td').index();
                    var nextIndex = indexList.indexOf(tdElemIndex) + 1;
                    if (nextIndex >= indexList.length)
                        return;
                    else
                        var nextTdIndex = indexList[nextIndex];
                    var nextTD = trElem.find('td:eq(' + nextTdIndex + ')');
                    if (nextTD.children().data('datepicker')) {
                        setTimeout(() => {
                            nextTD.children().datepicker('show');
                        }, 100);
                    }
                    nextTD.children().focus();
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
                    var nextTD = $('#ship-order-table .selected').find('td:eq('+nextIndex+')');
                    nextTD.focus();
                }
                break;
            case 37: // left
                if ($('#ship-order-table').find('.indi-edit').length > 0)
                    return;
                e.preventDefault();
                var trElem = $('#ship-order-table').find('tr.order-edit-tr');
                if (trElem.length > 0) {
                    var tdElemIndex = trElem.find(':focus').parent('td').index();
                    var nextIndex = indexList.indexOf(tdElemIndex) - 1;
                    if (nextIndex < 0)
                        return;
                    else
                        var nextTdIndex = indexList[nextIndex];
                    var nextTD = trElem.find('td:eq(' + nextTdIndex + ')');
                    if (nextTD.children().data('datepicker')) {
                        setTimeout(() => {
                            nextTD.children().datepicker('show');
                        }, 100);
                    }
                    nextTD.children().focus();
                    return;
                }

                var selectRow = $('#ship-order-table').find('.selected').not('.d-none').first();
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
                        $('#ship-order-table').parents('.dataTables_scrollBody').scrollLeft(0);
                    }
                    return;
                }
                break;
            case 38: // up
                if ($('#ship-order-table').find('.order-edit-tr').length > 0) {
                    var oldTdElme = $('#ship-order-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                    autoSaveOrderData();
                } else if ($('#ship-order-table').find('.direct-edit').length > 0) {
                    if ($('#ship-order-table').find(':focus').prop("tagName").toUpperCase() == 'TD')
                        var oldTdElme = $('#ship-order-table').find(':focus');
                    else
                        var oldTdElme = $('#ship-order-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index()
                } else {
                    var oldTdElme = $('#ship-order-table').find('tr.selected td:focus');
                    var oldTdFocusIndex = oldTdElme.index();
                }

                var index = $('#ship-order-table').find('tr.selected').index();
                if (index >= 1) {
                    $('#ship-order-table').find('tr.selected').removeClass('selected tr-orange');
                    $('#ship-order-table').find('tr:eq(' + (index) + ')').addClass('selected tr-orange').focus();

                    if (oldTdFocusIndex != -1) {
                        var nextTdElem = $('#ship-order-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')');
                        nextTdElem.focus();
                        updateTdElem(oldTdElme, nextTdElem);
                    } else {
                        $('#ship-order-table').find('tr.selected td').first().focus();
                    }

                    var oneStepHeight = $('#ship-order-table tbody').find('tr:eq(0)').height();
                    var trPos = oneStepHeight * ($('#ship-order-table').find('tr.selected').index() + 1);
                    var mainPoint = trPos - oneStepHeight * 4;
                    $('#ship-order-table').parents('.dataTables_scrollBody').scrollTop(mainPoint);

                    updatedByChangedShipOrderTable();
                    e.preventDefault();
                }
                break;
            case 39: // right
                if ($('#ship-order-table').find('.indi-edit').length > 0)
                    return;
                e.preventDefault();
                var trElem = $('#ship-order-table').find('tr.order-edit-tr');
                if (trElem.length > 0) {
                    var tdElemIndex = trElem.find(':focus').parent('td').index();
                    var nextIndex = indexList.indexOf(tdElemIndex) + 1;
                    if (nextIndex >= indexList.length)
                        return;
                    else
                        var nextTdIndex = indexList[nextIndex];

                    var nextTD = trElem.find('td:eq(' + nextTdIndex + ')');
                    if (nextTD.children().data('datepicker')) {
                        setTimeout(() => {
                            nextTD.children().datepicker('show');
                        }, 100);
                    }
                    nextTD.children().focus();
                    return;
                }

                var selectRow = $('#ship-order-table').find('.selected').not('.d-none').first();
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
                            $('#ship-order-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                        }
                    } else {
                        if (selectRow.find(':focus').is(':last-child')) {
                            selectRow.find('td:first-child').focus();
                            return;
                        }
                        selectRow.find(':focus').next().focus();
                        if (selectRow.find(':focus').is(':last-child')) {
                            $('#ship-order-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                        }
                    }
                }
                break;
            case 40: // down
                if ($('#ship-order-table').find('.order-edit-tr').length > 0) {
                    var oldTdElme = $('#ship-order-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                    autoSaveOrderData();
                } else if ($('#ship-order-table').find('.direct-edit').length > 0) {
                    if ($('#ship-order-table').find(':focus').prop("tagName").toUpperCase() == 'TD')
                        var oldTdElme = $('#ship-order-table').find(':focus');
                    else
                        var oldTdElme = $('#ship-order-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index()
                } else {
                    var oldTdElme = $('#ship-order-table').find('tr.selected td:focus');
                    var oldTdFocusIndex = oldTdElme.index();
                }

                var index = $('#ship-order-table').find('tr.selected').index();
                if (index <= $('#ship-order-table tbody').find('tr').length - 2) {
                    var curScrollPos = $('#ship-order-table').parents('.dataTables_scrollBody').scrollTop();
                    var oneStepHeight = $('#ship-order-table tbody').find('tr:eq(0)').height();
                    $('#ship-order-table').find('tr.selected').removeClass('selected tr-orange');
                    $('#ship-order-table').find('tr:eq(' + (index + 2) + ')').addClass('selected tr-orange').focus();
                    if (oldTdFocusIndex != -1) {
                        var nextTdElem = $('#ship-order-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')');
                        nextTdElem.focus();
                        updateTdElem(oldTdElme, nextTdElem);
                    } else {
                        $('#ship-order-table').find('tr.selected td').first().focus();
                    }

                    var trPos = oneStepHeight * $('#ship-order-table').find('tr.selected').index();
                    var diff = trPos - curScrollPos;
                    if (diff < oneStepHeight * 5)
                        $('#ship-order-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos);
                    else if (diff > oneStepHeight * 6) {
                        $('#ship-order-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + diff);
                    } else
                        $('#ship-order-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + oneStepHeight);
                    updatedByChangedShipOrderTable();
                    e.preventDefault();
                }
                break;
            case 35: // end
                if ($('#ship-order-table').find('.indi-edit').length > 0)
                return;
                e.preventDefault();
                
                var selectRow = $('#ship-order-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    if (selectRow.find(':focus').is(':first-child')) {
                        selectRow.find('td:eq(25)').focus();
                        return;
                    }
                    selectRow.find('td:eq(25)').focus();
                    return;
                }
                break;
            case 36: // home
                if ($('#ship-order-table').find('.indi-edit').length > 0)
                return;
                e.preventDefault();
                
                var selectRow = $('#ship-order-table').find('.selected').not('.d-none').first();
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
                    var curTd = $('#ship-order-table').find(':focus');
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
        case 79: //o
            if (e.altKey) $('#search-ship-order-date').focus();
            break;
        case 66: //b
            if (e.altKey) $('#search-order-number').focus();
            break;
        case 72: //h
            if (e.altKey) $('#search-estimated-date').focus();
            break;
        case 68: //d
            if (e.altKey) $('#search-order-date').focus();
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
            if (e.altKey) $('#supplier-register-btn').click();
            break;
        case 51: //3
            if (e.altKey) $('#order-btn').click();
            break;
        case 52: //4
            if (e.altKey) $('#change-status-btn').click();
            break;
        case 53: //5
            if (e.altKey) $('#return-to-order-btn').click();
            break;

        //message key
        case 74: //j
            if (e.altKey) $('textarea.message-box').focus();
            break;
    }
    return;
})
