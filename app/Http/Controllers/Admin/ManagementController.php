<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Meneses\LaravelMpdf\Facades\LaravelMpdf as PDF;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;

use App\Models\Rate;
use App\Models\Tax;
use App\Models\TaxLog;
use App\Models\ImportGoods;
use App\Models\RfqRequest;
use App\Models\CustomerLog;
use App\Models\OrderHeader;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\RfqDetail;
use App\Models\QuoteCustomer;
use App\Models\FeeShipping;
use App\Models\Daibiki;
use App\Models\Maker;
use App\Models\Alert;
use App\Models\ShipTo;
use App\Models\Transport;
use App\Models\Parts;
use App\Models\User;
use App\Models\ChargeBusiness;
use App\Models\Supplier;
use App\Models\RateLog;
use App\Models\HeaderQuarter;
use App\Models\TemplateInfo;

use App\Http\Requests\Admin\ShipToRequest;
use App\Http\Requests\Admin\SalesRepresentativeRequest;

class ManagementController extends Controller
{

    public function index($flag = 0)
    {
        return view('admin.management.index')->with([
            'customer_flag' => $flag
        ]);
    }

    public function management_index(Request $request)
    {
        switch ($request->type) {
            case 'rate': {
                    return json_encode(view('admin.management.rate_index')->with([
                        'rate_list' => Rate::all()
                    ])->render());
                    break;
                }
            case 'shipping_fee': {
                    return json_encode(view('admin.management.shipping_cash_fee_index')->render());
                    break;
                }
            case 'inventory': {
                    return json_encode(view('admin.management.inventory_index')->render());
                    break;
                }
            case 'maker': {
                    return json_encode(view('admin.management.maker_index')->render());
                    break;
                }
            case 'representative': {
                    return json_encode(view('admin.management.sales_representative_index')->render());
                    break;
                }
            case 'notification': {
                    return json_encode(view('admin.management.notification_index')->render());
                    break;
                }
            case 'statistics': {
                    return json_encode(view('admin.management.statistics_index')->render());
                    break;
                }
            case 'supplier': {
                    return json_encode(view('admin.management.supplier_index')->render());
                    break;
                }
            case 'delivery_carrier': {
                    return json_encode(view('admin.management.delivery_carrier_index')->render());
                    break;
                }
            case 'summary': {
                    return json_encode(view('admin.management.sales_summary_index')->render());
                    break;
                }
            case 'customer': {
                    return json_encode(view('admin.management.customer_index')->render());
                    break;
                }
            case 'tax': {
                    return json_encode(view('admin.management.tax_index')->with([
                        'tax' => Tax::first(),
                        'tax_log' => TaxLog::orderBy('created_at', 'desc')->get(),
                    ])->render());
                    break;
                }
            case 'table_setting': {
                    return json_encode(view('admin.management.table_setting_index')->render());
                    break;
                }
            case 'email_template': {
                    return json_encode(view('admin.management.template_edit_index')->with([
                        'templates' => TemplateInfo::all()
                    ])->render());
                    break;
                }
        }
    }

