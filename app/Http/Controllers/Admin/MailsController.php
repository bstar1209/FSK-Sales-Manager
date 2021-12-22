<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerLog;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use App\Models\RequestQuoteVendor;
use App\Models\QuoteCustomer;
use App\Models\RfqDetail;
use App\Models\OrderDetail;
use App\Models\RfqRequest;
use App\Models\ImportGoods;
use App\Models\SupplierLog;
use App\Models\FeeShipping;
use App\Models\Daibiki;
use App\Models\Rate;
use App\Models\PaymentCondition;
use App\Models\Tax;
use App\Models\TemplateInfo;
use App\Models\HeaderQuarter;
use App\Models\Alert;
use Illuminate\Support\Facades\Auth;
use Meneses\LaravelMpdf\Facades\LaravelMpdf as PDF;

class MailsController extends Controller
{
    public function send_to_supplier(Request $request)
    {
        $data = RequestQuoteVendor::with(['rfq_request'])
            ->findOrFail($request->id);
        $data->is_sendmail = 1;
        $data->save();

        $details = [
            'content' => $request->content,
            'model_name' => $data->katashiki,
            'buy_quantity' => $data->quantity_buy,
        ];

        Mail::to($request->email)
            ->send(new \App\Mail\SendToSupplierMail($details));

        // Mail::to("sandy815.dev@outlook.com")
        //     ->send(new \App\Mail\SendToSupplierMail($details));

        return 'success';
    }

