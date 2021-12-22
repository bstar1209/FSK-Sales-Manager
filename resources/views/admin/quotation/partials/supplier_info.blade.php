<div class="col-6 table-responsive">
    <table id="supplier-info" class="table table-bordered table-striped table-sm">
        <tbody>
            <tr>
                <td class="w-15">@lang('Supplier name')</td>
                <td id="supplier-id" class="w-35" colspan="3"></td>
            </tr>
            <tr>
                <td class="w-15">@lang('Payment terms')</td>
                <td id="supplier-pay-term" class="w-35" colspan="3"></td>
            </tr>
            <tr>
                <td class="w-15">@lang('Rank')</td>
                <td id="supplier-rank" class="w-35"></td>
                <td class="w-15">@lang('Number of order cancellations')</td>
                <td id="supplier-orders-cancel-num" class="w-35"></td>
            </tr>
            <tr>
                <td class="w-15">@lang('Number of requests for quotation')</td>
                <td id="supplier-rfq-num" class="w-35"></td>
                <td class="w-15">@lang('Estimated number of responses')</td>
                <td id="supplier-est-response-num" class="w-35"></td>
            </tr>
            <tr>
                <td class="w-15">@lang('Number of sold out')</td>
                <td id="supplier-sold-out" class="w-35"></td>
                <td class="w-15">@lang('Number of returns')</td>
                <td id="supplier-return-num" class="w-35"></td>
            </tr>
            <tr>
                <td class="w-15">@lang('Number of orders')</td>
                <td id="supplier-orders-num" class="w-35"></td>
                <td class="w-15">@lang('Purchase price')</td>
                <td id="supplier-purchase-price" class="w-35"></td>
            </tr>
            <tr>
                <td class="w-15">@lang('Remarks')</td>
                <td id="supplier-remarks" colspan="3"><textarea class="form-control w-100" style="height: 54px"
                        disabled></textarea></td>
            </tr>
        </tbody>
    </table>
    <div class="row justify-content-center align-items-center" style="color: black">
        <div class="col-4">
            @lang('Message') (j)
        </div>
        <div class="col-8">
            <textarea class="form-control w-100 message-box"></textarea>
        </div>
    </div>
</div>
