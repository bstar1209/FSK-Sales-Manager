<div class="row">
    <div class="col-12 d-flex justify-content-between align-items-center mb-2">
        <h6 class="font-weight-bold text-warning">@lang('Shipping list')</h6>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang('Period')</span>
            </div>
            <input type="text" id="search-period" class="form-control">
        </div>
    </div>
    <div class="col-md-2">
        <button class="btn btn-sm btn-primary" id="pdf-generate">@lang('PDF')</button>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-12">
        <table id="stat-table" class="table table-bordered" cellspacing="0" style="width:100%">
            <thead>
                <th>@lang('Number of new customer registrations')</th>
                {{-- <th>過去１年間 (2020/10 - 2021/09)</th> --}}
            </thead>
            <tbody>
                <tr>
                    <th class="text-wrap" style="max-width: 100px !important">@lang('Number of new customer registrations')</th>
                    <td></td>
                </tr>
                <tr>
                    <th class="text-wrap" style="max-width: 100px !important">@lang('Number of active customers')</th>
                    <td></td>
                </tr>
                <tr>
                    <th class="text-wrap" style="max-width: 100px !important">@lang('Total number of registered customers')</th>
                    <td></td>
                </tr>
                <tr>
                    <th class="text-wrap" style="max-width: 100px !important">@lang('Number of registered stocks')</th>
                    <td></td>
                </tr>
                <tr>
                    <th class="text-wrap" style="max-width: 100px !important">@lang('Number of login parts searches')</th>
                    <td></td>
                </tr>
                <tr>
                    <th class="text-wrap" style="max-width: 100px !important">@lang('Number of unlogged-in parts searches')</th>
                    <td></td>
                </tr>
                <tr>
                    <th class="text-wrap" style="max-width: 100px !important">@lang('Number of requests for quotation management')</th>
                    <td></td>
                </tr>
                <tr>
                    <th style="max-width: 100px !important">@lang('Number of orders management')</th>
                    <td></td>
                </tr>
                <tr>
                    <th class="text-wrap" style="max-width: 100px !important">@lang('Sales')</th>
                    <td></td>
                </tr>
                <tr>
                    <th class="text-wrap" style="max-width: 100px !important">@lang('Purchasing')</th>
                    <td></td>
                </tr>
                <tr>
                    <th class="text-wrap" style="max-width: 100px !important">@lang('Profit')</th>
                    <td></td>
                </tr>
                <tr>
                    <th class="text-wrap" style="max-width: 100px !important">@lang('Average profit margin')</th>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row col-12 mt-3">
    <canvas id="myChart" width="100" height="30"></canvas>
</div>

