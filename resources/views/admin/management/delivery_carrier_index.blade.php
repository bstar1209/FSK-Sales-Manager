<div class="row">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="font-weight-bold text-warning">@lang('Shipping list')</h6>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#delivery-edit-modal">@lang('Register')</button>
        </div>
        <table id="delivery-table" class="table table-bordered" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>@lang('Registered name')</th>
                    <th>@lang('Company name')</th>
                    <th>TEL</th>
                    <th>@lang('Address')</th>
                    <th>@lang('Person in charge')</th>
                    <th>@lang('Management content')</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-md-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="font-weight-bold text-warning">運送業者一覧</h6>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#transport-edit-modal">@lang('Register')</button>
        </div>
        <table id="transport-table" class="table table-bordered table-striped" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>@lang('Management content')</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="row" style="margin-top: 50px !important">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="font-weight-bold text-warning">本社住所登録画面</h6>
        </div>
        <table id="company-address-table" class="table table-bordered" cellspacing="0">
            <thead>
                <tr>
                    <th>@lang('Company name')</th>
                    <th>TEL</th>
                    <th>@lang('Address')</th>
                    <th>@lang('Management content')</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script>
$(function() {
    $('#company-address').ckeditor();
    $('#cke_1_top').remove();
    var deliveryListTable = $('#delivery-table').DataTable({
        "processing": true,
        "searching": false,
        "lengthChange": false,
        "paging": true,
        "bInfo" : false,
        "autoWidth": false,
        "responsive": true,
        "scroller": true,
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
        "ajax": {
            url: "{{ route('admin.management.delivery_carrier.ship.list') }}",
            type: 'POST',
            dataSrc: '',
            dataType: "json",
        },
        columns: [
            { data: 'id', name: 'ID' },
            { data: 'staff', name: '@lang("Registered name")'},
            { data: 'comp_name', name: '@lang("Company name")'},
            { data: 'tel', name: 'TEL'},
            {
                data: null,
                name: '@lang("Address")',
                render: function(data, type, row) {
                    return row.province+' '+row.city+' '+row.address+' '+row.address1+' '+row.country;
                }
            },
            { data: 'representative', name: '@lang("Person in charge")'},
            {
                data: null,
                name: "@lang('Management content')",
                class: 'd-flex flex-column justify-content-center',
                render: function(data) {
                    return `
                        <button class="btn btn-sm btn-primary update-delivery-btn" data-toggle="modal" data-target="#delivery-edit-modal">@lang('Edit')</button><button class="btn btn-sm btn-danger delete-delivery-btn" data-toggle="modal" data-target="#confirm-modal">@lang('Delete')</button>
                    `;
                }
            },
        ],
        'createdRow': function(row, data, dataIndex) {
            $(row).data('rowInfo', data);
        },
    })

    var transportTable = $('#transport-table').DataTable({
        "processing": true,
        "searching": false,
        "lengthChange": false,
        "paging": true,
        "bInfo" : false,
        "autoWidth": false,
        "responsive": true,
        "scroller": true,
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
        "ajax": {
            url: "{{ route('admin.management.delivery.transport.list') }}",
            type: 'POST',
            dataSrc: '',
            dataType: "json",
        },
        columns: [
            { data: 'id', name: 'ID' },
            { data: 'name', name: 'Name'},
            {
                data: null,
                class: 'd-flex justify-content-center',
                name: "@lang('Management content')",
                render: function(data) {
                    return `
                        <a class="btn btn-sm btn-primary transport-edit-btn mr-2" data-toggle="modal" data-target="#transport-edit-modal">@lang('Edit')</a><a class="btn btn-sm btn-danger transport-delete-btn ml-2" data-toggle="modal" data-target="#confirm-modal">@lang('Delete')</a>
                    `;
                }
            },
        ],
        'createdRow': function(row, data, dataIndex) {
            $(row).data('rowInfo', data);
        },
    })

    var companyAddressTable = $('#company-address-table').DataTable({
        "processing": true,
        "searching": false,
        "lengthChange": false,
        "paging": false,
        "bInfo" : false,
        "autoWidth": false,
        "responsive": true,
        "scroller": true,
        'language':{
            "zeroRecords": "テーブル内のデータなし.",
            "loadingRecords": "&nbsp;",
            "processing": "読み込み中...",
            "search": ""
        },
        "ajax": {
            url: "{{ route('admin.management.get_company_address') }}",
            type: 'POST',
            dataSrc: '',
            dataType: "json",
            complete: function(data) {
            }
        },
        columns: [
            { data: 'company_name', name: '@lang("Company name")' },
            { data: 'tel', name: 'TEL'},
            { data: 'address', name: "@lang('Address')"},
            {
                data: null,
                name: "@lang('Management content')",
                render: function(data) {
                    return `
                        <button class="btn btn-sm btn-primary transport-edit-btn mr-2" data-toggle="modal" data-target="#headerquarter-edit-modal">@lang('Edit')</button>
                    `;
                }
            },
        ],
        'createdRow': function(row, data, dataIndex) {
            $(row).data('rowInfo', data);
        },
    })

    $("#delivery-edit-modal").on('show.bs.modal', function(e) {
        $('#delivery-edit-modal').find('input').val('');
        $('#delivery-edit-modal').find('.is-invalid').removeClass('is-invalid');
        var elemTarget = $(e.relatedTarget);
        if (elemTarget.hasClass('update-delivery-btn')) {
            var currentData = elemTarget.parents('tr').data('rowInfo');
            $('#delivery-edit-modal .registered-name').val(currentData.comp_name);
            $('#delivery-edit-modal .company-name').val(currentData.staff);
            $('#delivery-edit-modal .tel').val(currentData.tel);
            $('#delivery-edit-modal .fax').val(currentData.fax);
            $('#delivery-edit-modal .country').val(currentData.country);
            $('#delivery-edit-modal .prefecture').val(currentData.prefecture);
            $('#delivery-edit-modal .city').val(currentData.city);
            $('#delivery-edit-modal .address1').val(currentData.address);
            $('#delivery-edit-modal .address2').val(currentData.address1);
            $('#delivery-edit-modal .zip_code').val(currentData.zip);
            $('#delivery-edit-modal .person-in-charge').val(currentData.representative);
            $('#delivery-edit-btn').data('id', currentData.id);
        }
    });

    $(document).on('click', '#delivery-edit-btn', function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);
        $('#delivery-edit-modal').find('.is-invalid').removeClass('is-invalid');
        var registeredName = $('#delivery-edit-modal .registered-name').val();
        var companyName = $('#delivery-edit-modal .company-name').val();
        var tel = $('#delivery-edit-modal .tel').val();
        var fax = $('#delivery-edit-modal .fax').val();
        var country = $('#delivery-edit-modal .country').val();
        var prefecture = $('#delivery-edit-modal .prefecture').val();
        var city = $('#delivery-edit-modal .city').val();
        var address1 = $('#delivery-edit-modal .address1').val();
        var address2 = $('#delivery-edit-modal .address2').val();
        var zipCode = $('#delivery-edit-modal .zip_code').val();
        var personInCharge = $('#delivery-edit-modal .person-in-charge').val();
        var ajaxData = {
            registeredName: registeredName,
            companyName: companyName,
            tel: tel,
            fax: fax,
            country: country,
            prefecture: prefecture,
            city: city,
            address1: address1,
            address2: address2,
            zipCode: zipCode,
            personInCharge: personInCharge,
            id: $(this).data('id')
        };
        $.ajax({
            url: "{{ route('admin.management.delivery.ship.create') }}",
            type: 'post',
            data: ajaxData,
            success: function(data) {
                thisElem.prop('disabled', false);
                toastr.success('納品先情報の登録を完了しました。');
                $("#delivery-edit-modal").modal('hide');
                deliveryListTable.ajax.reload();
            },
            error: function(xhr, status, error) {
                thisElem.prop('disabled', false);
                var errors = xhr.responseJSON.errors;
                toastr.warning('正しく入力してください。');
                for (key in errors) {
                    var message = null, elem = null;
                    switch(key) {
                        case 'registeredName':
                            $('#delivery-edit-modal .registered-name').addClass('is-invalid');
                            break;
                        case 'companyName':
                            $('#delivery-edit-modal .company-name').addClass('is-invalid');
                            break;
                        case 'tel':
                            $('#delivery-edit-modal .tel').addClass('is-invalid');
                            break;
                        case 'country':
                            $('#delivery-edit-modal .country').addClass('is-invalid');
                            break;
                        case 'city':
                            $('#delivery-edit-modal .city').addClass('is-invalid');
                            break;
                        case 'address1':
                            $('#delivery-edit-modal .address1').addClass('is-invalid');
                            break;
                        case 'personInCharge':
                            $('#delivery-edit-modal .person-in-charge').addClass('is-invalid');
                            break;
                    }
                }
            },
        });
    })

    $(document).on('click', '#confirm-cancel', function() {
        $("#confirm-modal").modal('hide');
    })

    $("#confirm-modal").on('show.bs.modal', function(e) {
        var elemTarget = $(e.relatedTarget).parents('tr').data('rowInfo');
        var type = '';
        if ($(e.relatedTarget).hasClass('transport-delete-btn')) {
            $('#confirm-btn').data('type', 'transport');
        } else {
            $('#confirm-btn').data('type', 'ship');
        }
        $('#confirm-btn').data('id', elemTarget.id);
    });

    $(document).on('click', '#confirm-btn', function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);
        var url = '';
        var type = $(this).data('type');
        if (type == 'transport')
            url = "{{ route('admin.management.delivery.transport.delete') }}";
        else
            url = "{{ route('admin.management.delivery.ship.delete') }}";
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                id: $(this).data('id')
            },
            success: function(data) {
                thisElem.prop('disabled', false);
                $('#confirm-modal').modal('hide');
                if (type == 'transport')
                    transportTable.ajax.reload();
                else
                    deliveryListTable.ajax.reload();
            }
        });
    })

    $('#transport-edit-btn').click(function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);
        $('#transport').removeClass('is-invalid');
        var transportName = $('#transport-name').val();
        if (!transportName || transportName == null || transportName == undefined) {
            thisElem.prop('disabled', false);
            $('#transport-name').addClass('is-invalid');
            toastr.warning('正しく入力してください。');
            return;
        }

        $.ajax({
            url: "{{ route('admin.management.delivery.transport.edit') }}",
            method: 'POST',
            data: {
                name: transportName,
                id: $(this).data('id')
            },
            success: function(data) {
                thisElem.prop('disabled', false);
                $('#transport-edit-modal').modal('hide');
                transportTable.ajax.reload();
            }
        });
    })

    $('#transport-edit-modal').on('show.bs.modal', function(e) {
        $('#transport-edit-modal').find('input').val('');
        $('#transport-edit-modal').find('.is-invalid').removeClass('is-invalid');
        var elemTarget = $(e.relatedTarget);
        if (elemTarget.hasClass('transport-edit-btn')) {
            var currentData = elemTarget.parents('tr').data('rowInfo');
            $('#transport-name').val(currentData.name);
            $('#transport-edit-btn').data('id', currentData.id);
        }
    });
    $('#headerquarter-edit-modal').on('show.bs.modal', function(e) {
        $('#cke_1_top').remove();
        // $('#cke_company-address').css('style', '78%');
        var data = $(e.relatedTarget).parents('tr').data('rowInfo');
        $('#company-name').val(data.company_name);
        $('#company-tel').val(data.tel);
        $('#company-address').val(data.address);
        $('#company-address-edit-btn').data('id', data.id);
    });

    $('#company-address-edit-btn').click(function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);
        var companyName = $('#company-name').val();
        var companyTel = $('#company-tel').val();
        var companyAddress = $('#company-address').val();
        var validFlag = true;
        if (!companyName || companyName == '' || companyName == undefined) {
            $('#company-name').addClass('is-invalid');
            validFlag = false;
        }

        if (!companyTel || companyTel == '' || companyTel == undefined) {
            $('#company-tel').addClass('is-invalid');
            validFlag = false;
        }

        if (!companyAddress || companyAddress == '' || companyAddress == undefined) {
            $('#company-address').addClass('is-invalid');
            validFlag = false;
        }

        if (!validFlag) {
            thisElem.prop('disabled', false);
            toastr.warning('この項目は入力必須です。');
            return ;
        }

        $.ajax({
            url: "{{ route('admin.management.company_address.edit') }}",
            method: 'POST',
            data: {
                id: $(this).data('id'),
                companyName: companyName,
                tel: companyTel,
                address: companyAddress
            },
            success: function(data) {
                thisElem.prop('disabled', false);
                $('#headerquarter-edit-modal').modal('hide');
                toastr.success('本社住所情報の更新を完了しました。');
                companyAddressTable.ajax.reload();
            }
        });
    })
});
</script>
