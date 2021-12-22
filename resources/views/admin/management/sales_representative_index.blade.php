<div class="row">
    <div class="col-12 d-flex justify-content-between align-items-center mb-2">
        <h6 class="font-weight-bold text-warning">営業担当管理画面</h6>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Given name')</span>
            </div>
            <input type="text" id="search-name" class="form-control">
        </div>
    </div>
    <div class="col-md-2">
        <button class="btn btn-sm btn-primary" id="clear-search">@lang('Clear')</button>
        <button class="btn btn-sm btn-primary" data-toggle="modal"
            data-target="#sales-representative-edit-modal">@lang('Register')</button>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-8">
        <table id="sales-representative-table" class="table table-bordered" cellspacing="0">
            <thead>
                <tr>
                    <th>@lang('Sales representative ID')</th>
                    <th>@lang('English name')</th>
                    <th>@lang('Japanese name')</th>
                    <th>@lang('Phone number')</th>
                    <th>@lang('Email')</th>
                    <th>@lang('Password')</th>
                    <th style="min-width: 120px !important">@lang('Management')</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script>
    $(function() {
        $('#search-name').val(null);
        var salesRepresentativeTable = $('#sales-representative-table').DataTable({
            "processing": true,
            "searching": false,
            "lengthChange": false,
            "scrollCollapse": true,
            "paging": false,
            "ordering": false,
            "bInfo": false,
            "autoWidth": false,
            "scroller": true,
            'language': {
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
                url: "{{ route('admin.management.sales_representative.list') }}",
                type: 'POST',
                dataSrc: '',
                data: function(data) {
                    data.name = $('#search-name').val();
                },
                dataType: "json",
            },
            columns: [{
                    data: 'id',
                    name: '@lang("Sales representative ID")'
                },
                {
                    data: 'username_eng',
                    name: '@lang("English name")'
                },
                {
                    data: 'username_jap',
                    name: '@lang("Japanese name")'
                },
                {
                    data: 'tel',
                    name: '@lang("Phone number")'
                },
                {
                    data: 'mail',
                    name: '@lang("Email")'
                },
                {
                    data: null,
                    name: '@lang("Password")',
                    render: function(data, type, row) {
                        return '***********';
                    }
                },
                {
                    data: null,
                    name: "@lang('Management content')",
                    class: 'd-flex justify-content-center',
                    render: function(data) {
                        return `
                        <a class="btn btn-sm btn-primary edit-sales-representative-btn mr-2" data-toggle="modal" data-target="#sales-representative-edit-modal">@lang('Edit')</a>
                        <a class="btn btn-sm btn-danger delete-maker-btn ml-2" data-toggle="modal" data-target="#confirm-modal">@lang('Delete')</a>
                    `;
                    }
                },
            ],
            'createdRow': function(row, data, dataIndex) {
                $(row).data('rowInfo', data);
            },
        })

        $('#sales-representative-edit-modal').on('hidden.bs.modal', function(e) {
            $('#sales-representative-edit-modal').find('input').val('');
            $('#sales-representative-edit-modal').find('.invalid-feedback').remove();
            $('#sales-representative-edit-modal').find('.is-invalid').removeClass('is-invalid');
            $('#sales-representative-edit-btn').text('登録');
        });

        $('#sales-representative-edit-modal').on('show.bs.modal', function(e) {
            $('#sales-representative-edit-modal').find('input').val('');
            $('#sales-representative-edit-modal').find('.invalid-feedback').remove();
            $('#sales-representative-edit-modal').find('.is-invalid').removeClass('is-invalid');
            var elemTarget = $(e.relatedTarget);
            if (elemTarget.hasClass('edit-sales-representative-btn')) {
                var currentData = elemTarget.parents('tr').data('rowInfo');
                $('.english-name').val(currentData.username_eng);
                $('.email').val(currentData.mail);
                $('.tel').val(currentData.tel);
                $('.japanese-name').val(currentData.username_jap);
                $('.password').val('*********');
                $('.fax').val(currentData.fax);
                $('#sales-representative-edit-btn').text('更新');
                $('#sales-representative-edit-btn').data('id', currentData.id);
            }
        });

        $("#confirm-modal").on('show.bs.modal', function(e) {
            var elemTarget = $(e.relatedTarget).parents('tr').data('rowInfo');
            if (elemTarget.id)
                $('#confirm-btn').data('id', elemTarget.id);
        });

        $(document).on('click', '#confirm-btn', function() {
            var thisElem = $(this);
            thisElem.prop('disabled', true);

            $.ajax({
                url: "{{ route('admin.management.sales_representative.delete') }}",
                method: 'POST',
                data: {
                    id: $(this).data('id')
                },
                success: function(data) {
                    thisElem.prop('disabled', false);
                    salesRepresentativeTable.ajax.reload();
                    $("#confirm-modal").modal('hide');
                }
            });
        })

        $(document).on('keyup', '#search-name', function() {
            salesRepresentativeTable.ajax.reload();
        })

        $(document).on('click', '#confirm-cancel', function() {
            $("#confirm-modal").modal('hide');
        })

        $(document).on('click', '#clear-search', function() {
            $('#search-name').val(null);
        })

        $('#sales-representative-edit-btn').click(function() {
            var thisElem = $(this);
            $('#sales-representative-edit-modal').find('.invalid-feedback').remove();
            $('#sales-representative-edit-modal').find('.is-invalid').removeClass('is-invalid');
            thisElem.prop('disabled', true);

            var englishName = $('#sales-representative-edit-modal .english-name').val();
            var email = $('#sales-representative-edit-modal .email').val();
            var tel = $('#sales-representative-edit-modal .tel').val();
            var japaneseName = $('#sales-representative-edit-modal .japanese-name').val();
            var password = $('#sales-representative-edit-modal .password').val();
            var fax = $('#sales-representative-edit-modal .fax').val();

            $.ajax({
                url: "{{ route('admin.management.sales_representative.edit') }}",
                type: 'post',
                data: {
                    english: englishName,
                    japanese: japaneseName,
                    tel: tel,
                    password: password,
                    fax: fax,
                    email: email,
                    id: $(this).data('id')
                },
                success: function(data) {
                    thisElem.prop('disabled', false);
                    $('#sales-representative-edit-modal').modal('hide');
                    salesRepresentativeTable.ajax.reload();
                    thisElem.data('id', null);
                    toastr.success('登録を完了しました。');
                },
                error: function(xhr, status, error) {
                    thisElem.prop('disabled', false);
                    var errors = xhr.responseJSON.errors;
                    toastr.warning('正しく入力してください。');
                    for (key in errors) {
                        var message = null,
                            elem = null;
                        switch (key) {
                            case 'english':
                                message = errors['english'];
                                elem = $('.english-name').parents('.input-group');
                                break;
                            case 'password':
                                message = errors['password'];
                                elem = $('.password').parents('.input-group');
                                break;
                            case 'email':
                                message = errors['email'];
                                elem = $('.email').parents('.input-group');
                                break;
                            case 'tel':
                                message = errors['tel'];
                                elem = $('.tel').parents('.input-group');
                                break;
                            case 'fax':
                                message = errors['fax'];
                                elem = $('.fax').parents('.input-group');
                                break;
                        }

                        if (elem) {
                            elem.append(
                                    '<div class="invalid-feedback" style="display: block !important; margin-left: 100px">' +
                                    message + '</div>')
                                .find('input').addClass('is-invalid');
                        }
                    }
                },
            });

        })
    })
</script>
