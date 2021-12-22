<div class="row mt-5">
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="font-weight-bold text-warning float-left">@lang('Currency management screen')</h6>
            <button class="btn btn-sm btn-primary float-right" id="btn-create-rate">@lang('Register')</button>
        </div>
        <table class="table table-bordered table-striped" cellspacing="0" id="rate-list-table">
            <thead>
                <tr>
                    <th rowspan="2"></th>
                    <th colspan="6">@lang('Currency rate')</th>
                </tr>
                <tr>
                    <th>@lang('Buy rate')</th>
                    <th>@lang('Update date')</th>
                    <th>@lang('Selling rate')</th>
                    <th>@lang('Update date')</th>
                    <th colspan="2">@lang('Management')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rate_list as $rate)
                <tr @if($loop->first) class="tr-orange selected" @endif>
                    <td>{{ $rate->type_money }}</td>
                    <td>{{ $rate->buy_rate }}</td>
                    <td>{{ $rate->updated_at->format('Y-m-d') }}</td>
                    <td>{{ $rate->sale_rate }}</td>
                    <td>{{ $rate->updated_at->format('Y-m-d') }}</td>
                    <td class="d-flex justify-content-center py-2"><a class="btn btn-primary btn-sm btn-edit-rate">@lang('Edit')</a></td>
                    <td class="d-flex justify-content-center py-2"><a class="btn btn-danger btn-sm btn-delete-rate">@lang('Delete')</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-5 pl-5">
        <h6 class="font-weight-bold text-warning mb-3">@lang('Currency rate history')</h6>
        <table class="table table-bordered table-striped" cellspacing="0" id="rate-log-table">
            <thead>
                <tr>
                    <th rowspan="2"></th>
                    <th colspan="4">@lang('Currency rate')</th>
                </tr>
                <tr>
                    <th>@lang('Buy rate')</th>
                    <th>@lang('Update date')</th>
                    <th>@lang('Selling rate')</th>
                    <th>@lang('Update date')</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script>
