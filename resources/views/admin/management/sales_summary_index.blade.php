<div class="row">
    <div class="col-2 input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate" style="min-width: 60px !important">期間</span>
        </div>
        <input type="text" id="search-date" class="form-control">
    </div>
</div>
<div class="row mt-2" style="position: relative;">
    <div class="col-12 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-warning" style="min-width: 220px">得意先別</h6>
        <div class="d-flex">
            <button class="btn btn-primary btn-sm text-truncate w-100 btn-min-width-120 t-pdf-btn" style="margin-right: 20px" data-type="customer">売り上げ</button>
            <button class="btn btn-primary btn-sm text-truncate w-100 btn-min-width-120 t-pdf-detail-btn" data-type="customer">明細売上</button>
        </div>
    </div>
    <div class="col-12 table-responsive" style="margin-top: 10px;">
        <table id="by-customer-table" class="table table-bordered table-striped table-sm mb-2" cellspacing="0">
            <thead>
                <tr>
                    <th>@lang('Customer name')</th>
                    <th>@lang('Sales total')</th>
                    <th>@lang('Sales total (tax included)')</th>
                    <th>@lang('Purchase total')</th>
                    <th>@lang('Purchase total (tax included)')</th>
                    <th>@lang('Profit margin')</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th>合計</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="row mt-5" style="position: relative;">
    <div class="col-12 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-warning" style="min-width: 220px">明細売上</h6>
        <div class="d-flex">
            <button class="btn btn-primary btn-sm text-truncate w-100 btn-min-width-120 t-pdf-btn" style="margin-right: 20px" data-type="supplier">仕入</button>
            <button class="btn btn-primary btn-sm text-truncate w-100 btn-min-width-120 t-pdf-detail-btn" data-type="supplier">明細仕入</button>
        </div>
    </div>
    <div class="col-12 table-responsive" style="margin-top: 10px;">
        <table id="by-supplier-table" class="table table-bordered table-striped table-sm mb-2" cellspacing="0">
            <thead>
                <tr>
                    <th>@lang('Supplier name')</th>
                    <th>@lang('Sales total')</th>
                    <th>@lang('Sales total (tax included)')</th>
                    <th>@lang('Purchase total')</th>
                    <th>@lang('Purchase total (tax included)')</th>
                    <th>@lang('Profit margin')</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th>合計</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script>
