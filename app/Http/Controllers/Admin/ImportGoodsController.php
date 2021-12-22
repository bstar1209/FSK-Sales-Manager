<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

use App\Models\OrderDetail;
use App\Models\ImportGoods;
use App\Models\Supplier;
use App\Models\Address;
use App\Models\UserInfo;
use App\Models\Rate;

class ImportGoodsController extends Controller
{
    public function stock_index(Request $request)
    {
        return view('admin/stock/index');
    }

    public function get_stock_list(Request $request)
    {
        $stock_list = $this->get_stock_query($request);
        return Datatables::of($stock_list)->make(true);
    }

    public function get_stock_more_list(Request $request)
    {
        $current_number = $request->currentLength;
        $data_query = $this->get_stock_query($request);

        if ($current_number > $data_query->count())
            return json_encode([]);
        else if ($data_query->count() - $current_number > ImportGoods::$shipment_limit) {
            $ids = $data_query->slice($current_number, ImportGoods::$shipment_limit)->keys();
            return UserInfo::convertCollectionToArray($data_query, $ids);
        } else {
            $ids = $data_query->slice($current_number, $data_query->count() - ImportGoods::$shipment_limit)->keys();
            return UserInfo::convertCollectionToArray($data_query, $ids);
        }
    }

    public function update_stock(Request $request)
    {
        $stock = ImportGoods::find($request->id);
        $stock->ship_quantity = $request->shipQty;
        $stock->type_money_ship = $request->typeMoneyShip;
        $stock->price_ship = $request->priceShip;
        $stock->import_date = $request->importDate;
        $stock->import_qty = $request->importQty;
        $stock->import_unit_price = $request->importUnitPrice;
        $stock->coo = $request->coo;
        $stock->in_tr = $request->inTr;
        $stock->save();

        return json_encode($stock);
    }

    private function get_stock_query(Request $request)
    {
        $stock_query = OrderDetail::with([
            'order_header', 'quote_customer', 'quote_customer.request_vendors', 'quote_customer.request_vendors.messages',
            'quote_customer.customer', 'supplier', 'quote_customer.customer.user_info',
            'supplier.user_info', 'supplier.user_info.address',
            'import_goods', 'ship_to_info', 'transport',
            'supplier.user_info.payment', 'supplier.user_info.payment.common',
        ])->where('order_KBN', '=', 1);

        if ($request->has('status') && $request->status != null) {
            if ($request->status == 1) {
                $stock_query = $stock_query->whereHas('import_goods', function ($query) use ($request) {
                    $query->where('import_status', '=', 0);
                });
            }

            if ($request->status == 2) {
                $stock_query = $stock_query->whereHas('import_goods', function ($query) use ($request) {
                    $query->where('import_status', '=', 1);
                });
            }
        }

        if ($request->has('customerName') && $request->customerName != null) {
            $stock_query = $stock_query->whereHas('quote_customer.customer.user_info', function ($query) use ($request) {
                $query->where('company_name', 'LIKE', "$request->customerName%");
            });
        }

        if ($request->has('supplierName') && $request->supplierName != null) {
            $stock_query = $stock_query->whereHas('supplier.user_info', function ($query) use ($request) {
                $query->where('company_name', 'LIKE', "$request->supplierName%");
            });
        }

        if ($request->has('modelNumber') && $request->modelNumber != null) {
            $stock_query = $stock_query->whereHas('import_goods', function ($query) use ($request) {
                $query->where('katashiki', 'LIKE', "$request->modelNumber%");
            });
        }

        if ($request->has('maker') && $request->maker != null) {
            $stock_query = $stock_query->whereHas('import_goods', function ($query) use ($request) {
                $query->where('maker', 'LIKE', "$request->maker%");
            });
        }

        if ($request->has('orderNumber') && $request->orderNumber != null) {
            $stock_query = $stock_query->whereHas('import_goods', function ($query) use ($request) {
                $query->where('user_code', 'LIKE', "$request->orderNumber%");
            });
        }

        if ($request->has('shipOrderNumber') && $request->shipOrderNumber != null) {
            $stock_query = $stock_query->whereHas('import_goods', function ($query) use ($request) {
                $query->where('code_send', 'LIKE', "$request->shipOrderNumber%");
            });
        }

        $table_info_arr = explode('.', $request->filterColumn);
        $table_name = 'import_detail';
        $field_name = array_pop($table_info_arr);

        if (count($table_info_arr) > 0)
            $table_name = array_pop($table_info_arr);

        $stock_list = $stock_query->get()
            ->whereNotNull('import_goods')
            ->whereNotNull('quote_customer.customer')
            ->whereNotNull('supplier');

        if (in_array($field_name, ['import_date_plan', 'send_date', 'expect_ship_date', 'import_date'])) {
            if ($request->order[0]['dir'] == 'asc')
                $stock_list = $stock_list->sortBy($request->filterColumn);
            else
                $stock_list = $stock_list->sortByDesc($request->filterColumn);
            return $stock_list;
        }

        $field_type = Schema::getColumnType($table_name, $field_name);
        if ($request->order[0]['dir'] == 'asc') {
            if ($field_type == 'string')
                $stock_list = $stock_list->sortBy($request->filterColumn, SORT_STRING);
            else
                $stock_list = $stock_list->sortBy($request->filterColumn, SORT_NUMERIC);
        } else {
            if ($field_type == 'string')
                $stock_list = $stock_list->sortByDesc($request->filterColumn, SORT_STRING);
            else
                $stock_list = $stock_list->sortByDesc($request->filterColumn, SORT_NUMERIC);
        }

        return $stock_list;
    }