    public function get_summary_list(Request $request)
    {
        $start_date = date_create($request->startDate);
        $end_date = date_create($request->endDate);
        $user_info = [];
        $supplier_info = [];

        if ($request->has('type') && $request->type == 'customer') {
            $all_customers = Customer::all();
            foreach ($all_customers as $customer) {
                $order_info = OrderDetail::where('solved', '=', 1)
                    ->whereHas('order_header', function ($query) use ($customer) {
                        $query->where('customer_id', '=', $customer->id);
                    })
                    ->whereDate('send_date', '>', $start_date)
                    ->whereDate('send_date', '<=', $end_date)
                    ->get();

                $order_old_id = 0;
                $total_money_sale = 0;
                $total_money_buy = 0;
                $tax = 0;
                if (count($order_info) > 0) {
                    foreach ($order_info as $detail) {
                        $rate = RateLog::where('type_money', '=' . $detail->order_header->type_money)
                            ->whereDate('created_at', '<=', $detail->send_date)
                            ->orderByDesc('id')
                            ->first();
                        $total_money_sale += $detail->sale_money * $rate->sale_rate + $detail->order_header->fee_shipping + $detail->order_header->fee_daibiki;
                        $total_money_buy += $detail->price_ship * $rate->sale_rate + $detail->order_header->fee_shipping + $detail->order_header->fee_daibiki +
                            $detail->quote_customer->fee_shipping;
                    }
                    $tax = $order_info[0]->tax->tax;
                }
                $total_money_buy_tax = $total_money_buy + ($total_money_buy * $tax);
                $total_money_sale_tax = $total_money_sale + ($total_money_sale * $tax);
                $profit = 0;
                if ($total_money_sale_tax > 0)
                    $profit = ($total_money_sale_tax - $total_money_buy_tax) / $total_money_sale_tax;

                array_push($user_info, [
                    'cus_name' => $customer->user_info->company_name,
                    'total_money_sale' => number_format($total_money_sale),
                    'total_money_buy' => number_format($total_money_buy),
                    'total_money_sale_tax' => number_format($total_money_sale_tax),
                    'total_money_buy_tax' => number_format($total_money_buy_tax),
                    'rate_profit' => number_format($profit, 3)
                ]);
            }
            return json_encode($user_info);
        }

        if ($request->has('type') && $request->type == 'supplier') {
            $all_suppliers = Supplier::all();
            foreach ($all_suppliers as $supplier) {
                $import_info = ImportGoods::whereHas('quote_customer', function ($query) use ($supplier) {
                    $query->where('supplier_id', '=', $supplier->id);
                })->whereDate('import_date', '>', $start_date)
                    ->whereDate('import_date', '<=', $end_date)
                    ->get();

                $total_money_sale2 = 0;
                $total_money_buy2 = 0;
                $tax_info = Tax::latest()->first();
                if (count($import_info) > 0) {
                    foreach ($import_info as $detail) {
                        if ($detail->order_detail) {
                            $rate = RateLog::where('type_money', '=' . $detail->order_header->type_money)
                                ->whereDate('created_at', '<=', $detail->send_date)
                                ->orderByDesc('id')
                                ->first();
                            $total_money_sale2 += $detail->sale_money * $rate->sale_rate + $detail->order_header->fee_shipping + $detail->order_header->fee_daibiki;
                            $total_money_buy2 += $detail->price_ship * $rate->buy_rate + $detail->order_header->fee_daibiki + $detail->order_header->fee_shipping +
                                $detail->quote_customer->fee_shipping;
                        }
                    }
                }

                $total_money_buy_tax2 = $total_money_buy2 + ($total_money_buy2 * $tax_info->tax);
                $total_money_sale_tax2 = $total_money_sale2 + ($total_money_sale2 * $tax_info->tax);
                $profit2 = 0;
                if ($total_money_sale_tax2 > 0)
                    $profit2 = ($total_money_sale_tax2 - $total_money_buy_tax2) / $total_money_sale_tax2;

                array_push($supplier_info, [
                    'sup_name' => $supplier->user_info->company_name,
                    'total_money_sale' => number_format($total_money_sale2),
                    'total_money_buy' => number_format($total_money_buy2),
                    'total_money_sale_tax' => number_format($total_money_sale_tax2),
                    'total_money_buy_tax' => number_format($total_money_buy_tax2),
                    'rate_profit' => number_format($profit2, 3)
                ]);
            }
            return json_encode($supplier_info);
        }
    }