$(function() {
    var storageUrl = "/storage/pdf/";

    $(document).on('focusout', '#search-date', function() {
        var date = $('#search-date').val();
    })

    $(document).on('keypress', 'input#search-date', function(e) {
        useList = '0123456789-';
        if (useList.search(e.key) == -1) {
            return false;
        }
    })

    $(document).on('click', '.t-pdf-btn', function() {
        var type = $(this).data("type");
        var pdfData = null;
        if (type == "customer")
            pdfData = $('#by-customer-table').data('currentData');
        else
            pdfData = $('#by-supplier-table').data('currentData');

        $.ajax({
            url: '{{ route("admin.management.summary.pdf") }}',
            type: 'post',
            dataType: 'json',
            data: {
                pdf_data: pdfData,
                period: $('#search-date').val(),
                type: type
            },
            success: function(data) {
                var pdfUrl = storageUrl+data;
                window.open(pdfUrl, "_blank");
            }
        });
    });

    $(document).on('click', '.t-pdf-detail-btn', function() {
        var type = $(this).data("type");
        var pdfData = null;
        if (type == "customer") {
            pdfData = $('#by-customer-table').data('currentData');
            dateArr = convertDateFormat(true);
        } else {
            pdfData = $('#by-supplier-table').data('currentData');
            dateArr = convertDateFormat(false);
        }

        $.ajax({
            url: '{{ route("admin.management.summary.pdf") }}',
            type: 'post',
            dataType: 'json',
            data: {
                pdf_data: pdfData,
                period: $('#search-date').val(),
                type: type,
                flag: 'detail',
                startDate: dateArr[0].toDateString(),
                endDate: dateArr[1].toDateString(),
            },
            success: function(data) {
                var pdfUrl = storageUrl+data;
                window.open(pdfUrl, "_blank");
            }
        });
    })

    function checkPeriodValidation(period) {
        var regFull = /^[0-9]{2}[0-1]{1}[0-9]{1}-[0-9]{2}[0-1]{1}[0-9]{1}$/;
        if (regFull.test(period))
            return 1;

        var regYear = /^[0-9]{2}[0-1]{1}[0-9]{1}$/;
        if (regYear.test(period))
            return 2;

        return 0;
    }

    function convertDateFormat(flag) {
        var periodNum = $('#search-date').val();
        var res = checkPeriodValidation(periodNum);
        if (res == 2) {
            var startDate = new Date('20'+periodNum.substr(0, 2)+'-'+periodNum.substr(2, 2)+'-01');
            var endDate = new Date('20'+periodNum.substr(0, 2)+'-'+periodNum.substr(2, 2)+'-31');
        } else if (res == 1) {
            var startYear = '20'+periodNum.substr(0, 2);
            var startMonth = parseInt(periodNum.substr(2, 2));
            var endYear = '20'+periodNum.substr(5, 2);
            var endMonth = parseInt(periodNum.substr(7, 2));
            var startDate = new Date(startYear + '-' + startMonth);
            var endDate = new Date(endYear + '-' + endMonth);
        } else {
            if(periodNum.length > 0 && flag)
                toastr.warning('日付形式が正しくありません。');
            return [new Date(), new Date()];
        }
        return [startDate, endDate];
    }

    var byCustomerTable = $('#by-customer-table').DataTable({
        "searching": false,
        "lengthChange": false,
        "paging": true,
        "bInfo" : false,
        "ordering": false,
        "autoWidth": false,
        "pagingType": "full_numbers",
        "language": {
            "zeroRecords": "",
            "loadingRecords": "",
            "processing": "",
            "paginate": {
                "first": "<< @lang('first')",
                "previous": "< @lang('previous')",
                "next": "@lang('next') >",
                "last": "@lang('last') >>"
            }
        },
        ajax: {
            url: managementSalseSummaryListUrl,
            dataSrc: '',
            type: 'POST',
            data: function(data) {
                var dateArr = convertDateFormat(true);
                data.type = 'customer';
                data.startDate = dateArr[0].toDateString();
                data.endDate = dateArr[1].toDateString();
            },
            complete: function(data) {
                var ajaxData = data.responseJSON;
                var total_sale = 0;
                var total_sale_tax = 0;
                var total_buy = 0;
                var total_buy_tax = 0;
                var average_rate = 0;
                var total_rate = 0;

                $.each(ajaxData, function(index, item) {
                    total_sale += parseInt(item.total_money_sale);
                    total_sale_tax += parseInt(item.total_money_buy_tax);
                    total_buy += parseInt(item.total_money_buy);
                    total_buy_tax += parseInt(item.total_money_buy_tax);
                    total_rate += parseInt(item.total_money_buy_tax);
                });

                $('#by-customer-table').data('currentData', ajaxData);

                $('#by-customer-table tfoot th:eq(1)').text(total_sale);
                $('#by-customer-table tfoot th:eq(2)').text(total_sale_tax);
                $('#by-customer-table tfoot th:eq(3)').text(total_buy);
                $('#by-customer-table tfoot th:eq(4)').text(total_buy_tax);
                if (ajaxData)
                    $('#by-customer-table tfoot th:eq(5)').text(Math.round(total_rate/ajaxData.length, 2));
                else
                    $('#by-customer-table tfoot th:eq(5)').text(0);
            }
        },
        'createdRow': function(row, data, dataIndex) {
            $(row).data('id', data.id);
        },
        columns: [
            { data: 'cus_name', name: "@lang('Customer name')" },
            { data: 'total_money_sale', name: "@lang('Sales total')" },
            { data: 'total_money_sale_tax', name: "@lang('Sales total (tax included)')" },
            { data: 'total_money_buy', name: "@lang('Purchase total')" },
            { data: 'total_money_buy_tax', name: "@lang('Purchase total (tax included)')" },
            { data: 'rate_profit', name: "@lang('Profit margin')" },
        ]
    });

    var bySupplierTable = $('#by-supplier-table').DataTable({
        "searching": false,
        "lengthChange": false,
        "paging": true,
        "bInfo" : false,
        "ordering": false,
        "autoWidth": false,
        "pagingType": "full_numbers",
        "language": {
            "zeroRecords": "",
            "loadingRecords": "",
            "processing": "",
            "paginate": {
                "first": "<< @lang('first')",
                "previous": "< @lang('previous')",
                "next": "@lang('next') >",
                "last": "@lang('last') >>"
            }
        },
        "ajax": {
            url: managementSalseSummaryListUrl,
            dataSrc: '',
            type: 'POST',
            data: function(data) {
                var dateArr = convertDateFormat(false);
                data.type = 'supplier';
                data.startDate = dateArr[0].toDateString();
                data.endDate = dateArr[1].toDateString();
            },
            complete: function(data) {
                var ajaxData = data.responseJSON;
                var total_sale = 0;
                var total_sale_tax = 0;
                var total_buy = 0;
                var total_buy_tax = 0;
                var average_rate = 0;
                var total_rate = 0;
                $.each(ajaxData, function(index, item) {
                    total_sale += parseInt(item.total_money_sale);
                    total_sale_tax += parseInt(item.total_money_buy_tax);
                    total_buy += parseInt(item.total_money_buy);
                    total_buy_tax += parseInt(item.total_money_buy_tax);
                    total_rate += parseInt(item.total_money_buy_tax);
                });

                $('#by-supplier-table').data('currentData', ajaxData);

                $('#by-supplier-table tfoot th:eq(1)').text(total_sale);
                $('#by-supplier-table tfoot th:eq(2)').text(total_sale_tax);
                $('#by-supplier-table tfoot th:eq(3)').text(total_buy);
                $('#by-supplier-table tfoot th:eq(4)').text(total_buy_tax);
                if (ajaxData)
                    $('#by-supplier-table tfoot th:eq(5)').text(Math.round(total_rate/ajaxData.length, 2));
                else
                    $('#by-supplier-table tfoot th:eq(5)').text(Math.round(0, 2));
            }
        },
        'createdRow': function(row, data, dataIndex) {
            $(row).data('id', data.id);
        },
        "columns": [
            { data: 'sup_name', name: "@lang('Supplier name')" },
            { data: 'total_money_sale', name: "@lang('Sales total')" },
            { data: 'total_money_sale_tax', name: "@lang('Sales total (tax included)')" },
            { data: 'total_money_buy', name: "@lang('Purchase total')" },
            { data: 'total_money_buy_tax', name: "@lang('Purchase total (tax included)')" },
            { data: 'rate_profit', name: "@lang('Profit margin')" },
        ]
    });

    $('#search-date').focusout(function() {
        byCustomerTable.ajax.reload();
        bySupplierTable.ajax.reload();
    })
});
</script>
