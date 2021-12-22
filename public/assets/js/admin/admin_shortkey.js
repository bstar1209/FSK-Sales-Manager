$(document).keydown(function (e) {
    // $('.datepicker.datepicker-dropdown.dropdown-menu').remove();
    switch (e.keyCode) {
        case 27: //esc
            curElem = $(':focus');
            if (curElem.hasClass('editing-td')) {
                curElem.attr('tabindex', 1);
                curElem.text(curElem.data('origin'));
            } else if (curElem.hasClass('indi-edit')) {
                tdElem = curElem.parents('td');
                tdElem.parents('tr').removeClass('direct-edit');
                curElem.attr('tabindex', 1);
                tdElem.text(tdElem.data('origin'));
                tdElem.focus();
            }
            break;
        case 32: //space
            if ($(':focus').children('input:checkbox').length > 0) {
                if ($(':focus').children('input:checkbox').prop('checked'))
                    $(':focus').children('input:checkbox').prop('checked', false);
                else
                    $(':focus').children('input:checkbox').prop('checked', true);
                e.preventDefault();
            }
            break
        case 81: //q
            if (e.altKey) $('#header-rfq-link-btn').get(0).click();
            break;
        case 87:
            if (e.altKey) $('#header-estimate-link-btn').get(0).click();
            break;
        case 82:
            if (e.altKey) $('#header-order-receive-link-btn').get(0).click();
            break;
        case 89:
            if (e.altKey) $('#header-order-link-btn').get(0).click();
            break;
        case 85:
            if (e.altKey) $('#header-stock-link-btn').get(0).click();
            break;
        case 65:
            if (e.altKey) $('#header-shipment-link-btn').get(0).click();
            break;
        case 77:
            if (e.altKey) $('#header-manage-link-btn').get(0).click();
            break;
    }
    return;
})