    public function summary_pdf_generation(Request $request)
    {
        if ($request->has('flag') && $request->flag == 'detail') {
            $start_date = date_create($request->startDate);
            $end_date = date_create($request->endDate);

            if ($request->type == 'customer') {
                $order_info = OrderDetail::with(['order_header', 'quote_customer'])
                    ->where('solved', '=', 1)
                    ->whereDate('send_date', '>', $start_date)
                    ->whereDate('send_date', '<=', $end_date)
                    ->get();
            } else {
                $order_info = ImportGoods::with(['order_header', 'quote_customer'])
                    ->whereDate('import_date', '>', $start_date)
                    ->whereDate('import_date', '<=', $end_date)
                    ->get();
            }

            $pdf = PDF::loadView('pdf_templates.sales_summary_pdf', [
                'summary_data' => $order_info,
                'type' => $request->type,
                'flag' => 'detail'
            ]);
            if ($request->type == "customer")
                $pdf_title = $request->period . '明細売上.pdf';
            else
                $pdf_title = $request->period . '明細仕入.pdf';

            Storage::put($pdf_title, $pdf->output());
            return json_encode($pdf_title);
        } else {
            $data = $request->pdf_data;
            $pdf = PDF::loadView('pdf_templates.sales_summary_pdf', [
                'summary_data' => $data,
                'type' => $request->type
            ]);

            if ($request->type == "customer")
                $pdf_title = $request->period . '売り上げ.pdf';
            else
                $pdf_title = $request->period . '仕入.pdf';

            Storage::put($pdf_title, $pdf->output());

            return json_encode($pdf_title);
        }
    }

    public function get_shipping_list()
    {
        $fee_shipping = FeeShipping::all();
        return json_encode($fee_shipping);
    }

    public function delete_shipping(Request $request)
    {
        if ($request->has('id') && $request->id) {
            $delete_shipping = FeeShipping::find($request->id);
            $delete_shipping->delete();
            return 'success';
        }
        return 'false';
    }

    public function delete_cash(Request $request)
    {
        if ($request->has('id') && $request->id) {
            $delete_cash = Daibiki::find($request->id);
            $delete_cash->delete();
            return 'success';
        }
        return 'false';
    }

    public function edit_shipping(Request $request)
    {
        if ($request->has('id'))
            $fee_shipping = FeeShipping::find($request->id);
        else
            $fee_shipping = new FeeShipping;
        $fee_shipping->area = $request->region;
        $fee_shipping->fee = $request->fee;
        $fee_shipping->more_information = $request->moreInfo;
        $fee_shipping->save();
        return json_encode($fee_shipping);
    }

    public function get_daibiki_list()
    {
        $daibiki = Daibiki::all();
        return json_encode($daibiki);
    }

    public function edit_daibiki(Request $request)
    {
        if ($request->has('id'))
            $daibiki = Daibiki::find($request->id);
        else
            $daibiki = new Daibiki;
        $daibiki->min = $request->min;
        $daibiki->max = $request->max;
        $daibiki->fee = $request->fee;
        $daibiki->information = $request->info;
        $daibiki->save();
        return 'success';
    }

    public function get_maker_list(Request $request)
    {
        $maker = Maker::orderBy('id', 'asc');
        if ($request->has('filterId') && $request->filterId != null) {
            $maker = $maker->where('id', 'LIKE', "$request->filterId%");
        }
        if ($request->has('filterName') && $request->filterName != null) {
            $maker = $maker->where('maker_name', 'LIKE', "$request->filterName%");
        }
        return json_encode($maker->get());
    }

    public function notification_list(Request $request)
    {
        $alerts = Alert::all();
        return json_encode($alerts);
    }

    public function notification_create(Request $request)
    {
        if ($request->has('id') && $request->id != null)
            $alert = Alert::find($request->id);
        else
            $alert = new Alert;
        $alert->title = $request->title;
        $alert->message = $request->content;
        $alert->start_date = date_create($request->postStart);
        $alert->end_date = date_create($request->postEnd);
        $alert->save();
    }

    public function notification_delete(Request $request)
    {
        $notification = Alert::find($request->id);
        $notification->delete();
        return 'success';
    }

    public function delivery_carrier_ship_list(Request $request)
    {
        $ship_to_list = ShipTo::all();
        return json_encode($ship_to_list);
    }