    public function send_to_customer(Request $request)
    {
        if (!$request->has('is_pdf_only'))
            $request->is_pdf_only = false;
        else
            $request->is_pdf_only = true;

        $customer_ids = $request->customerIds;
        $tax_info = Tax::latest()->first();

        if (isset($tax)) {
            $tax_date = $tax_info->create_at;
            $tax_value = $tax_info->tax;
        } else {
            $tax_date = '';
            $tax_value = 0;
        }

        if (strlen(trim($tax_date)) > 0)
            $tax_date = '平成' . (date('Y', strtotime($tax_date)) - 1988) . '年 ' . date('m', strtotime($tax_date)) . '月 ' . date('d', strtotime($tax_date)) . '日';

        $tax_value_diplay = '';
        if (strlen(trim($tax_value)) > 0)
            $tax_value_diplay = ($tax_value * 100) . '%';

        $mail_from = 'foresky@foresky.co.jp';
        $mail_template_name = "send_mail_estimate_to_customer";
        $file_paths = [];
        foreach ($customer_ids as $key => $item) {
            $info_quotes = QuoteCustomer::whereIn('id', $item)->get();
            $info_quotes->date_send = date_create();
            $shipping = FeeShipping::get_fee_shipping($info_quotes[0]->customer->user_info->billing_address[0]->address1, $info_quotes[0]->type_money_sell);
            // $shipping = FeeShipping::get_fee_shipping($info_quotes[0]->customer->user_info->address->address1, $info_quotes[0]->type_money_sell);
            $count_sale = 0;
            $sale_cost = 0;
            $is_send_mail = false;
            foreach ($info_quotes as $key => $item) {
                if ($item->is_sendmail == 1) {
                    $is_send_mail = true;
                    $count_sale += $item->sell_quantity_second;
                    $sale_cost += $item->money_sell_second;
                    $info_quotes[$key]->unit_price_sell = $item->unit_price_second;
                    $info_quotes[$key]->money_sell = $item->money_sell_second;
                } else {
                    $count_sale += $item->sell_quantity;
                    $sale_cost += $item->money_sell;
                }
            }

            if ((strlen($count_sale) != 0) && (strlen($sale_cost) != 0)) {
                $rate = Rate::where('type_money', '=', $info_quotes[0]->type_money_sell)->get();
                $rate_money = intval($rate[0]->sale_rate);
                $sale_cost = $sale_cost * $rate_money;
                $fee_ship = $shipping;

                if (strlen($fee_ship) == 0)
                    $fee_ship = 0;
                if ($sale_cost >= 20000 || $sale_cost == 0)
                    $fee_ship = 0;

                $sale_money_add_ship = $sale_cost + $fee_ship;
                $fee_daibiki = 0;
                $payment_codition = PaymentCondition::with('common')->find($info_quotes[0]->cond_payment);
                if ($payment_codition) {
                    if ($payment_codition->common->common_type == 0 && ($info_quotes[0]->type_money_sell == 'JPY')) {
                        $tax = (intval($sale_cost) + intval($fee_ship)) * $tax_value;
                        $sale_money_add_ship_and_tax = $sale_money_add_ship + $tax;
                        $daibiki = Daibiki::all();
                        foreach ($daibiki as $item) {
                            $min = intval($item->min);
                            $max = intval($item->max);
                            if (($sale_money_add_ship_and_tax >= $min) && ($sale_money_add_ship_and_tax < $min)) {
                                $fee_daibiki = $item->fee;
                            }
                        }
                    }
                }

                $total_sub = $sale_cost + $fee_ship + $fee_daibiki;
                // $total_sub = $request->price;
                $tax = intval($total_sub) * intval($tax_value);
                $total_money = $total_sub + $tax;
                $notice = []; //

                $attachment_file_path = "quoteToCustomer_" . $info_quotes[0]->id . '.pdf';

                $pdf = PDF::loadView('pdf_templates.send_to_customer', [
                    'tax_value' => $tax_value,
                    'info_quote' => $info_quotes,
                    'fee_daibiki' => $fee_daibiki,
                    'fee_ship' => $fee_ship,
                    'unit_price' => $request->price,
                ]);
                Storage::put($attachment_file_path, $pdf->output());
                // -----------------------------------------
                // $attachment_file_path = $this->create_pdf_quotation_to_customer($info_quotes, $tax_value, $fee_daibiki, $fee_ship, $payment_codition);

                if (!$request->is_pdf_only) {
                    $file_paths[] = $attachment_file_path;
                    $mail_tuuka = $info_quotes[0]->type_money_sell == 'JPY' ? '￥' : $info_quotes[0]->type_money_sell;
                    $mail_title = 'お見積もりメール　' . $info_quotes[0]->request_vendors->rfq_request_id . "-" . $info_quotes[0]->request_vendors->rfq_request_child_id . '　' . $info_quotes[0]->katashiki . '　' . $info_quotes[0]->sell_quantity . $info_quotes[0]->unit_sell . '　' . $mail_tuuka . $info_quotes[0]->unit_price_sell . '';
                    if ($is_send_mail)
                        $mail_title = '（変更有り）' . $mail_title;

                    $send_email = $info_quotes[0]->customer->user_info->email1;
                    // $send_email = 'sandy815.dev@outlook.com';

                    $data = [
                        'type_money' => $info_quotes[0]->type_money_sell,
                        'quotes' => $info_quotes,
                        'tax_value' => $tax_value,
                        'sale_cost' => $sale_cost,
                        'time_delivery' => '',
                        'fee_ship' => $fee_ship,
                        'info_quotes' => $info_quotes,
                        'notice' => $notice,
                        'payment_condition' => $payment_codition,
                        'fee_daibiki' => $fee_daibiki,
                        'total_sub' => $total_sub,
                        'tax' => $tax,
                        'tax_date' => $tax_date,
                        'title' => $mail_title,
                        'total_money' => $total_money
                    ];

                    $today = date_create()->format('Y-m-d');
                    $notification = Alert::whereDate('start_date', '<', $today)->whereDate('end_date', '>', $today)->orderBy('created_at')->first();

                    $quote_template_jp = TemplateInfo::where('template_index', '=', TemplateInfo::$template_type['Quote email to customer'])->first();
                    $header_quarter_jp = HeaderQuarter::where('type', '=', HeaderQuarter::$language_type['JP'])->first();

                    $get_type_currency = $info_quotes[0]->type_money_sell;
                    if ($info_quotes[0]->type_money_sell == 'JPY')
                        $type_money = '円';
                    else if ($info_quotes[0]->type_money_sell == 'USD')
                        $type_money = '$';
                    else if ($info_quotes[0]->type_money_sell == 'EUR')
                        $type_money = '€';
                    else
                        $type_money = $info_quotes[0]->type_money_sell;

                    foreach ($info_quotes as $item) {
                        $sell_quantity = $item->sell_quantity;
                        $unit_price_sell = $item->unit_price_sell;
                        $money_sell = $item->money_sell;
                    }

                    $common_name = '';
                    if ($payment_codition && $payment_codition->has('common'))
                        $common_name = $payment_codition->common->common_name;

                    $mailData = [
                        $info_quotes[0]->customer->user_info_company_name,
                        $info_quotes[0]->customer->representative,
                        (isset($notification)) ? $notification->message : '',
                        $info_quotes[0]->katashiki,
                        $info_quotes[0]->sell_quantity,
                        $info_quotes[0]->unit_sell,
                        $this->mail_format_numbers(intval($unit_price_sell), $get_type_currency),
                        $info_quotes[0]->maker,
                        $info_quotes[0]->deadline_quote,
                        $info_quotes[0]->dc,
                        $info_quotes[0]->rohs,
                        $info_quotes[0]->kbn2,
                        str_replace(array("\r\n", "\r", "\n"), "<br>　　　", $item->quote_prefer),
                        $this->mail_format_numbers(intval($money_sell), $get_type_currency),
                        $this->mail_format_numbers(intval($fee_ship),  $get_type_currency),
                        number_format(intval($fee_daibiki)),
                        $this->mail_format_numbers(intval($total_sub), $get_type_currency),
                        $this->mail_format_numbers(intval($tax), $get_type_currency),
                        $this->mail_format_numbers(intval($total_money), $get_type_currency),
                        $common_name,
                        $common_name,
                        $header_quarter_jp->company_name . '</br>' . $header_quarter_jp->tel . '</br>' . $header_quarter_jp->address,
                        $type_money
                    ];

                    $params = json_decode($quote_template_jp->template_params);
                    $email_text =  json_decode($quote_template_jp->template_content);
                    $email_title = $quote_template_jp->template_name;
                    foreach ($params as $key => $item) {
                        $email_text = str_replace($item, $mailData[$key], $email_text);
                    }

                    $data = [
                        'email_text' => $email_text
                    ];

                    Mail::send('emails.send_to_customer_mail1', $data, function ($message) use ($email_title, $pdf, $send_email) {
                        $message->to($send_email, $send_email)
                            ->subject($email_title)
                            ->attachData($pdf->output(), "text.pdf");
                    });

                    foreach ($info_quotes as $quote) {
                        if ($quote->is_send_mail == 1) {
                            $quote->rate_profit = $quote->rate_profit_second;
                            $quote->unit_price_sell = $quote->unit_price_second;
                            $quote->sell_quantity = $quote->sell_quantity_second;
                            $quote->money_sell = $quote->money_sell_second;
                        }
                        $quote->is_sendmail = 1;
                        $quote->rank_quote = $info_quotes[0]->rank_quote;
                        $quote->date_send = date_create();
                        $quote->save();
                        $slected_quote[] = $quote->id;
                        $quote->request_vendors->rfq_request->is_send_cus = 1;
                        $quote->request_vendors->rfq_request->save();
                    }

                    $current_time = date_create();
                    $old_log = CustomerLog::select(['customer_id', 'ans_quote_cus', 'created_at'])
                        ->where('customer_id', '=', $info_quotes[0]->customer_id)
                        ->whereDate('created_at', $current_time)->first();

                    if (!$old_log) {
                        $log = new CustomerLog;
                        $log->customer_id = $info_quotes[0]->customer_id;
                        $log->ans_quote_cus = 1;
                        $log->save();
                    } else {
                        $old_log->ans_quote_cus = +1;
                        $old_log->save();
                    }
                }
            }

            if ($request->is_pdf_only)
                return $attachment_file_path;
            else
                return 'success';
        }
    }

