<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\RfqRequest;
use App\Models\RfqDetail;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\UserInfo;
use App\Models\QuoteCustomer;
use App\Models\RequestQuoteVendor;
use Illuminate\Http\Request;

use App\Http\Requests\Admin\QuoteVendorRequest;

class RequestQuoteVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuoteVendorRequest $request)
    {
        $quote = new RequestQuoteVendor;
        $quote->supplier_id = $request->supplier_id;
        $quote->rfq_request_id = $request->id;
        $quote->rfq_request_child_id = $request->id;
        $quote->maker = $request->maker;
        $quote->katashiki = $request->katashiki;
        $quote->katashiki_not_spl = $request->katashiki;
        $quote->quantity_buy = $request->count_aspiration;
        $quote->type_money_buy = $request->type_money_buy;
        $quote->unit_price_buy = $request->unit_price_buy;
        $quote->dc = $request->dc;
        $quote->fee_shipping = $request->fee_shipping;
        $quote->date_quote = $request->date_quote;
        $quote->code_quote = $request->code_quote;
        $quote->kbn2 = $request->kbn2;
        $quote->rohs = $request->rohs;
        $quote->unit_buy = $request->unit_buy;
        $quote->deadline_buy_vendor = $request->deadline_buy_vendor;
        // $quote->count_aspiration = $request->count_aspiration;
        $quote->save();
        return json_encode(RequestQuoteVendor::with(
            [
                'vendor', 'vendor.user_info', 'rfq_request', 'rfq_request.customer',
                'rfq_request.customer.user_info', 'rfq_request.customer.user_info.payment', 'messages',
                'vendor.user_info.address',
                'vendor.user_info.emails',
            ]
        )->find($quote->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuoteVendorRequest $request, $id)
    {
        $quote = RequestQuoteVendor::find($id);
        $quote->supplier_id = $request->supplier_id;
        $quote->maker = $request->maker;
        $quote->katashiki = $request->katashiki;
        $quote->quantity_buy = $request->count_aspiration;
        $quote->unit_buy = $request->unit_buy;
        $quote->type_money_buy = $request->type_money_buy;
        $quote->unit_price_buy = $request->unit_price_buy;
        $quote->dc = $request->dc;
        $quote->kbn2 = $request->kbn2;
        $quote->rohs = $request->rohs;
        $quote->deadline_buy_vendor = $request->deadline_buy_vendor;
        $quote->fee_shipping = $request->fee_shipping;
        $quote->code_quote = $request->code_quote;
        $request_quote = $request->date_quote;
        $date_quote = $quote->date_quote;
        $quote->date_quote = $request->date_quote;
        $quote->save();

        if(strtotime($request_quote) != strtotime($date_quote))
        {
            $supplier = Supplier::find($quote->supplier_id);
            $vendor = UserInfo::find($supplier->user_info_id);
            $vendor->est_ans_time += 0.5;
            $vendor->save();
        }


        return json_encode(RequestQuoteVendor::with(
            [
                'vendor', 'vendor.user_info', 'rfq_request', 'rfq_request.customer',
                'rfq_request.customer.user_info', 'rfq_request.customer.user_info.payment', 'messages',
                'vendor.user_info.address',
                'vendor.user_info.emails',
            ]
        )->find($quote->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function send_quote(Request $request)
    {
        $info_request_quote_vendor =  RequestQuoteVendor::findOrFail($request->id);
        $info_request = RfqRequest::findOrFail($info_request_quote_vendor->rfq_request_id);
        if ($info_request_quote_vendor->is_send_est != 1) {
            $newEst = new QuoteCustomer();
            $newEst->request_vendor_id = $info_request_quote_vendor->id;
            $newEst->receive_date = date_create($info_request->rfq_date);
            // $newEst->quote_date = $info_request_quote_vendor->date_quote;
            $newEst->quote_date = date_create()->format('Y-m-d');
            $newEst->quote_code = $info_request_quote_vendor->code_quote;
            $newEst->customer_id = $info_request->customer_id;
            $newEst->user_res = $info_request->customer->representative;
            $newEst->maker = $info_request_quote_vendor->maker;
            $newEst->katashiki = $info_request_quote_vendor->katashiki;
            $newEst->katashiki_not_spl = $info_request_quote_vendor->katashiki_not_spl;
            $newEst->dc = $info_request_quote_vendor->dc;
            $newEst->rohs = $info_request_quote_vendor->rohs;
            $newEst->country = $info_request->customer->user_info->country;
            $newEst->kbn2 = $info_request_quote_vendor->kbn2;
            $newEst->count_predict = $info_request->quantity_aspiration;
            $newEst->deadline_quote = $info_request_quote_vendor->deadline_buy_vendor;
            $newEst->supplier_id = $info_request_quote_vendor->supplier_id;
            $newEst->buy_quantity = $info_request_quote_vendor->quantity_buy;
            $newEst->unit_buy = $info_request_quote_vendor->unit_buy ? $info_request_quote_vendor->unit_buy : 'psc';
            $newEst->unit_sell = $info_request_quote_vendor->unit_buy ? $info_request_quote_vendor->unit_buy : 'psc';
            $newEst->is_together = 0;
            $newEst->is_sendmail = 1;
            $newEst->is_order = 0;

            $newEst->sell_quantity = $info_request_quote_vendor->quantity_buy;
            // $newEst->money_sell = 500;

            $newEst->type_money_buy = $info_request_quote_vendor->type_money_buy ? $info_request_quote_vendor->type_money_buy : 'JPY';
            $newEst->unit_price_buy = $info_request_quote_vendor->unit_price_buy;
            $newEst->money_buy = ($info_request_quote_vendor->quantity_buy * $info_request_quote_vendor->unit_price_buy);
            $newEst->type_money_sell = 'JPY';
            $newEst->fee_shipping = $info_request_quote_vendor->fee_shipping ? $info_request_quote_vendor->fee_shipping : 0;
            if (is_array($info_request->customer->user_info->payment) && $info_request->customer->user_info->payment[0] && $info_request->customer->user_info->payment[0]->common)
                $newEst->cond_payment = $info_request->customer->user_info->payment[0]->common->common_name;
            $newEst->save();
            $info_request_quote_vendor->is_send_est = 1;
            $info_request_quote_vendor->date_quote = date_create()->format('Y-m-d');
            $info_request_quote_vendor->save();
        }

        return date_create()->format('Y-m-d');
    }
}