    public function delivery_carrier_ship_create(ShipToRequest $request)
    {
        if ($request->has('id') && $request->id != null)
            $ship_to = ShipTo::find($request->id);
        else
            $ship_to = new ShipTo;
        $ship_to->comp_name = $request->registeredName;
        $ship_to->staff = $request->companyName;
        $ship_to->address = $request->address1;
        $ship_to->address1 = $request->address2;
        $ship_to->tel = $request->tel;
        $ship_to->fax = $request->fax;
        $ship_to->zip = $request->zipCode;
        $ship_to->representative = $request->personInCharge;
        $ship_to->country = $request->country;
        $ship_to->province = $request->province;
        $ship_to->city = $request->city;
        $ship_to->save();
        return 'success';
    }

    public function delivery_ship_delete(Request $request)
    {
        $ship_to = ShipTo::find($request->id);
        $ship_to->delete();
        return 'success';
    }

    public function delivery_transport_list(Request $request)
    {
        $transport = Transport::all();
        return json_encode($transport);
    }

    public function delivery_carrier_transport_edit(Request $request)
    {
        if ($request->has('id') && $request->id != null)
            $transport = Transport::find($request->id);
        else
            $transport = new Transport;
        $transport->name = $request->name;
        $transport->save();
        return 'success';
    }

    public function delivery_carrier_transport_delete(Request $request)
    {
        $transport = Transport::find($request->id);
        $transport->delete();
        return 'success';
    }

    public function inventory_list(Request $request)
    {
        $parts_list = Parts::orderBy('id', 'asc');

        if ($request->has('id') && $request->id != null) {
            $parts_list = $parts_list->where('id', 'LIKE', "$request->id%");
        }

        if ($request->has('model') && $request->model != null) {
            $parts_list = $parts_list->where('katashiki', 'LIKE', "$request->model%");
        }

        if ($request->has('maker') && $request->maker != null) {
            $parts_list = $parts_list->where('maker', 'LIKE', "$request->maker%");
        }

        if ($request->has('classification') && $request->classification != null) {
            $parts_list = $parts_list->where('kubun', 'LIKE', "$request->classification%");
        }

        return json_encode($parts_list->get());
    }

    public function inventory_excel_import(Request $request)
    {
        $file_name = time() . '.csv';
        $request->file->move(public_path('uploads'), $file_name);

        $obj_reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Csv');
        $obj_php_excel = $obj_reader->load(public_path('uploads/' . $file_name));
        $sheet = $obj_php_excel->getActiveSheet();

        Parts::truncate();

        $highest_row = $sheet->getHighestRow();
        for ($row = 2; $row <= $highest_row; ++$row) {
            $model = $sheet->getCellByColumnAndRow(1, $row)->getValue();
            $maker = $sheet->getCellByColumnAndRow(2, $row)->getValue();
            $qty = $sheet->getCellByColumnAndRow(3, $row)->getValue();
            $dc = $sheet->getCellByColumnAndRow(4, $row)->getValue();
            $kubun = $sheet->getCellByColumnAndRow(5, $row)->getValue();
            $kubun2 = $sheet->getCellByColumnAndRow(6, $row)->getValue();
            // $created_at = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($sheet->getCellByColumnAndRow(7, $row)->getValue()))->format('Y-m-d');
            // $updated_at = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($sheet->getCellByColumnAndRow(8, $row)->getValue()))->format('Y-m-d');

            $part = new Parts;
            $part->katashiki = $model;
            $part->maker = $maker;
            $part->qty = $qty;
            $part->dc = $dc;
            $part->kubun = $kubun;
            $part->kubun2 = $kubun2;
            // $part->created_at = $created_at;
            // $part->updated_at = $updated_at;
            $part->save();
        }
        unset($this->obj_reader);
        unset($this->obj_php_excel);
        return 'success';
    }

    public function sales_representative_list(Request $request)
    {
        $charge_list = ChargeBusiness::with(['user']);

        if ($request->has('name') && $request->name != null) {
            $charge_list = $charge_list->where('username_eng', 'LIKE', "$request->name%")
                ->orWhere('username_jap', 'LIKE', "$request->name%");
        }

        return json_encode($charge_list->get());
    }

