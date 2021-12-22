<div class="modal fade" id="supplier-register-modal" tabindex="-1" role="dialog"
    aria-labelledby="supplierRegisterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 800px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="supplierRegisterModalLabel" class="modal-title text-warning">仕入先変更</h5>
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
                            <input type="text" id="register-supplier-company-name" class="form-control"
                                aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">国(*)</span>
                            </div>
                            <select class="selectpicker countrypicker" id="register-supplier-country"
                                data-flag="true"></select>
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">郵便番号</span>
                            </div>
                            <input type="text" id="register-postal-code" class="form-control" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">担当者</span>
                            </div>
                            <input type="text" id="register-person-in-charge" class="form-control" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">電話番号</span>
                            </div>
                            <input type="text" id="register-supplier-phone-number" class="form-control"
                                aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">メールアドレス1(*)</span>
                            </div>
                            <input type="text" id="register-supplier-email1" class="form-control" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">メールアドレス3</span>
                            </div>
                            <input type="text" id="register-supplier-email3" class="form-control" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">備考</span>
                            </div>
                            <textarea id="register-supplier-remarks" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">支払い条件(*)</span>
                            </div>
                            <select id="register-supplier-payment-term" class="form-control">
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">会社カナ(*)</span>
                            </div>
                            <input type="text" id="register-supplier-company-name-kana" class="form-control"
                                aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">都道府県</span>
                            </div>
                            <input type="text" id="register-supplier-prefectures" class="form-control"
                                aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">ランク</span>
                            </div>
                            <input type="text" id="register-supplier-rank" class="form-control" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">住所(*)</span>
                            </div>
                            <input type="text" id="register-supplier-address" class="form-control" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">FAX</span>
                            </div>
                            <input type="text" id="register-supplier-fax" class="form-control" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">メールアドレス2</span>
                            </div>
                            <input type="text" id="register-supplier-email2" class="form-control" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">メールアドレス4</span>
                            </div>
                            <input type="text" id="register-supplier-email4" class="form-control" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">登録日</span>
                            </div>
                            <input type="date" id="register-supplier-date" class="form-control" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                        <div class="form-check form-check-block mt-2">
                            <input type="checkbox" class="form-check-input" id="daily-RFQ"
                                name="inlineMaterialRadiosExample">
                            <label class="form-check-label" for="daily-RFQ" style="margin-top: 3px">毎日のRFQ</label>
                        </div>
                        <button type="button" id="register-supplier-add-payment"
                            class="btn btn-primary btn-sm mt-2">支払い方法追加</button>
                    </div>
                </div>
                <div class="row mt-3" style="padding: 0 12px;">
                    <div class="col-12">
                        <table id="supplier-log-table" class="table table-bordered table-sm d-none">
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
                                    <th scope="row">売切回数</th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">発注回数</th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">返品回数</th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">POキャンセル数</th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">購入金額</th>
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
            <div class="modal-footer justify-content-between">
                <button type="button" id="register-supplier" class="btn btn-primary btn-sm">更新</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</div>
