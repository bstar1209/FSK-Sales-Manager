<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $payment->close_date = date_create($request->closeDate);
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

        $user_info->email1 = $request->email1;
        $user_info->email2 = $request->email2;
        $user_info->email3 = $request->email3;
        $user_info->email4 = $request->email4;
        $user_info->address_id = $address->id;
        $user_info->save();
        $customer->save();
        return json_encode($user_info->address_id);
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
}
