@inject('charge_business', 'App\Models\ChargeBusiness')
@php
$sales_list = $charge_business->get();
@endphp

<div class="modal fade" id="customer-info-modal" tabindex="-1" role="dialog" aria-labelledby="customerInfoLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 800px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="customerInfoLabel" class="modal-title text-warning">客先情報更新</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">会社名(*)</span>
                            </div>
                            <input type="text" class="form-control customer-company-name">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">業種</span>
                            </div>
                            {{-- <input type="text" class="form-control customer-business-type"> --}}
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
                                <span class="input-group-text text-truncate">電話番号</span>
                            </div>
                            <input type="text" class="form-control customer-phone-number">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">顧客担当者名</span>
                            </div>
                            <input type="text" class="form-control customer-name">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">部署</span>
                            </div>
                            <input type="text" class="form-control customer-department">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">eメール1(*)</span>
                            </div>
                            <input type="email" class="form-control customer-email1">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">eメール3</span>
                            </div>
                            <input type="email" class="form-control customer-email3">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">カナ(*)</span>
                            </div>
                            <input type="text" class="form-control customer-company-name-kana">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">ホームページ</span>
                            </div>
                            <input type="text" class="form-control customer-home-page">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">FAX番号</span>
                            </div>
                            <input type="text" class="form-control customer-fax-number">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">営業担当者(*)</span>
                            </div>
                            <select class="form-control customer-sales" aria-readonly="true">
                                @foreach ($sales_list as $item)
                                    <option value="{{ $item->id }}">{{ $item->username_jap }}</option>
                                @endforeach
                            </select>
                            {{-- <input type="text" class="form-control customer-sales"> --}}
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">ランク</span>
                            </div>
                            <select class="form-control customer-rank" aria-readonly="true">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">eメール2</span>
                            </div>
                            <input type="email" class="form-control customer-email2">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">eメール4</span>
                            </div>
                            <input type="email" class="form-control customer-email4">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">備考</span>
                            </div>
                            <textarea class="form-control customer-remarks" style="width:87%" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend" style="border-radius: 0.2rem !important">
                                <span class="input-group-text text-truncate">支払い条件</span>
                            </div>
                            <form class="row justify-content-around payment-1 ml-2" style="width: 87%">
                            </form>
                            <form class="row justify-content-around payment-2" style="width: 87%; margin-left: 13%">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <select class="col-3 form-control form-control-sm customer-close-date">
                            <option value="05">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="30">末</option>
                        </select>
                        <span>日締め</span>
                        <select class="col-3 form-control form-control-sm customer-type-date">
                            <option value="0">当月</option>
                            <option value="1">翌月</option>
                            <option value="2">翌々月</option>
                        </select>
                        <select class="col-3 form-control form-control-sm customer-send-date">
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
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <button type="button" id="update-customer-info" class="btn btn-primary btn-sm">更新</button>
                    <button type="button" class="btn btn-danger btn-sm ml-3" data-dismiss="modal">キャンセル</button>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm edit-billing-address mr-3"
                        data-type="address">請求先住所</button>
                    <button type="button" class="btn btn-primary btn-sm edit-billing-address"
                        data-type="delivery">納品先住所</button>
                </div>
            </div>
            <div class="row" style="padding: 0 12px;">
                <div class="col-12">
                    <table id="customer-log-table" class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">１ヶ月</th>
                                <th scope="col">６ヶ月</th>
                                <th scope="col">１年</th>
                                <th scope="col">全期間</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">検索回数</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">見積依頼数</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">見積回答数</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">受注回数</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">受注金額</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
