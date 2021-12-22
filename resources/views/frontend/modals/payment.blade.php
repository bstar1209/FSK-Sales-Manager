@inject('common', 'App\Models\Common')

@php
$common_list = $common->where('common_type', 0)->get();
@endphp
<div class="modal fade" id="payment-modal" tabindex="-1" role="dialog" aria-labelledby="PaymentModelLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 600px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h6 id="PaymentModelLabel" class="modal-title text-warning">
                    振込手数料はお客様負担になります。
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-primary">支払い条件：</p>
                <p class="pl-3">支払い条件はご希望に添えられない場合があることをご容赦ください。</p>
                <form id="customer-payment-form" class="row justify-content-around ml-1"
                    style="flex-direction: column;">
                    @php
                        $payment1 = $common_list
                            ->where('common_name', '=', '商品到着後、一週間以内現金振り込み')
                            ->first();
                        $payment2 = $common_list
                            ->where('common_name', '=', '代引き')
                            ->first();
                        
                        $flag = 0;
                        if (Auth::user()->customer->user_info->payment->first()) {
                            $user_payment = Auth::user()->customer->user_info->payment[0];
                        } else {
                            $user_payment = null;
                        }

                        $payment_flag = 1;

                        if (isset($user_payment)) {
                            if ($payment1->id == $user_payment->common_id) {
                                $flag = 1;
                            } elseif ($payment2->id == $user_payment->common_id) {
                                $flag = 2;
                            } else {
                                $flag = 3;
                            }

                            $payment_flag = $user_payment->payment_flag;
                        } else {
                            $flag = 3;
                        }

                    @endphp

                    <div class="form-check form-check-block">
                        <input type="radio" class="form-check-input payment-type"
                            id="payment-type-{{ $payment1->id }}" name="payment"
                            data-commonId="{{ $payment1->id }}" @if ($flag == 1) checked @endif>
                        <label class="form-check-label"
                            for="payment-type-{{ $payment1->id }}">代引き</label>
                    </div>

                    <div class="form-check form-check-block">
                        <input type="radio" class="form-check-input payment-type"
                            id="payment-type-{{ $payment2->id }}" name="payment"
                            data-commonId="{{ $payment2->id }}" @if ($flag == 2) checked @endif>
                        <label class="form-check-label"
                            for="payment-type-{{ $payment2->id }}">商品到着後、一週間以内現金振り込み</label>
                    </div>

                    <div class="form-check form-check-block @if ($payment_flag != 1) d-none @endif">
                        <input type="radio" class="form-check-input payment-type" id="payment-type-radio" name="payment"
                            @if ($flag == 3) checked @endif>
                        <label class="form-check-label" for="payment-type-radio">掛け取引申請</label>
                    </div>

                    <div class="form-check form-check-block @if ($payment_flag != 2) d-none @endif">
                        <input type="radio" class="form-check-input payment-type" id="payment-type-10" name="payment">
                        <label class="form-check-label" for="payment-type-10">末 日締め 当月 10 日現金振り込み</label>
                    </div>
                </form>

                <div id="payment-terms" class="row mt-1 @if ($payment_flag != 1) d-none @endif">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <select class="col-2 form-control form-control-sm customer-close-date">
                            <option value="05">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="30">末</option>
                        </select>
                        <span>日締め</span>
                        <select class="col-2 form-control form-control-sm customer-type-date">
                            <option value="0">当月</option>
                            <option value="1">翌月</option>
                            <option value="2">翌々月</option>
                        </select>
                        <select class="col-2 form-control form-control-sm customer-send-date">
                            <option value="05">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="30">末</option>
                        </select>
                        <span>日現金振り込み</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" id="payment-choose-btn" class="btn btn-primary btn-sm">決定</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">戻る</button>
            </div>
        </div>
    </div>
</div>