    public function send_update_order(Request $request)
    {
        $order_id_list = $request->idList;
        $email = $request->email;
        $emailType = $request->emailType;
        $title = $request->title;
        $content = $request->content;

        // Mail::to("sandy815.dev@outlook.com")
        //     ->send(new \App\Mail\SendUpdatedOrderMail($content));

        Mail::to($email)
            ->send(new \App\Mail\SendUpdatedOrderMail($content));

        foreach ($order_id_list as $key => $id) {
            $order_detail = OrderDetail::find($id);
            $order_detail->order_status = 9;
            $order_detail->solved = 9;
            $order_detail->save();

            $request = RfqDetail::find($order_detail->quote_customer->request_vendors->rfq_request->detail_id);
            $request->is_solved = 0;
            $request->save();
        }
        return 'success';
    }

    public function send_order_to_supplier(Request $request)
    {
        $order = OrderDetail::find($request->id);
        $country = $order->supplier->user_info->address->country;
        $email = $order->supplier->user_info->email1;
        // $email = 'sandy815.dev@outlook.com';
        $order_KBN = $order->order_KBN;
        $pdf_path = $request->pdf;

        if ($order_KBN == 1) {
            $order->sent_ship = 1;
            $order->save();
            if ($order->import_goods) {
                $order->import_goods->user_code = $order->order_no_by_customer;
                $order->import_goods->expectShipDate = $order->order_header->expect_ship_date;
                $order->import_goods->import_date_plan = $order->import_date_plan;
                $order->import_goods->send_date = $order->send_date;
                $order->import_goods->maker = $order->maker;
                $order->import_goods->katashiki = $order->katasiki;
                $order->import_goods->dc = $order->dc;
                $order->import_goods->rohs = $order->order_header->rohs;
                $order->import_goods->code_send = $order->code_send;
                $order->import_goods->ship_quantity = $order->ship_quantity;
                $order->import_goods->type_money_ship = $order->type_money_ship;
                $order->import_goods->unit_ship = $order->unit_buy_ship;
                $order->import_goods->price_ship = $order->price_ship;
                $order->import_goods->invoice_code = $order->order_header->code_invoice;
            } else {
                $import_goods = new ImportGoods;
                $import_goods->order_id = $order->order_header_id;
                $import_goods->order_detail_id = $order->id;
                $import_goods->quote_id = $order->quote_id;
                $import_goods->user_code = $order->order_no_by_customer;
                $import_goods->expect_ship_date = $order->order_header->expect_ship_date;
                $import_goods->import_date_plan = $order->import_date_plan;
                $import_goods->send_date = $order->send_date;
                $import_goods->maker = $order->maker;
                $import_goods->katashiki = $order->katashiki;
                $import_goods->dc = $order->dc;
                $import_goods->rohs = $order->order_header->rohs;
                $import_goods->code_send = $order->code_send;
                $import_goods->ship_quantity = $order->ship_quantity;
                $import_goods->type_money_ship = $order->type_money_ship;
                $import_goods->unit_ship = $order->unit_buy_ship;
                $import_goods->price_ship = $order->price_ship;
                $import_goods->invoice_code = $order->order_header->code_invoice;
                $import_goods->save();
            }
        }

        $pdf_content = Storage::get($pdf_path);

        $data = [
            "content" => $request->content,
            "title" => "Send order mail to supplier",
            'pdf_content' => $pdf_content
        ];

        Mail::send('emails.send_order_to_supplier_mail', $data, function ($message) use ($data, $email) {
            $message->to($email, $email)
                ->subject($data['title'])
                ->attachData($data['pdf_content'], "text.pdf");
        });

        $order->supplier->user_info->order_qty += 1;
        $order->supplier->user_info->save();

        $supplier_log = SupplierLog::where('supplier_id', '=', $order->supplier_id)
            ->whereDate('request_date', '=', date_create())
            ->first();

        if (isset($supplier_log)) {
            $supplier_log->ship_order_count += 1;
            $supplier_log->save();
        } else {
            $supplier_log = new SupplierLog;
            $supplier_log->supplier_id = $order->supplier_id;
            $supplier_log->request_date = date_create()->format('Y-m-d');
            $supplier_log->ship_order_count = 1;
            $supplier_log->save();
        }

        return 'success';
    }

