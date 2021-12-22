var indexList = [
    quoteColumns.indexOf(originalQuoteKeyArr[12]) + 1,
    quoteColumns.indexOf(originalQuoteKeyArr[13]) + 1,
    quoteColumns.indexOf(originalQuoteKeyArr[21]) + 1,
    quoteColumns.indexOf(originalQuoteKeyArr[24]) + 1,
    quoteColumns.indexOf(originalQuoteKeyArr[25])+1,
    quoteColumns.indexOf(originalQuoteKeyArr[26])+1,
    quoteColumns.indexOf(originalQuoteKeyArr[27]) + 1,
    quoteColumns.indexOf(originalQuoteKeyArr[28]) + 1,
];

indexList.sort(function (a, b) {
    return a - b;
});

    $('#quote-table').keydown(function (e) {
    if (!e.altKey) {
        switch (e.keyCode) {
            case 8: //backspace
                var curTd = $('#quote-table').find(':focus');
                if (!indexList.includes(curTd.index()))
                    return;
                var curText = curTd.text();
                curTd.text(curText.slice(0, -1));
                curTd.parents('tr').addClass('direct-edit');
                break;
            case 113: //F2 shortkey
                var curText = $('#quote-table').find(':focus').text();
                var curTd = $('#quote-table').find(':focus');
                curTd.data('origin', curText);
                if (!indexList.includes(curTd.index()) || curTd.index() == 0)
                    return;

                curTd.removeAttr('tabindex');
                curTd.html('<input type="text" class="form-control form-control-sm indi-edit" value="' + curText + '">').removeClass('p-48').addClass('p-0');
                curTd.find('input').focus();
                break;
            case 46: //delete key
                var curTd = $('#quote-table').find(':focus');

                if (!indexList.includes(curTd.index()) || curTd.index() == 0)
                    return;

                $('#quote-table').find(':focus').text('');
                break;
            case 9: //tab
                e.preventDefault();
                if (!e.shiftKey) {
                    if ($('#quote-table').find('.indi-edit').length > 0) {
                        var editInput = $('#quote-table').find('.indi-edit');
                        var editTd = editInput.parents('td');
                        var editTr = editTd.parents('tr');


                        var nextIndex = editTd.index() + 1;
                        if (nextIndex >= editTr.find('td').length)
                            return;
                        editTr.find('td:eq(' + nextIndex + ')').focus();
                        editTr.addClass('direct-edit');
                        return;
                    }   

                    var trElem = $('#quote-table').find('tr.quote-edit-tr');
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
                    break;
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
                    var nextTD = $('#quote-table .selected').find('td:eq('+ nextIndex +')');
                    nextTD.focus();
                }
                break;
            case 37: //left
                if ($('#quote-table').find('.indi-edit').length > 0)
                    return

                e.preventDefault();
                var trElem = $('#quote-table').find('tr.quote-edit-tr');
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

                var selectRow = $('#quote-table').find('.selected').not('.d-none').first();
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
                        $('#quote-table').parents('.dataTables_scrollBody').scrollLeft(0);
                    }
                    return;
                }
                break;
            case 38: // up
                if ($('#quote-table').find('.indi-edit').length > 0)
                    return;

                if ($('#quote-table').find('.quote-edit-tr').length > 0) {
                    var oldTdElme = $('#quote-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                    autoQuoteSave();
                } else if ($('#quote-table').find('.direct-edit').length > 0) {
                    if ($('#quote-table').find(':focus').prop("tagName").toUpperCase() == 'TD')
                        var oldTdElme = $('#quote-table').find(':focus');
                    else
                        var oldTdElme = $('#quote-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index()
                } else {
                    var oldTdElme = $('#quote-table').find('tr.selected td:focus');
                    var oldTdFocusIndex = oldTdElme.index();
                }

                var index = $('#quote-table').find('tr.selected').index();
                if (index >= 1) {
                    $('#quote-table').find('tr.selected').removeClass('selected tr-orange');
                    $('#quote-table').find('tr:eq(' + (index) + ')').addClass('selected tr-orange').focus();

                    if (oldTdFocusIndex != -1) {
                        var nextTdElem = $('#quote-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')');
                        nextTdElem.focus();
                        updateTdElem(oldTdElme, nextTdElem);
                    } else {
                        $('#quote-table').find('tr.selected td').first().focus();
                    }

                    var oneStepHeight = $('#quote-table tbody').find('tr:eq(0)').height();
                    var trPos = oneStepHeight * ($('#quote-table').find('tr.selected').index() + 1);
                    var mainPoint = trPos - oneStepHeight * 2;
                    $('#quote-table').parents('.dataTables_scrollBody').scrollTop(mainPoint);

                    updatedByChangedQuoteTable();
                    e.preventDefault();
                }
                break;
            case 39: // right
                if ($('#quote-table').find('.indi-edit').length > 0)
                    return;

                e.preventDefault();
                var trElem = $('#quote-table').find('tr.quote-edit-tr');
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

                var selectRow = $('#quote-table').find('.selected').not('.d-none').first();
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
                            $('#quote-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                        }
                    } else {
                        if (selectRow.find(':focus').is(':last-child')) {
                            selectRow.find('td:first-child').focus();
                            return;
                        }
                        selectRow.find(':focus').next().focus();
                        if (selectRow.find(':focus').is(':last-child')) {
                            $('#quote-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                        }
                    }
                    return;
                }
                if (selectRow.length > 0) {
                    if (selectRow.find(':focus').is(':last-child')) {
                        selectRow.find('td:first-child').focus();
                        return;
                    }
                    selectRow.find(':focus').next().focus();
                    if (selectRow.find(':focus').is(':last-child')) {
                        $('#quote-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                    }
                    return;
                }
                break;
            case 40: // down
                if ($('#quote-table').find('.quote-edit-tr').length > 0) {
                    var oldTdElme = $('#quote-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                    autoQuoteSave();
                } else if ($('#quote-table').find('.direct-edit').length > 0) {
                    if ($('#quote-table').find(':focus').prop("tagName").toUpperCase() == 'TD')
                        var oldTdElme = $('#quote-table').find(':focus');
                    else
                        var oldTdElme = $('#quote-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index()
                } else {
                    var oldTdElme = $('#quote-table').find('tr.selected td:focus');
                    var oldTdFocusIndex = oldTdElme.index();
                }

                var index = $('#quote-table').find('tr.selected').index();
                if (index <= $('#quote-table tbody').find('tr').length - 2) {
                    var curScrollPos = $('#quote-table').parents('.dataTables_scrollBody').scrollTop();
                    var oneStepHeight = $('#quote-table tbody').find('tr:eq(0)').height();
                    $('#quote-table').find('tr.selected').removeClass('selected tr-orange');
                    $('#quote-table').find('tr:eq(' + (index + 2) + ')').addClass('selected tr-orange').focus();

                    if (oldTdFocusIndex != -1) {
                        var nextTdElem = $('#quote-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')');
                        nextTdElem.focus();
                        updateTdElem(oldTdElme, nextTdElem);
                    } else {
                        $('#quote-table').find('tr.selected td').first().focus();
                    }

                    var trPos = oneStepHeight * $('#quote-table').find('tr.selected').index();
                    var diff = trPos - curScrollPos;
                    if (diff < oneStepHeight * 3)
                        $('#quote-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos);
                    else if (diff > oneStepHeight * 4) {
                        $('#quote-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + diff);
                    } else
                        $('#quote-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + oneStepHeight);

                    updatedByChangedQuoteTable();
                    e.preventDefault();
                }
                break;
            case 35: // end
                if ($('#quote-table').find('.indi-edit').length > 0)
                return;
                e.preventDefault();
                
                var selectRow = $('#quote-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    if (selectRow.find(':focus').is(':first-child')) {
                        selectRow.find('td:eq(30)').focus();
                        return;
                    }
                    selectRow.find('td:eq(30)').focus();
                    return;
                }
                break;
            case 36: // home
                if ($('#quote-table').find('.indi-edit').length > 0)
                return;
                e.preventDefault();
                
                var selectRow = $('#quote-table').find('.selected').not('.d-none').first();
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
                if ((47 < e.keyCode && e.keyCode < 58) || (64 < e.keyCode && e.keyCode < 91) || (95 < e.keyCode && e.keyCode < 106) || e.keyCode == 110 || e.keyCode == 130 || e.keyCode == 186 || e.keyCode == 187 || e.keyCode == 188 ||  e.keyCode == 189 || e.keyCode == 190 ||e.keyCode == 191 ||   e.keyCode == 192 ||  e.keyCode == 219 || e.keyCode == 220 ||  e.keyCode == 221 || e.keyCode == 222) {
                    var curTd = $('#quote-table').find(':focus');
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

$('#history-table').keydown(function (e) {
    switch (e.keyCode) {
        case 37: // left
            var selectRow = $('#history-table').find('.selected').not('.d-none').first();
            if (selectRow.length > 0) {
                if (selectRow.find(':focus').is(':first-child')) {
                    selectRow.find('td:last-child').focus();
                    return;
                }
                selectRow.find(':focus').prev().focus();
                if (selectRow.find(':focus').is(':first-child')) {
                    $('#history-table').parents('.dataTables_scrollBody').scrollLeft(0);
                }
                return;
            }
            break;
        case 38: // up
            var oldTdFocusIndex = $('#history-table').find('tr.selected td:focus').index();
            var index = $('#history-table').find('tr.selected').index();
            if (index >= 1) {
                var curScrollPos = $('#history-table').parents('.dataTables_scrollBody').scrollTop();
                var oneStepHeight = $('#history-table tbody').find('tr:eq(0)').height();
                $('#history-table').find('tr.selected').removeClass('selected tr-orange');
                $('#history-table').find('tr:eq(' + (index) + ')').addClass('selected tr-orange').focus();

                if (oldTdFocusIndex != -1)
                    $('#history-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')').focus();

                var trPos = oneStepHeight * $('#history-table').find('tr.selected').index();
                var diff = trPos - curScrollPos;
                if (diff < oneStepHeight * 2)
                    $('#history-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos - diff);
                else if (diff > oneStepHeight * 3) {
                    $('#history-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos);
                } else
                    $('#history-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos - oneStepHeight);

                e.preventDefault();
            }
            break;
        case 39: // right
            var selectRow = $('#history-table').find('.selected').not('.d-none').first();
            if (selectRow.length > 0) {
                if (selectRow.find(':focus').is(':last-child')) {
                    selectRow.find('td:first-child').focus();
                    return;
                }
                selectRow.find(':focus').next().focus();
                if (selectRow.find(':focus').is(':last-child')) {
                    $('#history-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                }
                return;
            }

            break;
        case 40: // down
            var oldTdFocusIndex = $('#history-table').find('tr.selected td:focus').index();
            var index = $('#history-table').find('tr.selected').index();
            if (index <= $('#history-table tbody').find('tr').length - 2) {
                var curScrollPos = $('#history-table').parents('.dataTables_scrollBody').scrollTop();
                var oneStepHeight = $('#history-table tbody').find('tr:eq(0)').height();
                $('#history-table').find('tr.selected').removeClass('selected tr-orange');
                $('#history-table').find('tr:eq(' + (index + 2) + ')').addClass('selected tr-orange').focus();
                if (oldTdFocusIndex != -1)
                    $('#history-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')').focus();

                var trPos = oneStepHeight * $('#history-table').find('tr.selected').index() - 1;
                var diff = trPos - curScrollPos;
                if (diff < oneStepHeight * 2)
                    $('#history-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos);
                else if (diff > oneStepHeight * 3) {
                    $('#history-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + diff);
                } else
                    $('#history-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + oneStepHeight);
                e.preventDefault();
            }
            break;
        case 36: // home
            if ($('#history-table').find('.indi-edit').length > 0)
            return;
            e.preventDefault();
            
            var selectRow = $('#history-table').find('.selected').not('.d-none').first();
            if (selectRow.length > 0) {
                if (selectRow.find(':focus').is(':first-child')) {
                    selectRow.find('td:eq(0)').focus();
                    return;
                }
                selectRow.find('td:eq(0)').focus();
                return;
            }
            break;
        case 35: // end
            if ($('#history-table').find('.indi-edit').length > 0)
            return;
            e.preventDefault();
            
            var selectRow = $('#history-table').find('.selected').not('.d-none').first();
            if (selectRow.length > 0) {
                if (selectRow.find(':focus').is(':first-child')) {
                    selectRow.find('td:eq(11)').focus();
                    return;
                }
                selectRow.find('td:eq(11)').focus();
                return;
            }
            break;
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
            if (e.altKey) $('#search-supplier').focus();
            break;
        case 90: //z
            if (e.altKey) $('#search-model').focus();
            break;
        case 72: //h
            if (e.altKey) $('#search-estimate').focus();
            break;
        case 80: //n
            if (e.altKey) $('#search-reception-date').focus();
            break;
        case 76: //l
            if (e.altKey) $('#search-status').focus();
            break;
        case 78: //l
            if (e.altKey) $('#search-reception').focus();
            break;
        case 70: //f
            if (e.altKey) $('#search-quote').focus();
            break;
        case 73: //i
            if (e.altKey) $('#search-customer-id').focus();
            break;

        //clear key
        case 46: //delete
            if (e.altKey) $('#search-area-clear').click();
            break;
        //action key
        case 51: //3
            if (e.altKey) $('#re-investigation-btn').click();
            break;
        case 71: //delete
            if (e.altKey) $('#send-to-customer-btn').click();
            break;
        case 52: //4
            if (e.altKey) $('#change-quote-status-btn').click();
            break;
        case 53: //5
            if (e.altKey) $('#duplicated-quote-btn').click();
            break;
        case 54: //6
            if (e.altKey) $('#sold-out-btn').click();
            break;
        case 55: //7
            if (e.altKey) $('#quotation-issue-btn').click();
            break;
        case 56: //8
            if (e.altKey) $('#customer-update-btn').click();
            break;
        case 57: //9
            if (e.altKey) $('#to-order-btn').click();
            break;

        //message key
        case 74: //j
            if (e.altKey) $('textarea.message-box').focus();
            break;

        //model key
        case 48: //0
            if (e.altKey) $('#history-table_filter').focus();
            break;

        //table key
        case 49: //1
            if (e.altKey) {
                $('#quote-table tr.selected td:first').focus();
                $('html').scrollTop($('#quote-table').offset().top - 50);
                $selectedRow = $('#quote-table tr.selected');
                if ($selectedRow.length) {
                    $('#quote-table').parents('.dataTables_scrollBody').scrollTop($selectedRow.offset().top - $('#quote-table').offset().top - 100);
                    $('#quote-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                }
            }
            break;
        case 50: //2
            if (e.altKey) {
                $('#history-table tr.selected td:first').focus();
                $('html').scrollTop($('#history-table').offset().top - 150);
                $selectedRow = $('#history-table tr.selected');
                if ($selectedRow.length) {
                    $('#history-table').parents('.dataTables_scrollBody').scrollTop($selectedRow.offset().top - $('#history-table').offset().top - 100);
                }
            }
            break;
    }
    return;
})

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