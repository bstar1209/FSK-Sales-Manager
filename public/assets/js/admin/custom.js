var commonList = {};
var commonPaymentList = {};
var customerInfoList = [];
var rateOptionHtml = null;

$('.modal').on("hidden.bs.modal", function (e) {
    if ($('.modal:visible').length) {
        $('body').addClass('modal-open');
    }
});

$('#page-header a[href="' + location.href + '"]').addClass('nav-active');

function loadCustomerInfoList() {
    $.ajax({
        url: getCustomerListUrl,
        type: 'post',
        dataType: 'json',
        success: function (data) {
            customerInfoList = data;
            customerInfoList.forEach(function (item, index) {
                if (profileId == item.id)
                    profileInfo = item;
            })
        }
    });
}

function loadMakerList() {
    $.ajax({
        url: getMakerListUrl,
        type: 'post',
        dataType: 'json',
        success: function (data) {
            makerList = data;
        }
    });
}

function loadSupplierInfoList() {
    $.ajax({
        url: getSupplierListUrl,
        type: 'post',
        dataType: 'json',
        success: function (data) {
            supplierList = data;
            supplierList.forEach(function (item, index) {
                if (profileId == item.id)
                    profileInfo = item;
            })
        }
    });
}

function loadCommonList() {
    $.ajax({
        url: getCommonListUrl,
        type: 'post',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                commonList[item.id] = { name: item.common_name, type: item.common_type };
            });
        }
    });
}

function getRatelist() {
    $.ajax({
        url: getRateListUrl,
        type: 'post',
        dataType: 'json',
        success: function (data) {
            rateList = data;
            $.each(rateList, function (index, item) {
                rateOptionHtml += '<option value="' + item.type_money + '">' + item.type_money + '</option>';
            })
        }
    });
}

var selectOptions = {
    "matcher": matchCustom,
    "language": {
        "noResults": function () {
            return "";
        }
    },
    "sorter": function (results) {
        var query = $('.select2-search__field').val().toLowerCase();
        return results.sort(function (a, b) {
            return a.text.toLowerCase().indexOf(query) -
                b.text.toLowerCase().indexOf(query);
        });
    }
}

// customize select2 plugin's match funtion for multiple search feature
function matchCustom(params, data) {
    if ($.trim(params.term) === '') {
        return data;
    }
    if (typeof data.text === 'undefined') {
        return null;
    }
    if (data.text.startsWith(params.term) ||
        ($(data.element).prop('class').startsWith(params.term) && !$(data.element).parents('select').hasClass('maker'))) {
        var modifiedData = $.extend({}, data, true);
        return modifiedData;
    }
    return null;
}


$(document).on('keydown', '.input-check-number', function (e) {
    var useList = '0123456789.';
    if (e.key == 'Backspace')
        return true;

    if (useList.search(e.key) == -1) {
        return false;
    }
})

var listOfPrefectures = [
    { id: '北海道', name: "北海道" },
    { id: '青森', name: "青森" },
    { id: '秋田', name: "秋田" },
    { id: '岩手', name: "岩手" },
    { id: '宮城', name: "宮城" },
    { id: '山形', name: "山形" },
    { id: '福島', name: "福島" },
    { id: '茨城', name: "茨城" },
    { id: '栃木', name: "栃木" },
    { id: '群馬', name: "群馬" },
    { id: '埼玉', name: "埼玉" },
    { id: '千葉', name: "千葉" },
    { id: '神奈川', name: "神奈川" },
    { id: '山梨', name: "山梨" },
    { id: '東京', name: "東京" },
    { id: '新潟', name: "新潟" },
    { id: '長野', name: "長野" },
    { id: '富山', name: "富山" },
    { id: '石河', name: "石河" },
    { id: '福井', name: "福井" },
    { id: '静岡', name: "静岡" },
    { id: '愛知', name: "愛知" },
    { id: '三重', name: "三重" },
    { id: '岐阜', name: "岐阜" },
    { id: '大阪', name: "大阪" },
    { id: '京都', name: "京都" },
    { id: '滋賀', name: "滋賀" },
    { id: '奈良', name: "奈良" },
    { id: '和歌山', name: "和歌山" },
    { id: '兵庫', name: "兵庫" },
    { id: '岡山', name: "岡山" },
    { id: '広島', name: "広島" },
    { id: '山口', name: "山口" },
    { id: '鳥取', name: "鳥取" },
    { id: '島根', name: "島根" },
    { id: '香川', name: "香川" },
    { id: '徳島', name: "徳島" },
    { id: '愛媛', name: "愛媛" },
    { id: '高知', name: "高知" },
    { id: '福岡', name: "福岡" },
    { id: '佐賀', name: "佐賀" },
    { id: '長崎', name: "長崎" },
    { id: '熊本', name: "熊本" },
    { id: '大分', name: "大分" },
    { id: '宮崎', name: "宮崎" },
    { id: '鹿児島', name: "鹿児島" },
    { id: '沖縄', name: "沖縄" }
];

$(document).on('shown.bs.modal', '.modal', function () {
    if ($(this).prop('id') == 'email-send-modal') {
        var ckObj = CKEDITOR.instances['email_content'];
        ckObj.focus();
        return;
    }

    $('.cke_top.cke_reset_all').remove();
    var inputLength = $(this).find('input').length;
    if (inputLength > 0) {
        $(this).find('input:eq(0)').focus();
        return true;
    }

    var btnLength = $(this).find('button').length;
    if (btnLength > 0) {
        $(this).find('button:eq(0)').focus();
    }
    return true;
})

