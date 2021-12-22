<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

use Yajra\Datatables\Datatables;

use App\Models\RfqRequest;
use App\Models\UserInfo;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\RequestQuoteVendor;
use App\Http\Requests\Admin\CreateRfqRequest;

class RfqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/rfq/index');
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
        $rfq = new RfqRequest;
        if ($request->has('detail_id') && $request->detail_id != null) {
            $rfq->detail_id = $request->detail_id;
            $rfq->child_index = RfqRequest::where('detail_id', '=', $request->detail_id)->count() + 1;
        } else
            $rfq->detail_id = 0;
        $rfq->customer_id = $request->customer_id;
        $rfq->maker = $request->maker;
        $rfq->katashiki = $request->katashiki;
        $rfq->katashiki_not_spl = $request->katashiki;
        $rfq->count_aspiration = $request->countAspiration;
        $rfq->price_aspiration = $request->priceAspiration;
        $rfq->kbn = $request->kbn;
        $rfq->comment = $request->comment;
        $rfq->dc = $request->dc;
        $rfq->quantity_aspiration = $request->countAspiration;
        $rfq->is_old_data = false;
        $rfq->condition1 = $request->condition1;
        $rfq->condition2 = $request->condition2;
        $rfq->condition3 = $request->condition3;
        $rfq->is_cancel = 0;
        $rfq->is_solved = 1;
        if ($request->solved_date)
            $rfq->solved_date = date_create($request->solved_date);
        if ($request->cancel_date)
            $rfq->cancel_date = date_create($request->cancel_date);
        $rfq->save();

        if ($rfq->detail_id == 0) {
            $rfq->detail_id = $rfq->id;
            $rfq->child_index = 1;
            $rfq->save();
        }

        $new_rfq = RfqRequest::with([
            'customer', 'customer.user_info', 'customer.user_info.emails',
            'customer.user_info.address', 'customer.user_info.payment',
            'customer.user_info.billing_address',
            'customer.user_info.deliver_address',
        ])->find($rfq->id);

        return json_encode($new_rfq);
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
    public function update(CreateRfqRequest $request, $id)
    {
        $rfq = RfqRequest::find($id);
        $rfq->customer_id = $request->customer_id;
        $rfq->maker = $request->maker;
        $rfq->katashiki = $request->katashiki;
        $rfq->katashiki_not_spl = $request->katashiki;
        $rfq->count_aspiration = $request->countAspiration;
        $rfq->price_aspiration = $request->priceAspiration;
        $rfq->kbn = $request->kbn;
        $rfq->comment = $request->comment;
        $rfq->dc = $request->dc;
        $rfq->quantity_aspiration = $request->countAspiration;
        $rfq->is_old_data = false;

        if ($request->solved_date)
            $rfq->solved_date = date_create($request->solved_date);

        if ($request->cancel_date)
            $rfq->cancel_date = date_create($request->cancel_date);

        $rfq->condition1 = $request->condition1;
        $rfq->condition2 = $request->condition2;
        $rfq->condition3 = $request->condition3;
        $rfq->save();

        $customer = Customer::find($rfq->customer_id);
        $customer->representative = $request->representative;
        $customer->save();

        $new_rfq = RfqRequest::with([
            'customer', 'customer.user_info', 'customer.user_info.emails',
            'customer.user_info.address', 'customer.user_info.payment',
            'customer.user_info.billing_address',
            'customer.user_info.deliver_address',
        ])->find($rfq->id);

        return json_encode($new_rfq);
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

    private function get_unrfq_list(Request $request)
    {
        $data_query = RfqRequest::with([
            'customer', 'customer.user_info', 'customer.user_info.emails',
            'customer.user_info.address', 'customer.user_info.payment',
            'customer.user_info.billing_address',
            'customer.user_info.deliver_address',
        ]);

        if ($request->has('searchStatus') && $request->searchStatus != null) {
            if ($request->searchStatus == 1) {
                $data_query = $data_query->where([
                    ['is_solved', '=', 1]
                    // ['is_cancel', '=', 0]
                ]);
            }

            if ($request->searchStatus == 2) {
                $data_query = $data_query->where('is_solved', '=', '0');
            }
        }

        if ($request->has('customerName') && $request->customerName != null) {
            $data_query = $data_query->whereHas('customer.user_info', function ($query) use ($request) {
                $query->where('company_name', 'LIKE', "$request->customerName%");
            });
        }

        if ($request->has('customerId') && $request->customerId != null) {
            $data_query = $data_query->where('customer_id', 'LIKE', "%$request->customerId%");
        }

        if ($request->has('rfqRequestId') && $request->rfqRequestId != null) {
            $data_query = $data_query->where('id', 'LIKE', "%$request->rfqRequestId%");
        }

        $receptionDate = date('Y-m-d',strtotime($request->receptionDate));
        
        if ($request->has('receptionDate') && $request->receptionDate != null) {
            $data_query = $data_query->whereDate('created_at', $receptionDate);
        }

        if ($request->has('modelNumber') && $request->modelNumber != null) {
            $data_query = $data_query->where('katashiki', 'LIKE', "%$request->modelNumber%");
        }

        $rfq_list = $data_query->get()->whereNotNull('customer');

        $table_info_arr = explode('.', $request->filterColumn);
        $table_name = 'rfq_request';
        $field_name = array_pop($table_info_arr);

        if (count($table_info_arr) > 0)
            $table_name = array_pop($table_info_arr);

        if ($field_name == '0') {
            $field_name = 'detail_id';
            $request->filterColumn = 'detail_id';
        }

        if (in_array($field_name, ['created_at', 'cancel_date', 'solved_date'])) {
            if ($request->order[0]['dir'] == 'asc')
                $rfq_list = $rfq_list->sortBy($request->filterColumn);
            else
                $rfq_list = $rfq_list->sortByDesc($request->filterColumn);
            return $rfq_list;
        }

        $field_type = Schema::getColumnType($table_name, $field_name);
        if ($request->order[0]['dir'] == 'asc') {
            if ($field_type == 'string')
                $rfq_list = $rfq_list->sortBy($request->filterColumn, SORT_STRING);
            else
                $rfq_list = $rfq_list->sortBy($request->filterColumn, SORT_NUMERIC);
        } else {
            if ($field_type == 'string')
                $rfq_list = $rfq_list->sortByDesc($request->filterColumn, SORT_STRING);
            else
                $rfq_list = $rfq_list->sortByDesc($request->filterColumn, SORT_NUMERIC);
        }

        return $rfq_list;
    }

    public function get_unRfq(Request $request)
    {
        $rfq_list = $this->get_unrfq_list($request);
        return Datatables::of($rfq_list->slice(0, 9))->make(true);
    }

    public function get_more_data(Request $request)
    {
        $current_number = $request->currentLength;
        $rfq_list = $this->get_unrfq_list($request);
        if ($current_number > $rfq_list->count())
            return json_encode([]);
        else if ($rfq_list->count() - $current_number > RfqRequest::$row_limit) {
            $ids = $rfq_list->slice($current_number, RfqRequest::$row_limit)->keys();
            return UserInfo::convertCollectionToArray($rfq_list, $ids);
        } else {
            $ids = $rfq_list->slice($current_number, $rfq_list->count() - RfqRequest::$row_limit)->keys();
            return UserInfo::convertCollectionToArray($rfq_list, $ids);
        }
    }

    private function get_quote_list(Request $request)
    {
        $data = RequestQuoteVendor::with([
            'vendor', 'vendor.user_info', 'rfq_request', 'rfq_request.customer',
            'rfq_request.customer.user_info', 'rfq_request.customer.user_info.payment', 'messages',
            'vendor.user_info.address',
            'vendor.user_info.emails', 'vendor.user_info.payment',
            'vendor.user_info.payment.common'
        ])->where('rfq_request_id', '=', $request->requestId)
            ->get()
            ->whereNotNull('vendor')
            ->whereNotNull('rfq_request.customer');

        $table_info_arr = explode('.', $request->filterColumn);
        $table_name = 'request_quote_vendor';
        $field_name = array_pop($table_info_arr);

        if (count($table_info_arr) > 0)
            $table_name = array_pop($table_info_arr);

        if (in_array($field_name, ['rfq_request.created_at', 'date_quote'])) {
            if ($request->order[0]['dir'] == 'asc')
                $data = $data->sortBy($request->filterColumn);
            else
                $data = $data->sortByDesc($request->filterColumn);
            return $data;
        }

        $field_type = Schema::getColumnType($table_name, $field_name);
        if ($request->order[0]['dir'] == 'asc') {
            if ($field_type == 'string')
                $data = $data->sortBy($request->filterColumn, SORT_STRING);
            else
                $data = $data->sortBy($request->filterColumn, SORT_NUMERIC);
        } else {
            if ($field_type == 'string')
                $data = $data->sortByDesc($request->filterColumn, SORT_STRING);
            else
                $data = $data->sortByDesc($request->filterColumn, SORT_NUMERIC);
        }

        return $data;
    }

    public function get_request_quote_vendor(Request $request)
    {
        $quote_list = $this->get_quote_list($request);
        return Datatables::of($quote_list->slice(0, RequestQuoteVendor::$request_quote_vendor_limit))->make(true);
    }

    public function get_request_quote_vendor_more_data(Request $request)
    {
        $current_number = $request->currentLength;

        $quote_list = $this->get_quote_list($request);

        if ($current_number > $quote_list->count())
            return json_encode([]);
        else if ($quote_list->count() - $current_number > RequestQuoteVendor::$request_quote_vendor_limit) {
            $ids = $quote_list->slice($current_number, RequestQuoteVendor::$request_quote_vendor_limit)->keys();
            return UserInfo::convertCollectionToArray($quote_list, $ids);
        } else {
            $ids = $quote_list->slice($current_number, $quote_list->count() - RequestQuoteVendor::$request_quote_vendor_limit)->keys();
            return UserInfo::convertCollectionToArray($quote_list, $ids);
        }
    }

    private function get_history_list(Request $request)
    {
        if ($request->has('katashiki') && $request->katashiki != null) {
            $data = RequestQuoteVendor::with([
                'vendor', 'vendor.user_info',
                'rfq_request', 'rfq_request.customer',
                'rfq_request.customer.user_info',
                'quote_customer',
                'quote_customer.order_detail',
                'quote_customer.order_detail.order_header'
            ])
                ->where('katashiki', 'LIKE', "{$request->katashiki}%")
                ->where('unit_price_buy', '>', 0)
                ->get()
                ->whereNotNull('quote_customer')
                ->whereNotNull('vendor')
                ->whereNotNull('date_quote');

            $table_info_arr = explode('.', $request->filterColumn);
            $table_name = 'request_quote_vendor';
            $field_name = array_pop($table_info_arr);

            if (count($table_info_arr) > 0)
                $table_name = array_pop($table_info_arr);

            if ($field_name == '0') {
                $table_name = 'quote_customer';
                $field_name = 'unit_price_sell';
                $request->filterColumn = 'quote_customer.unit_price_sell';
            } else if ($field_name == '1') {
                $table_name = 'order_header';
                $field_name = 'receive_order_date';
                $request->filterColumn = 'quote_customer.order_detail.order_header.receive_order_date';
            }

            if (in_array($field_name, ['date_quote', 'quote_customer.order_detail.order_header.receive_order_date'])) {
                if ($request->order[0]['dir'] == 'asc')
                    $data = $data->sortBy($request->filterColumn);
                else
                    $data = $data->sortByDesc($request->filterColumn);
                return $data;
            }

            $field_type = Schema::getColumnType($table_name, $field_name);
            if ($request->order[0]['dir'] == 'asc') {
                if ($field_type == 'string')
                    $data = $data->sortBy($request->filterColumn, SORT_STRING);
                else
                    $data = $data->sortBy($request->filterColumn, SORT_NUMERIC);
            } else {
                if ($field_type == 'string')
                    $data = $data->sortByDesc($request->filterColumn, SORT_STRING);
                else
                    $data = $data->sortByDesc($request->filterColumn, SORT_NUMERIC);
            }

            return $data;
        }
        return collect([]);
    }

    public function get_history(Request $request)
    {
        $history_list = $this->get_history_list($request);
        return Datatables::of($history_list->slice(0, RequestQuoteVendor::$history_limit))->make(true);
    }

    public function get_history_more_data(Request $request)
    {
        $current_number = $request->currentLength;
        $history_list = $this->get_history_list($request);
        if ($current_number > $history_list->count())
            return json_encode([]);
        else if ($history_list->count() - $current_number > RequestQuoteVendor::$history_limit) {
            $ids = $history_list->slice($current_number, RequestQuoteVendor::$history_limit)->keys();
            return UserInfo::convertCollectionToArray($history_list, $ids);
        } else {
            $ids = $history_list->slice($current_number, $history_list->count() - RequestQuoteVendor::$history_limit)->keys();
            return UserInfo::convertCollectionToArray($history_list, $ids);
        }
    }

    public function daily_rfq(Request $request)
    {
        $ids = Supplier::get_daily_rfq_suppliers($request->id);
        foreach ($ids as $id) {
            // if (RequestQuoteVendor::where([['rfq_request_id', $request->id], ['supplier_id', $id]])->count() != 0)
            //     continue;
            $quote = new RequestQuoteVendor;
            $quote->supplier_id = $id;
            $quote->rfq_request_id = $request->id;
            $quote->rfq_request_child_id = $request->id;
            $quote->maker = $request->maker;
            $quote->katashiki = $request->katashiki;
            $quote->katashiki_not_spl = $request->katashiki;
            $quote->quantity_buy = $request->countAspiration;
            $quote->save();
        }
        return $ids;
    }

    public function date_rfq(Request $request)
    {
        $rfq = RfqRequest::find($request->id);

        if ($request->solved_date)
            $rfq->solved_date = date_create($request->solved_date);
        elseif (!$request->solved_date)
            $rfq->solved_date = null;

        $rfq->save();

        return 'success';
    }

    public function change_rfq_status(Request $request)
    {
        $rfq = RfqRequest::find($request->id);
        $rfq->is_solved = $rfq->is_solved == 1 ? 0 : 1;
        $rfq->save();
        return 'success';
    }
}
