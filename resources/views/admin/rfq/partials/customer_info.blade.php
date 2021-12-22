<div class="col-4 table-responsive">
    <table id="customer-info" class="table table-bordered table-striped table-sm">
        <tbody>
            <tr>
                <td class="w-15">客先</td>
                <td id="custmer-company-name" class="customer-info-elem"></td>
                <td class="w-15">注文キャンセル回数</td>
                <td id="customer-orders-cancel-num" class="text-truncate w-35 customer-info-elem"></td>
            </tr>
            <tr>
                <td class="w-15">ランク</td>
                <td id="customer-rank" class="customer-info-elem w-35">
                    {{-- <select class="form-control form-control-sm" style="width: 99%">
                        <option ></option>
                        <option value="0">0</option>
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
                    </select> --}}
                </td>
                <td class="w-15">支払い条件 </td>
                <td id="customer-pay-term" class="text-truncate w-35 customer-info-elem"></td>
            </tr>
            <tr>
                <td class="w-15">見積依頼数</td>
                <td id="customer-rfq-num" class="w-35 customer-info-elem"></td>
                <td class="w-15">見積回答数</td>
                <td id="customer-est-response-num" class="w-35 customer-info-elem"></td>
            </tr>
            <tr>
                <td class="w-15">受注回数</td>
                <td id="customer-orders-num" class="w-35 customer-info-elem"></td>
                <td class="w-15">受注金額</td>
                <td id="customer-order-amount" class="w-35 customer-info-elem"></td>
            </tr>
            <tr>
                <td class="w-15">備考</td>
                <td id="customer-remarks" colspan="3"><textarea class="form-control w-100" style="height: 84px"
                        readonly></textarea></td>
            </tr>
        </tbody>
    </table>
    <div class="row justify-content-center align-items-center" style="color: black">
        <div class="col-4 text-right">
            顧客からのメッセージ
        </div>
        <div class="col-8">
            <textarea id="message-from-customer" class="form-control w-100"></textarea>
        </div>
    </div>
</div>