$(document).on('focusin', '.indi-edit', function () {
    $(this).parents('tr').addClass('direct-edit');
})

$(document).on('blur', '.indi-edit', function (e) {
    if ($(e.relatedTarget).prop('id') == "quote-from-supplier-table" || $(e.relatedTarget).prop('class') == undefined)
        return;
    var elem = $(this).val();
    $(this).parents('td').prop('tabindex', 10).empty().text(elem);
})

$(document).on('blur', '.editing-td', function () {
    $(this).removeClass('editing-td');
    return true;
})

function updateTdElem(curElem, nextElem) {
    curElem.removeClass('td-decoration');
    nextElem.addClass('td-decoration');
}

$(document).on('blur', '.td-decoration', function () {
    $(this).removeClass('td-decoration');
})

$(document).on('click', '#quote-from-supplier-table td, #quote-table td, #order-table td, #ship-order-table td, #stock-table td, #shipment-table td', function () {
    $(this).parents('table').find('.td-decoration').removeClass('td-decoration');
    $(this).addClass('td-decoration');
});

$(document).on('select2:open', () => {
    document.querySelector('.select2-search__field').focus();
});

$(document).on('select2:select', '.select2', function () {
    trElem = $(this).parents('tr');
    if (trElem.hasClass('direct-edit')) {
        var tdElem = $(this).parents('td');
        var value = $(this).val();
        if ($(this).data('select2')) {
            $(this).select2('destroy');
        }
        tdElem.text(value).prop('tabindex', 1);
        tdElem.focus();
        return true;
    }
})

$(document).on('select2:closing', '.select2', function (e) {
    if ($(this).hasClass('indi-edit')) {
        var tdElem = $(this).parents('td');
        var value = $(this).val();
        if ($(this).data('select2')) {
            $(this).select2('destroy');
        }
        tdElem.text(value).prop('tabindex', 1);
        tdElem.focus();
        return true;
    }
})

// get address from zip code
$(document).on('focusout', '.billing-address-zip-code', function() {
    var elem = $(this);
    var postCode = elem.val();
    var elemParents = elem.parents(".input-group");
    elemParents.find('.invalid-feedback').remove().end().find('input');

    if (!postCode)
        return
    $.ajax({
        url: getAddressFromPostCodeUrl,
        type: 'POST',
        dataType: 'json',
        data: {
            zip: postCode 
        },
        success: function (data) {
            if (!data) {
                elem.parents('.input-group').append('<div class="invalid-feedback" style="display: block !important; margin-left: 100px">郵便番号から住所を取得できません。</div>').find('input').addClass('is-invalid');
            } else {
                var addressTemplate = elem.parents('.col-6');
                addressTemplate.find('.billing-address-zip-code').removeClass('is-invalid');
                addressTemplate.find('.billing-address-prefecture').removeClass('is-invalid');
                addressTemplate.find('.billing-address-municipality').removeClass('is-invalid');
                addressTemplate.find('.billing-address-address').removeClass('is-invalid');
                addressTemplate.find('.billing-address-zip-code').parent().find('.invalid-feedback').remove();
                addressTemplate.find('.billing-address-prefecture').parent().find('.invalid-feedback').remove();
                addressTemplate.find('.billing-address-municipality').parent().find('.invalid-feedback').remove();
                addressTemplate.find('.billing-address-address').parent().find('.invalid-feedback').remove();
                addressTemplate.find('.billing-address-prefecture').val(data.city);
                addressTemplate.find('.billing-address-municipality').val(data.state);
                addressTemplate.find('.billing-address-address').val(data.street);
            }
        }
    });
})

// customize keydown event datepicker plugin
function datepickerKeyDownHandler(elem, e) {
    var code = e.keyCode || e.which;
    var status = $(".datepicker-dropdown").is(":visible") ? true : false;
    // If key is not TAB
    var parts = elem.val().split("/"),
        currentDate = new Date(parts[2], parts[0] - 1, parts[1]);
    switch (code) {
        case 13:
            tdElem = elem.parents('td');
            elem.datepicker('hide');
            if (tdElem && elem.hasClass('indi-edit')) {
                tdElem.text(elem.val());
                tdElem.prop("tabindex", 1);
                tdElem.focus();
                if (elem.hasClass('select2')) {
                    elem.select2('destory');
                    tdElem.focus();
                }
            }
            break
        case 27:
            tdElem = elem.parents('td');
            elem.datepicker('hide');
            if (tdElem && elem.hasClass('indi-edit')) {
                tdElem.parents('tr').removeClass('direct-edit');
                tdElem.text(elem.val());
                tdElem.prop("tabindex", 1);
                tdElem.focus();
                var instance = elem.data("datepicker");
                if (instance)
                    instance.remove();
            }
            e.preventDefault();
            e.stopPropagation();
            break
        case 113:
            setTimeout(() => {
                elem.datepicker('show');
            });
            return false;
        case 37:
            if (status) {
                e.preventDefault();
                e.stopPropagation();
                currentDate.setDate(currentDate.getDate() - 1);
            } else
                return true;
            break;
        case 38:
            if (status) {
                e.preventDefault();
                e.stopPropagation();
                currentDate.setDate(currentDate.getDate() - 7);
            } else
                return true;
            break;
        case 39:
            if (status) {
                e.preventDefault();
                e.stopPropagation();
                currentDate.setDate(currentDate.getDate() + 1);
            } else
                return true;
            break;
        case 40:
            if (status) {
                e.preventDefault();
                e.stopPropagation();
                currentDate.setDate(currentDate.getDate() + 7);
            } else
                return true;
            break;
    }
}