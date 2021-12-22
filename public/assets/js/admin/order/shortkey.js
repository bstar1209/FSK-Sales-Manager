$('#order-table').keydown(function (e) {
    switch (e.keyCode) {
        case 9: //tab
            e.preventDefault();
            break;
        case 37: // left
            var selectRow = $('#order-table').find('.selected').not('.d-none').first();
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
                    $('#order-table').parents('.dataTables_scrollBody').scrollLeft(0);
                }
                return;
            }
            e.preventDefault();
            break;
        case 38: // up
            var oldTdElme = $('#order-table').find('tr.selected td:focus');
            var oldTdFocusIndex = oldTdElme.index();
            var index = $('#order-table').find('tr.selected').index();
            if (index >= 1) {
                $('#order-table').find('tr.selected').removeClass('selected tr-orange');
                $('#order-table').find('tr:eq(' + (index) + ')').addClass('selected tr-orange').focus();
                if (oldTdFocusIndex != -1) {
                    var nextTdElem = $('#order-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')');
                    nextTdElem.focus();
                    updateTdElem(oldTdElme, nextTdElem);
                } else {
                    $('#order-table').find('tr.selected td').first().focus();
                }

                var oneStepHeight = $('#order-table tbody').find('tr:eq(0)').height();
                var trPos = oneStepHeight * ($('#order-table').find('tr.selected').index() + 1);
                var mainPoint = trPos - oneStepHeight * 4;
                $('#order-table').parents('.dataTables_scrollBody').scrollTop(mainPoint);

                updatedByChangedOrderTable();
                e.preventDefault();
            }
            break;
        case 39: // right
            var selectRow = $('#order-table').find('.selected').not('.d-none').first();
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
                        $('#order-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                    }
                } else {
                    if (selectRow.find(':focus').is(':last-child')) {
                        selectRow.find('td:first-child').focus();
                        return;
                    }
                    selectRow.find(':focus').next().focus();
                    if (selectRow.find(':focus').is(':last-child')) {
                        $('#order-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                    }
                }
            }
            e.preventDefault();
            break;
        case 40: // down
            var oldTdElme = $('#order-table').find('tr.selected td:focus');
            var oldTdFocusIndex = oldTdElme.index();
            var index = $('#order-table').find('tr.selected').index();
            if (index <= $('#order-table tbody').find('tr').length - 2) {
                var curScrollPos = $('#order-table').parents('.dataTables_scrollBody').scrollTop();
                var oneStepHeight = $('#order-table tbody').find('tr:eq(0)').height();
                $('#order-table').find('tr.selected').removeClass('selected tr-orange');
                $('#order-table').find('tr:eq(' + (index + 2) + ')').addClass('selected tr-orange').focus();

                if (oldTdFocusIndex != -1) {
                    var nextTdElem = $('#order-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')');
                    nextTdElem.focus();
                    updateTdElem(oldTdElme, nextTdElem);
                } else {
                    $('#order-table').find('tr.selected td').first().focus();
                }

                var trPos = oneStepHeight * $('#order-table').find('tr.selected').index();
                var diff = trPos - curScrollPos;
                if (diff < oneStepHeight * 5)
                    $('#order-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos);
                else if (diff > oneStepHeight * 6) {
                    $('#order-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + diff);
                } else
                    $('#order-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + oneStepHeight);
                updatedByChangedOrderTable();
                e.preventDefault();
            }
            break;
        case 35: // end
            if ($('#order-table').find('.indi-edit').length > 0)
            return;
            e.preventDefault();
            
            var selectRow = $('#order-table').find('.selected').not('.d-none').first();
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
            if ($('#order-table').find('.indi-edit').length > 0)
            return;
            e.preventDefault();
            
            var selectRow = $('#order-table').find('.selected').not('.d-none').first();
            if (selectRow.length > 0) {
                if (selectRow.find(':focus').is(':first-child')) {
                    selectRow.find('td:eq(1)').focus();
                    return;
                }
                selectRow.find('td:eq(1)').focus();
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
        case 84: //t
            if (e.altKey) $('#search-maker').focus();
            break;
        case 69: //e
            if (e.altKey) $('#search-order-number').focus();
            break;
        case 72: //h
            if (e.altKey) $('#search-estimated-date').focus();
            break;
        case 90: //n
            if (e.altKey) $('#search-model').focus();
            break;
        case 68: //d
            if (e.altKey) $('#search-order-date').focus();
            break;
        case 70: //f
            if (e.altKey) $('#search-quote').focus();
            break;
        case 76: //l
            if (e.altKey) $('#search-status').focus();
            break;

        //clear key
        case 46: //delete
            if (e.altKey) $('#search-area-clear').click();
            break;

        //action key
        case 49: //1
            if (e.altKey) {
                $('#order-table tr.selected td:first').focus();
                $('html').scrollTop($('#order-table').offset().top - 150);
                $selectedRow = $('#order-table tr.selected');
                if ($selectedRow.length) {
                    $('#order-table').parents('.dataTables_scrollBody').scrollTop($selectedRow.offset().top - $('#request-unrfq-table').offset().top - 100);
                    // $('#request-unrfq-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                    $selectedRow.find('td').first().focus()
                }
            }
            break;
        case 51: //2
            if (e.altKey) $('#to-order-btn').click();
            break;
        case 51: //3
            if (e.altKey) $('#customer-update-btn').click();
            break;
        case 53: //5
            if (e.altKey) $('#invoice-btn').click();
            break;
        case 54: //6
            if (e.altKey) $('#change-status-btn').click();
            break;
        case 55: //7
            if (e.altKey) $('#order-detail-change-btn').click();
            break;
        case 56: //8
            if (e.altKey) $('#order-cancel-btn').click();
            break;

        //message key
        case 74: //j
            if (e.altKey) $('textarea.message-box').focus();
            break;
    }
    return;
})
