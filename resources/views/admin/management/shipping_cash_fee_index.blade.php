<div class="row">
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="font-weight-bold text-warning">@lang('Shipping list')</h6>
            <button class="btn btn-sm btn-primary" id="shipping-add-btn" data-toggle="modal" data-target="#shipping-edit-modal">@lang('Register')</button>
        </div>
        <table id="shipping-list-table" class="table table-bordered" cellspacing="0">
            <thead>
                <tr>
                    <th>@lang('Area')</th>
                    <th>@lang('Shipping (excluding tax)')</th>
                    <th>@lang('Update date')</th>
                    <th>@lang('Management content')</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="font-weight-bold text-warning">@lang('Cash on delivery setting screen')</h6>
            <button class="btn btn-sm btn-primary" id="cash-on-delivery-btn" data-toggle="modal" data-target="#cash-on-delivery-edit-modal">@lang('Register')</button>
        </div>
        <table id="cash-on-delivery-table" class="table table-bordered table-striped" cellspacing="0">
            <thead>
                <tr>
                    <th>@lang('Applicable amount range')</th>
                    <th>@lang('Fee (excluding tax)')</th>
                    <th>@lang('Update date')</th>
                    <th>@lang('Management content')</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script>
$(function() {
    var shippingListTable = $('#shipping-list-table').DataTable({
        "processing": true,
        "searching": false,
        "lengthChange": false,
        "pagingType": "full_numbers",
        "paging": true,
        "bInfo" : false,
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
            url: "{{ route('admin.management.shipping.list') }}",
            type: 'POST',
            dataSrc: '',
            dataType: "json",
        },
        columns: [
            {
                data: 'area',
                name: '@lang("Area")'
            },
            {
                data: 'fee',
                name: '@lang("Shipping (excluding tax)")'
            },
            {
                data: 'updated_at',
                name: '@lang("Update date")',
                render: function(data, type, row) {
                    return changeDateFormat(new Date(data));
                }
            },
            {
                data: null,
                name: "@lang('Management content')",
                render: function(data) {
                    return `
                        <a class="btn btn-sm btn-primary update-shipping-btn mr-2" data-toggle="modal" data-target="#shipping-edit-modal">@lang('Edit')</a>
                        <a class="btn btn-sm btn-danger delete-shipping-btn ml-2" data-toggle="modal" data-target="#confirm-modal">@lang('Delete')</a>
                    `;
                }
            },
        ],
        'createdRow': function(row, data, dataIndex) {
            $(row).data('rowInfo', data);
            $(row).find('td:eq(3)').addClass('d-flex justify-content-center');
        },
    })

    var cashOnDeliveryTable = $('#cash-on-delivery-table').DataTable({
        "processing": true,
        "searching": false,
        "lengthChange": false,
        "pagingType": "full_numbers",
        "paging": true,
        "bInfo" : false,
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
            url: "{{ route('admin.management.daibiki.list') }}",
            type: 'POST',
            dataSrc: '',
            dataType: "json",
        },
        columns: [
            {
                data: 'information',
                name: '@lang("Applicable amount range")'
            },
            {
                data: 'fee',
                name: 'Fee (excluding tax)'
            },
            {
                data: 'updated_at',
                name: '@lang("Update date")',
                render: function(data, row) {
                    return changeDateFormat(new Date(data));
                }
            },
            {
                data: null,
                name: "@lang('Management content')",
                render: function(data) {
                    return `
                        <a class="btn btn-sm btn-primary update-cash-btn mr-2" data-toggle="modal" data-target="#cash-on-delivery-edit-modal">@lang('Edit')</a>
                        <a class="btn btn-sm btn-danger delete-cash-btn ml-2" data-toggle="modal" data-target="#confirm-modal">@lang('Delete')</a>
                    `;
                }
            },
        ],
        'createdRow': function(row, data, dataIndex) {
            $(row).data('rowInfo', data);
            $(row).find('td:eq(3)').addClass('d-flex justify-content-center');
        },
    })

    $('#pre-list-input, .shipping-region').autoComplete({
        resolver: 'custom',
        events: {
            search: function (qry, callback) {
                callback(listOfPrefectures.filter(function(item) {
                    const matcher = new RegExp('^' + qry, 'i');
                    return matcher.test(item.id)
                        || matcher.test(item.name)
                }));
            }
        },
        formatResult: function (item) {
            return {
                value: item.id,
                text: item.id,
                html: [
                    `${item.name}`
                ]
            };
        },
        noResultsText: '',
        minLength: 1
    })

    $('#pre-list-input').on('autocomplete.select', function(evt, item) {
        var cloneDiv = $('.preBtnModel:eq(0)').clone().removeClass('preBtnModel d-none');
        cloneDiv.find('.pre-name').text(item.name);

        var statusFlag = false;
        $.each($('.pre-list-div').find('.pre-name'), function(index, preItem) {
            if($(preItem).text() == item.name)
                statusFlag = true;
        })

        if (!statusFlag)
            $('.pre-list-div').append(cloneDiv);

        $('#pre-list-input').val('');
    });

    $(document).on('click', '.pre-btn span.badge', function() {
        $(this).parents('button').remove();
    })

    $('#shipping-edit-btn').click(function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);
        $('#shipping-edit-table').find('.is-invalid').removeClass('is-invalid');
        var preList = [];
        $.each($('.pre-list-div').find('.pre-name'), function(index, item) {
            preList.push($(item).text());
        })

        var region = $('#shipping-edit-table .shipping-region').val();
        var fee = $('#shipping-edit-table .shipping-fee').val();

        if (!region || region == undefined) {
            thisElem.prop('disabled', false);
            $('#shipping-edit-table .shipping-region').addClass('is-invalid');
            toastr.warning('データを入力してください。');
            return;
        }

        var regionFlag = false;
        $.each(listOfPrefectures, function(index, item) {
            if (item.id == region)
                regionFlag = true;
        })

        if(!regionFlag) {
            thisElem.prop('disabled', false);
            $('#shipping-edit-table .shipping-region').addClass('is-invalid');
            toastr.warning('エラーが発生しました。');
            return;
        }

        if (!fee || fee == undefined) {
            thisElem.prop('disabled', false);
            $('#shipping-edit-table .shipping-fee').addClass('is-invalid');
            toastr.warning('データを入力してください。');
            return;
        }

        if (!preList.join(',') || preList.join(',') == undefined) {
            thisElem.prop('disabled', false);
            $('#shipping-edit-table #pre-list-input').addClass('is-invalid');
            toastr.warning('データを入力してください。');
            return;
        }

        var ajaxData = {
            id: $(this).data('id'),
            region: region,
            fee: fee,
            moreInfo: preList.join(',')
        };

        $.ajax({
            url: "{{ route('admin.management.shipping.register') }}",
            method: 'POST',
            data: ajaxData,
            success: function(data) {
                thisElem.prop('disabled', false);
                var jsonData = JSON.parse(data);
                // shippingListTable.row.add(jsonData).draw(true);
                shippingListTable.ajax.reload();
                toastr.success('送料の登録を完了しました。');
                $('#shipping-edit-modal').modal('hide');
            }
        });
    })

    $("#shipping-edit-modal").on('show.bs.modal', function(e) {
        $('.pre-list-div').empty();
        $('#shipping-edit-table .shipping-region').val('');
        $('#shipping-edit-table .shipping-fee').val(0);
        $('#shipping-edit-table').find('.is-invalid').removeClass('is-invalid');

        var elemTarget = $(e.relatedTarget);

        if (elemTarget.hasClass('update-shipping-btn')) {
            var currentData = elemTarget.parents('tr').data('rowInfo');
            $('#shipping-edit-btn').data('id', currentData.id);
            var region = $('#shipping-edit-table .shipping-region').val(currentData.area);
            var fee = $('#shipping-edit-table .shipping-fee').val(currentData.fee);
            var moreInfo = currentData.more_information.split(',');
            $.each(moreInfo, function(index, item) {
                var cloneDiv = $('.preBtnModel').first().clone().removeClass('preBtnModel d-none');
                cloneDiv.find('.pre-name').text(item);
                $('.pre-list-div').append(cloneDiv);
            })
        }
    });

    $("#confirm-modal").on('show.bs.modal', function(e) {
        var elemTarget = $(e.relatedTarget).parents('tr').data('rowInfo');
        $(this).data('id', elemTarget.id);
        var elemTarget = $(e.relatedTarget);
        if (elemTarget.hasClass('delete-cash-btn'))
            $(this).data('type', 'cash');
        else
            $(this).data('type', 'ship');
    });

    $(document).on('click', '#confirm-btn', function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);
        var type = $(this).data('type');
        var url = '';
        if (type = 'cash')
            url = "{{ route('admin.management.cash.delete') }}"
        else
            url = "{{ route('admin.management.shipping.delete') }}";
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                id: $("#confirm-modal").data('id')
            },
            success: function(data) {
                thisElem.prop('disabled', false);
                if (data == 'success') {
                    if (type == 'cash')
                        cashOnDeliveryTable.ajax.reload();
                    else
                        shippingListTable.ajax.reload();
                    toastr.success('送料の登録を完了しました。');
                }

                $('#confirm-modal').modal('hide');
            }
        });
    })

    $(document).on('click', '#confirm-cancel', function() {
        $("#confirm-modal").modal('hide');
    })

    $('#cash-on-delivery-edit-modal').on('hidden.bs.modal', function(e) {
        $('#cash-on-delivery-edit-modal').find('input').val('');
    })

    $('#cash-on-delivery-edit-modal').on('show.bs.modal', function(e) {
        $('.pre-list-div').empty();
        $('#shipping-edit-table .shipping-region').val('');
        $('#shipping-edit-table .shipping-fee').val(0);
        $('#cash-on-delivery-edit-modal').find('.is-invalid').removeClass('is-invalid');

        var elemTarget = $(e.relatedTarget);

        if (elemTarget.hasClass('update-cash-btn')) {
            var currentData = elemTarget.parents('tr').data('rowInfo');
            $('#cash-on-delivery-edit-modal .fee-min').val(currentData.min);
            $('#cash-on-delivery-edit-modal .fee-max').val(currentData.max);
            $('#cash-on-delivery-edit-modal .fee').val(currentData.fee);
            $('#cash-on-delivery-edit-modal .fee-information').val(currentData.information);
            $('#cash-edit-btn').data('id', currentData.id);
        }
    })

    $('#cash-edit-btn').click(function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);
        var feeMin = $('#cash-on-delivery-edit-modal .fee-min').val();
        var feeMax = $('#cash-on-delivery-edit-modal .fee-max').val();
        var fee = $('#cash-on-delivery-edit-modal .fee').val();
        var feeInfo = $('#cash-on-delivery-edit-modal .fee-information').val();

        if (!feeMin || feeMin == undefined) {
            thisElem.prop('disabled', false);
            $('#cash-on-delivery-edit-modal .fee-min').addClass('is-invalid');
            toastr.warning('データを入力してください。');
            return;
        }

        if (!feeMax || feeMax == undefined) {
            thisElem.prop('disabled', false);
            $('#cash-on-delivery-edit-modal .fee-max').addClass('is-invalid');
            toastr.warning('データを入力してください。');
            return;
        }

        if (!fee || fee == undefined) {
            thisElem.prop('disabled', false);
            $('#cash-on-delivery-edit-modal .fee').addClass('is-invalid');
            toastr.warning('データを入力してください。');
            return;
        }

        if (!feeInfo || feeInfo == undefined) {
            thisElem.prop('disabled', false);
            $('#cash-on-delivery-edit-modal .fee-information').addClass('is-invalid');
            toastr.warning('データを入力してください。');
            return;
        }

        if(parseInt(feeMin) > parseInt(feeMax)) {
            thisElem.prop('disabled', false);
            $('#cash-on-delivery-edit-modal .fee-max').addClass('is-invalid');
            $('#cash-on-delivery-edit-modal .fee-min').addClass('is-invalid');
            toastr.warning('最高金額は最低金額以上の値を入力してください。');
            return;
        }

        var ajaxData = {
            id: $(this).data('id'),
            max: feeMax,
            min: feeMin,
            fee: fee,
            info: feeInfo
        };

        $.ajax({
            url: "{{ route('admin.management.daibiki.register') }}",
            method: 'POST',
            data: ajaxData,
            success: function(data) {
                thisElem.prop('disabled', false);
                if (data == 'success') {
                    cashOnDeliveryTable.ajax.reload();
                    toastr.success('代引き手数料の登録を完了しました。');
                    $('#cash-on-delivery-edit-modal').modal('hide');
                }
            }
        });
    })
});
</script>
