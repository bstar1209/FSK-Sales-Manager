@inject('tax', 'App\Models\Tax')

@php
$tax_info = $tax->latest()->first();
@endphp

<link href="{{ asset('vendor/steps/jquery-steps.css') }}" rel="stylesheet">
<div id="purchase-steps" class="step-app mt-5">
    <ul class="step-steps">
        <li data-step-target="step1">Step 1</li>
        <li data-step-target="step2">Step 2</li>
        <li data-step-target="step3">Step 3</li>
    </ul>
    <div class="step-content">
        <div class="step-tab-panel" data-step="step1">
            <h5 class="text-center mt-5">請求先住所と納品先住所を選択してください.</h5>
            <hr style="border: 1px solid">
            <div class="row">
                @include('frontend.partials.address', ['index' => 1, 'type' => 0, 'status' => true])
                @include('frontend.partials.address', ['index' => 2, 'type' => 0, 'status' => true])
                @include('frontend.partials.address', ['index' => 1, 'type' => 1, 'status' => true])
                @include('frontend.partials.address', ['index' => 2, 'type' => 1, 'status' => true])
            </div>
            <hr style="border: 1px solid">,
            <div class="row">
                @include('frontend.partials.address', ['index' => 3, 'type' => 0, 'status' => true])
                @include('frontend.partials.address', ['index' => 4, 'type' => 0, 'status' => true])
                @include('frontend.partials.address', ['index' => 3, 'type' => 1, 'status' => true])
                @include('frontend.partials.address', ['index' => 4, 'type' => 1, 'status' => true])
            </div>
        </div>
        <div class="step-tab-panel" data-step="step2">
            @include('frontend.partials.payment_term')
        </div>
        <div class="step-tab-panel" data-step="step3">
            <h5 class="text-center mt-5">注文内容に間違いがないかご確認ください.</h5>
            <hr style="border: 1px solid">

            <div class="row">
                <div class="col-3">
                    <h6>@lang('Billing Address')</h6>
                    <hr>
                    <div class="address1_info d-flex flex-column">
                    </div>
                </div>
                <div class="col-3">
                    <h6>@lang('Delivery Address')</h6>
                    <hr>
                    <div class="address2_info d-flex flex-column">
                    </div>
                </div>
                <div class="col-3">
                    <h6>@lang('Method of payment')</h6>
                    <hr>
                    <div class="payment-method"></div>
                </div>
                <div class="col-3">
                    <h6>@lang('Desired delivery date')</h6>
                    <hr>
                    <div class="desired-date"></div>
                </div>
            </div>
            <hr style="border: 1px solid">
            <table class="table table-bordered table-striped table-sm mt-2" id="for-order-table" cellspacing="0"
                tabindex="0">
                <thead>
                    <tr>
                        <th>型番</th>
                        <th>メーカー</th>
                        <th>注文数</th>
                        <th>単価</th>
                        <th>DC</th>
                        <th>Rohsｽﾃｰﾀｽ</th>
                        <th>めやす納期 </th>
                        <th>注文番号</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <div class="row" id="in-total-price">
                <div class="offset-8 col-4">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate" style="width:120px !important">商品代合計</span>
                        </div>
                        <input type="text" class="form-control" disabled>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate"
                                style="width:120px !important">@lang('Shipping')</span>
                        </div>
                        <input type="text" class="form-control" disabled>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate"
                                style="width:120px !important">代引き手数料</span>
                        </div>
                        <input type="text" class="form-control" disabled>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate" style="width:120px !important">@lang('Total amount excluding tax')</span>
                        </div>
                        <input type="text" class="form-control" disabled>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate"
                                style="width:120px !important">消費税</span>
                        </div>
                        <input type="text" class="form-control" disabled>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate"
                                style="width:120px !important">税込合計金額</span>
                        </div>
                        <input type="text" class="form-control" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="offset-2 col-8 text-white py-2" style="background-color: rgb(55, 96, 146)">
                    現時点ではまだ商品が確保されたわけではございません。<br>
                    発注のタイミングや仕入れ先の事情で数量や価格の変更、または売り切れのため<br>
                    供給不可になりご注文キャンセルとなってしまう可能性がございます。<br>
                    見積内容から変更が生じてしまった場合は速やかにご連絡いたします。
                </div>
            </div>
        </div>
    </div>
    <div class="step-footer d-flex justify-content-end">
        <button class="btn btn-warning btn-sm text-white mr-2" data-step-action="prev">@lang('Return')</button>
        <button class="btn btn-secondary btn-sm text-white mr-2" data-step-action="next">
            @lang('To the next step')</button>
        <button class="btn btn-success btn-sm text-white mr-2" data-step-action="finish">
            @lang('Agree to the terms and order')</button>
    </div>
</div>

