
var indexRfqList = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
var removeArr = [
    rfqColumns.indexOf(originalRfqKeyArr[0]),
    rfqColumns.indexOf(originalRfqKeyArr[1]),
    rfqColumns.indexOf(originalRfqKeyArr[2]),
    rfqColumns.indexOf(originalRfqKeyArr[4]),
    // rfqColumns.indexOf(originalRfqKeyArr[14]), //datepicker field
    // rfqColumns.indexOf(originalRfqKeyArr[15]),
];
indexRfqList = indexRfqList.filter(function (item) {
    if (!removeArr.includes(item))
        return item;
});

var originalKeyArr = Object.keys(columnsQuoteData);

$('#request-unrfq-table').keydown(function (e) {
    if ($('#request-unrfq-table').find(".bootstrap-autocomplete.dropdown-menu.show").length != 0) {
        return true;
    }
    if (!e.altKey) {
        switch (e.keyCode) {
            case 8: //backspace
                var curTd = $('#request-unrfq-table').find(':focus');
                if (!indexRfqList.includes(curTd.index()))
                    return;
                var curText = curTd.text();
                curTd.text(curText.slice(0, -1));
                curTd.parents('tr').addClass('direct-edit');
                break;
            case 46: //delete key
                var curTd = $('#request-unrfq-table').find(':focus');
                if (!indexRfqList.includes(curTd.index()))
                    return;

                $('#request-unrfq-table').find(':focus').text('');
                break;
            case 9: //tab
                if (!e.shiftKey) {
                    e.preventDefault();
                    if ($('#request-unrfq-table').find('.indi-edit').length > 0) {
                        var editInput = $('#request-unrfq-table').find('.indi-edit');
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

                    var trElem = $('#request-unrfq-table').find('.add-part-new-rfq');
                    var tdElemIndex = trElem.find(':focus').parents('th').index();
                    var nextIndex = indexRfqList.indexOf(tdElemIndex) + 1;
                    if (nextIndex >= indexRfqList.length)
                        return;
                    else
                        var nextTdIndex = indexRfqList[nextIndex];

                    var nextTD = trElem.find('th:eq(' + nextTdIndex + ')');
                    if (nextTD.children().data('datepicker')) {
                        setTimeout(() => {
                            nextTD.children().datepicker('show');
                        }, 100);
                        nextTD.children().focus();
                    } else if (nextTD.children().hasClass('select2') && nextTD.children().data('select2')) {
                        nextTD.find('select.select2-hidden-accessible').select2('open');
                    } else {
                        nextTD.children().focus();
                    }
                } else {
                    if ($('#request-unrfq-table').find('.indi-edit').length > 0)
                        return;

                    e.preventDefault();
                    var trElem = $('#request-unrfq-table').find('.add-part-new-rfq').first();
                    if (trElem.length > 0) {
                        var tdElemIndex = trElem.find(':focus').parents('th').index();
                        var nextIndex = indexRfqList.indexOf(tdElemIndex) - 1;
                        if (nextIndex < 0)
                            return;
                        else
                            var nextTdIndex = indexRfqList[nextIndex];
                        var nextTD = trElem.find('th:eq(' + nextTdIndex + ')');
                        if (nextTD.children().data('datepicker')) {
                            setTimeout(() => {
                                nextTD.children().datepicker('show');
                            }, 100);
                            nextTD.children().focus();
                        } else if (nextTD.children().hasClass('select2') && nextTD.children().data('select2')) {
                            nextTD.find('select.select2-hidden-accessible').select2('open');
                        } else {
                            nextTD.children().focus();
                        }
                        return;
                    }

                    var selectRow = $('#request-unrfq-table').find('.selected').not('.d-none').first();
                    if (selectRow.length > 0) {
                        if (selectRow.find(':focus').is(':first-child')) {
                            selectRow.find('td:last-child').focus();
                            return;
                        }
                        selectRow.find(':focus').prev().focus();
                        if (selectRow.find(':focus').is(':first-child')) {
                            $('#request-unrfq-table').parents('.dataTables_scrollBody').scrollLeft(0);
                        }
                        return;
                    }
                }
                break;
            case 37: // left
                if ($('#request-unrfq-table').find('.indi-edit').length > 0)
                    return;

                e.preventDefault();
                var trElem = $('#request-unrfq-table').find('.add-part-new-rfq').first();
                if (trElem.length > 0) {
                    var tdElemIndex = trElem.find(':focus').parents('th').index();
                    var nextIndex = indexRfqList.indexOf(tdElemIndex) - 1;
                    if (nextIndex < 0)
                        return;
                    else
                        var nextTdIndex = indexRfqList[nextIndex];
                    var nextTD = trElem.find('th:eq(' + nextTdIndex + ')');
                    if (nextTD.children().data('datepicker')) {
                        setTimeout(() => {
                            nextTD.children().datepicker('show');
                        }, 100);
                        nextTD.children().focus();
                    } else if (nextTD.children().hasClass('select2') && nextTD.children().data('select2')) {
                        nextTD.find('select.select2-hidden-accessible').select2('open');
                    } else {
                        nextTD.children().focus();
                    }
                    return;
                }

                var selectRow = $('#request-unrfq-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    if (selectRow.find(':focus').is(':first-child')) {
                        selectRow.find('td:last-child').focus();
                        return;
                    }
                    selectRow.find(':focus').prev().focus();
                    if (selectRow.find(':focus').is(':first-child')) {
                        $('#request-unrfq-table').parents('.dataTables_scrollBody').scrollLeft(0);
                    }
                    return;
                }
                break;
            case 39: // right
                if ($('#request-unrfq-table').find('.indi-edit').length > 0)
                    return;

                e.preventDefault();
                var trElem = $('#request-unrfq-table').find('.add-part-new-rfq').first();
                if (trElem.length > 0) {
                    var tdElemIndex = trElem.find(':focus').parents('th').index();
                    var nextIndex = indexRfqList.indexOf(tdElemIndex) + 1;
                    if (nextIndex >= indexRfqList.length)
                        return;
                    else
                        var nextTdIndex = indexRfqList[nextIndex];

                    var nextTD = trElem.find('th:eq(' + nextTdIndex + ')');
                    if (nextTD.children().data('datepicker')) {
                        setTimeout(() => {
                            nextTD.children().datepicker('show');
                        }, 100);
                        nextTD.children().focus();
                    } else if (nextTD.children().hasClass('select2') && nextTD.children().data('select2')) {
                        nextTD.find('select.select2-hidden-accessible').select2('open');
                    } else {
                        nextTD.children().focus();
                    }
                    return;
                }

                var selectRow = $('#request-unrfq-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    if (selectRow.find(':focus').is(':last-child')) {
                        selectRow.find('td:first-child').focus();
                        return;
                    }
                    selectRow.find(':focus').next().focus();
                    if (selectRow.find(':focus').is(':last-child')) {
                        $('#request-unrfq-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                    }
                    return;
                }
                break;
            case 38: // up
                if ($('#request-unrfq-table').find('.indi-edit').length > 0)
                    return;

                if ($('#request-unrfq-table').find('.add-part-new-rfq').length != 0) {
                    e.preventDefault();
                    $("#confirm-modal").modal('show');
                    if ($('.add-part-new-rfq').data('id'))
                        $("#confirm-btn").data("type", "updateNewRfq");
                    else
                        $("#confirm-btn").data("type", "addNewRfq");
                    $('#confirm-cancel').data("type", "new-unrfq-cancel");
                    return;
                }

                var oldTdFocusIndex = $('#request-unrfq-table').find('tr.selected td:focus').index();
                var index = $('#request-unrfq-table').find('tr.selected').index();
                if (index >= 1) {
                    $('#request-unrfq-table').find('tr.selected').removeClass('selected tr-orange');
                    $('#request-unrfq-table').find('tr:eq(' + (index) + ')').addClass('selected tr-orange').focus();

                    if (oldTdFocusIndex != -1)
                        $('#request-unrfq-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')').focus();

                    var oneStepHeight = $('#request-unrfq-table tbody').find('tr:eq(0)').height();
                    var trPos = oneStepHeight * ($('#request-unrfq-table').find('tr.selected').index() + 1);
                    var mainPoint = trPos - oneStepHeight * 2;
                    $('#request-unrfq-table').parents('.dataTables_scrollBody').scrollTop(mainPoint);

                    updateByUnrfqTable();
                    e.preventDefault();
                }
                break;
            case 40: // down
                if ($('#request-unrfq-table').find('.add-part-new-rfq').length != 0) {
                    e.preventDefault();
                    $("#confirm-modal").modal('show');
                    if ($('.add-part-new-rfq').data('id'))
                        $("#confirm-btn").data("type", "updateNewRfq");
                    else
                        $("#confirm-btn").data("type", "addNewRfq");
                    $('#confirm-cancel').data("type", "new-unrfq-cancel");
                    return;
                }
                var oldTdFocusIndex = $('#request-unrfq-table').find('tr.selected td:focus').index();
                var index = $('#request-unrfq-table').find('tr.selected').index();
                if (index <= $('#request-unrfq-table tbody').find('tr').length - 2) {
                    var curScrollPos = $('#request-unrfq-table').parents('.dataTables_scrollBody').scrollTop();
                    var oneStepHeight = $('#request-unrfq-table tbody').find('tr:eq(0)').height();
                    $('#request-unrfq-table').find('tr.selected').removeClass('selected tr-orange');
                    $('#request-unrfq-table').find('tr:eq(' + (index + 2) + ')').addClass('selected tr-orange').focus();
                    if (oldTdFocusIndex != -1)
                        $('#request-unrfq-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')').focus();

                    var trPos = oneStepHeight * $('#request-unrfq-table').find('tr.selected').index();
                    var diff = trPos - curScrollPos;
                    if (diff < oneStepHeight * 3)
                        $('#request-unrfq-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos);
                    else if (diff > oneStepHeight * 4) {
                        $('#request-unrfq-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + diff);
                    } else
                        $('#request-unrfq-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + oneStepHeight);

                    updateByUnrfqTable();
                    e.preventDefault();
                }
                break;
            case 36: // home
                if ($('#request-unrfq-table').find('.indi-edit').length > 0)
                return;

                e.preventDefault();
                var selectRow = $('#request-unrfq-table').find('.selected').not('.d-none').first();
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
                if ($('#request-unrfq-table').find('.indi-edit').length > 0)
                return;

                e.preventDefault();
                var selectRow = $('#request-unrfq-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    if (selectRow.find(':focus').is(':first-child')) {
                        selectRow.find('td:eq(15)').focus();
                        return;
                    }
                    selectRow.find('td:eq(15)').focus();
                    return;
                }
                break;
        }
    }
})

var indexList = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
indexList.splice(rfqQuoteColumns.indexOf(originalQuoteKeyArr[0]), 1);
// indexList.splice(rfqQuoteColumns.indexOf(originalQuoteKeyArr[12]), 1);
// indexList.splice(rfqQuoteColumns.indexOf(originalQuoteKeyArr[14]), 1); //datepicker field

$('#quote-from-supplier-table').keydown(function (e) {
    if (!e.altKey) {
        switch (e.keyCode) {
            case 8: //backspace
                var curTd = $('#quote-from-supplier-table').find(':focus');
                var curText = curTd.text();
                curTd.text(curText.slice(0, -1));
                curTd.parents('tr').addClass('direct-edit');
                break;
            case 113://F2 shortkey
                var curText = $('#quote-from-supplier-table').find(':focus').text();
                var curTd = $('#quote-from-supplier-table').find(':focus');
                curTd.data('origin', curText);

                if (!indexList.includes(curTd.index()))
                    return;

                curTd.removeAttr('tabindex');
                curTd.html('<input type="text" class="form-control form-control-sm indi-edit" value="' + curText + '">').removeClass('p-48').addClass('p-0');

                if (rfqQuoteColumns.indexOf(originalQuoteKeyArr[2]) == curTd.index()) {
                    var supplierOptionList = '';
                    $.each(supplierList, function (index, item) {
                        supplierOptionList += '<option class="' + item.user_info.company_name_kana + '">' + item.user_info.company_name + '</option>'
                    })
                    curTd.html('<select class="form-control form-control-sm indi-edit supplier-select select2">' + supplierOptionList + '</select>');
                    $('select.supplier-select').val(curText);
                    var select2 = $('select.supplier-select').select2(selectOptions);
                    select2.select2('open');
                    curTd.parents('tr').addClass('direct-edit');
                } else if (rfqQuoteColumns.indexOf(originalQuoteKeyArr[3]) == curTd.index()) {
                    var makerOptions = '';
                    $.each(makerList, function (index, item) {
                        makerOptions += '<option class="' + item.id + '">' + item.maker_name + '</option>'
                    })
                    curTd.html('<select class="form-control form-control-sm indi-edit maker select2">' + makerOptions + '</select>');
                    $('select.maker').val(curText);
                    var select2 = $('select.maker').select2(selectOptions);
                    select2.select2('open');
                    curTd.parents('tr').addClass('direct-edit')
                } else if (rfqQuoteColumns.indexOf(originalQuoteKeyArr[7]) == curTd.index()) {
                    curTd.html('<select class="form-control form-control-sm select-rate indi-edit">' + rateOptionHtml + '</select>');
                    $(".select-rate").val(curText);
                    $(".select-rate").focus();
                } else if (rfqQuoteColumns.indexOf(originalQuoteKeyArr[10]) == curTd.index()) {
                    curTd.html('<select class="form-control form-control-sm select-kbn indi-edit">' + kbnOptionHtml + '</select>');
                    $(".select-kbn").val(curText);
                    $(".select-kbn").focus();
                } else if (rfqQuoteColumns.indexOf(originalQuoteKeyArr[11]) == curTd.index()) {
                    curTd.html('<select class="form-control form-control-sm select-rohs indi-edit">' + rohsOptionHtml + '</select>');
                    $(".select-rohs").val(curText);
                    $(".select-rohs").focus();
                } else if (rfqQuoteColumns.indexOf(originalKeyArr[14]) == curTd.index()) {
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
                if (!curTd.find('input').hasClass('f-datepicker'))
                    curTd.find('input').focus();
                break;
            case 46: //delete key
                var curTd = $('#quote-from-supplier-table').find(':focus');
                if (!indexList.includes(curTd.index()))
                    return;

                $('#quote-from-supplier-table').find(':focus').text('');
                break;
            case 9: //tab
                if (!e.shiftKey) {
                    e.preventDefault();
                    if ($('#quote-from-supplier-table').find('.indi-edit').length > 0) {
                        var editInput = $('#quote-from-supplier-table').find('.indi-edit');
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
                    var trElem = $('#quote-from-supplier-table').find('.edit-quote');
                    var tdElemIndex = trElem.find(':focus').parents('td').index();

                    // in case select2 fields such as supplier or maker is focusing on
                    if (parseInt(tdElemIndex) == -1 && trElem.find(':focus').hasClass('select2-selection')) {
                        if (trElem.find(':focus').parents('select').hasClass('supplier-select'))
                            tdElemIndex = rfqQuoteColumns.indexOf(originalQuoteKeyArr[2]);
                        else
                            tdElemIndex = rfqQuoteColumns.indexOf(originalQuoteKeyArr[3]);
                    }
                    var nextIndex = indexList.indexOf(tdElemIndex) + 1;
                    if (nextIndex >= indexList.length)
                        return;
                    else
                        var nextTdIndex = indexList[nextIndex];

                    var nextTD = trElem.find('td:eq(' + nextTdIndex + ')');
                    if (nextTD.children().hasClass('select2') && nextTD.children().data('select2')) {
                        nextTD.find('select.select2-hidden-accessible').select2('open');
                    } else {
                        nextTD.children().focus();
                    }
                } else if ((e.shiftKey)) {
                    e.preventDefault();
                    curElem = $(':focus');
                    tdElem = curElem.parents('td');
                    tdElemIndex = curElem.parents('td').index();
           
                    // in case select2 fields such as supplier or maker is focusing on
                    if (parseInt(tdElemIndex) == -1 && curElem.hasClass('select2-selection')) {
                        if (curElem.parents('select').hasClass('supplier-select'))
                            tdElemIndex = rfqQuoteColumns.indexOf(originalQuoteKeyArr[2]);
                        else
                            tdElemIndex = rfqQuoteColumns.indexOf(originalQuoteKeyArr[3]);
                    }

                    tdElem.text(tdElem.data('origin'));
                    var nextIndex = indexList.indexOf(tdElemIndex) + 1;
                    if (nextIndex >= indexList.length)
                        return;
                    else
                        var nextTdIndex = indexList[nextIndex];

                    var nextTD = $('#quote-from-supplier-table .selected').find('td:eq(' + nextTdIndex + ')');
                    if (nextTD.children().hasClass('select2') && nextTD.children().data('select2')) {
                        nextTD.find('select.select2-hidden-accessible').select2('open');
                    } else {
                        nextTD.focus();
                    }
                }
                break;
            case 37: // left
                if ($('#quote-from-supplier-table').find('.indi-edit').length > 0)
                    return;

                e.preventDefault();

                var trElem = $('#quote-from-supplier-table').find('.edit-quote').first();
                if (trElem.length > 0) {
                    var tdElemIndex = trElem.find(':focus').parents('td').index();

                    var nextIndex = indexList.indexOf(tdElemIndex) - 1;
                    if (nextIndex < 0)
                        return;
                    else
                        var nextTdIndex = indexList[nextIndex];
                    var nextTD = trElem.find('td:eq(' + nextTdIndex + ')');
                    if (nextTD.children().hasClass('select2') && nextTD.children().data('select2')) {
                        nextTD.find('select.select2-hidden-accessible').select2('open');
                    } else {
                        nextTD.children().focus();
                    }
                    return;
                }

                var selectRow = $('#quote-from-supplier-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    var curOne = selectRow.find(':focus');
                    if (curOne.hasClass('td-decoration')) {
                        curOne.removeClass('td-decoration');
                        if (curOne.is(':first-child')) {
                            selectRow.find('td:last-child').focus().addClass('td-decoration');
                            $('#quote-from-supplier-table').parents('.dataTables_scrollBody').scrollLeft(5000);
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
                        $('#quote-from-supplier-table').parents('.dataTables_scrollBody').scrollLeft(0);
                    }
                    return;
                }
                break;
            case 39: // right
                if ($('#quote-from-supplier-table').find('.indi-edit').length > 0)
                    return;

                e.preventDefault();
                var trElem = $('#quote-from-supplier-table').find('.edit-quote').first();
                if (trElem.length > 0) {
                    var tdElemIndex = trElem.find(':focus').parents('td').index();

                    var nextIndex = indexList.indexOf(tdElemIndex) + 1;
                    if (nextIndex >= indexList.length)
                        return;
                    else
                        var nextTdIndex = indexList[nextIndex];
                    var nextTD = trElem.find('td:eq(' + nextTdIndex + ')');
                    if (nextTD.children().hasClass('select2') && nextTD.children().data('select2')) {
                        nextTD.find('select.select2-hidden-accessible').select2('open');
                    } else {
                        nextTD.children().focus();
                    }
                }

                var selectRow = $('#quote-from-supplier-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    var curOne = selectRow.find(':focus');
                    if (curOne.hasClass('td-decoration')) {
                        curOne.removeClass('td-decoration');
                        if (selectRow.find(':focus').is(':last-child')) {
                            selectRow.find('td:first-child').addClass('td-decoration').focus();
                        } else {
                            selectRow.find(':focus').next().addClass('td-decoration').focus();
                            if (selectRow.find(':focus').is(':last-child')) {
                                $('#quote-from-supplier-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                            }
                        }
                        return;
                    } else {
                        if (selectRow.find(':focus').is(':last-child')) {
                            selectRow.find('td:first-child').focus();
                            $('#quote-from-supplier-table').parents('.dataTables_scrollBody').scrollLeft();
                        } else {
                            selectRow.find(':focus').next().focus();
                            if (selectRow.find(':focus').is(':last-child')) {
                                $('#quote-from-supplier-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                            }
                        }
                        return;
                    }
                }
                break;
            case 38: // up
                // disable key event when opening select2 plugin
                if ($('.select2-dropdown').length != 0) {
                    return true;
                }

                if ($('#quote-from-supplier-table').find('.edit-quote').length > 0) {
                    var oldTdElme = $('#quote-from-supplier-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                    autoEditedQuote($(e.relatedTarget));
                    flag = false;
                } else if ($('#quote-from-supplier-table').find('.direct-edit').length > 0) {
                    if ($('#quote-from-supplier-table').find(':focus').prop("tagName").toUpperCase() == 'TD')
                        var oldTdElme = $('#quote-from-supplier-table').find(':focus');
                    else
                        var oldTdElme = $('#quote-from-supplier-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                } else {
                    var oldTdElme = $('#quote-from-supplier-table').find('tr.selected td:focus');
                    var oldTdFocusIndex = oldTdElme.index();
                }

                var index = $('#quote-from-supplier-table').find('tr.selected').index();
                if (index >= 1) {
                    $('#quote-from-supplier-table').find('tr.selected').removeClass('selected tr-orange');
                    $('#quote-from-supplier-table').find('tr:eq(' + (index) + ')').addClass('selected tr-orange').focus();

                    if (oldTdFocusIndex != -1) {
                        var nextTdElem = $('#quote-from-supplier-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')');
                        nextTdElem.focus();
                        updateTdElem(oldTdElme, nextTdElem);
                    } else {
                        $('#quote-from-supplier-table').find('tr.selected td').first().focus();
                    }

                    var oneStepHeight = $('#quote-from-supplier-table tbody').find('tr:eq(0)').height();
                    var trPos = oneStepHeight * ($('#quote-from-supplier-table').find('tr.selected').index() + 1);
                    var mainPoint = trPos - oneStepHeight * 2;
                    $('#quote-from-supplier-table').parents('.dataTables_scrollBody').scrollTop(mainPoint);

                    updatedByQuoteTable();
                    e.preventDefault();
                }
                break;
            case 40: // down
                // disable key event when opening select2 plugin
                if ($('.select2-dropdown').length != 0) {
                    return true;
                }

                var flag = true;
                if ($('#quote-from-supplier-table').find('.edit-quote').length > 0) {
                    var oldTdElme = $('#quote-from-supplier-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                    autoEditedQuote($(e.relatedTarget));
                    flag = false;
                } else if ($('#quote-from-supplier-table').find('.direct-edit').length > 0) {
                    if ($('#quote-from-supplier-table').find(':focus').prop("tagName").toUpperCase() == 'TD')
                        var oldTdElme = $('#quote-from-supplier-table').find(':focus');
                    else
                        var oldTdElme = $('#quote-from-supplier-table').find(':focus').parents('td');
                    var oldTdFocusIndex = oldTdElme.index();
                } else {
                    var oldTdElme = $('#quote-from-supplier-table').find('tr.selected td:focus');
                    var oldTdFocusIndex = oldTdElme.index();
                }

                var index = $('#quote-from-supplier-table').find('tr.selected').index();
                if (index <= $('#quote-from-supplier-table tbody').find('tr').length - 2) {
                    var curScrollPos = $('#quote-from-supplier-table').parents('.dataTables_scrollBody').scrollTop();
                    var oneStepHeight = $('#quote-from-supplier-table tbody').find('tr:eq(0)').height();
                    $('#quote-from-supplier-table').find('tr.selected').removeClass('selected tr-orange');

                    if (flag)
                        $('#quote-from-supplier-table').find('tbody tr:eq(' + (index + 1) + ')').addClass('selected tr-orange');
                    else
                        $('#quote-from-supplier-table').find('tbody tr:eq(' + (index + 2) + ')').addClass('selected tr-orange');

                    if (oldTdFocusIndex != -1) {
                        var nextTdElem = $('#quote-from-supplier-table').find('tr.selected td:eq(' + (oldTdFocusIndex) + ')');
                        nextTdElem.focus();
                        updateTdElem(oldTdElme, nextTdElem);
                    } else {
                        $('#quote-from-supplier-table').find('tr.selected td').first().focus();
                    }

                    var trPos = oneStepHeight * $('#quote-from-supplier-table').find('tr.selected').index();
                    var diff = trPos - curScrollPos;
                    if (diff < oneStepHeight * 3)
                        $('#quote-from-supplier-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos);
                    else if (diff > oneStepHeight * 4) {
                        $('#quote-from-supplier-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + diff);
                    } else
                        $('#quote-from-supplier-table').parents('.dataTables_scrollBody').scrollTop(curScrollPos + oneStepHeight);

                    updatedByQuoteTable();
                    e.preventDefault();
                }
                break;
            case 36: // home
                if ($('#quote-from-supplier-table').find('.indi-edit').length > 0)
                return;
                e.preventDefault();
                
                var selectRow = $('#quote-from-supplier-table').find('.selected').not('.d-none').first();
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
                if ($('#quote-from-supplier-table').find('.indi-edit').length > 0)
                return;
                e.preventDefault();
                
                var selectRow = $('#quote-from-supplier-table').find('.selected').not('.d-none').first();
                if (selectRow.length > 0) {
                    if (selectRow.find(':focus').is(':first-child')) {
                        selectRow.find('td:eq(15)').focus();
                        return;
                    }
                    selectRow.find('td:eq(15)').focus();
                    return;
                }
                break;
            default:
                if ((47 < e.keyCode && e.keyCode < 58) || (64 < e.keyCode && e.keyCode < 91) || (95 < e.keyCode && e.keyCode < 106) || e.keyCode == 110 || e.keyCode == 130 || e.keyCode == 186 || e.keyCode == 187 || e.keyCode == 188 || e.keyCode == 189 || e.keyCode == 190 || e.keyCode == 191 || e.keyCode == 192 || e.keyCode == 219 || e.keyCode == 220 || e.keyCode == 221 || e.keyCode == 222) {
                    var curTd = $('#quote-from-supplier-table').find(':focus');
                    if (!indexList.includes(curTd.index()))
                        return;

                    // to display pull down menu on direct mode, supplier or maker
                    if (curTd.index() == rfqQuoteColumns.indexOf(originalKeyArr[2]) || curTd.index() == rfqQuoteColumns.indexOf(originalKeyArr[3])) {
                        var e = $.Event('keydown');
                        e.which = 113;
                        e.keyCode = 113;
                        curTd.trigger(e);
                        return
                    }
                    
                    if (!curTd.hasClass('editing-td')) {
                        curTd.addClass('editing-td');
                        curTd.data('origin', curTd.text());
                        curTd.text('');
                    }

                    var trSelect = curTd.parents('tr');
                    if (!trSelect.hasClass('direct-edit'))
                        trSelect.addClass('direct-edit');

                    // var curText = curTd.text(curTd.data('origin'));
                    var curText = curTd.text();
                    // var curText = curTd.data('origin');
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
        case 35: // end
            if ($('#history-table').find('.indi-edit').length > 0)
            return;
            e.preventDefault();
            
            var selectRow = $('#history-table').find('.selected').not('.d-none').first();
            if (selectRow.length > 0) {
                if (selectRow.find(':focus').is(':first-child')) {
                    selectRow.find('td:eq(14)').focus();
                    return;
                }
                selectRow.find('td:eq(14)').focus();
                return;
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
    }
})

$("#message-from-customer").on('keydown', function(e) {
    if(e.keyCode == 13)
    {
        if(e.altKey)
        {
            var text = $("#message-from-customer").val();
            $("#message-from-customer").val($("#message-from-customer").val()+"\n");
            return true;
        }
        else
            return false;
    }
});

$(document).keydown(function (e) {
    $('.datepicker.datepicker-dropdown.dropdown-menu').remove();
    switch (e.keyCode) {
        //search key
        // case 13: //enter
        //     e.preventDefault();
        //     if (e.altKey) $('#message-from-customer :input').each(  function() {
        //     });
        //     break;
        case 67: //c
            if (e.altKey) $('#search-customer').focus();
            break;
        case 80: //p
            if (e.altKey) $('#search-reception-date').focus();
            break;
        case 90: //z
            if (e.altKey) $('#search-model-number').focus();
            break;
        case 73: //i
            if (e.altKey) $('#search-customer-id').focus();
            break;
        case 78: //n
            if (e.altKey) $('#search-reception-number').focus();
            break;
        case 76: //l
            if (e.altKey) $('#search-status').focus();
            break;

        //clear key
        case 46: //delete
            if (e.altKey) $('#search-area-clear').click();
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
                $('#request-unrfq-table tr.selected td:first').focus();
                $('html').scrollTop($('#request-unrfq-table').offset().top - 50);
                $selectedRow = $('#request-unrfq-table tr.selected');
                if ($selectedRow.length) {
                    $('#request-unrfq-table').parents('.dataTables_scrollBody').scrollTop($selectedRow.offset().top - $('#request-unrfq-table').offset().top - 100);
                    // $('#request-unrfq-table').parents('.dataTables_scrollBody').scrollLeft(5000);
                    $selectedRow.find('td').first().focus()
                }
            }
            break;
        case 50: //2
            if (e.altKey) {
                $('#quote-from-supplier-table tr.selected td:first').focus();
                $('html').scrollTop($('#quote-from-supplier-table').offset().top - 100);
                $selectedRow = $('#quote-from-supplier-table tr.selected');
                $selectedRow.find('td').first().focus();
            }
            break;
        case 51: //3
            if (e.altKey) {
                $('#history-table tr.selected td:first').focus();
                $('html').scrollTop($('#history-table').offset().top - 150);
                $selectedRow = $('#history-table tr.selected');
                if ($selectedRow.length) {
                    $('#history-table').parents('.dataTables_scrollBody').scrollTop($selectedRow.offset().top - $('#history-table').offset().top - 100);
                }
            }
            break;

        //action key
        case 52: //4
            if (e.altKey) $('#update-customer-btn').click();
            break;
        case 53: //5
            if (e.altKey) $('#add-part-rfq-btn').click();
            break;
        case 54: //6
            if (e.altKey) $('#supplier-register-modal').modal('show');
            break;
        case 55: //7
            if (e.altKey) $('#manufacturer-register-modal').modal('show');
            break;
        case 56: //8
            if (e.altKey) $('#update-rfq-btn').click();
            break;
        case 57: //9
            if (e.altKey) $('#update-unRfq-status').click();
            break;
        case 71: //g
            if (e.altKey) $('#email-send-btn').click();
            break;
        case 68: //d
            if (e.altKey) $('#daily-rfq-btn').click();
            break;
        case 75: //k
            if (e.altKey) $('#quote-send-btn').click();
            break;
        case 72: //h
            if (e.altKey) $('#add-new-rfq-btn').click();
            break;
        case 66://b
            if (e.altKey) $('#new-quote-btn').click();
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