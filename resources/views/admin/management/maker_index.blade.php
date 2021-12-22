<div class="row">
    <div class="col-12 d-flex justify-content-between align-items-center mb-2">
        <h6 class="font-weight-bold text-warning">@lang('Shipping list')</h6>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Maker Id')</span>
            </div>
            <input type="number" id="search-maker-id" class="form-control" min='0' aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Maker')</span>
            </div>
            <input type="text" id="search-maker-name" class="form-control" autocomplete="off" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>
    <div class="col-md-2">
        <button class="btn btn-sm btn-primary" id="clear-search-customer">@lang('Clear')</button>
        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#manufacturer-register-modal">@lang('Register')</button>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-6">
        <table id="maker-list-table" class="table table-bordered" cellspacing="0">
            <thead>
                <tr>
                    <th>@lang('Maker Id')</th>
                    <th>@lang('Maker')</th>
                    <th>@lang('Register Date')</th>
                    <th style="min-width: 120px !important">@lang('Management content')</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script>
    var makerTable = $('#maker-list-table').DataTable({
        "processing": true,
        "searching": false,
        "lengthChange": false,
        "scrollCollapse": true,
        "paging": false,
        "bInfo" : false,
        "autoWidth": false,
        "responsive": true,
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
            url: "{{ route('admin.management.maker.list') }}",
            type: 'POST',
            dataSrc: '',
            data: function(data) {
                data.filterId = $('#search-maker-id').val();
                data.filterName = $('#search-maker-name').val();
            },
            dataType: "json",
        },
        columns: [
            {
                data: 'id',
                name: '@lang("Maker Id")'
            },
            {
                data: 'maker_name',
                name: '@lang("Maker")'
            },
            {
                data: 'created_at',
                name: '@lang("Update date")',
                render: function(data, type, row) {
                    return changeDateFormat(new Date(data));
                }
            },
            {
                data: null,
                name: "@lang('Management content')",
                class: 'd-flex justify-content-center',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return `<a class="btn btn-sm btn-primary update-maker-btn mr-2" data-toggle="modal" data-target="#manufacturer-register-modal">@lang('Edit')</a><a class="btn btn-sm btn-danger delete-maker-btn ml-2" data-toggle="modal" data-target="#confirm-modal">@lang('Delete')</a>`;
                }
            },
        ],
        'createdRow': function(row, data, dataIndex) {
            $(row).data('rowInfo', data);
        },
        order : [[ 1, "asc"]],
    })

    $("#manufacturer-register-modal").on('show.bs.modal', function(e) {
        var elemTarget = $(e.relatedTarget);

        if (elemTarget.hasClass('update-maker-btn')) {
            var currentData = elemTarget.parents('tr').data('rowInfo');
            $('#register-maker').data('id', currentData.id);
            $('#register-maker-name').val(currentData.maker_name);
            $('#register-maker').text('更新');
        }
    });

    $('#manufacturer-register-modal').on('hidden.bs.modal', function(e) {

        $("#manufacturer-register-modal").find('.invalid-feedback').remove();
        $("#manufacturer-register-modal").find('.is-invalid').removeClass('is-invalid');

        $('#register-maker-name').val('');
        $('#register-maker').text('登録');
    })

    $("#register-maker").click(function() {
        $("#manufacturer-register-modal").find('.invalid-feedback').remove();
        $("#manufacturer-register-modal").find('.is-invalid').removeClass('is-invalid');

        var thisElem = $(this);
        thisElem.prop('disabled', true);

        var url = "{{ route('admin.maker.store') }}", method = 'POST';
        if($(this).data('id')) {
            url = '/admin/maker/'+$(this).data('id');
            method = 'PUT';
        }
        $.ajax({
            url: url,
            method: method,
            data: {
                name: $('#register-maker-name').val(),
                id: $(this).data('id')
            },
            success: function(data) {
                thisElem.prop('disabled', false);

                $('#manufacturer-register-modal').modal('hide');
                $('#register-maker-name').val('');
                $("#manufacturer-register-modal").find('.invalid-feedback').remove();
                $("#manufacturer-register-modal").find('.is-invalid').removeClass('is-invalid');
                makerTable.ajax.reload();
            },
            error: function(xhr, status, error) {
                thisElem.prop('disabled', false);

                var errors = xhr.responseJSON.errors;
                for (key in errors) {
                    if (key == 'name') {
                        $('#register-maker-name').parents('.input-group').append('<div class="invalid-feedback" style="display: block !important; margin-left: 100px">'+errors['name']+'</div>')
                        .find('input').addClass('is-invalid');
                    }
                }
            },
        });
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
            url: "/admin/maker/"+$(this).data('id'),
            method: 'DELETE',
            data: {
                id: $("#confirm-modal").data('id')
            },
            success: function(data) {
                thisElem.prop('disabled', false);

                $('#confirm-modal').modal('hide');
                makerTable.ajax.reload();
            }
        });
    })

    $(document).on('keyup', '#search-maker-id, #search-maker-name', function() {
        makerTable.ajax.reload();
    })

    $(document).on('click', '#clear-search-customer', function() {
        $('#search-maker-id').val('');
        $('#search-maker-name').val('');
        makerTable.ajax.reload();
    })
</script>
