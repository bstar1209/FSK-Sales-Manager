@inject('common', 'App\Models\Common')

@php
$common_list = $common->where('common_type', 0)->get();
@endphp
<div id="registration" class="mt-1 d-none account-tab">
    <div class="row">
        <div class="col-12 d-flex justify-content-between">
            <h6><strong>基本登録情報</strong></h6>
            <a id="customer-password-update" class="btn btn-success btn-sm" data-toggle="modal"
                data-target="#password-change-modal">パスワード変更ボタン</a>
        </div>
    </div>
    <div class="row">
        <div class="col-5">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">会社名(*)</span>
                </div>
                <input type="text" class="form-control customer-company-name"
                    value="{{ Auth::user()->customer->user_info->company_name }}">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">業種</span>
                </div>
                <select class="form-control customer-business-type">
                    <option value=""></option>
                    <option value="総合電機メーカー">総合電機メーカー</option>
                    <option value="通信機器メーカー">通信機器メーカー</option>
                    <option value="精密機器メーカー">精密機器メーカー</option>
                    <option value="デバイスメーカー">デバイスメーカー</option>
                    <option value="その他製品メーカー">その他製品メーカー</option>
                    <option value="EMS/OEMメーカー">EMS/OEMメーカー</option>
                    <option value="基板実装・組立">基板実装・組立</option>
                    <option value="商社・販売">商社・販売</option>
                    <option value="政府・公共機関">政府・公共機関</option>
                    <option value="学校・研究機関">学校・研究機関</option>
                    <option value="その他">その他</option>
                </select>
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">電話番号(*)</span>
                </div>
                <input type="text" class="form-control customer-phone-number"
                    value="{{ Auth::user()->customer->user_info->address->tel }}">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">ホームページ</span>
                </div>
                <input type="text" class="form-control customer-home-page"
                    value="{{ Auth::user()->customer->user_info->address->homepages }}">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">eメール1(*)</span>
                </div>
                <input type="email" class="form-control customer-email1"
                    value="{{ Auth::user()->customer->user_info->email1 }}">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">eメール3</span>
                </div>
                <input type="email" class="form-control customer-email3"
                    value="{{ Auth::user()->customer->user_info->email3 }}">
            </div>
            <input type="hidden" class="customer-sales" value="{{ Auth::user()->customer->representative_business }}">
        </div>
        <div class="col-5">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">お名前(*)</span>
                </div>
                <input type="text" class="form-control customer-name"
                    value="{{ Auth::user()->customer->representative }}">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">部署</span>
                </div>
                <input type="text" class="form-control customer-department"
                    value="{{ Auth::user()->customer->user_info->address->part_name }}">
            </div>

            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">FAX番号(*)</span>
                </div>
                <input type="text" class="form-control customer-fax-number"
                    value="{{ Auth::user()->customer->user_info->address->fax }}">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">希望支払条件</span>
                </div>
                @php
                    $flag = 0;
                    if (is_array(Auth::user()->customer->user_info->payment)) {
                        $user_payment = Auth::user()->customer->user_info->payment[0];
                    } else {
                        $user_payment = null;
                    }
                    $payment1 = $common_list->where('common_name', '=', '商品到着後、一週間以内現金振り込み')->first();
                    $payment2 = $common_list->where('common_name', '=', '代引き')->first();
                    
                    if (isset($user_payment)) {
                        if ($payment1->id == $user_payment->common_id) {
                            $flag = 1;
                            $text = '商品到着後、一週間以内現金振り込み';
                        } elseif ($payment2->id == $user_payment->common_id) {
                            $flag = 2;
                            $text = '代引き';
                        } else {
                            $flag = 3;
                            $text = '掛け取引申請';
                        }
                    } else {
                        $flag = 3;
                        $text = '掛け取引申請';
                    }
                @endphp

                <input type="text" class="form-control customer-payment-terms" value="{{ $text }}"
                    data-id="{{ $flag }}">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">eメール2</span>
                </div>
                <input type="email" class="form-control customer-email2"
                    value="{{ Auth::user()->customer->user_info->email2 }}">
            </div>
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text text-truncate">eメール4</span>
                </div>
                <input type="email" class="form-control customer-email4"
                    value="{{ Auth::user()->customer->user_info->email4 }}">
            </div>
        </div>
        <div class="col-2 d-flex justify-content-end align-items-end" style="padding-bottom: 5px;">
            <button type="button" id="basic-customer-update" class="btn btn-primary btn-sm"
                style="min-width: 100px">更新</button>
        </div>
    </div>

    <hr class="mt-5">
    <div class="row">
        @include('frontend.partials.address', ['index' => 1, 'type' => 0, 'status' => false])
        @include('frontend.partials.address', ['index' => 2, 'type' => 0, 'status' => false])
        @include('frontend.partials.address', ['index' => 1, 'type' => 1, 'status' => false])
        @include('frontend.partials.address', ['index' => 2, 'type' => 1, 'status' => false])
    </div>
    <hr>
    <div class="row">
        @include('frontend.partials.address', ['index' => 3, 'type' => 0, 'status' => false])
        @include('frontend.partials.address', ['index' => 4, 'type' => 0, 'status' => false])
        @include('frontend.partials.address', ['index' => 3, 'type' => 1, 'status' => false])
        @include('frontend.partials.address', ['index' => 4, 'type' => 1, 'status' => false])
    </div>
    <hr>
</div>