    public function sales_representative_edit(SalesRepresentativeRequest $request)
    {
        if ($request->has('id') && $request->id) {
            $sales_representative = ChargeBusiness::find($request->id);
            $sales_representative->username_eng = $request->english;
            $sales_representative->username_jap = $request->japanese;
            $sales_representative->tel = $request->tel;
            $sales_representative->fax = $request->fax;
            $sales_representative->mail = $request->email;
            $sales_representative->address = '';
            $sales_representative->user_id = 0;
            $sales_representative->save();

            $user = User::find($sales_representative->staff_id);
            $user->name = $request->english;
            $user->email = $request->email;
            $user->role = "role";
            $user->password = Hash::make($request->password);
            $user->save();
        } else {
            $user = new User;
            $user->name = $request->english;
            $user->email = $request->email;
            $user->role = "role";
            $user->password = Hash::make($request->password);
            $user->save();

            $sales_representative = new ChargeBusiness;
            $sales_representative->username_eng = $request->english;
            $sales_representative->username_jap = $request->japanese;
            $sales_representative->tel = $request->tel;
            $sales_representative->fax = $request->fax;
            $sales_representative->mail = $request->email;
            $sales_representative->staff_id = $user->id;
            $sales_representative->address = '';
            $sales_representative->user_id = 0;
            $sales_representative->save();
        }
        return true;
    }

    public function sales_representative_delete(Request $request)
    {
        $sales_representative =  ChargeBusiness::find($request->id);
        if ($sales_representative) {
            User::find($sales_representative->staff_id)->delete();
            $sales_representative->delete();
        }
        return 'success';
    }

    public function supplier_list(Request $request)
    {
        $supplier_list_query = Supplier::with([
            'user_info', 'user_info.address',
            'user_info.emails', 'user_info.payment',
            'user_info.payment.common'
        ]);

        if ($request->has('supplierId') && $request->supplierId != null) {
            $supplier_list_query = $supplier_list_query->where('id', 'LIKE', "$request->supplierId%");
        }

        if ($request->has('personInCharge') && $request->personInCharge != null) {
            $supplier_list_query = $supplier_list_query->where('representative', 'LIKE', "%$request->personInCharge%");
        }

        if ($request->has('kana') && $request->kana != null) {
            $supplier_list_query = $supplier_list_query->whereHas('user_info', function ($query) use ($request) {
                $query->where('company_name_kana', 'LIKE', "$request->kana%");
            });
        }

        if ($request->has('rank') && $request->rank != null) {
            $supplier_list_query = $supplier_list_query->whereHas('user_info', function ($query) use ($request) {
                $query->where('rank', 'LIKE', "$request->rank%");
            });
        }

        if ($request->has('country') && $request->country != null) {
            $supplier_list_query = $supplier_list_query->whereHas('user_info.address', function ($query) use ($request) {
                $query->where('country', 'LIKE', "$request->country%");
            });
        }

        return json_encode($supplier_list_query->get());
    }

    public function get_statistics_periode(Request $request)
    {
        $start_date = date_create($request->startDate);
        $end_date = date_create($request->endDate);

        $start_year = intval($start_date->format('Y'));
        $end_year = intval($end_date->format('Y'));

        $today = date_create()->format('Y-m-d');

        $result_arr = [];
        $result_date_arr = [];

        if ($start_year == $end_year) {
            array_push($result_arr, $this->calculate_statistics($start_date, $end_date));
            array_push($result_date_arr, $start_year);
        } else {
            for ($i = $start_year; $i <= $end_year; $i++) {
                if ($i == $start_year) {
                    $sub_end_date = date_create($i . '-12-31');
                    array_push($result_arr, $this->calculate_statistics($start_date, $sub_end_date));
                } elseif ($i == $end_year) {
                    $sub_start_date = date_create($i . '-01-01');
                    array_push($result_arr, $this->calculate_statistics($sub_start_date, $end_date));
                } else {
                    $sub_start_date = date_create($i . '-01-01');
                    $sub_end_date = date_create($i . '-12-31');
                    array_push($result_arr, $this->calculate_statistics($sub_start_date, $sub_end_date));
                }
                array_push($result_date_arr, $i);
            }
        }

        return json_encode([
            'data' => $result_arr,
            'years' => $result_date_arr
        ]);
    }

