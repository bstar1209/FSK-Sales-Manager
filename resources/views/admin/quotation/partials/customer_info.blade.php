<div class="col-6 table-responsive">
    <table id="customer-info" class="table table-bordered table-striped table-sm">
        <tbody>
            <tr>
                <td class="w-15">@lang('Customer')</td>
                <td id="custmer-name" class="customer-info-elem" colspan="3"></td>
            </tr>
            <tr>
                <td class="w-15">@lang('Payment terms')</td>
                <td id="custmer-payment" class="customer-info-elem" colspan="3"></td>
            </tr>
            <tr>
                <td class="w-15">@lang('Rank')</td>
                <td id="customer-rank" class="customer-info-elem w-35" colspan="3"></td>
            </tr>
            <tr>
                <td class="w-15">@lang('Number of requests for quotation')</td>
                <td id="customer-rfq-num" class="w-35 customer-info-elem"></td>
                <td class="w-15">@lang('Estimated number of responses')</td>
                <td id="customer-est-response-num" class="w-35 customer-info-elem"></td>
            </tr>
            <tr>
                <td class="w-15">@lang('Number of orders')</td>
                <td id="customer-orders-num" class="w-35 customer-info-elem"></td>
                <td class="w-15">@lang('Order amount')</td>
                <td id="customer-order-amount" class="w-35 customer-info-elem"></td>
            </tr>
            <tr>
                <td class="w-15">@lang('Remarks')</td>
                <td id="customer-remarks" colspan="3"><textarea class="form-control w-100" style="height: 54px"
                        disabled></textarea></td>
            </tr>
        </tbody>
    </table>
</div>