commonList = {};
$(function() {
    loadCommonList();
    loadCustomerInfoList();
    let currentNav = '#rate-screen';
    let action = '';
    customerInfo = {};
    let billingAddress = [];
    let deliveryAddress = [];
    let firstUpdate = false;

    let rateLogTable = $('#rate-log-table').DataTable({
        filter: false,
        orderable: false,
        paging: false,
        info: false,
        language:{
            zeroRecords: "テーブル内のデータなし.",
            loadingRecords: "&nbsp;",
            processing: "読み込み中...",
            search: "",
            paginate: {
                first: "<< @lang('first')",
                previous: "< @lang('previous')",
                next: "@lang('next') >",
                last: "@lang('last') >>"
            }
        },
        ajax: {
            url: "{{ route('admin.rate.log') }}",
            type: 'post',
            dataSrc: '',
            data: function(data) {
                data.type_money = $('#rate-list-table tr.selected').find('td').eq(0).text();
            }
        },
        columns: [
            { data: 'type_money' },
            { data: 'buy_rate' },
            {
                data: 'updated_at',
                render: function(data) {
                    return data.split('T')[0];
                }
            },
            { data: 'sale_rate' },
            {
                data: 'updated_at',
                render: function(data) {
                    return data.split('T')[0];
                }
            },
        ],
        language: {
            zeroRecords: "@lang('No data available in table')",
        }
    });

    $('#rate-list-table').on('click', 'tbody tr', function() {
        $('#rate-list-table tr.selected').removeClass('tr-orange selected');
        $(this).addClass('tr-orange selected');
        rateLogTable.ajax.reload();
    });

    $('#btn-create-rate').click(function() {
        action = 'create';
        $('#rate-update-modal').modal('show');
    });

    $('#rate-list-table').on('click', '.btn-edit-rate', function() {
        action = 'update';
        $('#rate-list-table tr.selected').removeClass('tr-orange selected');
        const $selectedRow = $(this).parents('tr').addClass('tr-orange selected');
        $('#input-type-money').val($selectedRow.find('td').eq(0).text());
        $('#input-buy-rate').val($selectedRow.find('td').eq(1).text());
        $('#input-sale-rate').val($selectedRow.find('td').eq(3).text());
        $('#rate-update-modal').modal('show');
    });

    $(document).on('click', '.btn-delete-rate', function() {
        action = 'delete';
        $('#rate-list-table tr.selected').removeClass('tr-orange selected');
        $(this).parents('tr').addClass('tr-orange selected');
        $('#rate-delete-confirm').modal('show');
    });

    $('#rate-update-modal').on('show.bs.modal', function() {
        $('#rate-update-modal .invalid-text').hide();
        if (action === 'create') {
            $(this).find('input').val('');
            $('#input-type-money').removeClass('disabled-input');
            $('#rate-update-modal .modal-title').text("@lang('Currency rate new registration')");
            $('#btn-update-rate').text("@lang('Registration')");
        } else {
            $('#input-type-money').addClass('disabled-input');
            $('#rate-update-modal .modal-title').text("@lang('Currency rate update')");
            $('#btn-update-rate').text("@lang('Update')");
        }
    });

    $('#btn-update-rate').click(function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);

        const typeMoney = $('#input-type-money').val();
        const buyRate = $('#input-buy-rate').val();
        const saleRate = $('#input-sale-rate').val();
        if (action === 'create' && typeMoney.length !== 3) {
            thisElem.prop('disabled', false);
            $('#rate-update-modal .invalid-text').show().text("@lang('Please enter the currency in 3 characters。')");
            return false;
        }
        if (!buyRate || !saleRate) {
            thisElem.prop('disabled', false);
            $('#rate-update-modal .invalid-text').show().text("@lang('Please enter the data。')");
            return false;
        }
        $.ajax({
            url: "{{ route('admin.rate.store') }}",
            type: 'post',
            dataType: 'json',
            data: {
                action,
                type_money: typeMoney,
                buy_rate: buyRate,
                sale_rate: saleRate
            },
            success: function(data) {
                thisElem.prop('disabled', false);
                if (data.result) {
                    $('#rate-update-modal').modal('hide');
                    if (action === 'create') {
                        $('#rate-list-table tbody').prepend(`<tr>
                            <td>${typeMoney}</td>
                            <td>${buyRate}</td>
                            <td>${data.updated_at}</td>
                            <td>${saleRate}</td>
                            <td>${data.updated_at}</td>
                            <td class="d-flex justify-content-center py-2"><a class="btn btn-primary btn-sm btn-edit-rate">@lang('Edit')</a></td>
                            <td class="d-flex justify-content-center py-2"><a class="btn btn-danger btn-sm btn-delete-rate">@lang('Delete')</a></td>
                        </tr>`);
                        toastr.success("@lang('Registered the currency。')");
                    } else {
                        const $selectedRow = $('#rate-list-table tr.selected').first();
                        $selectedRow.find('td').eq(1).text(buyRate);
                        $selectedRow.find('td').eq(3).text(saleRate);
                        $selectedRow.find('td').eq(2).text(data.updated_at);
                        $selectedRow.find('td').eq(4).text(data.updated_at);
                        toastr.success("@lang('The exchange rate has been updated。')");
                    }
                    rateLogTable.ajax.reload();
                } else {
                    $('#rate-update-modal .invalid-text').show().text("@lang('This currency exists。')");
                }
            },
            error: function() {
                thisElem.prop('disabled', false);
                $('#rate-update-modal .invalid-text').show().text("@lang('This currency exists。')");
            }
        });
    });

    $('#btn-rate-delete-confirm').click(function() {
        var thisElem = $(this);
        thisElem.prop('disabled', true);
        $.ajax({
            url: "{{ route('admin.rate.delete') }}",
            type: 'post',
            dataType: 'json',
            data: {
                type_money: $('#rate-list-table tr.selected').find('td').eq(0).text(),
            },
            success: function(data) {
                thisElem.prop('disabled', false);
                if (data.result) {
                    $('#rate-list-table tr.selected').remove();
                    $('#rate-list-table tbody tr:first').addClass('tr-orange selected');
                    rateLogTable.ajax.reload();
                    toastr.success("@lang('Delete successed')");
                } else {
                    toastr.error("@lang('Delete failed')");
                }
                $('#rate-delete-confirm').modal('hide');
            },
            error: function() {
                thisElem.prop('disabled', false);
                $('#rate-delete-confirm').modal('hide');
                toastr.error("@lang('Delete failed')");
            }
        });
    });
});
</script>
