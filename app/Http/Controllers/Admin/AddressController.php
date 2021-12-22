<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\Address;
use App\Http\Requests\Admin\AddressRequest;

class AddressController extends Controller
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
    public function store(AddressRequest $request)
    {
        if ($request->has("type")) {
            $type = ($request->type == 'billing') ? 1 : 2;
            $index = Address::where([
                ['address_type', '=', $type],
                ['user_info_id', '=', $request->user_info_id],
            ])->count();
        } else {
            $type = 0;
            $index = 0;
        }
        $address = new Address;
        $address->user_info_id = $request->user_info_id;
        $address->zip = $request->zip;
        $address->comp_type = $request->compName;
        $address->address1 = $request->prefecture;
        $address->address2 = $request->municipality;
        $address->address3 = $request->address3;
        $address->address4 = $request->buildingName;
        $address->part_name = $request->department;
        $address->address_type = $type;
        $address->address_index = $request->address_index;
        $address->customer_name = $request->addressNames;
        $address->tel = $request->tel;
        $address->fax = $request->fax;
        if ($request->user_info_id > 0) {
            $address->save();
        }
        return json_encode($address);
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
    public function update(AddressRequest $request, $id)
    {
        if ($request->has("type")) {
            $type = ($request->type == 'billing') ? 1 : 2;
        } else {
            $type = 0;
        }
        $address = Address::find($id);
        $address->zip = $request->zip;
        $address->comp_type = $request->compName;
        $address->address1 = $request->prefecture;
        $address->address2 = $request->municipality;
        $address->address3 = $request->address3;
        $address->address4 = $request->buildingName;
        $address->part_name = $request->department;
        $address->address_type = $type;
        $address->tel = $request->tel;
        $address->fax = $request->fax;
        $address->customer_name = $request->addressNames;
        $address->save();
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
        //
    }
}
