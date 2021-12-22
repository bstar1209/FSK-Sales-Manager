<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Customer;
use App\Models\UserInfo;
use App\Models\UserEmails;
use App\Models\Address;
use App\Models\Common;
use App\Models\PaymentCondition;
use App\Models\CustomerLog;
use App\Http\Requests\Admin\UpdateCustomerRequest;

class CustomerController extends Controller
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
    public function store(UpdateCustomerRequest $request)
    {
        $customer = new Customer;
        $customer->representative = $request->representative;
        $customer->representative_business = $request->sales;
        $user_info = new UserInfo;
        $user_info->company_name = $request->compName;
        $user_info->company_name_kana = $request->compNameKana;
        $user_info->type = 'customer';
        if ($request->rank) {
            $user_info->rank = $request->rank;
        }
        $user_info->message1 = $request->message;

        if (!isset($request->payment[0]))
            $common_id = Common::where('common_type', '=', Common::$custmer_type)->first()->id;
        else
            $common_id = $request->payment[0];

        $flag_list = Common::where('common_type', '=', Common::$payment_flag)->get()->pluck('id')->toArray();

        if (!isset($request->payment[1]))
            $payment_flag = 4;
        else
            $payment_flag = array_search($request->payment[1], $flag_list) + 4;

        if ($payment_flag == 4)
            $approve = 1;
        else if ($payment_flag == 5)
            $approve = 2;
        else if ($payment_flag == 6)
            $approve = 3;
        else
            $approve = 1;

        $payment = new PaymentCondition;
        $payment->user_info_id = 0;
        $payment->common_id = $common_id;
        $payment->payment_flag = $approve;
        $payment->close_date = date_create($request->closeDate);
        $payment->send_date = date_create($request->sendDate);
        $payment->send_date = date_create($request->sendDate);
        $payment->save();

        $address = new Address;
        $address->address1 = "address1";
        $address->tel = $request->tel;
        $address->homepages = $request->homepage;
        $address->comp_type = $request->businessType;
        $address->fax = $request->fax;
        $address->part_name = $request->department;
        $address->save();

        $user_info->email1 = $request->email1;
        $user_info->email2 = $request->email2;
        $user_info->email3 = $request->email3;
        $user_info->email4 = $request->email4;
        $user_info->address_id = $address->id;
        $user_info->save();

        $customer->user_info_id = $user_info->id;
        $payment->user_info_id = $user_info->id;
        $payment->save();
        $customer->save();

        if ($request->billingAddress) {
            foreach ($request->billingAddress as $address) {
                if ($address) {
                    $new = new Address;
                    $new->user_info_id = $user_info->id;
                    $new->zip = $address['zip'];
                    $new->comp_type = $address['comp_type'];
                    $new->address1 = $address['address1'];
                    $new->address2 = $address['address2'];
                    $new->address3 = $address['address3'];
                    $new->address4 = $address['address4'];
                    $new->part_name = $address['part_name'];
                    $new->address_type = $address['address_type'];
                    $new->customer_name = $address['customer_name'];
                    $new->tel = $address['tel'];
                    $new->fax = $address['fax'];
                    $new->save();
                }
            }
        }

        if ($request->deliveryAddress) {
            foreach ($request->deliveryAddress as $address) {
                if ($address) {
                    $new = new Address;
                    $new->user_info_id = $user_info->id;
                    $new->zip = $address['zip'];
                    $new->comp_type = $address['comp_type'];
                    $new->address1 = $address['address1'];
                    $new->address2 = $address['address2'];
                    $new->address3 = $address['address3'];
                    $new->address4 = $address['address4'];
                    $new->part_name = $address['part_name'];
                    $new->address_type = $address['address_type'];
                    $new->customer_name = $address['customer_name'];
                    $new->tel = $address['tel'];
                    $new->fax = $address['fax'];
                    $new->save();
                }
            }
        }

        return json_encode('success');
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
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = Customer::find($id);
        $customer->representative = $request->representative;
        $customer->representative_business = $request->sales;

        $user_info = UserInfo::find($customer->user_info_id);
        $user_info->company_name = $request->compName;
        $user_info->company_name_kana = $request->compNameKana;
        $user_info->rank = $request->rank;
        $user_info->message1 = $request->message;

        $ids = PaymentCondition::where('user_info_id', $user_info->id)->get()->pluck('id')->toArray();
        PaymentCondition::destroy($ids);
        if (!isset($request->payment[0]))
            $common_id = Common::where('common_type', '=', Common::$custmer_type)->first()->id;
        else
            $common_id = $request->payment[0];

        $flag_list = Common::where('common_type', '=', Common::$payment_flag)->get()->pluck('id')->toArray();

        if (!isset($request->payment[1]))
            $payment_flag = 4;
        else
            $payment_flag = array_search($request->payment[1], $flag_list) + 4;

        if ($payment_flag == 4)
            $approve = 1;
        else if ($payment_flag == 5)
            $approve = 2;
        else if ($payment_flag == 6)
            $approve = 3;
        else
            $approve = 1;

        $payment = new PaymentCondition;
        $payment->user_info_id = $user_info->id;
        $payment->common_id = $common_id;
        $payment->payment_flag = $approve;
        if($request->closeDate && $request->closeDate != 0)
            $payment->close_date = date_create($request->closeDate);
        if($request->sendDate && $request->sendDate != 0)
            $payment->send_date = date_create($request->sendDate);
        $payment->save();

        $address = Address::find($user_info->address_id);

        if (isset($address)) {
            $address = new Address;
            $address->address1 = "address1";
        }
        $address->tel = $request->tel;
        $address->homepages = $request->homepage;
        $address->comp_type = $request->businessType;
        $address->fax = $request->fax;
        $address->part_name = $request->department;
        $address->save();
        // return json_encode(Auth::user());
        
        // $user = User::find(Auth::user()->id);
        // $user->email = $request->email1;
        // $user->save();

        $user_info->email1 = $request->email1;
        $user_info->email2 = $request->email2;
        $user_info->email3 = $request->email3;
        $user_info->email4 = $request->email4;
        $user_info->address_id = $address->id;
        $user_info->save();
        $customer->save();

        return json_encode('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Customer::find($id)->delete();
        return json_encode('success');
    }

    public function list()
    {
        $customers = Customer::with(['user_info', 'user_info.payment'])->get();
        return json_encode($customers);
    }

    public function ajax_list(Request $request)
    {
        $columns = $request->columns;
        $order_column = $columns[$request->order[0]['column']]['name'];
        $order_dir = $request->order[0]['dir'];

        $customers = Customer::with([
            'user_info', 'user_info.payment', 'user_info.address',
            'user_info.billing_address', 'user_info.deliver_address',
            'salesman'
        ]);
        $total_length = $customers->count();

        if ($request->customer_id) {
            $customers->where('id', 'LIKE', $request->customer_id);
        }

        if ($request->representative) {
            $customers->where('name', 'LIKE', $request->representative);
        }

        if ($request->company_name) {
            $customers->whereHas('user_info', function ($query) use ($request) {
                $query->where('company_name', $request->company_name);
            });
        }

        if ($request->rank) {
            $customers->whereHas('user_info', function ($query) use ($request) {
                $query->where('rank', $request->rank);
            });
        }

        if ($request->transaction) {
            switch ($request->transaction) {
                case 1:
                    $customers->whereHas('user_info.payment', function ($query) use ($request) {
                        // $query->where('common_id', 4)->where('payment_flag', 1);
                        $query->where('payment_flag', 0);
                    });
                    break;
                case 2:
                    $customers->whereHas('user_info.payment', function ($query) use ($request) {
                        $query->where('payment_flag', 1);
                    });
                    break;
                case 3:
                    $customers->whereHas('user_info.payment', function ($query) use ($request) {
                        $query->where('payment_flag', 2);
                    });
                    break;
                case 4:
                    $customers->whereHas('user_info.payment', function ($query) use ($request) {
                        $query->where('payment_flag', 3);
                    });
                    break;
            }
        }

        return json_encode($customers->get());
    }

    public function get_customer_log(Request $request)
    {
        $today = date("Y-m-d", strtotime('now'));
        $startdayOneMonth = strtotime('-1 month', strtotime($today)); // 1 month
        $startdayOneMonth = date('Y-m-d', $startdayOneMonth);
        $startday3Month = strtotime('-6 month', strtotime($today)); // 3 month
        $startday3Month = date('Y-m-d', $startday3Month);

        $startday6Month = strtotime('-12 month', strtotime($today)); // 6 month
        $startday6Month = date('Y-m-d', $startday6Month);

        $log1 = CustomerLog::select(
            DB::raw(
                'SUM(search_count) as search_cout,
                    SUM(ans_count) as answer_count,
                    SUM(res_count) as result_count,
                    SUM(order_Qty) as order_qty,
                    SUM(order_money) as order_money
                '
            )
        )->where('customer_id', '=', $request->id)
            ->whereDate('request_date', '>=', $startdayOneMonth)
            ->whereDate('request_date', '<=', $today)
            ->get();

        $log3 = CustomerLog::select(
            DB::raw(
                'SUM(search_count) as search_cout,
                SUM(ans_count) as answer_count,
                SUM(res_count) as result_count,
                SUM(order_Qty) as order_qty,
                SUM(order_money) as order_money
            '
            )
        )->where('customer_id', '=', $request->id)
            ->whereDate('request_date', '>=', $startday3Month)
            ->whereDate('request_date', '<=', $today)
            ->get();

        $log6 = CustomerLog::select(
            DB::raw(
                'SUM(search_count) as search_cout,
                SUM(ans_count) as answer_count,
                SUM(res_count) as result_count,
                SUM(order_Qty) as order_qty,
                SUM(order_money) as order_money
            '
            )
        )->where('customer_id', $request->id)
            ->whereDate('request_date', '>=', $startday6Month)
            ->whereDate('request_date', '<=', $today)
            ->get();

        $logAll = CustomerLog::select(
            DB::raw(
                'SUM(search_count) as search_cout,
                SUM(ans_count) as answer_count,
                SUM(res_count) as result_count,
                SUM(order_Qty) as order_qty,
                SUM(order_money) as order_money
            '
            )
        )->where('customer_id', $request->id)
            ->get();

        $logs = [
            'log1' => $log1,
            'log3' => $log3,
            'log6' => $log6,
            'all' => $logAll
        ];

        return json_encode($logs);
    }
}