    public function send_shipment_customer_mail(Request $request)
    {
        $content = json_decode($request->mailContent);
        $email = $request->email;
        $id_list = $request->idList;
        $mail_title = '商品発送のご案内';
        $white_space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $content = str_replace('小 計', $white_space . "小 計", $content);
        $content = str_replace('消 費 税 ', $white_space . " 消 費 税 ", $content);
        $content = str_replace('合 計 ', $white_space . "合 計", $content);

        Mail::to($email)
            ->send(new \App\Mail\SendShipmentToCustomer($content));

        foreach ($id_list as $key => $id) {
            $order = OrderDetail::find($id);
            $order->solved = 1;
            $order->save();
            $order->import_goods->is_send_mail = 1;
            $order->import_goods->save();
        }
        return 'success';
    }

    private function create_pdf_quotation_to_customer($quotes, $tax_value, $fee_daibiki, $fee_ship, $payment_codition)
    {
        $attachment_file_path = "attachmentFile/quoteToCustomer_" . $quotes[0]->id . 'pdf';
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf_templates.send_to_customer', [
            'tax_value' => $tax_value,
            'info_quote' => $quotes,
            'fee_daibiki' => $fee_daibiki,
            'fee_ship' => $fee_ship
        ])->setOptions(['dpi' => 300, 'orientation' => 'landscape'])
            ->setPaper('a4', 'landscape')
            ->SetHeader('ページ数  {PAGENO} / {nb}');
        Storage::put($attachment_file_path, $pdf->output());

        return $attachment_file_path;
    }

    public function generate_pdf_test(Request $request)
    {
        $pdf = PDF::loadView('pdf_templates.send_updated_order', []);
        return $pdf->download('document.pdf');
    }

    public function template_test(Request $request)
    {
        $template = TemplateInfo::find($request->id);
        Mail::to(Auth::user()->email)
            ->send(new \App\Mail\SendUpdatedOrderMail(json_decode($template->template_content)));
        return 'success';
    }

    private function mail_format_numbers($number, $type)
    {
        $number = intval($number);
        $type = $type[1];
        if ($type == 'JPY')
            return number_format($number);
        else {
            $formated_number = '';
            $split_numbers = explode('.', $number);
            if (count($split_numbers) > 1)
                $formated_number = number_format($number, 2);
            else
                $formated_number = number_format($number);
        }
        return $formated_number;
    }
}