<script>
$(function() {
    var registeredCount = [];
    var newCount = [];
    var activeCount = [];
    var rfqCount = [];
    var orderCount = [];
    var salesCount = [];
    var loginPartsCount = [];
    var unLoginPartsCount = [];
    var totalMoney = [];
    var totalMoneyBuy = [];
    var totalProfit = [];
    var profit = [];
    var statChart = null;

    function drawChartFunction(data) {
        var ctx = document.getElementById('myChart').getContext('2d');
        statChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.years,
                datasets: [{
                    label: "@lang('Number of new customer registrations')",
                    data: newCount,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: "@lang('Number of active customers')",
                    data: activeCount,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: "@lang('Total number of registered customers')",
                    data: registeredCount,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: "@lang('Number of registered stocks')",
                    data: salesCount,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: "@lang('Number of login parts searches')",
                    data: loginPartsCount,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: "@lang('Number of unlogged-in parts searches')",
                    data: unLoginPartsCount,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: "@lang('Number of requests for quotation management')",
                    data: rfqCount,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: "@lang('Number of orders management')",
                    data: orderCount,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: "@lang('Sales')",
                    data: totalMoney,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: "@lang('Purchasing')",
                    data: totalMoneyBuy,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: "@lang('Profit')",
                    data: profit,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: "@lang('Average profit margin')",
                    data: totalProfit,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
            ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function drawStatTable(data) {
        $.each($('#stat-table').find('td'), function(index, item) {
            item.remove();
        });
        $.each($('#stat-table thead').find('th'), function(index, item) {
            if (index != 0)
                item.remove();
        });
        $.each(data.years, function(index, item) {
            $('#stat-table thead tr').append('<th> '+item+' </th>');
            $('#stat-table tbody tr:eq(0)').append('<td class="text-wrap">'+newCount[index]+'</td>');
            $('#stat-table tbody tr:eq(1)').append('<td class="text-wrap">'+activeCount[index]+'</td>');
            $('#stat-table tbody tr:eq(2)').append('<td class="text-wrap">'+registeredCount[index]+'</td>');
            $('#stat-table tbody tr:eq(3)').append('<td class="text-wrap">'+salesCount[index]+'</td>');
            $('#stat-table tbody tr:eq(4)').append('<td class="text-wrap">'+loginPartsCount[index]+'</td>');
            $('#stat-table tbody tr:eq(5)').append('<td class="text-wrap">'+unLoginPartsCount[index]+'</td>');
            $('#stat-table tbody tr:eq(6)').append('<td class="text-wrap">'+rfqCount[index]+'</td>');
            $('#stat-table tbody tr:eq(7)').append('<td class="text-wrap">'+orderCount[index]+'</td>');
            $('#stat-table tbody tr:eq(8)').append('<td class="text-wrap">'+totalMoney[index]+'</td>');
            $('#stat-table tbody tr:eq(9)').append('<td class="text-wrap">'+totalMoneyBuy[index]+'</td>');
            $('#stat-table tbody tr:eq(10)').append('<td class="text-wrap">'+profit[index]+'</td>');
            $('#stat-table tbody tr:eq(11)').append('<td class="text-wrap">'+totalProfit[index]+'</td>');
        });
    }

    function ajaxForDrawingDate(startDate, endDate) {
        $.ajax({
            url: "{{ route('admin.management.get_statistics_periode') }}",
            method: 'POST',
            data: {
                startDate: startDate.toDateString(),
                endDate: endDate.toDateString()
            },
            success: function(data) {
                var parseData = JSON.parse(data);

                registeredCount = [];
                newCount = [];
                activeCount = [];
                rfqCount = [];
                orderCount = [];
                salesCount = [];
                loginPartsCount = [];
                unLoginPartsCount = [];
                totalMoney = [];
                totalMoneyBuy = [];
                totalProfit = [];
                profit = [];

                $.each(parseData.data, function(index, item) {
                    registeredCount.push(item['registered_count'])
                    newCount.push(item['new_count'])
                    activeCount.push(item['active_count'])
                    rfqCount.push(item['rfq_count'])
                    orderCount.push(item['order_count'])
                    salesCount.push(item['sales_count'])
                    loginPartsCount.push(item['login_parts_count'])
                    unLoginPartsCount.push(item['unlogin_parts_count'])
                    totalMoney.push(item['total_money'])
                    totalMoneyBuy.push(item['total_money_buy'])
                    totalProfit.push(item['total_profit'])
                    profit.push(item['profit'])
                })
                if(statChart) {
                    statChart.destroy();
                    statChart = null;
                }
                drawChartFunction(parseData);
                drawStatTable(parseData);
            }
        });
    }


    function checkPeriodFormat(period) {
        var regFull = /^20[0-9]{2}[0-1]{1}[0-9]{1}-20[0-9]{2}[0-1]{1}[0-9]{1}$/;
        if (regFull.test(period))
            return 1;

        var regYear = /^20[0-9]{2}-20[0-9]{2}$/;
        if (regYear.test(period))
            return 2;

        var regShort = /^[0-9]{2}[0-1]{1}[0-9]{1}-[0-9]{2}[0-1]{1}[0-9]{1}$/;
        if (regShort.test(period))
            return 3;

        return 0;
    }

    function getDataForChart(flag=false) {
        var periodData = $('#search-period').val();
        var res = checkPeriodFormat(periodData);
        if (res == 1) {
            var startYear = periodData.substr(0, 4);
            var startMonth = parseInt(periodData.substr(4, 2));
            var endYear = periodData.substr(7, 4);
            var endMonth = parseInt(periodData.substr(11, 2));
            var startDate = new Date(startYear + '-' + startMonth);
            var endDate = new Date(endYear + '-' + endMonth);
        } else if (res == 2) {
            var startDate = new Date(parseInt(periodData.substr(0, 4))+1 + '-01-01');
            var endDate = new Date(periodData.substr(5, 4) + '-12-31');
        } else if (res == 3) {
            var startYear = periodData.substr(0, 2);
            var startMonth = parseInt(periodData.substr(2, 2));
            var endYear = periodData.substr(5, 2);
            var endMonth = parseInt(periodData.substr(7, 2));
            var startDate = new Date('20'+startYear + '-' + startMonth);
            var endDate = new Date('20'+endYear + '-' + endMonth);
        } else {
            toastr.warning('日付形式が正しくありません。');
            return ;
        }
        ajaxForDrawingDate(startDate, endDate);
    }

    $('#search-period').focusout(function() {
        getDataForChart();
    })

    // var staticDate = new Date()
    ajaxForDrawingDate(new Date('2015-01-01'), new Date());

    $('#pdf-generate').click(function() {
        var pdf = new jsPDF();
        pdf.addHTML(document.body,function() {
            var title = $('#search-period').val();
            if(title == '')
                title = '2014-2021';
            pdf.save(title+'.pdf');
        });
    });
});
</script>
