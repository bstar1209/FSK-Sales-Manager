<div class="row">
    <div class="col-12 d-flex justify-content-between align-items-center mb-2">
        <h6 class="font-weight-bold text-warning">仕入先管理画面</h6>
    </div>
</div>
<div class="row search-area">
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Supplier ID')</span>
            </div>
            <input type="text" id="search-supplier-id" class="form-control" autocomplete="off" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Kana')</span>
            </div>
            <input type="text" id="search-kana" class="form-control" autocomplete="off" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Person in charge')</span>
            </div>
            <input type="text" id="search-person-in-charge" class="form-control" autocomplete="off" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Rank')</span>
            </div>
            <select id="search-rank" class="form-control">
                <option value=""></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Country')</span>
            </div>
            <select class="selectpicker countrypicker" id="supplier-country" data-flag="true"></select>
        </div>
    </div>
    <div class="col-md-2">
        <button class="btn btn-sm btn-primary" id="clear-search">@lang('Clear')</button>
        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#supplier-register-modal">@lang('Register')</button>
    </div>
</div>

<table class="table table-bordered table-striped w-100" cellspacing="0" id="supplier-management-table">
    <thead>
        <tr>
            <th>@lang('Supplier ID')</th>
            <th>@lang('Company name')</th>
            <th>@lang('Kana')</th>
            <th>@lang('Person in charge')</th>
            <th>@lang('Rank')</th>
            <th style="min-width: 120px !important">@lang('Email')</th>
            <th>@lang('Country')</th>
            <th>@lang('Number of requests for quotation')</th>
            <th>@lang('Estimated number of responses')</th>
            <th>@lang('Sold out')</th>
            <th>@lang('Number of orders')</th>
            <th>@lang('Purchase price')</th>
            <th>@lang('Number of returns')</th>
            <th style="min-width: 120px !important">@lang('Number of order cancellations')</th>
            <th style="min-width: 120px !important">@lang('Management')</th>
        </tr>
    </thead>
</table>

