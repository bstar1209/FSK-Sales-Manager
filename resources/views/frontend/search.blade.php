@extends('layouts.frontend.page')

@section('title', '検索結果画面')

@section('main-container')
    <div class="row result mt-5">
        <div class="col-3">
            <strong>@lang('Number of search results') : </strong><span class="result-count">0</span>
        </div>
        <p class="col-9 result-status"></p>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <table id="search-table" class="table table-bordered table-striped table-sm" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 15% !important">@lang('Model number')</th>
                        <th style="width: 10% !important">@lang('D/C')</th>
                        <th style="width: 15% !important">@lang('Maker')</th>
                        <th style="width: 10% !important">@lang('Stock quantity')</th>
                        <th style="width: 15% !important">@lang('Hope number')</th> 
                        <th style="width: 15% !important">@lang('Area')</th>
                        <th style="width: 20% !important;">@lang('Estimates')</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(function() {

            @guest
                $('#login-card').addClass('d-none');
                $('#before-login').removeClass('d-none');
            @endguest

            @auth customerId = "{{ auth()->user()->customer->id }}"; @endauth
            var searchKey = localStorage.getItem('searchKey');
            var searchType = localStorage.getItem('searchType');

            if (searchKey && searchKey.length < 2)
                $('#search-btn').prop('disabled', true);
            else
                $('#search-btn').prop('disabled', false);

            $('#model-number-search').val(searchKey);
            $('#search-type').val(searchType);

            var dataLength = 0;

            var searchTable = $('#search-table').DataTable({
                "processing": true,
                "searching": false,
                "paging": true,
                "bInfo": false,
                "autoWidth": true,
                "pagingType": "full_numbers",
                "lengthChange": false,
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
                "dom": '<"row view-filter"<"col-md-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-md-12"<"d-flex justify-content-center"ip>>>',
                "ajax": {
                    url: "{{ route('frontend.model_search') }}",
                    type: 'POST',
                    dataSrc: '',
                    data: function(data) {
                        data.model = localStorage.getItem('searchKey');
                        data.type = localStorage.getItem('searchType');
                    },
                    complete: function(data, callback, settings) {
                        dataLength = data.responseJSON.length;
                        $('.result-count').text(dataLength);
                        if (dataLength == 0) {
                            $('.result-status').html(
                                `お探しの部品は弊社データベースでは見つかりませんでしたが、</br> 以下からメーカー名と希望数を入力してお見積もり依頼できます。`);
                            $('#search-table tbody').find('.dataTables_empty').remove();
                            $('#search-table tbody').append(
                                `
                        <tr id="edit-part-tr">
                            <td style="width: 15% !important"><input type="text" class="form-control form-control-sm model-num" value="` +
                                searchKey.trim() + `"></td>
                            <td style="width: 10% !important"></td>
                            <td style="width: 15% !important"><input type="text" class="form-control form-control-sm maker"></td>
                            <td style="width: 10% !important"></td>
                            <td style="width: 15% !important"><input type="number" min="0" class="form-control form-control-sm qty"></td>
                            <td style="width: 15% !important"></td>
                            <td style="width: 20% !important"><a class="btn btn-primary btn-sm add-cart">@lang('Estimates')</a></td>
                        </tr>
                    `);
                        } else {
                            $('.result-status').html(`提携先在庫情報　リアルタイム更新ではないので売切れの場合もございます。`);
                        }
                    },
                    dataType: "json",
                },
                columns: [{
                        data: 'katashiki',
                        name: "@lang('Model number')",
                        render: function(data) {
                            var key = localStorage.getItem('searchKey');
                            var styleData = data.toUpperCase().replace(key.toUpperCase(),
                                '<span class="text-danger">' + key.toUpperCase() + '</span>');
                            return styleData;
                        }
                    },
                    {
                        data: 'dc',
                        name: "@lang('D/C')"
                    },
                    {
                        data: 'maker',
                        name: "@lang('Maker')"
                    },
                    {
                        data: null,
                        name: "@lang('Stock quantity')",
                        render: function() {
                            return 0;
                        }
                    },
                    {
                        data: null,
                        name: "@lang('Hope number')",
                        render: function(data) {
                            return '<input type="number" class="form-control form-control-sm qty">';
                        }
                    },
                    {
                        data: 'kubun2',
                        name: "@lang('Area')"
                    },
                    {
                        data: null,
                        name: "@lang('Estimates')",
                        render: function(data) {
                            return `<a class="btn btn-md btn-primary btn-sm add-cart">@lang('Estimates')</a>`;
                        }
                    },
                ],
                'createdRow': function(row, data, dataIndex) {
                    $(row).data('rowInfo', data);
                    var trClassName = data.katashiki;
                    $(row).addClass(trClassName.trim());
                },
                ordering: false,
            })

            $(document).on('click', '.add-cart', function() {
                var thisElem = $(this);
                thisElem.prop('disabled', true);
                $('tr').find('.is-invalid').removeClass('is-invalid');
                if ($(this).parents('#edit-part-tr').length > 0) {
                    var trData = $(this).parents('tr');
                    var modelNum = $('#edit-part-tr').find('.model-num').val().toString().normalize('NFKC');
                    var maker = $('#edit-part-tr').find('.maker').val();
                    var qty = parseInt($('#edit-part-tr').find('.qty').val());

                    if (maker.toString().trim() == '') {
                        toastr.warning('メーカー名を入力してください。');
                        $('#edit-part-tr').find('.maker').addClass('is-invalid');
                        thisElem.prop('disabled', false);
                        return;
                    }

                    if (!isNaN(modelNum) || modelNum.toString().trim() == '') {
                        toastr.warning('型番名を入力してください。');
                        $('#edit-part-tr').find('.model-num').addClass('is-invalid');
                        thisElem.prop('disabled', false);
                        return;
                    }
                } else {
                    var trData = $(this).parents('tr');
                    var modelNum = trData.find('td:eq(0)').text().toString().normalize('NFKC');
                    var maker = trData.find('td:eq(2)').text();
                    var qty = parseInt(trData.find('.qty').val());
                }

                if (qty <= 0 || isNaN(qty) == true || qty.toString().trim().length == 0) {
                    toastr.warning('希望数の入力が必要です。');
                    trData.find('.qty').addClass('is-invalid');
                    thisElem.prop('disabled', false);
                    return;
                }

                // if(loginStatus) {
                $.ajax({
                    url: createCartUrl,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        // modelNumber: modelNum.toLowerCase(),
                        modelNumber: modelNum,
                        qty: qty,
                        maker: maker,
                        customerId: customerId
                    },
                    success: function(data) {
                        thisElem.prop('disabled', false);
                        var trClassName = data.part.katashiki;
                        var oldCart = $('.carts-list-tbody').find('.' + trClassName);

                        $('#quote-request-set-btn').removeAttr("disabled");
                        
                        if (oldCart.length > 0) {
                            var oldFirstCard = oldCart.first();
                            var oldQty = parseInt(oldFirstCard.find('td:eq(1) input').val());
                            var rowData = oldFirstCard.data('rowinfo');

                            oldCart.each(function(index, elem) {
                                $(elem).data('rowinfo', null);
                                $(elem).data('rowinfo', data);
                            });

                            oldCart.find('td:eq(1) input').val(oldQty + parseInt(qty));
                        } else {
                            $('.carts-list-tbody').append(`
                        <tr class="` + trClassName + `" data-rowinfo='` + JSON.stringify(data) + `'><td>` + data.part
                                .katashiki +
                                `</td><td><input class="form-control form-control-sm part-qty-input" value="` +
                                data.qty +
                                `"></td><td><a class="delete-cart" href="#"><img src="` +
                                trashImg + `"></a></td></tr>
                    `);
                        }

                        $('.quote-request-btn').prop('disabled', false);
                    }
                });
                // }
            })

            $(document).on('click', '.go-login-btn', function() {
                $('#before-login').addClass('d-none');
                $('#login-card').removeClass('d-none');
            })
        })
    </script>
@endsection
