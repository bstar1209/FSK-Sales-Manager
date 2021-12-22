<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;

use Yajra\Datatables\Datatables;

use App\Models\Tax;
use App\Models\Rate;
use App\Models\UserInfo;
use App\Models\CustomerLog;
use App\Models\OrderHeader;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\QuoteCustomer;
use App\Models\FeeShipping;
use App\Models\RequestQuoteVendor;
use App\Models\PaymentCondition;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.RfqRequest
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/quotation/index');
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, $id)
    {
        $quote_info = QuoteCustomer::with([
            'request_vendors', 'customer', 'supplier', 'customer.user_info',
            'supplier.user_info', 'request_vendors.rfq_detail', 'request_vendors.messages',
        ])->find($id);
        $quote_info->quote_prefer = $request->quote_prefer;
        $quote_info->deadline_quote = $request->deadline_quote;
        if($request->rate_profit < 1)
            $quote_info->rate_profit = $request->rate_profit;
        $quote_info->profit = $request->profit;
        $quote_info->unit_sell = $request->unit_sell;
        $quote_info->sell_quantity = $request->sell_qty;
        $quote_info->type_money_sell = $request->type_money_sell;
        $quote_info->unit_price_sell = $request->selling_unit_price;
        $quote_info->price_quote = $request->unit_price;
        $quote_info->money_sell = $request->selling_amount;
        $quote_info->comment_bus = $request->comment_bus;
        $quote_info->save();

        return json_encode($quote_info);
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

    private function get_quote_list_query(Request $request)
    {
        $quote_query = QuoteCustomer::with([
            'request_vendors', 'customer', 'supplier', 'customer.user_info',
            'supplier.user_info', 'request_vendors.rfq_request', 'request_vendors.messages',
            'supplier.user_info.payment', 'supplier.user_info.payment.common',
            'customer.user_info.payment', 'customer.user_info.payment.common'
        ])->where('is_request', '=', 0);

        if ($request->has('searchStatus') && $request->searchStatus != null) {
            if ($request->searchStatus == 1) {
                $quote_query = $quote_query->where([
                    ['is_solved', '=', 0]
                    // ['is_cancel', '=', 0]
                ]);
            }

            if ($request->searchStatus == 2) {
                $quote_query = $quote_query->where('is_solved', '=', '1');
            }
        }

        if ($request->has('customerName') && $request->customerName != null) {
            $quote_query = $quote_query->whereHas('customer.user_info', function ($query) use ($request) {
                $query->where('company_name', 'LIKE', "$request->customerName%");
            });
        }

        if ($request->has('supplierName') && $request->supplierName != null) {
            $quote_query = $quote_query->whereHas('supplier.user_info', function ($query) use ($request) {
                $query->where('company_name', 'LIKE', "$request->supplierName%");
            });
        }

        if ($request->has('modelNumber') && $request->modelNumber != null) {
            $quote_query = $quote_query->where('katashiki', 'LIKE', "$request->modelNumber%");
        }

        if ($request->has('estimateDate') && $request->estimateDate != null) {
            $quote_query = $quote_query->whereDate('date_send', '=', $request->estimateDate);
        }

        if ($request->has('receptionDate') && $request->receptionDate != null) {
            $quote_query = $quote_query->whereDate('receive_date', '=', $request->receptionDate);
        }

        if ($request->has('reception') && $request->reception != null) {
            $quote_query = $quote_query->whereHas('request_vendors.rfq_request', function ($query) use ($request) {
                $query->where('detail_id', 'LIKE', "$request->reception%");
            });
        }

        if ($request->has('quoteCode') && $request->quoteCode != null) {
            // $quote_query = $quote_query->where('is_sendmail', '=', 1)
            $quote_query = $quote_query->whereHas('request_vendors', function ($query) use ($request) {
                    $query->where('code_quote', 'LIKE', "$request->quoteCode%");
                });
        }

        if ($request->has('customerId') && $request->customerId != null) {
            $quote_query = $quote_query->where('customer_id', '=', $request->customerId);
        }

        $quote_list = $quote_query->get()->whereNotNull('customer')->whereNotNull('supplier');

        // if($request->filterColumn == 0) {
        //     $request->filterColumn = 'request_vendors.rfq_request.detail_id';
        // }

        $table_info_arr = explode('.', $request->filterColumn);
        $table_name = 'quote_customer';
        $field_name = array_pop($table_info_arr);

        if (count($table_info_arr) > 0)
            $table_name = array_pop($table_info_arr);

        if ($field_name == '0') {
            $table_name = 'rfq_request';
            $field_name = 'detail_id';
            $request->filterColumn = 'request_vendors.rfq_request.detail_id';
        } else if ($field_name == '1') {
            $table_name = 'request_quote_vendor';
            $field_name = 'code_quote';
            $request->filterColumn = 'request_vendors.code_quote';
        } else if ($field_name == '2') {
            $field_name = 'sell_quantity';
            $request->filterColumn = 'sell_quantity';
        } else if ($field_name == '3') {
            $field_name = 'unit_price_sell';
            $request->filterColumn = 'unit_price_sell';
        } else if ($field_name == '4') {
            $field_name = 'money_sell';
            $request->filterColumn = 'money_sell';
        } else if ($field_name == '5') {
            $field_name = 'rate_profit';
            $request->filterColumn = 'rate_profit';
        } else if ($field_name == '6') {
            $field_name = 'type_money_sell';
            $request->filterColumn = 'type_money_sell';
        }

        if (in_array($field_name, ['receive_date', 'date_send'])) {
            if ($request->order[0]['dir'] == 'asc')
                $quote_list = $quote_list->sortBy($request->filterColumn);
            else
                $quote_list = $quote_list->sortByDesc($request->filterColumn);
            return $quote_list;
        }

        $field_type = Schema::getColumnType($table_name, $field_name);
        if ($request->order[0]['dir'] == 'asc') {
            if ($field_type == 'string')
                $quote_list = $quote_list->sortBy($request->filterColumn, SORT_STRING);
            else
                $quote_list = $quote_list->sortBy($request->filterColumn, SORT_NUMERIC);
        } else {
            if ($field_type == 'string')
                $quote_list = $quote_list->sortByDesc($request->filterColumn, SORT_STRING);
            else
                $quote_list = $quote_list->sortByDesc($request->filterColumn, SORT_NUMERIC);
        }

        return $quote_list;
    }

    public function get_quote_list(Request $request)
    {
        $quote_list = $this->get_quote_list_query($request);
        return Datatables::of($quote_list->slice(0, QuoteCustomer::$quote_limit))->make(true);
    }


    public function re_investigation_request(Request $request)
    {
        if (count($request->ids) == 0)
            return false;
        foreach ($request->ids as $id) {
            $quote_customer = QuoteCustomer::with(['request_vendors'])->find($id);
            $quote_customer->is_request = 1;
            $request_vendor = $quote_customer->request_vendors;
            if($quote_customer->is_sendmail == 0)
                $quote_customer->delete();

            $request_vendor->rfq_request->is_solved = 1;
            $request_vendor->rfq_request->solved_date = null;
            $request_vendor->save();
            $request_vendor->rfq_request->save();   
        }
        return true;
    }

    public function change_quote_status(Request $request)
    {
        if (count($request->ids) == 0)
            return false;
        $process_count = 0;
        $unprocess_count = 0;
        foreach ($request->ids as $id) {
            $quote_customer = QuoteCustomer::find($id);
            if ($quote_customer->is_solved == 1) {
                $quote_customer->is_solved = 0;
                $unprocess_count++;
            } else {
                $quote_customer->is_solved = 1;
                $process_count++;
            }
            $quote_customer->save();
        }
        if ($process_count <= $unprocess_count)
            return true;
        else
            return false;
    }

    public function duplicated_quote(Request $request)
    {
        if (count($request->ids) == 0)
            return false;

        foreach ($request->ids as $id) {
            $quote_info = QuoteCustomer::find($id);
            $new_quote = new QuoteCustomer;
            $new_quote->quote_date = $quote_info->quote_date;
            $new_quote->quote_code = $quote_info->quote_code;
            $new_quote->customer_id = $quote_info->customer_id;
            $new_quote->user_res = $quote_info->user_res;
            $new_quote->maker = $quote_info->maker;
            $new_quote->katashiki = $quote_info->katashiki;
            $new_quote->dc = $quote_info->dc;
            $new_quote->rohs = $quote_info->rohs;
            $new_quote->country = $quote_info->country;
            $new_quote->count_predict = $quote_info->count_predict;
            $new_quote->quote_prefer = $quote_info->quote_prefer;
            $new_quote->deadline_quote = $quote_info->deadline_quote;
            $new_quote->supplier_id = $quote_info->supplier_id;
            $new_quote->buy_quantity = $quote_info->buy_quantity;
            $new_quote->unit_buy = $quote_info->unit_buy;
            $new_quote->type_money_buy = $quote_info->type_money_buy;
            $new_quote->unit_price_buy = $quote_info->unit_price_buy;
            $new_quote->money_buy = $quote_info->money_buy;
            $new_quote->fee_shipping = $quote_info->fee_shipping;
            if($quote_info->rate_profit < 1)
            {
               $new_quote->rate_profit = $quote_info->rate_profit;
            }
            else
            {
                $new_quote->rate_profit = 0;
            }
            $new_quote->price_quote = $quote_info->price_quote;
            $new_quote->sell_quantity = $quote_info->sell_quantity;
            $new_quote->unit_sell = $quote_info->unit_sell;
            $new_quote->type_money_sell = $quote_info->type_money_sell;
            $new_quote->unit_price_sell = $quote_info->unit_price_sell;
            $new_quote->money_sell = $quote_info->money_sell;
            $new_quote->cond_payment = $quote_info->cond_payment;
            $new_quote->comment_bus = $quote_info->comment_bus;
            $new_quote->request_vendor_id = $quote_info->request_vendor_id;
            $new_quote->receive_date = $quote_info->receive_date;
            $new_quote->profit = $quote_info->profit;
            $new_quote->save();
        }
        return true;
    }

    public function sold_out(Request $request)
    {
        if (count($request->ids) == 0)
            return false;

        foreach ($request->ids as $id) {
            $quote_customer = QuoteCustomer::find($id);
            $quote_customer->sell_quantity = 0;
            $quote_customer->money_sell = 0;
            $quote_customer->unit_price_sell = 0;
            if ($quote_customer->save()) {
                $request_vendor = RequestQuoteVendor::find($quote_customer->request_vendor_id);
                $request_vendor->fee_ship2 .= "売り切れました。";
                $request_vendor->save();
            }
        }
        return true;
    }

    public function order_to(Request $request)
    {
        $list_ids = $request->idList;
        $cond = $request->payment;
        $payment_condition = PaymentCondition::with('common')->find($cond);
        $expect_ship_date = $request->orderDesired;
        $order_no = $request->orderYour;
        if ($order_no == null)
            $order_no = '';
        $type_money_sell = $request->typeMoneyList[0];
        $customer_id = $request->customerIds[0];
        $customer = Customer::with(['user_info', 'user_info.address', 'user_info.billing_address', 'user_info.deliver_address'])->find($customer_id);
        $type_money_sell = $request->typeMoneyList[0];
        $send_address = $customer->user_info->billing_address[0];
        $request_address = $customer->user_info->deliver_address[0];

        $fee_shipping = FeeShipping::get_fee_shipping($send_address->address1, $type_money_sell);
        $total_daibiki = $request->sellingBuyTotal;

        $fee_daibiki = 0;

        if ($cond == 2) {
            if ($total_daibiki < 1000)
                $fee_daibiki = 300;
            else if (($total_daibiki >= 10000) && ($total_daibiki < 30000))
                $fee_daibiki = 400;
            else if (($total_daibiki >= 30000) && ($total_daibiki < 100000))
                $fee_daibiki = 600;
            else if (($total_daibiki >= 100000) && ($total_daibiki < 300000))
                $fee_daibiki = 1000;
        }

        $sql_tax = Tax::latest()->first();
        $tax = $sql_tax->tax;
        $code_quote = $order_no;

        $order_header = new OrderHeader();
        $order_header->customer_id = $customer_id;
        $order_header->tax_id = $sql_tax->id;
        $order_header->expect_ship_date = $expect_ship_date;
        $order_header->cond_payment = $payment_condition->common->common_name;
        $order_header->payment_cond_id = $cond;
        $order_header->type_cond_pay = $cond;
        $order_header->order_no_by_customer = $order_no;
        $order_header->sale_type_money = $type_money_sell;
        $order_header->type_money = $type_money_sell;

        $order_header->fee_shipping = $fee_shipping;
        $order_header->fee_daibiki = $fee_daibiki;
        $order_header->receive_order_date = date_create();
        if ($order_header->save()) {
            $order_header_id = $order_header->id;
            $count = 1;
            $total_order_qty = 0;
            $total_money_buy = 0;
            $list_ID = '0,';
            foreach ($list_ids as $item) {
                $list_ID .= $item . ',';
                $quote_info = QuoteCustomer::with(['request_vendors', 'request_vendors.rfq_request'])->find($item);
                $order_detail = new OrderDetail();
                $order_detail->kubun = $quote_info->request_vendors->rfq_request->kbn;
                $order_detail->kubun2 = $quote_info->request_vendors->rfq_request->kbn2;
                $order_detail->order_KBN = 0;
                $order_detail->quote_id = $quote_info->id;
                $order_detail->order_header_id = $order_header->id;
                $order_detail->request_address_id = $request_address->id;
                $order_detail->send_address_id = $send_address->id;
                $order_detail->tax_id = $sql_tax->id;
                $order_detail->supplier_id = $quote_info->supplier_id;
                $order_detail->est_date = $quote_info->quote_date;
                $order_detail->katashiki = $quote_info->katashiki;
                $order_detail->katashiki_not_spl = $quote_info->katashiki_not_spl;
                $order_detail->maker = $quote_info->maker;
                $order_detail->dc = $quote_info->dc;
                $order_detail->order_no_by_customer = $order_no;
                $order_detail->message = $quote_info->comment_bus;
                $order_detail->sale_qty = $quote_info->sell_quantity;
                $order_detail->ship_quantity = $quote_info->buy_quantity;
                $order_detail->sale_unit = $quote_info->unit_sell;
                $order_detail->sale_cost = $quote_info->unit_price_sell;
                $order_detail->unit_buy_ship = $quote_info->unit_price_buy;
                $order_detail->condition2 = $quote_info->request_vendors->rfq_request->condition2;
                $order_detail->sale_money = $quote_info->money_sell;
                $order_detail->price_ship = $quote_info->money_buy;
                $order_detail->type_money_ship = $quote_info->type_money_buy;
                $code_rank = '';
                $current_count = OrderDetail::count();
                if ($current_count == 0)
                    $code_number = 1;
                else
                    $code_number = OrderDetail::count() + 1;

                $number_zero = 6 - (strlen($code_number));
                for ($i = 1; $i <= $number_zero; $i++)
                    $code_rank .= '0';

                $order_detail->code_send = $code_rank . $code_number;
                $order_detail->save();
                $quote_info->is_order = 1;

                $list_quote = QuoteCustomer::where('rank_quote', '=', $quote_info->rank_quote)->get();

                if ($list_quote->count() > 0) {
                    foreach ($list_quote as $quote_item) {
                        $quote_item->is_together = 1;
                        $quote_item->save();
                    }
                }

                $total_order_qty += $quote_info->sell_quantity;
                $total_money_buy += $quote_info->money_sell;
                $count = $count + 1;
                $quote_info->save();
            }


            $rate = Rate::where('type_money', '=', $type_money_sell)->first();

            $total_money_with_all = (($total_money_buy + $fee_daibiki + $fee_shipping) * $sql_tax->tax) + ($total_money_buy + $fee_daibiki + $fee_shipping);

            $total_money_with_all = $total_money_with_all * $rate->sale_rate;
            $order_money = $customer->user_info->order_money + $total_money_with_all;
            $order_qty = $customer->user_info->order_qty + $total_order_qty;

            $customer_info = UserInfo::find($customer->user_info_id);
            $customer_info->order_money = $order_money;
            $customer_info->order_qty = $order_qty;
            $customer_info->save();

            $customer_log = CustomerLog::where('customer_id', $customer->id)
                ->whereDate('request_date', '=', date_create('y-m-d'))
                ->first();

            if (!isset($old_log)) {
                $customer_log = new CustomerLog();
                $customer_log->order_money = $total_money_with_all;
                $customer_log->order_qty = $total_order_qty;
                $customer_log->customer_id = $customer->id;
            } else {
                $customer_log->order_money = $customer_log->order_money + $total_money_with_all;
                $customer_log->order_qty = $customer_log->order_qty + $total_order_qty;
            }
            $customer_log->request_date = date_create();
            $customer_log->save();


            //sending message
            $mail_title = "発注確認メール";

            $details = [
                'address' => $request_address,
                'quote_info' => QuoteCustomer::with(['request_vendors', 'customer', 'customer.user_info'])->whereIn('id', $list_ids)->get(),
                'fee_shipping' => $fee_shipping,
                'fee_daibiki' => $fee_daibiki,
                'code_quote' => $code_quote,
                'sub_total' => $total_money_buy,
                'tax' => $sql_tax->tax,
                'notice' => '',
                'mail_title' => $mail_title
            ];

            $mail_to = $customer->user_info->email1;
            // $mail_to = "sandy815.dev@outlook.com";
            Mail::to($mail_to)
                ->send(new \App\Mail\SendToCustomerOrderMail($details));
            return 'success';
        }
    }

    public function get_quote_more_list(Request $request)
    {
        $current_number = $request->currentLength;
        $data_query = $this->get_quote_list_query($request);

        if ($current_number > $data_query->count())
            return json_encode([]);
        else if ($data_query->count() - $current_number > QuoteCustomer::$quote_limit) {
            $ids = $data_query->slice($current_number, QuoteCustomer::$quote_limit)->keys();
            return UserInfo::convertCollectionToArray($data_query, $ids);
        } else {
            $ids = $data_query->slice($current_number, $data_query->count() - QuoteCustomer::$quote_limit)->keys();
            return UserInfo::convertCollectionToArray($data_query, $ids);
        }
    }

    private function get_history_list(Request $request)
    {
        if ($request->has('katashiki') && $request->katashiki != null) {
            $data_query = QuoteCustomer::with(
                ['supplier', 'supplier.user_info']
            )
                ->where('katashiki', 'LIKE', "{$request->katashiki}%")
                ->where('unit_price_sell', '!=', 0)
                ->whereNotNull('date_send')
                ->get()
                ->whereNotNull('supplier');

            $table_info_arr = explode('.', $request->filterColumn);
            $table_name = 'quote_customer';
            $field_name = array_pop($table_info_arr);

            if (count($table_info_arr) > 0)
                $table_name = array_pop($table_info_arr);

            if (in_array($field_name, ['quote_date'])) {
                if ($request->order[0]['dir'] == 'asc')
                    $data_query = $data_query->sortBy($request->filterColumn);
                else
                    $data_query = $data_query->sortByDesc($request->filterColumn);
                return $data_query;
            }

            $field_type = Schema::getColumnType($table_name, $field_name);
            if ($request->order[0]['dir'] == 'asc') {
                if ($field_type == 'string')
                    $histroy_list = $data_query->sortBy($request->filterColumn, SORT_STRING);
                else
                    $histroy_list = $data_query->sortBy($request->filterColumn, SORT_NUMERIC);
            } else {
                if ($field_type == 'string')
                    $histroy_list = $data_query->sortByDesc($request->filterColumn, SORT_STRING);
                else
                    $histroy_list = $data_query->sortByDesc($request->filterColumn, SORT_NUMERIC);
            }
            return $histroy_list;
        } else
            return collect([]);
    }

    public function get_history(Request $request)
    {
        $history_list = $this->get_history_list($request);
        return Datatables::of($history_list->slice(0, QuoteCustomer::$history_limit))->make(true);
    }

    public function get_history_more_data(Request $request)
    {
        $current_number = $request->currentLength;
        $history_list = $this->get_history_list($request);

        if ($current_number > $history_list->count())
            return json_encode([]);
        else if ($history_list->count() - $current_number > QuoteCustomer::$history_limit) {
            $ids = $history_list->slice($current_number, QuoteCustomer::$history_limit)->keys();
            return UserInfo::convertCollectionToArray($history_list, $ids);
        } else {
            $ids = $history_list->slice($current_number, $history_list->count() - QuoteCustomer::$history_limit)->keys();
            return UserInfo::convertCollectionToArray($history_list, $ids);
        }
    }
}
