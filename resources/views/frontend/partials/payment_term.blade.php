@inject('common', 'App\Models\Common')

@php
$payment_option1 = $common->where('common_name', '=', '代引き')->first();
$payment_option2 = $common->where('common_name', '=', '商品到着後、一週間以内現金振り込み')->first();

$payment_flag = Auth::user()->customer->user_info->payment[0]->payment_flag;
@endphp

<div id="address-payment-term">
    <div class="row  mt-5">
        <div class="col-12">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend" style="border-radius: 0.2rem !important">
                    <span class="input-group-text text-truncate">支払い条件</span>
                </div>
                <form class="row justify-content-around payment-1 ml-2" style="width: 75%; flex-direction: column;">
                    <h6>支払い条件はご希望に添えられない場合があることをご容赦ください.</h6>
                    <div class="form-check form-check-block">
                        <input type="radio" class="form-check-input payment-type"
                            id="payment-cond-{{ $payment_option1->id }}" name="payment"
                            data-commonId="{{ $payment_option1->id }}" checked>
                        <label class="form-check-label"
                            for="payment-cond-{{ $payment_option1->id }}">{{ $payment_option1->common_name }}</label>
                    </div>
                    <div class="form-check form-check-block">
                        <input type="radio" class="form-check-input payment-type"
                            id="payment-cond-{{ $payment_option2->id }}" name="payment"
                            data-commonId="{{ $payment_option2->id }}">
                        <label class="form-check-label" for="payment-cond-{{ $payment_option2->id }}">商品到着後、一週間以内現金振り込み(振込手数料はお客さま負担になります。)</label>
                    </div>

                    <div class="form-check form-check-block @if ($payment_flag != 2) d-none @endif">
                        <input type="radio" class="form-check-input payment-type" id="payment-type-10" name="payment" data-commonId="{{ $payment_option1->id }}">
                        <label class="form-check-label" for="payment-type-10" title="掛け取引は登録情報変更の希望支払い条件から申請してください。">末 日締め 当月 10 日現金振り込み</label>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4 input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang("Desired")(*)</span>
            </div>
            <input type="text" class="form-control order-to-desired" aria-label="Small"
                aria-describedby="inputGroup-sizing-sm" title="ご希望の納期に問いに合わない場合がございます。そのときはできる限り最短納期となります。">
        </div>
        <div class="col-8">
            納期を保証するものではございません。弊社見積のめやす納期を大幅に過ぎることが分かりましたらご連絡致します。
        </div>
    </div>

    <div class="row">
        <div class="col-4 input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text text-truncate">@lang("Your")(*)</span>
            </div>
            <input type="text" class="form-control order-to-your" aria-label="Small"
                aria-describedby="inputGroup-sizing-sm" title="注文確定前に個々の部品に注文番号を入力できます。">
        </div>
    </div>

</div>
