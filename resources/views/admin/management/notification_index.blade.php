<div class="row">
    <div class="col-12 d-flex justify-content-between align-items-center mb-2">
        <h6 class="font-weight-bold text-warning">@lang('Notification management')</h6>
        <button class="btn btn-sm btn-primary" data-toggle="modal"
            data-target="#notification-edit-modal">@lang('Register')</button>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12">
        <table id="notification-list-table" class="table table-bordered" cellspacing="0">
            <thead>
                <tr>
                    <th>@lang('Title')</th>
                    <th>@lang('Notiication Content')</th>
                    <th>@lang('Publication start date')</th>
                    <th>@lang('Posting end date')</th>
                    <th>@lang('Management content')</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script>
    $('.notification_content').ckeditor();
    $('.post_start_date, .post_end_date').datepicker({
        format: 'yyyy-mm-dd',
        inline: false,
    }).keydown(function(event) {
        var code = event.keyCode || event.which;
        // If key is not TAB
        var parts = $(this).val().split("/"),
            currentDate = new Date(parts[2], parts[0] - 1, parts[1]);
        switch (code) {
            case 27:
                $(this).datepicker('hide');
                return false;
                break;
            case 113:
                $(this).datepicker('show');
                return false;
                break;
            case 37:
                event.preventDefault();
                event.stopPropagation();
                currentDate.setDate(currentDate.getDate() - 1);
                break;
            case 38:
                event.preventDefault();
                event.stopPropagation();
                currentDate.setDate(currentDate.getDate() - 7);
                break;
            case 39:
                event.preventDefault();
                event.stopPropagation();
                currentDate.setDate(currentDate.getDate() + 1);
                break;
            case 40:
                event.preventDefault();
                event.stopPropagation();
                currentDate.setDate(currentDate.getDate() + 7);
                break;
        };
    });
    var NotificationTable = $('#notification-list-table').DataTable({
        "processing": true,
        "searching": false,
        "lengthChange": false,
        "scrollCollapse": true,
        "pagingType": "full_numbers",
        "bInfo": false,
        "ordering": false,
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
            url: "{{ route('admin.management.notification.list') }}",
            type: 'POST',
            dataSrc: '',
            dataType: "json",
        },
        columns: [{
                data: 'title',
                name: "@lang('Title')"
            },
            {
                data: 'message',
                name: "@lang('Notiication Content')",
            },
            {
                data: 'start_date',
                name: '@lang("Publication start date")',
                render: function(data, type, row) {
                    return changeDateFormat(new Date(data));
                }
            },
            {
                data: 'end_date',
                name: '@lang("Publication start date")',
                render: function(data, type, row) {
                    return changeDateFormat(new Date(data));
                }
            },
            {
                data: null,
                name: "@lang('Management content')",
                class: 'd-flex justify-content-center',
                render: function(data) {
                    return `
                        <a class="btn btn-sm btn-primary update-notificaiton-btn mr-2" data-toggle="modal" data-target="#notification-edit-modal">@lang('Edit')</a>
                        <a class="btn btn-sm btn-danger delete-notificaiton-btn ml-2" data-toggle="modal" data-target="#confirm-modal">@lang('Delete')</a>
                    `;
                }
            },
        ],
        columnDefs: [{
            targets: [1],
            className: 'text-wrap'
        }],
        createdRow: function(row, data, dataIndex) {
            $(row).data('rowInfo', data);
        },
    })

    $("#notification-edit-modal").on('show.bs.modal', function(e) {
        $('#cke_1_top').remove();
        var elemTarget = $(e.relatedTarget);
        if (elemTarget.hasClass('update-notificaiton-btn')) {
            var currentData = elemTarget.parents('tr').data('rowInfo');
            var title = $('.notification_title').val(currentData.title);
            var content = $('.notification_content').val(currentData.message);
            var postStart = $('.post_start_date').val(currentData.start_date);
            var postEnd = $('.post_end_date').val(currentData.end_date);
            $('.notification-edit-btn').data('id', currentData.id);
        }
    });

    $("#notification-edit-modal").on('hidden.bs.modal', function(e) {
        $('.notification_title').val('');
        $('.notification_content').val('');
        $('.post_start_date').val('');
        $('.post_end_date').val('');
    });

    $('.notification-edit-btn').click(function() {

        var thisElem = $(this);
        thisElem.prop('disabled', true);

        $("#notification-edit-modal").find('.is-invalid').removeClass('is-invalid');
        var title = $('.notification_title').val();
        var content = $('.notification_content').val();
        var postStart = $('.post_start_date').val();
        var postEnd = $('.post_end_date').val();

        if (!title || title == null || title == undefined) {
            thisElem.prop('disabled', false);
            toastr.warning('タイトルが未入力です。');
            $('.notification_title').addClass('is-invalid');
            return;
        }

        if (!content || content == null || content == undefined) {
            thisElem.prop('disabled', false);
            toastr.warning('内容が未入力です。');
            $('.notification_content').addClass('is-invalid');
            return ;
        }

        if (!postStart || postStart == null || postStart == undefined) {
            thisElem.prop('disabled', false);
            toastr.warning('内容が未入力です。');
            $('.post_start_date').addClass('is-invalid');
            return ;
        }

        if (!postEnd || postEnd == null || postEnd == undefined) {
            thisElem.prop('disabled', false);
            toastr.warning('内容が未入力です。');
            $('.post_end_date').addClass('is-invalid');
            return ;
        }

        if (postStart && !validateDate(postStart)) {
            thisElem.prop('disabled', false);
            toastr.warning('無効デートです。');
            return;
        }

        if (postEnd && !validateDate(postEnd)) {
            thisElem.prop('disabled', false);
            toastr.warning('無効デートです。')
            return;
        }

        if (postEnd && postStart) {
            var mPostStart = +new Date(postStart);
            var mPostEnd = +new Date(postEnd);
            if (mPostStart > mPostEnd) {
                thisElem.prop('disabled', false);
                toastr.warning('無効デートです。');
                $('.post_start_date').addClass('is-invalid');
                $('.post_end_date').addClass('is-invalid');
                return;
            }
        }

        $.ajax({
            url: "{{ route('admin.management.notification.create') }}",
            method: 'POST',
            data: {
                title: title,
                content: content,
                postStart: postStart,
                postEnd: postEnd,
                id: $(this).data('id')
            },
            success: function(data) {
                thisElem.prop('disabled', false);
                NotificationTable.ajax.reload();
                $("#notification-edit-modal").modal('hide');
            }
        });
    })


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
            url: "{{ route('admin.management.notification.delete') }}",
            method: 'POST',
            data: {
                id: $(this).data('id')
            },
            success: function(data) {
                thisElem.prop('disabled', false);
                $('#confirm-modal').modal('hide');
                NotificationTable.ajax.reload();
            }
        });
    })

</script>
