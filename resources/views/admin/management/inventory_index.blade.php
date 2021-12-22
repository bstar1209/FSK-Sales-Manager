<div class="row">
    <h6 class="col-12 font-weight-bold text-warning mb-3">@lang('Inventory list screen')</h6>
</div>
<div class="row" id="search-area">
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">ID</span>
            </div>
            <input type="text" id="inventory-id" class="form-control" autocomplete="off" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Model')</span>
            </div>
            <input type="text" id="inventory-model" class="form-control" autocomplete="off" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Maker')</span>
            </div>
            <input type="text" id="inventory-maker" class="form-control" autocomplete="off" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Classification')</span>
            </div>
            <input type="text" id="inventory-classification" class="form-control" autocomplete="off" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>
    <div class="col-md-2">
        <button class="btn btn-sm btn-primary" id="inventory-clear">@lang('Clear button')</button>
    </div>
</div>

<div class="row mt-3 mb-3">
    <div class="col-md-3">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('File selection')</span>
            </div>
            <input type="text" id="file-selection" class="form-control" placeholder="ファイルが選択されていません.">
            <input type="file" id="inventory-file" class="d-none">
        </div>
    </div>
    <div class="col-md-3">
        <button class="btn btn-sm btn-primary" id="inventory-update-btn" data-toggle="modal" data-target="#inventory-update-confirm">在庫更新</button>
    </div>
</div>

<table class="table table-bordered table-striped w-100" cellspacing="0" id="inventory-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>@lang('Maker')</th>
            <th>@lang('Model')</th>
            <th>@lang('Quantity')</th>
            <th>DC</th>
            <th>@lang('Classification')</th>
            <th>@lang('Update date')</th>
        </tr>
    </thead>
</table>

<div class="modal fade" id="inventory-update-confirm" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 500px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="confirmModalLabel" class="modal-title text-warning">通知</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 inventory-message">
                        在庫更新しますか?
                    </div>
                </div>
                <div class="row inventory-progress d-none">
                    <div class="col-12 progress">
                        <div class="progress-bar" role="progressbar" style="width: 1%" data-number="1" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="update-confirm-btn" class="btn btn-primary btn-sm">はい</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">いいえ</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    $(document).on('keyup', '#inventory-id, #inventory-model, #inventory-maker, #inventory-classification', function() {
        inventoryTable.ajax.reload();
    });

    const inventoryTable = $('#inventory-table').DataTable({
        process: true,
        serverSide: false,
        lengthChange: false,
        searching: false,
        info: false,
        pagingType: "full_numbers",
        language: {
            zeroRecords: "",
            loadingRecords: "",
            processing: "",
            paginate: {
                first: "<< @lang('first')",
                previous: "< @lang('previous')",
                next: "@lang('next') >",
                last: "@lang('last') >>"
            }
        },
        dom: '<"row view-filter"<"col-md-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-md-12"<"d-flex justify-content-center"ip>>>',
        ajax: {
            url: "{{ route('admin.management.inventory.list') }}",
            type: "post",
            dataSrc: '',
            dataType: "json",
            data: function (data) {
                data.id = $('#inventory-id').val();
                data.model = $('#inventory-model').val();
                data.maker = $('#inventory-maker').val();
                data.classification = $('#inventory-classification').val();
            }
        },
        columns: [
            {
                data: 'id',
                name: 'ID'
            },
            {
                data: 'maker',
                name: '@lang("Maker")'
            },
            {
                data: 'katashiki',
                name: '@lang("Model")',
            },
            {
                data: 'qty',
                name: '@lang("Quantity")',
            },
            {
                data: 'dc',
                name: 'DC',
            },
            {
                data: 'kubun',
                name: '@lang("Classification")',
            },
            {
                data: 'updated_at',
                name: '@lang("Update date")',
                render: function(data) {
                    return data.split('T')[0];
                }
            },
        ],
        order: [[1, 'desc']]
    });

    $(document).on('click', '#inventory-clear', function() {
        $('#search-area').find('input').val('');
        inventoryTable.ajax.reload();
    })

    $('#file-selection').click(function() {
        $('#inventory-file').click();
    });

    $('#inventory-file').change(function() {
        $('#file-selection').val($(this).val());
    })

    $('#inventory-update-confirm').on('hidden.bs.modal', function(e) {
        $('.progress-bar').css('width', '1%');
        $('.progress-bar').data('number', 1);
        $('.inventory-message').html('在庫更新しますか?。');
        $('.inventory-progress').addClass('d-none');
    });

    $('#update-confirm-btn').click(function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);

        var fileUrl = $("#file-selection").val();
        var completeFlag = false;
        if(fileUrl == '' || fileUrl == null || fileUrl == undefined || fileUrl.split('.').pop().toLowerCase() != 'csv') {
            toastr.warning('CSVのフォーマットが正しくないです。');
            thisElem.prop('disabled', false);
            return ;
        }

        $('.inventory-message').html('在庫更新中です。 &nbsp; 完了までお待ちしてください。');
        $('.inventory-progress').removeClass('d-none');

        var progressTimer = setInterval( function() {
            var currentStatus = parseInt($('.progress-bar').data('number'));

            if (currentStatus < 96) {
                $('.progress-bar').data('number', currentStatus+3);
                $('.progress-bar').css('width', (currentStatus+3)+'%');
            } else {
                if (completeFlag) {
                    $('.progress-bar').css('width', '100%');
                    clearInterval(progressTimer);
                    $('#inventory-update-confirm').modal('hide');
                    inventoryTable.ajax.reload();
                }
            }
        }, 300);

        var excelName = document.getElementById('inventory-file');
        var uploadFile = new FormData();
        uploadFile.append("file", excelName.files[0]);

        $.ajax({
            url: "{{ route('admin.management.inventory.excel_import') }}",
            contentType: false,
            processData: false,
            data: uploadFile,
            type: 'post',
            cache: false,
            enctype:"multipart/form-data",
            success: function(data) {
                thisElem.prop('disabled', false);
                completeFlag = true;
            },
            error: function() {
                thisElem.prop('disabled', false);
                toastr.warning('在庫更新にエラが発生しました。 再度実施するか。CSVファイルを確認して下さい。');
                inventoryTable.ajax.reload();
                $('#inventory-update-confirm').modal('hide');
            }
        });
    })
})
</script>
