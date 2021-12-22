<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Supplier;
use App\Models\UserInfo;
use App\Models\SupplierLog;
use App\Models\Address;
use App\Models\PaymentCondition;
use App\Http\Requests\Admin\RegisterSupplierRequest;

class SupplierController extends Controller
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
    public function store(RegisterSupplierRequest $request)
    {

        $address = new Address;
        $address->zip = $request->postalCode;
        $address->tel = $request->phoneNumber;
        $address->fax = $request->fax;
        $address->country = $request->country;
        $address->address1 = $request->address;
        $address->save();

        $user_info = new UserInfo;
        $user_info->type = "supplier";
        $user_info->address_id = $address->id;
        $user_info->company_name = $request->compName;
        $user_info->company_name_kana = $request->compNameKana;
        $user_info->message1 = $request->remarks;
        $user_info->email1 = $request->email1;
        $user_info->email2 = $request->email2;
        $user_info->email3 = $request->email3;
        $user_info->email4 = $request->email4;
        $user_info->rank = 1;
        $user_info->save();

        $payment = new PaymentCondition;
        $payment->user_info_id = $user_info->id;
        $payment->common_id = $request->payTerm;
        $payment->payment_flag = 2;
        $payment->save();

        $supplier = new Supplier;
        $supplier->user_info_id = $user_info->id;
        $supplier->representative = $request->personInCharge;
        $supplier->district = $request->prefectures;
        $supplier->daily_rfq = $request->dailyRFQ;
        $supplier->save();
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
    public function update(RegisterSupplierRequest $request, $id)
    {
        $supplier = Supplier::find($id);
        $supplier->representative = $request->personInCharge;
        $supplier->district = $request->prefectures;
        $supplier->daily_rfq = $request->dailyRFQ;
        $supplier->save();

        $user_info = UserInfo::find($supplier->user_info_id);
        $user_info->type = "supplier";
        $user_info->company_name = $request->compName;
        $user_info->company_name_kana = $request->compNameKana;
        $user_info->message1 = $request->remarks;
        $user_info->rank = 1;
        $user_info->email1 = $request->email1;
        $user_info->email2 = $request->email2;
        $user_info->email3 = $request->email3;
        $user_info->email4 = $request->email4;
        $user_info->save();

        $address = Address::find($user_info->address_id);
        if (!isset($address)) {
            $address = new Address;
        }
        $address->zip = $request->postalCode;
        $address->tel = $request->phoneNumber;
        $address->fax = $request->fax;
        $address->country = $request->country;
        $address->address1 = $request->address;
        $address->save();
        $payment = PaymentCondition::where('user_info_id', $user_info->id)->first();
        if (!isset($payment)) {
            $payment = new PaymentCondition;
            $payment->user_info_id = $user_info->id;
        }
        $payment->common_id = $request->payTerm;
        $payment->payment_flag = 2;
        $payment->save();
        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Supplier::find($id)->delete();
        return 'success';
    }

    public function list()
    {
        $suppliers = Supplier::with(['user_info', 'user_info.payment', 'user_info.address'])->get();
        return json_encode($suppliers);
    }

    public function get_supplier_log(Request $request)
    {
        $today = date("Y-m-d", strtotime('now'));
        $startdayOneMonth = strtotime('-1 month', strtotime($today)); // 1 month
        $startdayOneMonth = date('Y-m-d', $startdayOneMonth);
        $startday3Month = strtotime('-6 month', strtotime($today)); // 3 month
        $startday3Month = date('Y-m-d', $startday3Month);

        $startday6Month = strtotime('-12 month', strtotime($today)); // 6 month
        $startday6Month = date('Y-m-d', $startday6Month);

        $supplier_log_query = SupplierLog::select(
            DB::raw(
                'SUM(est_req_count) as estReqCount,
                SUM(ans_est_count) as ansEstCount,
                SUM(ans_emp_count) as ansEmpCount,
                SUM(ship_order_count) as shipOrderCount,
                SUM(ship_order_money) as shipOrderMoney,
                SUM(return_time) as returnTime,
                SUM(cancel_OP_Qty) as cancelOpQty
            '
            )
        );

        $log1 = $supplier_log_query->where('supplier_id', '=', $request->id)
            ->whereDate('request_date', '>=', $startdayOneMonth)
            ->whereDate('request_date', '<=', $today)
            ->get();

        $log3 = $supplier_log_query->where('supplier_id', '=', $request->id)
            ->whereDate('request_date', '>=', $startday3Month)
            ->whereDate('request_date', '<=', $today)
            ->get();

        $log6 = $supplier_log_query->where('supplier_id', $request->id)
            ->whereDate('request_date', '>=', $startday6Month)
            ->whereDate('request_date', '<=', $today)
            ->get();

        $logAll = $supplier_log_query->where('supplier_id', $request->id)
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