    public function get_company_address(Request $request)
    {
        $header_quarter = HeaderQuarter::all();
        return json_encode($header_quarter->sortBy('id'));
    }

    public function edit_company_address(Request $request)
    {
        $header_quarter = HeaderQuarter::find($request->id);
        $header_quarter->company_name = $request->companyName;
        $header_quarter->tel = $request->tel;
        $header_quarter->address = $request->address;
        $header_quarter->save();
        return 'success';
    }

    public function template_edit(Request $request)
    {
        $template = TemplateInfo::find($request->id);
        $template->template_name = $request->templateName;
        $template->template_content = $request->templateContent;
        $template->save();

        return json_encode($template);
    }

    private function calculate_statistics($start_date, $end_date)
    {
        $all_registered_customer_count = Customer::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->count();

        $new_customer_count = Customer::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->count();

        $active_customer_count = Customer::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->where('is_active', '=', 1)->count();

        $all_rfq_count = CustomerLog::whereDate('request_date', '>=', $start_date,)
            ->whereDate('request_date', '<=', $end_date)->where('customer_id', '!=', 0)->sum('res_count');

        $all_order_count = CustomerLog::whereDate('request_date', '>=', $start_date,)
            ->whereDate('request_date', '<=', $end_date)->where('customer_id', '!=', 0)->sum('order_Qty');

        $all_sales = CustomerLog::whereDate('request_date', '>=', $start_date,)
            ->whereDate('request_date', '<=', $end_date)->where('customer_id', '!=', 0)->sum('order_Qty');

        $all_login_parts = CustomerLog::where('customer_id', '!=', 0)->whereDate('request_date', '>=', $start_date,)
            ->whereDate('request_date', '<=', $end_date)->count();
        $all_unlogin_parts = CustomerLog::where('customer_id', '=', 0)->whereDate('request_date', '>=', $start_date,)
            ->whereDate('request_date', '<=', $end_date)->count();

        $total_money = 0;
        $total_money_buy = 0;
        $order_list = OrderDetail::whereDate('send_date', '>=', $start_date)
            ->whereDate('send_date', '<=', $end_date)
            ->get();
        foreach ($order_list as $item) {
            $rate = RateLog::where('type_money', '=', $item->order_header->type_money)
                ->whereDate('created_at', '<=', $end_date)->orderByDesc('created_at')->first();

            if (!isset($rate)) {
                $total_money += $item->sale_money + $item->order_header->fee_shipping + $item->order_header->fee_daibiki;
                $total_money_buy += $item->sale_money + $item->order_header->fee_shipping + $item->order_header->fee_daibiki + $item->quote_customer->fee_shipping;
            } else {
                $total_money += $item->sale_money * intval($rate->sale_rate) + $item->order_header->fee_shipping + $item->order_header->fee_daibiki;
                $total_money_buy += $item->sale_money * intval($rate->buy_rate) + $item->order_header->fee_shipping + $item->order_header->fee_daibiki + $item->quote_customer->fee_shipping;
            }
        }

        $total_profit = $total_money - $total_money_buy;
        if ($total_money == 0 || !isset($total_money))
            $total = 1;
        else
            $total = $total_money;
        $profit = number_format($total_profit / $total, 3);

        return [
            'registered_count' => $all_registered_customer_count,
            'new_count' => $new_customer_count,
            'active_count' => $active_customer_count,
            'rfq_count' => $all_rfq_count,
            'order_count' => $all_order_count,
            'sales_count' => $all_sales,
            'login_parts_count' => $all_login_parts,
            'unlogin_parts_count' => $all_unlogin_parts,
            'total_money' => $total_money,
            'total_money_buy' => $total_money_buy,
            'total_profit' => $total_profit,
            'profit' => $profit
        ];
    }
}