<div class="modal fade" id="agree-confirm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                ご注文ありがとうございます。<br>
                ご注文内容確認のメールが送信されます。<br>
                もしメールが届かないようでしたら、ご注文が正しく送信しれてない場合がございますのでお手数ですが下記へご連絡ください。<br>
                TEL 04-2963-1276
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary btn-sm">マイアカウント画面に戻る</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/steps/jquery-steps.min.js') }}"></script>
<script>
    $(function() {
        $('#step-steps').find('li').unbind('click');
        $('.order-to-desired').datepicker({
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

        var billingAddressId = null;
        var deliveryAddressId = null;
        var orderDesiredDate = null;
        var orderNo = null;
        var paymentCond = null;
        var commonName = null;

        var address1 = null;
        var address2 = null;

        var billingFlag = false;
        var deliveryFlag = false;
        $('.choice-btn').each(function(index, item) {
            if ($(item).hasClass('billing-choice')) {
                if (!billingFlag) {
                    $(item).removeClass('btn-secondary').addClass('btn-warning');
                    billingAddressId = $(item).data('address_id');
                    address1 = $(item).data('address');
                    billingFlag = true;
                    return;
                } else
                    return;
            } else if ($(item).hasClass('delivery-choice')) {
                if (!deliveryFlag) {
                    $(item).removeClass('btn-secondary').addClass('btn-warning');
                    deliveryAddressId = $(item).data('address_id');
                    address2 = $(item).data('address');
                    deliveryFlag = true;
                    return;
                } else
                    return;
            }
        });

        $(document).on('click', '.delivery-choice', function() {
            $('#purchase-steps').find('.delivery-choice.btn-warning').removeClass('btn-warning')
                .addClass('btn-secondary');
            $(this).removeClass('btn-secondary').addClass('btn-warning');
            deliveryAddressId = $(this).data('address_id');
            address2 = $(this).data('address');
            toastr.success('正常に変更されました。');
        })

        $(document).on('click', '.billing-choice', function() {
            $('#purchase-steps').find('.billing-choice.btn-warning').removeClass('btn-warning')
                .addClass('btn-secondary');
            $(this).addClass('btn-warning').removeClass('btn-secondary');
            billingAddressId = $(this).data('address_id');
            address1 = $(this).data('address');
            toastr.success('正常に変更されました。');
        })

        function delayRedirect() {
            window.location.assign(accountUrl);
        }

        var agreePostData = null;

        $('#purchase-steps').steps({
            onChange: function(currentIndex, newIndex, stepDirection) {
                // step2
                if (currentIndex === 0) {
                    if (stepDirection === 'forward') {
                        if (billingAddressId && deliveryAddressId)
                            return true;
                        else
                            return false;
                    }
                }

                if (currentIndex === 1) {
                    if (stepDirection === 'forward') {

                        paymentCond = $("form.payment-1 input[type='radio']:checked").data(
                            'commonid');
                        commonName = $("form.payment-1 input[type='radio']:checked").parents(
                            '.input-group').find('label').text();

                        orderDesiredDate = $('.order-to-desired').val();
                        orderNo = $('.order-to-your').val();

                        if (!orderDesiredDate || orderDesiredDate == '' || orderDesiredDate ==
                            undefined) {
                            $('.order-to-desired').addClass('is-invalid');
                            toastr.warning('この欄を空白にはできません。');
                            return;
                        }

                        if (!orderNo || orderNo == '' || orderNo == undefined) {
                            $('.order-to-your').addClass('is-invalid');
                            toastr.warning('この欄を空白にはできません。');
                            return;
                        }
                        $('.address1_info').empty();
                        $('.address1_info').append(
                            `<span>` + address1.comp_type + `</span><span>` + address1
                            .customer_name + `</span><span>` + address1.part_name +
                            `</span><span>` + address1.zip + `</span><span>` + address1
                            .address1 + `</span><span>` + address1.address2 + `</span><span>` +
                            address1.address4 + `</span><span>` + address1.address3 +
                            `</span><span>` + address1.tel + `</span><span>` + address1.fax +
                            `</span>`
                        );

                        $('.address2_info').empty();
                        $('.address2_info').append(
                            `<span>` + address2.comp_type + `</span><span>` + address2
                            .customer_name + `</span><span>` + address2.part_name +
                            `</span><span>` + address2.zip + `</span><span>` + address2
                            .address1 + `</span><span>` + address2.address2 + `</span><span>` +
                            address2.address4 + `</span><span>` + address2.address3 +
                            `</span><span>` + address2.tel + `</span><span>` + address1.fax +
                            `</span>`
                        );
                        $('.payment-method').empty();
                        $('.payment-method').append('<span>' + commonName + '</span>');
                        $('.desired-date').empty();
                        $('.desired-date').append('<span>' + orderDesiredDate + '</span>');

                        $('span').each(function(index, item) {
                            if ($(item).text() == 'null') {
                                $(item).remove();
                            }
                        })

                        var money = 0.0;
                        var subTotal = 0;
                        var daibiki = 0;
                        var unitMoney = null;
                        var feeShipping = 0;

                        $('#for-order-table tbody').empty();
                        $('#order-list-table tbody').find('tr').each(function(index, item) {
                            var data = $(item).data('rowinfo');
                            
                            // var unitPrice = (!data.unit_price_sell) ? 0 : data
                            //     .unit_price_sell;
                            var price = ((data.money_buy*rateList[data.type_money_buy].buy_rate*data.buy_quantity)/(1-data.rate_profit)+data.fee_shipping)/(data.sell_quantity ? data.sell_quantity : data.sell_quantity_second)/rateList[data.type_money_sell].sale_rate;
                            
                            subTotal += (parseInt(data.sell_quantity) * parseInt(
                            (data.price_quote ? data.price_quote : price.toFixed(2))));


                            var moneyType = '';
                            if (data.type_money_sell == 'JPY')
                                moneyType = '円';
                            else if (data.type_money_sell == 'USD')
                                moneyType = '$';
                            else if (data.type_money_sell == 'EUR')
                                moneyType = '€';
                            else
                                moneyType = '円';

                            unitMoney = moneyType;
                            feeShipping = data.fee_shipping;
                            $('#for-order-table tbody').append(`<tr>
                                <td>` + data.katashiki + `</td>
                                <td>` + data.maker + `</td>
                                <td>` + convertNumberFormat(parseInt(data.sell_quantity)) + `</td>
                                <td>` + (data.price_quote ? data.price_quote + ' ' + moneyType : price.toFixed(2)+ ' ' + moneyType) + `</td>
                                <td>` + data.dc + `</td>
                                <td>` + "" + `</td>
                                <td>` + data.rohs + `</td>
                                <td><input type="text" class="form-control" value="` + orderNo + `"></td>
                            </tr>`);
                        })

                        if (unitMoney == 'JPY') {
                            totalDaibiki = $subTotal;
                            if ($condPay == 2) {
                                if ($totalDaibiki < 10000) {
                                    $daibiki = 300;
                                } else if (($totalDaibiki >= 10000) && ($totalDaibiki < 30000)) {
                                    $daibiki = 400;
                                } else if (($totalDaibiki >= 30000) && ($totalDaibiki < 100000)) {
                                    $daibiki = 600;
                                } else if (($totalDaibiki >= 100000) && ($totalDaibiki < 300000)) {
                                    $daibiki = 1000;
                                }
                            }
                        }

                        var tax = "{{ $tax_info->tax }}";
                        var taxMoney = parseFloat(tax) * subTotal;
                        var feeMoney = parseFloat(feeShipping) * subTotal;

                        $('#in-total-price').find('input:eq(0)').val(convertNumberFormat(subTotal) +
                            ' ' + unitMoney);
                        $('#in-total-price').find('input:eq(1)').val(convertNumberFormat(taxMoney) +
                            ' ' + unitMoney);
                        $('#in-total-price').find('input:eq(3)').val(convertNumberFormat(feeMoney) +
                            ' ' + unitMoney);
                        $('#in-total-price').find('input:eq(5)').val(convertNumberFormat(subTotal +
                            taxMoney + feeMoney) + ' ' + unitMoney);

                        $.each($('.step-tab-panel.active').find('*'), function(index, elem) {
                            if ($(elem).text() == 'null')
                                $(elem).text('');
                        })

                        $.each($('#for-order-table').find('td'), function(index, elem) {
                            if ($(elem).text() == 'null') {
                                $(elem).text('');
                            }
                        })

                        return true;
                    }
                }
                return true;
            },
            onFinish: function() {
                var idList = $('#order-list-table').data('ids');
                var orderNumList = [];
                var sellingBuyTotal = 0;
                var typeMoneyList = [];
                $.each($('#for-order-table tbody').find('tr'), function(index, item) {
                    orderNumList.push($(item).find('input').val());
                })

                $.each($('#order-list-table tbody').find('tr'), function(index, item) {
                    var trData = $(item).data('rowinfo');
                    sellingBuyTotal += parseInt(trData.money_sell);
                    typeMoneyList.push(trData.type_money_buy);
                })

                $("#agree-confirm").modal("show");

                agreePostData = {
                    idList: idList,
                    orderNumList: orderNumList,
                    payment: paymentCond,
                    nouki: orderDesiredDate,
                    orderYour: orderNo,
                    sendAddress: billingAddressId,
                    requestAddress: deliveryAddressId,
                    sellingBuyTotal: sellingBuyTotal,
                    typeMoneyList: typeMoneyList,
                };
            }
        });

        $("#agree-confirm button").on("click", function () {
            $.ajax({
                url: "{{ route('frontend.purchase.request') }}",
                type: 'post',
                data: agreePostData,
                success: function(data) {
                    agreePostData = null;
                    $("#agree-confirm").modal("hide"); 
                    toastr.success('内容を納品先住所 へコピーしました。');
                    setTimeout(delayRedirect, 2000)
                }
            });
        });
        // $(document).on('click', '.address-template', function() {
        //     console.log("you are welcome");
        // })
    })
</script>