    public function actual_slip(Request $request)
    {
        $stock_ids = $request->ids;

        if ($request->has('ids') && count($stock_ids) > 0) {
            $stocks = ImportGoods::with(['quote_customer', 'quote_customer.customer', 'quote_customer.customer.user_info'])->whereIn('id', $stock_ids)->get();
        } else {
            $stocks = ImportGoods::with(['quote_customer', 'quote_customer.customer', 'quote_customer.customer.user_info'])->whereDate('import_date', '=', date_create()->format('Y-m-d'))
                ->where('ship_quantity', '>', 0)->get();
        }

        if ($stocks->count() == 0)
            return "success";

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('tempdir'),
            'default_font_size' => 16,
            'default_font' => 'Sun-ExtA',
            'format' => [250, 170]
        ]);
        foreach ($stocks as $stock) {
            $page = View('pdf_templates.actual_slip', [
                'stock' => $stock
            ]);
            $mpdf->AddPage();
            $mpdf->WriteHTML($page);
        }

        $mpdf->Output('storage/pdf/actual_slip.pdf', \Mpdf\Output\Destination::FILE);
        return "actual_slip.pdf";
    }

    public function change_status(Request $request)
    {
        $stock_ids = $request->ids;
        $pro_count = 0;
        $un_count = 0;
        foreach ($stock_ids as $key => $id) {
            $stock = ImportGoods::find($id);
            if ($stock->import_status == 1) {
                $stock->import_status = 0;
                $un_count++;
            } else {
                $stock->import_status = 1;
                $pro_count++;
            }
            $stock->save();
        }
        if ($pro_count >= $un_count)
            return 'processed';
        else
            return 'unprocessed';
    }

    public function to_shipping(Request $request)
    {
        $stock_ids = $request->ids;
        foreach ($stock_ids as $key => $id) {
            $stock = ImportGoods::find($id);
            $stock->importKBN = 1;
            $stock->save();
        }
        return 'success';
    }

    public function return_to(Request $request)
    {
        $supplier_ids = $request->ids;
        foreach ($supplier_ids as $key => $id) {
            $supplier = Supplier::find($id);
            $supplier->return_time += 1;
            $supplier->save();
        }
        return 'success';
    }

    public function sold_out(Request $request)
    {
        foreach ($request->supplierIds as $key => $id) {
            $supplier = Supplier::find($id);
            $supplier->cal_po_time += 1;
            $supplier->save();
        }

        foreach ($request->importIds as $key => $id) {
            $import_goods = ImportGoods::find($id);
            $import_goods->import_status = 9;
            $import_goods->save();

            $import_goods->order_detail->order_KBN = 0;
            $import_goods->order_detail->order_status = 0;
            $import_goods->order_detail->solved = 0;
            $import_goods->order_detail->save();
        }
        return 'success';
    }

    public function shipment_index(Request $request)
    {
        return view('admin/shipment/index');
    }

    public function get_shipment_list(Request $request)
    {
        $shipment_list = $this->get_shipment_query($request);
        return Datatables::of($shipment_list->slice(0, ImportGoods::$shipment_limit))->make(true);
    }

    private function get_shipment_query(Request $request)
    {
        $order_type = $request->order[0]['dir'];
        $shipment_query = OrderDetail::with([
            'order_header', 'quote_customer', 'quote_customer.request_vendors', 'quote_customer.request_vendors.messages',
            'quote_customer.customer', 'supplier', 'quote_customer.customer.user_info',
            'supplier.user_info', 'supplier.user_info.address',
            'tax', 'import_goods', 'quote_customer.customer.user_info.billing_address',
            'quote_customer.customer.user_info.deliver_address'
        ])->whereHas('import_goods', function ($query) use ($request) {
            $query->where('importKBN', '=', 1);
        });

        if ($request->has('status') && $request->status != null) {
            if ($request->status == 1) {
                $shipment_query = $shipment_query->whereHas('import_goods', function ($query) use ($request) {
                    $query->where('export_status', '=', 0);
                });
            }

            if ($request->status == 2) {
                $shipment_query = $shipment_query->whereHas('import_goods', function ($query) use ($request) {
                    $query->where('export_status', '=', 1);
                });
            }
        }

        if ($request->has('customerName') && $request->customerName != null) {
            $shipment_query = $shipment_query->whereHas('quote_customer.customer.user_info', function ($query) use ($request) {
                $query->where('company_name', 'LIKE', "$request->customerName%");
            });
        }

        if ($request->has('invoiceNumber') && $request->invoiceNumber != null) {
            $shipment_query = $shipment_query->whereHas('import_goods', function ($query) use ($request) {
                $query->where('out_tr', 'LIKE', "$request->invoiceNumber%");
            });
        }

        if ($request->has('maker') && $request->maker != null) {
            $shipment_query = $shipment_query->whereHas('import_goods', function ($query) use ($request) {
                $query->where('maker', 'LIKE', "$request->maker%");
            });
        }

        if ($request->has('shipDate') && $request->shipDate != null) {
            $shipment_query = $shipment_query->whereHas('import_goods', function ($query) use ($request) {
                $query->whereDate('export_date', 'LIKE', "$request->shipDate%");
            });
        }

        if ($request->has('billingNumber') && $request->billingNumber != null) {
            $shipment_query = $shipment_query->whereHas('import_goods', function ($query) use ($request) {
                $query->whereDate('invoice_code', 'LIKE', "$request->billingNumber%");
            });
        }

        if ($request->has('model') && $request->model != null) {
            $shipment_query = $shipment_query->whereHas('import_goods', function ($query) use ($request) {
                $query->where('katashiki', 'LIKE', "$request->model%");
            });
        }

        if ($request->has('id') && $request->id != null) {
            $shipment_query = $shipment_query->whereHas('import_goods', function ($query) use ($request) {
                $query->where('order_id', 'LIKE', "$request->id%");
            });
        }

        $table_info_arr = explode('.', $request->filterColumn);
        $table_name = 'order_detail';
        $field_name = array_pop($table_info_arr);

        if (count($table_info_arr) > 0)
            $table_name = array_pop($table_info_arr);

        $shipment_list = $shipment_query->get()
            ->whereNotNull('import_goods')
            ->whereNotNull('quote_customer.customer')
            ->whereNotNull('supplier');

        if (in_array($field_name, ['import_date', 'export_date', 'expect_ship_date', 'import_date_plan', 'export_time'])) {
            if ($request->order[0]['dir'] == 'asc')
                $shipment_list = $shipment_list->sortBy($request->filterColumn);
            else
                $shipment_list = $shipment_list->sortByDesc($request->filterColumn);
            return $shipment_list;
        }

        $field_type = Schema::getColumnType($table_name, $field_name);
        if ($request->order[0]['dir'] == 'asc') {
            if ($field_type == 'string')
                $shipment_list = $shipment_list->sortBy($request->filterColumn, SORT_STRING);
            else
                $shipment_list = $shipment_list->sortBy($request->filterColumn, SORT_NUMERIC);
        } else {
            if ($field_type == 'string')
                $shipment_list = $shipment_list->sortByDesc($request->filterColumn, SORT_STRING);
            else
                $shipment_list = $shipment_list->sortByDesc($request->filterColumn, SORT_NUMERIC);
        }

        return $shipment_list;
    }

    public function shipment_update(Request $request)
    {
        $order = OrderDetail::find($request->id);
        $order->sale_qty = $request->shipQty;
        $order->sale_cost = $request->saleCost;
        $order->order_header->fee_shipping = $request->feeShipping;
        $order->order_header->fee_daibiki = $request->feeDaibiki;
        $order->import_goods->export_time = $request->exportTime;
        $order->import_goods->out_tr = $request->outTr;
        $order->save();
        $order->import_goods->save();
        $order->order_header->save();
        return "success";
    }

    public function envelope(Request $request)
    {
        $send_address_ids = $request->sendIds;
        $file_path = 'envelop' . date_create()->format('Y-m-d') . '.pdf';

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('tempdir'),
            'default_font_size' => 18,
            'default_font' => 'Sun-ExtA',
            'format' => [250, 170]
        ]);
        foreach ($send_address_ids as $key => $item) {
            $address = Address::find($item);
            $page = View('pdf_templates.envelope', [
                'address' => $address,
                'year' => date_create()->format("Y"),
                'month' => date_create()->format("m"),
                'day' => date_create()->format("d")
            ]);
            $mpdf->AddPage();
            $mpdf->WriteHTML($page);
        }
        $mpdf->Output('storage/pdf/' . $file_path, \Mpdf\Output\Destination::FILE);
        return $file_path;
    }

    public function voucher(Request $request)
    {
        $import_goods_ids = $request->importGoodsIds;
        $attachment_file_path = "delivery_note" . rand(1, 100000) . '.pdf';
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('tempdir'),
            'default_font_size' => 16,
            'default_font' => 'Sun-ExtA',
        ]);
        foreach ($import_goods_ids as $key => $item) {
            $order_details = OrderDetail::with([
                'import_goods', 'import_goods.quote_customer',
                'import_goods.quote_customer.request_vendors', 'order_header'
            ])
                ->where('order_header_id', '=', $key)
                ->whereHas('import_goods', function ($query) use ($item) {
                    $query->whereIn('id', $item);
                })->get();

            $send_address = Address::find($order_details[0]->send_address_id);

            if ($order_details[0]->order_header->type_cond_pay == 1) {
                $page = View('pdf_templates.shipment_vourche1', [
                    'order_details' => $order_details,
                    'send_address' => $send_address
                ]);
                $mpdf->AddPage();
                $mpdf->WriteHTML($page);
            } else {
                $page = View('pdf_templates.shipment_vourche1', [
                    'order_details' => $order_details,
                    'send_address' => $send_address
                ]);
                $mpdf->AddPage();
                $mpdf->WriteHTML($page);
                $page = View('pdf_templates.shipment_vourche2', [
                    'order_details' => $order_details,
                    'send_address' => $send_address,
                    'check_page' => false
                ]);
                $mpdf->AddPage();
                $mpdf->WriteHTML($page);
            }
        }

        foreach ($import_goods_ids as $key => $item) {
            $order_details = OrderDetail::with([
                'import_goods', 'import_goods.quote_customer', 'import_goods.quote_customer.request_vendors', 'order_header'
            ])->where('order_header_id', '=', $key)->get();

            $send_address = Address::find($order_details[0]->send_address_id);

            if ($order_details[0]->order_header->type_cond_pay != 1) {
                $page = View('pdf_templates.shipment_vourche2', [
                    'order_details' => $order_details,
                    'send_address' => $send_address,
                    'check_page' => true
                ]);
                $mpdf->AddPage();
                $mpdf->WriteHTML($page);
            }
        }
        $mpdf->Output('storage/pdf/' . $attachment_file_path, \Mpdf\Output\Destination::FILE);
        return  $attachment_file_path;
    }

    public function update_fee(Request $request)
    {
        $import_goods_ids = $request->idList;
        foreach ($import_goods_ids as $key => $id) {
            $import = ImportGoods::find($id);
            $import->order_detail->fee_daibiki = $import->order_header->fee_daibiki;
            $import->order_detail->fee_shipping = $import->order_header->fee_shipping;
            $import->order_detail->save();
        }
        return 'success';
    }

    public function shipment_change_status(Request $request)
    {
        $import_ids = $request->importGoodsIds;
        $pro_count = 0;
        $un_count = 0;
        foreach ($import_ids as $key => $id) {
            $shipment = ImportGoods::find($id);
            if ($shipment->export_status == 1) {
                $shipment->export_status = 0;
                $un_count++;
            } else {
                $shipment->export_status = 1;
                $pro_count++;
            }
            $shipment->save();
        }
        if ($pro_count >= $un_count)
            return 'processed';
        else
            return 'unprocessed';
    }

    public function export_excel(Request $request)
    {
        $obj_reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
        $obj_php_excel = $obj_reader->load(resource_path("views/excel_templates/shipment_excel_template.xls"));
        $j = 2;
        $ids = explode(",", $request->importIds[0]);
        foreach ($ids as $key => $item) {
            $import_goods = ImportGoods::find($item);
            $rate = Rate::where('type_money', '=', $import_goods->order_header->type_money)->first();
            $money = $import_goods->buy_qty * $import_goods->buy_cost * $rate->buy_rate;
            $customer_send_address = $import_goods->quote_customer->customer->user_info->billing_address[0];
            if ($customer_send_address) {
                $request_address = $customer_send_address->address1 . '-' . $customer_send_address->address2 . '-' . $customer_send_address->address3 . '-' . $customer_send_address->address4;
                $address_tel = $customer_send_address->tel;
                $address_zip = $customer_send_address->zip;
            } else {
                $request_address = '';
                $address_tel = '';
                $address_zip = '';
            }

            $obj_php_excel->getActiveSheet()->setCellValue('A' . $j, $import_goods->order_header->export_date);
            $obj_php_excel->getActiveSheet()->setCellValue('B' . $j, "");
            $obj_php_excel->getActiveSheet()->setCellValue('C' . $j, $import_goods->order_detail->plan_send_date);
            $obj_php_excel->getActiveSheet()->setCellValue('D' . $j, "");
            $obj_php_excel->getActiveSheet()->setCellValue('E' . $j, $import_goods->quote_customer->supplier->user_info->address->tel);
            $obj_php_excel->getActiveSheet()->setCellValue('F' . $j, $import_goods->quote_customer->supplier->user_info->address->zip);
            $obj_php_excel->getActiveSheet()->setCellValue('G' . $j, $import_goods->quote_customer->supplier->user_info->address->address1);
            $obj_php_excel->getActiveSheet()->setCellValue('H' . $j, "");
            $obj_php_excel->getActiveSheet()->setCellValue('I' . $j, $import_goods->quote_customer->supplier->user_info->company_name);
            $obj_php_excel->getActiveSheet()->setCellValue('J' . $j, "");
            $obj_php_excel->getActiveSheet()->setCellValue('K' . $j, $import_goods->quote_customer->supplier->user_info->representative);
            $obj_php_excel->getActiveSheet()->setCellValue('L' . $j, $import_goods->katashiki);
            $obj_php_excel->getActiveSheet()->setCellValue('M' . $j, "");
            $obj_php_excel->getActiveSheet()->setCellValue('N' . $j, $import_goods->interest_total);
            $obj_php_excel->getActiveSheet()->setCellValue('O' . $j, $money);
            $obj_php_excel->getActiveSheet()->setCellValue('P' . $j, $import_goods->quote_customer->customer->user_info->company_name);
            $obj_php_excel->getActiveSheet()->setCellValue('Q' . $j, $address_zip);
            $obj_php_excel->getActiveSheet()->setCellValue('R' . $j, $request_address);
            $obj_php_excel->getActiveSheet()->setCellValue('S' . $j, "");
            $obj_php_excel->getActiveSheet()->setCellValue('T' . $j, "");
            $obj_php_excel->getActiveSheet()->setCellValue('U' . $j, "");
            $obj_php_excel->getActiveSheet()->setCellValue('V' . $j, "");
            $obj_php_excel->getActiveSheet()->setCellValue('W' . $j, $address_tel);
            $j++;
        }

        $outputFileType = 'Xls';

        // Set file name
        $filename = 'ヤマト出荷Q';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');

        $obj_writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_php_excel, $outputFileType);
        $obj_writer->save('php://output');
        unset($this->obj_writer);
        unset($this->objWorksheet);
        unset($this->obj_reader);
        unset($this->obj_php_excel);
        exit();
    }

    public function import_excel(Request $request)
    {
        $file_name = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $file_name);

        $obj_reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
        $obj_php_excel = $obj_reader->load(public_path('uploads/' . $file_name));
        $sheet = $obj_php_excel->getActiveSheet();

        $highest_row = $sheet->getHighestRow(); // e.g. 10
        $highest_column = $sheet->getHighestColumn(); // e.g 'F'
        $highest_column_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highest_column); // e.g. 5
        for ($row = 2; $row <= $highest_row; ++$row) {
            // for ($col = 1; $col <= $highest_column_index; ++$col) {}
            $cus_phone_number = $sheet->getCellByColumnAndRow(5, $row)->getValue();
            $export_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($sheet->getCellByColumnAndRow(3, $row)->getValue()))->format('Y-m-d');

            $export_code = $sheet->getCellByColumnAndRow(6, $row)->getValue();
            $import_goods_list = ImportGoods::whereDate('export_date', '=', $export_date)->get();
            foreach ($import_goods_list as $item) {
                if ($item->order_detail->send_address->tel == $cus_phone_number) {
                    $item->out_tr = str_replace(',', '', number_format($export_code));
                    $item->save();
                }
            }
        }
        unset($this->obj_reader);
        unset($this->obj_php_excel);
        return 'success';
    }

    public function shipment_get_more(Request $request)
    {
        $current_number = $request->currentLength;
        $data_query = $this->get_shipment_query($request);

        if ($current_number > $data_query->count())
            return json_encode([]);
        else if ($data_query->count() - $current_number > ImportGoods::$shipment_limit) {
            $ids = $data_query->slice($current_number, ImportGoods::$shipment_limit)->keys();
            return UserInfo::convertCollectionToArray($data_query, $ids);
        } else {
            $ids = $data_query->slice($current_number, $data_query->count() - ImportGoods::$shipment_limit)->keys();
            return UserInfo::convertCollectionToArray($data_query, $ids);
        }
    }
}
