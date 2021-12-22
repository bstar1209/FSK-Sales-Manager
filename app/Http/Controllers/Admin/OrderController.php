<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Yajra\Datatables\Datatables;

use App\Models\OrderDetail;
use App\Models\OrderHeader;
use App\Models\ShipTo;
use App\Models\Transport;
use App\Models\UserInfo;

use DateTime;
use Meneses\LaravelMpdf\Facades\LaravelMpdf as PDF;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/order/index');
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
        //
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


    private function get_order_list_query(Request $request)
    {
        $order_query = OrderDetail::with([
            'order_header', 'quote_customer', 'quote_customer.request_vendors', 'quote_customer.request_vendors.messages',
            'quote_customer.customer', 'supplier', 'quote_customer.customer.user_info',
            'supplier.user_info', 'supplier.user_info.address', 'quote_customer.customer.user_info.address',
            'quote_customer.customer.user_info.payment', 'quote_customer.customer.user_info.payment.common',
            'quote_customer.customer.user_info.billing_address',
            'quote_customer.customer.user_info.deliver_address'
        ]);
        if ($request->has('searchStatus') && $request->searchStatus != null) {
            if ($request->searchStatus == 1) {
                $order_query = $order_query->where([
                    ['order_status', '=', 0]
                ]);
            }

            if ($request->searchStatus == 2) {
                $order_query = $order_query->where('order_status', '=', '1');
            }
        }

        if ($request->has('customerName') && $request->customerName != null) {
            $order_query = $order_query->whereHas('quote_customer.customer.user_info', function ($query) use ($request) {
                $query->where('company_name', 'LIKE', "$request->customerName%");
            });
        }

        if ($request->has('maker') && $request->maker != null) {
            $order_query = $order_query->where('maker', 'LIKE', "$request->maker%");
        }

        if ($request->has('modelNumber') && $request->modelNumber != null) {
            $order_query = $order_query->where('katashiki', 'LIKE', "$request->modelNumber%");
        }

        if ($request->has('estimateDate') && $request->estimateDate != null) {
            $order_query = $order_query->whereHas('quote_customer', function ($query) use ($request) {
                $query->whereDate('quote_date', '=', $request->estimateDate);
            });
        }

        if ($request->has('orderDate') && $request->orderDate != null) {
            $order_query = $order_query->whereDate('receive_order_aprrove_date', '=', $request->orderDate);
        }

        if ($request->has('quoteCode') && $request->quoteCode != null) {
            $order_query = $order_query->whereHas('quote_customer', function ($query) use ($request) {
                $query->where('quote_code', 'LIKE', "$request->quoteCode%");
            });
        }

        $table_info_arr = explode('.', $request->filterColumn);
        $table_name = 'order_detail';
        $field_name = array_pop($table_info_arr);

        if (count($table_info_arr) > 0)
            $table_name = array_pop($table_info_arr);

        if ($field_name == '0') {
            $table_name = 'ship_to';
            $field_name = 'comp_name';
            $request->filterColumn = 'ship_to_info.comp_name';
        } else if ($field_name == '1') {
            $table_name = 'transport';
            $field_name = 'name';
            $request->filterColumn = 'transport.name';
        }

        $field_type = Schema::getColumnType($table_name, $field_name);
        $order_list = $order_query->get()
            ->whereNotNull('quote_customer.customer')
            ->whereNotNull('supplier');

        if (in_array($field_name, ['receive_order_date', 'quote_date', 'expect_ship_date', 'cancel_date_user'])) {
            if ($request->order[0]['dir'] == 'asc')
                $order_list = $order_list->sortBy($request->filterColumn);
            else
                $order_list = $order_list->sortByDesc($request->filterColumn);
            return $order_list;
        }

        if ($request->order[0]['dir'] == 'asc') {
            if ($field_type == 'string')
                $order_list = $order_list->sortBy($request->filterColumn, SORT_STRING);
            else
                $order_list = $order_list->sortBy($request->filterColumn, SORT_NUMERIC);
        } else {
            if ($field_type == 'string')
                $order_list = $order_list->sortByDesc($request->filterColumn, SORT_STRING);
            else
                $order_list = $order_list->sortByDesc($request->filterColumn, SORT_NUMERIC);
        }
        return $order_list;
    }

    public function get_order_list(Request $request)
    {
        $order_list = $this->get_order_list_query($request);
        return Datatables::of($order_list->slice(0, OrderDetail::$order_limit))->make(true);
    }

    public function get_order_more_list(Request $request)
    {
        $current_number = $request->currentLength;
        $data_query = $this->get_order_list_query($request);

        if ($current_number > $data_query->count())
            return json_encode([]);
        else if ($data_query->count() - $current_number > OrderDetail::$order_limit) {
            $ids = $data_query->slice($current_number, OrderDetail::$order_limit)->keys();
            return UserInfo::convertCollectionToArray($data_query, $ids);
        } else {
            $ids = $data_query->slice($current_number, $data_query->count() - OrderDetail::$order_limit)->keys();
            return UserInfo::convertCollectionToArray($data_query, $ids);
        }
    }

    public function update_kbn(Request $request)
    {
        $ids = array_filter($request->idList);
        foreach ($ids as $id => $item) {
            $num_zero = 4 - strlen($item);
            $code_rank = '';
            for ($i = 1; $i < $num_zero; $i++) {
                $code_rank .= '0';
            }
            $current_time = new DateTime();
            $date_invoice = $current_time->format('Y-m-d');
            $code_invoice = $current_time->format('Ymd') . '-' . $code_rank . $item;
            $order_detail = OrderDetail::find($item);
            $order_detail->order_KBN = 1;
            $order_detail->save();

            $order_header = OrderHeader::find($order_detail->order_header_id);
            $order_header->code_invoice = $code_invoice;
            $order_header->date_invoice = $date_invoice;
            $order_header->save();
        }
        return 'success';
    }

    public function invoice(Request $request)
    {
        $order_header = OrderHeader::with([
            'order_details', 'order_details.quote_customer', 'order_details.quote_customer.customer', 'order_details.quote_customer.customer.user_info',
            'order_details.quote_customer.customer.user_info.address', 'tax_info',
            'order_details.quote_customer'
        ])->find($request->orderHeaderId);
        $attachment_file_path = "請求書.pdf";
        $image_path = public_path('assets/images/dau.png');
        $image_path2 = public_path('assets/images/dau1.png');
        $pdf = PDF::loadView('pdf_templates.send_updated_order', [
            "orders" => $order_header,
            "image_url" => $image_path,
            'image_url2' => $image_path2
        ]);
        Storage::put($attachment_file_path, $pdf->output());
        return $pdf->download();
        // return $attachment_file_path;
    }

    public function cancel_order(Request $request)
    {
        $order_ids = $request->cancelIds;
        foreach ($order_ids as $key => $id) {
            $order = OrderDetail::find($id);
            if (!$order->cancel_date_user)
                $order->cancel_date_user = date_create();
            $order->save();
        }
        return 'success';
    }

    public function change_status(Request $request)
    {
        $order_ids = $request->orderIds;
        foreach ($order_ids as $key => $id) {
            $order = OrderDetail::find($id);
            if ($order->order_status == 1)
                $order->order_status = 0;
            else
                $order->order_status = 1;
            $order->save();
        }
        return 'success';
    }

    public function ship_index(Request $request)
    {
        return view('admin/ship_order/index');
    }

    public function get_ship_order_list(Request $request)
    {
        $ship_order_list = $this->get_ship_order_list_query($request);
        return Datatables::of($ship_order_list->slice(0, OrderDetail::$ship_order_limit))->make(true);
    }

    public function get_ship_order_more_list(Request $request)
    {
        $current_number = $request->currentLength;
        $data_query = $this->get_ship_order_list_query($request);

        if ($current_number > $data_query->count())
            return json_encode([]);
        else if ($data_query->count() - $current_number > OrderDetail::$ship_order_limit) {
            $ids = $data_query->slice($current_number, OrderDetail::$ship_order_limit)->keys();
            return UserInfo::convertCollectionToArray($data_query, $ids);
        } else {
            $ids = $data_query->slice($current_number, $data_query->count() - OrderDetail::$ship_order_limit)->keys();
            return UserInfo::convertCollectionToArray($data_query, $ids);
        }
    }

    public function ship_change_status(Request $request)
    {
        $order_ids = $request->orderIds;
        foreach ($order_ids as $key => $id) {
            $order = OrderDetail::find($id);
            if ($order->status_ship == 1)
                $order->status_ship = 0;
            else
                $order->status_ship = 1;
            $order->save();
        }
        return 'success';
    }

    public function return_to_order(Request $request)
    {
        $order_ids = $request->orderIds;
        foreach ($order_ids as $key => $id) {
            $order = OrderDetail::find($id);
            $order->status_ship = 0;
            $order->order_KBN = 0;
            if (!$order->cancel_date_vendor)
                $order->cancel_date_vendor = date_create()->format('Y-m-d');
            $order->save();
            $order->supplier->cal_po_time += 1;
            $order->supplier->save();
        }
        return 'success';
    }

    public function update_ship_order(Request $request)
    {
        $ship_order = OrderDetail::find($request->id);
        $ship_order->ship_quantity = $request->shipQty;
        $ship_order->type_money_ship = $request->typeMoneyShip;
        $ship_order->unit_buy_ship = $request->unitBuyShip;
        $ship_order->price_ship = $request->priceShip;
        $ship_order->code_send = $request->codeSend;
        $ship_order->import_date_plan = date_create($request->importDatePlan)->format('Y-m-d');
        $ship_order->refer_vendor = $request->referVendor;
        if ($request->has('shipTo'))
            $ship_order->ship_to = $request->shipTo;
        if ($request->has('transport'))
            $ship_order->ship_by = $request->transport;
        $ship_order->cancel_date_vendor = date_create($request->cancelDate)->format('Y-m-d');
        $ship_order->save();
        $ship_order = OrderDetail::with([
            'order_header', 'quote_customer', 'quote_customer.request_vendors', 'quote_customer.request_vendors.messages',
            'quote_customer.customer', 'supplier', 'quote_customer.customer.user_info',
            'supplier.user_info', 'supplier.user_info.address', 'quote_customer.customer.user_info.address',
            'quote_customer.customer.user_info.payment',
            'quote_customer.customer.user_info.billing_address',
            'quote_customer.customer.user_info.deliver_address',
            'ship_to_info', 'transport', 'supplier.user_info.payment', 'supplier.user_info.payment.common'
        ])->find($request->id);
        return json_encode($ship_order);
    }

    public function generate_order_pdf(Request $request)
    {
        $order_id = $request->id;
        $attachment_file_path = "purchase_order" . $order_id . ".pdf";
        $nghiahh_temp = "nghiahh";

        $order_detail = OrderDetail::with([
            'order_header', 'supplier', 'supplier.user_info',
            'supplier.user_info.address', 'quote_customer'
        ])->find($order_id);

        if (!$order_detail->send_date)
            $order_detail->send_date = date_create()->format('Y-m-d');

        if ($order_detail->supplier->user_info->address->country == 'JP') {
            $pdf_footer = '<div class="note footer_pdf"><span>[発注備考]: </span>
                                <span>' . $order_detail->refer_vendor . '</span></div>';
            $pdf_path = 'pdf_templates.send_order_to_supplier_jp';
        } else {
            $pdf_footer = '<div class="note footer_pdf">
                     <span>Remark : </span>
                     <span>' . $order_detail->refer_vendor . '</span>
                 </div>
                 <div id="shipby">
                     <span>Ship By</span>&nbsp;&nbsp;&nbsp;
                     <span> ' . $order_detail->transport->name . '</span>
                 </div>';
            // $pdf_path = 'pdf_templates.send_order_to_supplier_en';
            $pdf_path = 'pdf_templates.send_order_to_supplier_jp';
        }

        $pdf = PDF::loadView($pdf_path, [
            "footer" => $pdf_footer,
            "order_detail" => $order_detail,
            'nghiahh_temp' => $nghiahh_temp
        ]);

        Storage::put($attachment_file_path, $pdf->output());
        return $attachment_file_path;
    }

    public function ship_transport_list(Request $request)
    {
        $ship_to_list = ShipTo::all();
        $transport_list = Transport::all();

        return json_encode([
            'ships' => $ship_to_list,
            'transports' => $transport_list
        ]);
    }

    private function get_ship_order_list_query(Request $request)
    {
        $ship_order_query = OrderDetail::with([
            'order_header', 'quote_customer', 'quote_customer.request_vendors', 'quote_customer.request_vendors.messages',
            'quote_customer.customer', 'supplier', 'quote_customer.customer.user_info',
            'supplier.user_info', 'supplier.user_info.address',
            'ship_to_info', 'transport', 'supplier.user_info.payment', 'supplier.user_info.payment.common'
        ])->where('order_KBN', '=', 1);

        if ($request->has('status') && $request->status != null) {
            if ($request->status == 1) {
                $ship_order_query = $ship_order_query->where('status_ship', '=', 0);
            }

            if ($request->status == 2) {
                $ship_order_query = $ship_order_query->where('status_ship', '=', 1);
            }
        }

        if ($request->has('customerName') && $request->customerName != null) {
            $ship_order_query = $ship_order_query->whereHas('quote_customer.customer.user_info', function ($query) use ($request) {
                $query->where('company_name', 'LIKE', "$request->customerName%");
            });
        }

        if ($request->has('supplierName') && $request->supplierName != null) {
            $ship_order_query = $ship_order_query->whereHas('supplier.user_info', function ($query) use ($request) {
                $query->where('company_name', 'LIKE', "$request->supplierName%");
            });
        }

        if ($request->has('modelNumber') && $request->modelNumber != null) {
            $ship_order_query = $ship_order_query->where('katashiki', 'LIKE', "$request->modelNumber%");
        }

        if ($request->has('maker') && $request->maker != null) {
            $ship_order_query = $ship_order_query->where('maker', 'LIKE', "$request->maker%");
        }

        if ($request->has('orderDate') && $request->orderDate != null) {
            $ship_order_query = $ship_order_query->whereDate('send_date', '=', "$request->orderDate%");
        }

        if ($request->has('orderNumber') && $request->orderNumber != null) {
            $ship_order_query = $ship_order_query->whereHas('quote_customer', function ($query) use ($request) {
                $query->where('quote_code', 'LIKE', "$request->orderNumber%");
            });
        }

        if ($request->has('shipOrderDate') && $request->shipOrderDate != null) {
            $ship_order_query = $ship_order_query->whereHas('order_header', function ($query) use ($request) {
                $query->whereDate('receive_order_date', '=', "$request->shipOrderDate%");
            });
        }

        $table_info_arr = explode('.', $request->filterColumn);
        $table_name = 'order_detail';
        $field_name = array_pop($table_info_arr);

        if (count($table_info_arr) > 0)
            $table_name = array_pop($table_info_arr);

        if ($field_name == '0') {
            $table_name = 'ship_to';
            $field_name = 'comp_name';
            $request->filterColumn = 'ship_to_info.comp_name';
        } else if ($field_name == '1') {
            $table_name = 'transport';
            $field_name = 'name';
            $request->filterColumn = 'transport.name';
        }

        $field_type = Schema::getColumnType($table_name, $field_name);
        $ship_order_list = $ship_order_query->get()
            ->whereNotNull('quote_customer.customer')
            ->whereNotNull('supplier');

        if (in_array($field_name, ['import_date', 'export_date', 'expect_ship_date', 'import_date_plan', 'export_time', 'date_invoice'])) {
            if ($request->order[0]['dir'] == 'asc')
                $ship_order_list = $ship_order_list->sortBy($request->filterColumn);
            else
                $ship_order_list = $ship_order_list->sortByDesc($request->filterColumn);
            return $ship_order_list;
        }

        if ($request->order[0]['dir'] == 'asc') {
            if ($field_type == 'string')
                $ship_order_list = $ship_order_list->sortBy($request->filterColumn, SORT_STRING);
            else
                $ship_order_list = $ship_order_list->sortBy($request->filterColumn, SORT_NUMERIC);
        } else {
            if ($field_type == 'string')
                $ship_order_list = $ship_order_list->sortByDesc($request->filterColumn, SORT_STRING);
            else
                $ship_order_list = $ship_order_list->sortByDesc($request->filterColumn, SORT_NUMERIC);
        }

        return $ship_order_list;
    }
}