<div class="modal fade" id="customer-delete-confirm" tabindex="-1" role="dialog"
aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-warning">@lang('Delete. is this good?')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @lang('Delete. is this good?')
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="btn-customer-delete-confirm" class="btn btn-primary btn-sm">@lang('Ok')</button>
                <button type="button" class="btn btn-danger btn-sm ml-3" data-dismiss="modal">@lang('Cancel')</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {

    loadCommonList();
    $('#register-supplier-country, #supplier-country').selectpicker();
    $('#register-supplier-country, #supplier-country').countrypicker();

    function getSupplierLog(id)
    {
        $.ajax({
            url: "{{ route('admin.management.supplier.log') }}",
            type: 'post',
            dataType: 'json',
            data: {
                id : id,
            },
            success: function(data) {
                drawSupplierLog(data);
            }
        });
    }

    function drawSelectPaymentList () {
        $('#register-supplier-payment-term').empty();
        $.each(commonPaymentList, function(key, val) {
            $('#register-supplier-payment-term').append('<option value="'+key+'">'+val+'</option>');
        });
    }

    function drawSupplierLog(data) {
        $.each($('#supplier-log-table tbody').find('tr'), function(index, item) {
            switch(index) {
                case 0:
                    $(item).find('td:eq(0)').text(data.log1[0].estReqCount);
                    $(item).find('td:eq(1)').text(data.log3[0].estReqCount);
                    $(item).find('td:eq(2)').text(data.log6[0].estReqCount);
                    $(item).find('td:eq(3)').text(data.all[0].estReqCount);
                    break;
                case 1:
                    $(item).find('td:eq(0)').text(data.log1[0].ansEstCount);
                    $(item).find('td:eq(1)').text(data.log3[0].ansEstCount);
                    $(item).find('td:eq(2)').text(data.log6[0].ansEstCount);
                    $(item).find('td:eq(3)').text(data.all[0].ansEstCount);
                    break;
                case 2:
                    $(item).find('td:eq(0)').text(data.log1[0].ansEmpCount);
                    $(item).find('td:eq(1)').text(data.log3[0].ansEmpCount);
                    $(item).find('td:eq(2)').text(data.log6[0].ansEmpCount);
                    $(item).find('td:eq(3)').text(data.all[0].ansEmpCount);
                    break;
                case 3:
                    $(item).find('td:eq(0)').text(data.log1[0].shipOrderCount);
                    $(item).find('td:eq(1)').text(data.log3[0].shipOrderCount);
                    $(item).find('td:eq(2)').text(data.log6[0].shipOrderCount);
                    $(item).find('td:eq(3)').text(data.all[0].shipOrderCount);
                    break;
                case 4:
                    $(item).find('td:eq(0)').text(data.log1[0].shipOrderMoney);
                    $(item).find('td:eq(1)').text(data.log3[0].shipOrderMoney);
                    $(item).find('td:eq(2)').text(data.log6[0].shipOrderMoney);
                    $(item).find('td:eq(3)').text(data.all[0].shipOrderMoney);
                    break;
                case 5:
                    $(item).find('td:eq(0)').text(data.log1[0].returnTime);
                    $(item).find('td:eq(1)').text(data.log3[0].returnTime);
                    $(item).find('td:eq(2)').text(data.log6[0].returnTime);
                    $(item).find('td:eq(3)').text(data.all[0].returnTime);
                    break;
                case 6:
                    $(item).find('td:eq(0)').text(data.log1[0].cancelOpQty);
                    $(item).find('td:eq(1)').text(data.log3[0].cancelOpQty);
                    $(item).find('td:eq(2)').text(data.log6[0].cancelOpQty);
                    $(item).find('td:eq(3)').text(data.all[0].cancelOpQty);
                    break;
            }
        });
    }

    var supplierManagementTable = $('#supplier-management-table').DataTable({
        "processing": true,
        "searching": false,
        "lengthChange": false,
        "scrollCollapse": true,
        "pagingType": "full_numbers",
        "bInfo" : false,
        "autoWidth": false,
        "responsive": true,
        "bRetrieve": 'true',
        "destroy": "true",
        'language':{
            "zeroRecords": "テーブル内のデータなし.",
            "loadingRecords": "&nbsp;",
            "processing": "読み込み中...",
            "search": "",
            "paginate": {
                "first": "<< @lang('first')",
                "previous": "< @lang('previous')",
                "next": "@lang('next') >",
                "last": "@lang('last') >>"
            }
        },
        "dom": '<"row view-filter"<"col-md-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-md-12"<"d-flex justify-content-center"ip>>>',
        "ajax": {
            url: "{{ route('admin.management.supplier.list') }}",
            type: 'POST',
            dataSrc: '',
            data: function(data) {
                if (!supplierManagementTable) {
                    $('#supplier-country').data('selectpicker').destroy();
                    $('#supplier-country').selectpicker();
                    $('#supplier-country').countrypicker();
                }

                data.supplierId = $('#search-supplier-id').val();
                data.kana = $('#search-kana').val();
                data.personInCharge= $('#search-person-in-charge').val();
                data.rank = $('#search-rank').val();
                // data.country = $('#supplier-country').val();
            },
            dataType: "json",
        },
        columns: [
            {
                data: 'id',
                name: "@lang('Supplier ID')"
            },
            {
                data: 'user_info.company_name',
                name: "@lang('Company name')"
            },
            {
                data: 'user_info.company_name_kana',
                name: "@lang('Kana')"
            },
            {
                data: 'representative',
                name: "@lang('Person in charge')"
            },
            {
                data: 'user_info.rank',
                name: "@lang('Rank')"
            },
            {
                data: 'user_info.email1',
                name: "@lang('Email')"
            },
            {
                data: 'user_info.address.country',
                name: "@lang('Country')"
            },
            {
                data: 'user_info.est_req_time',
                name: "@lang('Number of requests for quotation')"
            },
            {
                data: 'user_info.est_ans_time',
                name: "@lang('Estimated number of responses')"
            },
            {
                data: 'emp_ans_time',
                name: "@lang('Sold out')"
            },
            {
                data: 'user_info.order_qty',
                name: "@lang('Number of orders')"
            },
            {
                data: 'user_info.order_money',
                name: "@lang('Purchase price')"
            },
            {
                data: 'return_time',
                name: "@lang('Number of returns')"
            },
            {
                data: 'cal_po_time',
                name: "@lang('Number of order cancellations')"
            },
            {
                data: null,
                name: "@lang('Management content')",
                render: function(data) {
                    return `<div class="d-flex flex-column justify-content-center"><a class="btn btn-sm btn-primary update-maker-btn" data-toggle="modal" data-target="#supplier-register-modal">@lang('Edit')</a><a class="btn btn-sm btn-danger delete-maker-btn" data-toggle="modal" data-target="#confirm-modal">@lang('Delete')</a></div>`;
                }
            },
        ],
        'createdRow': function(row, data, dataIndex) {
            $(row).data('rowInfo', data);
        },
    })

    var paymentTable = $('#payment-term-table').DataTable({
        "searching": false,
        "lengthChange": false,
        "scrollY":  "170px",
        "autoWidth": false,
        "responsive": true,
        "ordering": false,
        "paging": false,
        "bInfo" : false,
        "bRetrieve": 'true',
        "destroy": "true",
        "autoWidth": false,
        'language': {
            "zeroRecords": "テーブル内のデータなし.",
            "loadingRecords": "&nbsp;",
            "processing": "読み込み中...",
            "search": ""
        },
        ajax: {
            url: getPaymentUrl,
            dataSrc: '',
            type: 'POST',
            complete: function(data) {
                data.responseJSON.forEach(function(item) {
                    commonPaymentList[item.id] = item.common_name;
                    commonList[item.id] = {name: item.common_name, type: item.common_type};
                });

            }
        },
        'createdRow': function(row, data, dataIndex) {
            $(row).data('id', data.id);
        },
        columns: [
            { data: 'common_name', name: '支払方法' },
            {
                data: null,
                name: '更新',
                render: function(data, row) {
                    return `<a class="btn btn-sm payment-edit"><i class="fa fa-edit fa-sm"></i></a><a class="btn btn-sm payment-save d-none"><i class="fa fa-save fa-sm"></i></a>`;
                }
            },
            {
                data: null,
                name: '削除',
                render: function(data, row) {
                    return `<a class="btn btn-sm payment-delete"><i class="fa fa-trash fa-sm"></i></a><a class="btn btn-sm payment-cancel d-none"><i class="fa fa-close fa-sm"></i></a>`;
                }
            },
        ]
    })

    paymentTable.on('click', '.payment-delete',  function() {
        $('#payment-term-table').find('tr.selected').removeClass('selected');
        var id = $(this).parents('tr').addClass('selected').data('id');
        $("#confirm-btn").data("type", "deletePayment");
        $("#confirm-btn").data("id", id);
        $("#confirm-modal").modal('show');
        $("#add-new-payment-modal").modal('hide');
    })

    paymentTable.on('click', '.payment-cancel', function() {
        $('#payment-term-table').find('tr.selected').removeClass('selected');
        var trElem = $(this).parents('tr').addClass('selected');
        var id = trElem.data('id');
        trElem.find('td:eq(0)').empty().text($(this).data('old'));
        trElem.find('a.payment-cancel').addClass('d-none');
        trElem.find('a.payment-delete').removeClass('d-none');
        trElem.find('a.payment-edit').removeClass('d-none');
        trElem.find('a.payment-save').addClass('d-none');
    })

    paymentTable.on('click', '.payment-edit', function() {
        $('#payment-term-table').find('tr.selected').removeClass('selected');
        var trElem = $(this).parents('tr').addClass('selected');
        var id = trElem.data('id');
        var oldContent = trElem.find('td').eq(0).text();
        trElem.find('td').eq(0).empty().append('<input type="text" class="form-control form-control-sm" value="'+oldContent+'">');
        trElem.find('a.payment-cancel').removeClass('d-none').data('old', oldContent);
        trElem.find('a.payment-delete').addClass('d-none');
        trElem.find('a.payment-edit').addClass('d-none');
        trElem.find('a.payment-save').removeClass('d-none');
    })

    paymentTable.on('click', '.payment-save', function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);

        var trElem = $(this).parents('tr').addClass('selected');
        var id = trElem.data('id');

        $("#add-new-payment-modal").find('.invalid-feedback').remove();
        $("#add-new-payment-modal").find('.is-invalid').removeClass('is-invalid');

        $.ajax({
            url: "/admin/common/"+id,
            method: 'PUT',
            data: {
                commonName: trElem.find('td:eq(0) input').val()
            },
            success: function(result) {

                thisElem.prop('disabled', false);
                $('#register-new-common-name').val('');
                $("#add-new-payment-modal").find('.invalid-feedback').remove();
                $("#add-new-payment-modal").find('.is-invalid').removeClass('is-invalid');
                var data = {
                    common_name: result.common_name,
                    '更新':'<a class="btn btn-sm"><i class="fas fa-edit fa-sm"></i>edit</a>',
                    '削除': '<a class="btn btn-sm"><i class="fas fa-edit fa-sm"></i>delete</a>'
                }
                paymentTable.row('.selected').remove().draw(false);
                paymentTable.row.add(data).draw(false);
                commonPaymentList[result.id] = result.common_name;
                drawSelectPaymentList();
            },
            error: function(xhr, status, error) {
                thisElem.prop('disabled', false);
                var errors = xhr.responseJSON.errors;
                for (key in errors) {
                    if (key == 'commonName') {
                        trElem.find('td:eq(0)').addClass('text-wrap').append('<div class="invalid-feedback" style="display: block !important;">'+errors['commonName']+'</div>')
                        .find('input').addClass('is-invalid');
                    }
                }
            },
        });
    })

    $('#add-new-common-btn').click(function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);

        $("#add-new-payment-modal").find('.invalid-feedback').remove();
        $("#add-new-payment-modal").find('.is-invalid').removeClass('is-invalid');
        $.ajax({
            url: "{{ route('admin.common.store') }}",
            method: 'POST',
            data: {
                commonName: $('#register-new-common-name').val()
            },
            success: function(result) {
                thisElem.prop('disabled', false);
                var data = {
                    common_name: result.common_name,
                    '更新':'<a class="btn btn-sm"><i class="fas fa-edit fa-sm"></i>edit</a>',
                    '削除': '<a class="btn btn-sm"><i class="fas fa-edit fa-sm"></i>delete</a>'
                }
                paymentTable.row.add(data).draw(true);
                $('#register-new-common-name').val('');
                commonPaymentList[result.id] = result.common_name;
                drawSelectPaymentList();

            },
            error: function(xhr, status, error) {
                thisElem.prop('disabled', false);
                var errors = xhr.responseJSON.errors;
                for (key in errors) {
                    if (key == 'commonName') {
                        $('#register-new-common-name').parents('.input-group').append('<div class="invalid-feedback" style="display: block !important; margin-left: 100px">'+errors['commonName']+'</div>')
                        .find('input').addClass('is-invalid');
                    }
                }
            },
        });
    });

    $('#supplier-register-modal').on('show.bs.modal', function(e) {
        drawSelectPaymentList();
        $("#supplier-log-table").addClass('d-none');
        $("#supplier-register-modal").find('.invalid-feedback').remove();
        $("#supplier-register-modal").find('.is-invalid').removeClass('is-invalid');
        $('#supplier-register-modal').find('input').val('');
        $('#supplier-register-modal').find('select').val(0);
        $('#supplier-register-modal').find('textarea').val('');
        $('#supplier-register-modal').find('input[type=checkbox]').prop('checked', false);
        var vendor = $(e.relatedTarget).parents('tr').data('rowInfo');
        $('#register-supplier-country').data('selectpicker').destroy();
        if (vendor) {
            $("#supplier-log-table").removeClass('d-none');
            getSupplierLog(vendor.id);
            drawSelectPaymentList();
            $('#register-supplier-country').val(vendor.user_info.address.country);
            $('#register-supplier-country').data('default', vendor.user_info.address.country);
            $("#register-supplier").data('registerType', 'update');
            $("#register-supplier").data('registerId', vendor.id);
            $('#register-supplier-company-name').val(vendor.user_info.company_name);
            $('#register-postal-code').val(vendor.user_info.address.zip);
            $('#register-person-in-charge').val(vendor.representative);
            $('#register-supplier-phone-number').val(vendor.user_info.address.tel);
            $('#register-supplier-email1').val(vendor.user_info.email1);
            $('#register-supplier-email2').val(vendor.user_info.email2);
            $('#register-supplier-email3').val(vendor.user_info.email3);
            $('#register-supplier-email4').val(vendor.user_info.email4);
            $('#register-supplier-remarks').val(vendor.user_info.message1);
            if (Array.isArray(vendor.user_info.payment) && vendor.user_info.payment[0] && vendor.user_info.payment[0].common) {
                $("#register-supplier-payment-term").val(parseInt(vendor.user_info.payment[0].common.id));
            } else {
                $("#register-supplier-payment-term").val(0);
            }
            $('#register-supplier-company-name-kana').val(vendor.user_info.company_name_kana);
            $('#register-supplier-rank').val(vendor.user_info.rank);
            $('#register-supplier-prefectures').val(vendor.district);
            $('#register-supplier-address').val(vendor.user_info.address.address1);
            $('#register-supplier-fax').val(vendor.user_info.address.fax);
            if (vendor.daily_rfq == 1)
                $('#daily-RFQ').prop('checked', true);
            else
                $('#daily-RFQ').prop('checked', false);
        } else {
            $('#register-supplier').data('registerType', 'create');
        }
        $('#register-supplier-country').selectpicker();
        $('#register-supplier-country').countrypicker();
    });

    $("#register-supplier").unbind('click');

    $("#register-supplier").click(function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);

        $("#supplier-register-modal").find('.invalid-feedback').remove();
        $("#supplier-register-modal").find('.is-invalid').removeClass('is-invalid');
        var registerType = $(this).data('registerType');
        if (registerType == 'update') {
            registerId = $(this).data('registerId');
            var routeUrl = '/admin/supplier/'+registerId;
            var method = "PUT";
        } else {
            var routeUrl = "{{ route('admin.supplier.store') }}";
            var method = "POST";
        }
        var email1 = $('#register-supplier-email1').val();
        var email2 = $('#register-supplier-email2').val();
        var email3 = $('#register-supplier-email3').val();
        var email4 = $('#register-supplier-email4').val();

        var checkDupplicate = validationEmails(email1, email2, email3, email4);
        if (checkDupplicate != 'success') {
            thisElem.prop('disabled', false);
            let elem = $('#register-supplier-email'+(checkDupplicate[1]));
            elem.parents('.input-group').append('<div class="invalid-feedback" style="display: block !important; margin-left: 100px">メールアドレスが重複されています.</div>')
                    .find('input').addClass('is-invalid')
            return;
        }

        var storedData = {
            compName: $('#register-supplier-company-name').val(),
            country: $('#register-supplier-country').val(),
            postalCode: $('#register-postal-code').val(),
            personInCharge: $('#register-person-in-charge').val(),
            phoneNumber: $('#register-supplier-phone-number').val(),
            email1: $('#register-supplier-email1').val(),
            email2: $('#register-supplier-email2').val(),
            email3: $('#register-supplier-email3').val(),
            email4: $('#register-supplier-email4').val(),
            remarks: $('#register-supplier-remarks').val(),
            payTerm: $('#register-supplier-payment-term').val(),
            compNameKana: $('#register-supplier-company-name-kana').val(),
            rank: $('#register-supplier-rank').val(),
            prefectures: $('#register-supplier-prefectures').val(),
            address: $('#register-supplier-address').val(),
            fax: $('#register-supplier-fax').val(),
            registerDate: $('#register-supplier-date').val(),
            dailyRFQ: $('#daily-RFQ').prop('checked') ? 1 : 0,
        };
        $.ajax({
            url: routeUrl,
            method: method,
            data: storedData,
            success: function(data) {
                thisElem.prop('disabled', false);
                $("#supplier-register-modal").modal("hide");
                $("#supplier-register-modal").find('input').val('');
                if (registerType == 'update')
                    toastr.success('仕入先が更新完了しました。');
                else
                    toastr.success('仕入先は登録完了しました。');
                supplierManagementTable.ajax.reload();
            },
            error: function(xhr, status, error) {
                thisElem.prop('disabled', false);
                var errors = xhr.responseJSON.errors;
                for (key in errors) {
                    var errorElem = null;
                    var message = null;
                    switch(key) {
                        case 'compName':
                            errorElem = $('#register-supplier-company-name');
                            message = errors['compName'];
                            break;
                        case 'compNameKana':
                            errorElem = $('#register-supplier-company-name-kana');
                            message = errors['compNameKana'];
                            break;
                        case 'email1':
                            errorElem = $('#register-supplier-email1');
                            message = errors['email1'];
                            break;
                        case 'email2':
                            errorElem = $('#register-supplier-email2');
                            message = errors['email2'];
                            break;
                        case 'email3':
                            errorElem = $('#register-supplier-email3');
                            message = errors['email3'];
                            break;
                        case 'email4':
                            errorElem = $('#register-supplier-email4');
                            message = errors['email'];
                            break;
                        case 'country':
                            errorElem = $('#register-supplier-country');
                            message = errors['country'];
                            break;
                        case 'address':
                            errorElem = $('#register-supplier-address');
                            message = errors['address'];
                            break;
                        case 'payTerm':
                            errorElem = $('#register-supplier-payment-term');
                            message = errors['payTerm'];
                            break;
                        default:
                            errorElem = null;
                            break;
                    }
                    if (errorElem) {
                        errorElem.parents('.input-group').append('<div class="invalid-feedback" style="display: block !important; margin-left: 100px">'+message+'</div>')
                        .find('input').addClass('is-invalid');
                    }
                }
            }
        });
    });

    $("#register-supplier-add-payment").click(function() {
        $('#add-new-payment-modal').modal('show');
        $('#supplier-register-modal').modal('hide');
    });

    $("#confirm-modal").on('show.bs.modal', function(e) {
        var elemTarget = $(e.relatedTarget).parents('tr').data('rowInfo');
        if(elemTarget.id) 
            $('#confirm-btn').data('id', elemTarget.id);
    });

    $(document).on('click', '#confirm-cancel', function() {
        $("#confirm-modal").modal('hide');
    })

    $(document).on('click', '#confirm-btn', function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);
        $.ajax({
            url: "/admin/supplier/"+$(this).data('id'),
            method: 'DELETE',
            success: function(data) {
                thisElem.prop('disabled', false);
                $('#confirm-modal').modal('hide');
                supplierManagementTable.ajax.reload();
            }
        });
    })

    $(document).on('keyup', '#search-supplier-id, #search-kana, #search-person-in-charge', function() {
        supplierManagementTable.ajax.reload();
    })

    $('#search-rank, #supplier-country').change(function() {
        supplierManagementTable.ajax.reload();
    })

    $('#clear-search').click(function() {
        $('.search-area').find('input').val('');
        $('.search-area').find('select').val(0);
    })

})
</script>
