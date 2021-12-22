<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\UserInfo;
use App\Models\Customer;
use App\Models\CustomerLog;
use App\Models\TemplateInfo;
use App\Models\HeaderQuarter;
use App\Models\Alert;
use App\Models\Address;
use App\Models\Parts;
use App\Models\RfqRequest;
use App\Models\RequestQuoteVendor;
use App\Models\OrderDetail;
use App\Models\Maker;
use App\Models\CartLog;
use App\Models\QuoteCustomer;
use App\Models\Rate;
use App\Models\Tax;
use App\Models\OrderHeader;
use App\Models\FeeShipping;
use App\Models\Common;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ChangePassowrdRequest;
use App\Http\Requests\MemberRegisterRequest;
use App\Models\ImportGoods;

use Meneses\LaravelMpdf\Facades\LaravelMpdf as PDF;

class AccountController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }

    public function search_index()
    {
        return view('frontend.search');
    }

    public function purchase_index()
    {
        return view('frontend.purchase')->render();
    }

    public function terms_index()
    {
        return view('frontend.terms');
    }

    public function account_index()
    {
        $date = date('Y-m-d', strtotime("-1 month"));
        $wait_estimate_count = RfqRequest::whereDate('created_at', '>', $date)
            ->where([
                ['customer_id', Auth::user()->customer->id],
                ['is_cancel', 0],
                ['is_send_cus', 0],
            ])->count();

        $estimate_count = QuoteCustomer::whereDate('receive_date', '>', $date)->where([
            ['is_sendmail', 1],
            ['is_order', 0],
            ['is_delete', 0],
            ['is_together', 0],
            ['customer_id', Auth::user()->customer->id],
        ])->count();

        $order_detail_count = OrderDetail::with(['order_header'])
            ->get()
            ->where('order_header.receive_order_date', '>', $date)
            ->where('order_header.customer_id', '=', Auth::user()->customer->id)
            ->where('solved', '=', 0)->count();

        $date = date('Y-m-d', strtotime("-1 months"));
        $shipment_count = ImportGoods::with('order_detail')->where('is_send_mail', '=', 1)->whereDate('export_date', '>', $date)->count();
        return view('frontend.account')->with([
            'wait_count' => $wait_estimate_count,
            'estimate_count' => $estimate_count,
            'order_detail_count' => $order_detail_count,
            'shipment_count' => $shipment_count
        ]);
    }

    public function order_index(Request $request)
    {
        return view('frontend.partials.order_table')->with([
            'order_date' => $request->orderDate,
            'order_number' => $request->orderNumber,
            'model_number' => $request->modelNumber,
            'period' => $request->period
        ])->render();
    }

    public function company_profile_index()
    {
        return view('frontend.company_profile');
    }

    public function overseas_manufacturer_index()
    {
        return view('frontend.overseas_manufacturer');
    }

    public function parts_mass_production_index()
    {
        return view('frontend.parts_mass_production');
    }

    public function shipping_cash_on_delivery_index()
    {
        return view('frontend.shipping_cash_on_delivery');
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // User can not be found using email
            if (!User::where('email', '=', $request->email)->first()) {
                return json_encode([
                    'success' => false,
                    'property' => 'email',
                ]);
            }

            // Password does not match
            return json_encode([
                'success' => false,
                'property' => 'password',
            ]);
        }

        return json_encode([
            'success' => true,
            'user' => User::with(['customer', 'customer.user_info'])->find(Auth::user()->id)
        ]);
    }

    public function password_reset(ResetPasswordRequest $request)
    {
        $user = User::with(['customer', 'customer.user_info'])->where('email', '=', $request->email)->first();

        if (!$user) {
            return json_encode([
                'success' => false,
                'msg' => 'メールアドレスが見つかりません.',
            ]);
        }

        $str_password = '12345678';
        $new_password = Hash::make($str_password);
        $user->password = $new_password;
        $user->save();

        $today = date_create()->format('Y-m-d');
        $notification = Alert::whereDate('start_date', '<', $today)->whereDate('end_date', '>', $today)->orderBy('created_at')->first();
        $quote_template_jp = TemplateInfo::where('template_index', '=', TemplateInfo::$template_type['Password reset email'])->first();
        $header_quarter_jp = HeaderQuarter::where('type', '=', HeaderQuarter::$language_type['JP'])->first();

        $mailData = [
            $user->customer->user_info->company_name,
            $user->customer->representative,
            (isset($notification)) ? $notification->message : '',
            $user->email,
            $str_password,
            $header_quarter_jp->company_name . '</br>' . $header_quarter_jp->tel . '</br>' . $header_quarter_jp->address,
        ];
        $params = json_decode($quote_template_jp->template_params);
        $email_text =  json_decode($quote_template_jp->template_content);
        $email_title = $quote_template_jp->template_name;
        foreach ($params as $key => $item) {
            $email_text = str_replace($item, $mailData[$key], $email_text);
        }
        Mail::to($user->email)
            ->send(new \App\Mail\SendUpdatedOrderMail($email_text));

        return json_encode([
            'success' => true,
            'msg' => '新しいパスワードをメールで送信しました. メールをご確認ください.',
        ]);
    }

    public function password_change(ChangePassowrdRequest $request)
    {
        $user = User::find(Auth::id());
        $user->password = Hash::make($request->password);
        $user->save();
        return json_encode('success');
    }

    public function model_search(Request $request)
    {
        if ($request->has('model') && $request->model != null)
            $model = $request->model;
        else if ($request->session()->has('model'))
            $model = $request->session()->get('model');
        else
            $model = '';

        if ($request->has('type') && $request->type != null)
            $type = $request->type;
        else if ($request->session()->has('type'))
            $type = $request->session()->get('type');
        else
            $type = 0;
        
        // remove special characters in model name given
        $not_special_model = preg_replace('/[^A-Za-z0-9\-]/', '', $model);

        if ($type == 1)
            $parts_list = Parts::where('katashiki', 'LIKE', "%$not_special_model%")->get();
        else
            $parts_list = Parts::where('katashiki', 'LIKE', "$not_special_model%")->get();

        return json_encode($parts_list->whereNotNull('maker')->unique('katashiki')->values()->all());
    }

    public function get_address_from_post_code(Request $request) 
    {
        $item = $this->get_zip2address($request->zip);
        if (!$item)
            return json_encode(false);
        return $item;
    }

    public function member_register(MemberRegisterRequest $request)
    {
        $item = $this->get_zip2address($request->zip);
        if (!$item)
            return false;

        $user = new User;
        $user->name = $request->company_name;
        $user->email = $request->email;
        $user->role = "customer";
        $user->password = Hash::make($request->password);
        $user->save();

        $address_list = [];
        for ($i = 2; $i > -1; $i--) {
            $address = new Address;
            $address->zip = $request->zip;
            $address->address1 = $item['state'];
            $address->address2 = $item['city'];
            $address->address3 = $item['street'];
            $address->address_type = $i;
            $address->address_index = 1;
            $address->save();

            array_push($address_list, $address);
        }

        $user_info = new UserInfo;
        $user_info->type = 'customer';
        $user_info->address_id = $address->id;
        $user_info->company_name = $request->company_name;
        $user_info->company_name_kana = $request->company_name;
        $user_info->email1 = $request->email;
        $user_info->rank = 1;
        $user_info->est_req_time = 0;
        $user_info->est_ans_time = 0;
        $user_info->order_qty = 0;
        $user_info->order_money = 0;
        $user_info->save();

        foreach ($address_list as $address) {
            $address->user_info_id = $user_info->id;
            $address->save();
        }

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->user_info_id = $user_info->id;
        $customer->representative = $request->name;
        $customer->representative_business = $request->name;
        $customer->name = $request->name;
        $customer->is_friend = 0;
        $customer->save();

        $today = date_create()->format('Y-m-d');
        $notification = Alert::whereDate('start_date', '<', $today)->whereDate('end_date', '>', $today)->orderBy('created_at')->first();
        $quote_template_jp = TemplateInfo::where('template_index', '=', TemplateInfo::$template_type['Member registration confirmation email'])->first();
        $header_quarter_jp = HeaderQuarter::where('type', '=', HeaderQuarter::$language_type['JP'])->first();

        $mailData = [
            $customer->user_info->company_name,
            $customer->representative,
            (isset($notification)) ? $notification->message : '',
            $header_quarter_jp->company_name . '</br>' . $header_quarter_jp->tel . '</br>' . $header_quarter_jp->address,
        ];
        $params = json_decode($quote_template_jp->template_params);
        $email_text =  json_decode($quote_template_jp->template_content);
        $email_title = $quote_template_jp->template_name;
        foreach ($params as $key => $item) {
            $email_text = str_replace($item, $mailData[$key], $email_text);
        }

        Mail::to($request->email)
            ->send(new \App\Mail\SendUpdatedOrderMail($email_text));

        return true;
    }

    public function getURL_zip2address($zip)
    {
        $res = preg_match('/(\d{3})\-?(\d{4})/', $zip, $arr);
        return ($res == 0) ? FALSE : ("http://api.thni.net/jzip/X0401/JSON/{$arr[1]}/{$arr[2]}.js");
    }

    public function get_zip2address($zip)
    {
        $url =  $this->getURL_zip2address($zip);    // Request URL
        
        if ($url == FALSE)    return FALSE;

        $json = @file_get_contents($url);
        if ($json == FALSE)    return FALSE;

        $arr = json_decode($json);
        if (!isset($arr->state))    return FALSE;

        $item['state']  = (string)$arr->stateName;
        $item['city']   = (string)$arr->city;
        $item['street'] = (string)$arr->street;

        return $item;
    }

    public function customer_log_update(Request $request)
    {
        $current_time = date_create()->format('Y-m-d');
        if (Auth::user()) {
            $customer = Customer::find(Auth::id());
            $customer->search_time += 1;
            $customer->save();
            $customer_id = $customer->id;
        } else
            $customer_id = 0;

        $log = CustomerLog::whereDate('created_at', '=', $current_time)
            ->where('customer_id', $customer_id)
            ->first();
        if ($log) {
            $log->search_count += 1;
            $log->save();
        } else {
            $cus_log = new CustomerLog;
            $cus_log->customer_id = $customer_id;
            $cus_log->search_count = 1;
            $cus_log->request_date = $current_time;
            $cus_log->save();
        }
        return 'success';
    }

    public function get_quote_wait_list(Request $request)
    {
        $date = date('Y-m-d', strtotime("-". $request->month. " months"));
        $data = RfqRequest::whereDate('created_at', '>', $date)
            ->where([
                ['customer_id', Auth::user()->customer->id],
                ['is_cancel', 0],
                ['is_send_cus', 0],
            ])
            ->orderBy('created_at', 'desc');

        if ($request->has('model') && $request->model != null)
            $data = $data->where('katashiki', 'LIKE', "%$request->model%");

        return json_encode($data->get());
    }

    public function get_estimate_answer(Request $request)
    {
        $date = date('Y-m-d', strtotime("-". $request->month ." months"));
        $data = QuoteCustomer::whereDate('created_at', '>', $date)
            ->where([
                ['customer_id', Auth::user()->customer->id],
                ['is_sendmail', 1],
                ['is_together', 0],
                ['is_delete', 0],
                ['is_order', 0],
            ])->orderBy('created_at', 'desc');

        if ($request->has('model') && $request->model != null)
            $data = $data->where('katashiki', 'LIKE', "%$request->model%");

        return json_encode($data->get());
    }

    public function cancel_rfq(Request $request)
    {
        $un_rfq = RfqRequest::find($request->id);
        $un_rfq->is_cancel = 1;
        $un_rfq->cancel_date = date_create()->format('Y-m-d');
        $un_rfq->save();
        return true;
    }

    public function create_rfq(Request $request)
    {
        $data = $request->data;
        $today = date_create()->format('Y-m-d');
        $main_id = 0;
        foreach ($data as $key => $item) {
            $cart = CartLog::with(['part'])->find($item['id']);

            $rfq_request = new RfqRequest;
            $rfq_request->customer_id = Auth::user()->customer->id;
            if (isset($cart->part->maker) && $cart->part->maker != '') {
                $maker = Maker::where('maker_name', '=', $cart->part->maker)->first();
                if (!isset($maker)) {
                    $maker = new Maker;
                    $maker->maker_name = $cart->part->maker;
                    $maker->save();
                }
            }

            $rfq_request->maker = $cart->part->maker;
            $rfq_request->dc = $cart->dc;
            $rfq_request->kbn2 = $cart->part->kubun2;
            $rfq_request->kbn = $cart->part->kubun;
            $rfq_request->katashiki = $cart->part->katashiki;
            $rfq_request->katashiki_not_spl = $cart->part->katashiki;
            $rfq_request->price_aspiration = $item['unitPrice'];
            $rfq_request->quantity_aspiration = $item['qty'];
            $rfq_request->count_aspiration = $item['qty'];
            $rfq_request->condition1 = $item['cond1'];
            $rfq_request->condition2 = $item['cond2'];
            $rfq_request->condition3 = $item['cond3'];
            $rfq_request->total = count($data);
            $rfq_request->child_index = $key + 1;
            $rfq_request->detail_id = 0;
            $rfq_request->is_solved = 1;
            $rfq_request->comment = $item['remarks'];
            $rfq_request->save();
            if ($key == 0)
                $main_id = $rfq_request->id;
            $rfq_request->detail_id = $main_id;
            $rfq_request->save();

            $request_quote_vendor = new RequestQuoteVendor;
            $request_quote_vendor->rfq_request_id = $rfq_request->id;
            $request_quote_vendor->rfq_request_child_id = $rfq_request->id;
            $request_quote_vendor->supplier_id = 0;
            $request_quote_vendor->katashiki = $rfq_request->katashiki;
            $request_quote_vendor->katashiki_not_spl = $rfq_request->katashiki_not_spl;
            $request_quote_vendor->quantity_buy = $rfq_request->quantity_aspiration;
            $request_quote_vendor->unit_price_buy = $rfq_request->price_aspiration;
            $request_quote_vendor->save();

            $today = date_create()->format('Y-m-d');
            $notification = Alert::whereDate('start_date', '<', $today)->whereDate('end_date', '>', $today)->orderBy('created_at')->first();
            $quote_template_jp = TemplateInfo::where('template_index', '=', TemplateInfo::$template_type['Quotation request confirmation email'])->first();
            $header_quarter_jp = HeaderQuarter::where('type', '=', HeaderQuarter::$language_type['JP'])->first();
            $user = Auth::user();
            $mailData = [
                $user->customer->user_info->company_name,
                $user->customer->representative,
                (isset($notification)) ? $notification->message : '',
                $rfq_request->price_aspiration,
                $rfq_request->katashiki,
                $rfq_request->quantity_aspiration,
                $rfq_request->price_aspiration,
                $rfq_request->maker,
                $rfq_request->dc,
                '',
                '',
                $item['cond1'],
                $item['cond2'],
                $item['cond3'],
            ];
            $params = json_decode($quote_template_jp->template_params);
            $email_text =  json_decode($quote_template_jp->template_content);
            $email_title = $quote_template_jp->template_name;
            foreach ($params as $key => $item) {
                $email_text = str_replace($item, $mailData[$key], $email_text);
            }

            Mail::to($user->email)
                ->send(new \App\Mail\SendUpdatedOrderMail($email_text));
            $cart->delete();
        }

        $customer_log = CustomerLog::whereDate('request_date', '=', $today)->where('customer_id', '=', Auth::user()->customer->id)->first();
        if (!isset($customer_log)) {
            $customer_log = new CustomerLog;
            $customer_log->customer_id = Auth::user()->customer->id;
            $customer_log->res_count = 1;
            $customer_log->request_date = $today;
        } else {
            $customer_log->res_count += 1;
        }
        $customer_log->save();

        return 'success';
    }

    public function re_quote_request(Request $request)
    {
        $quote = QuoteCustomer::find($request->id);
        $quote->sell_quantity = $request->qty;
        $quote->save();
        return 'success';
    }

    public function quote_customer_delete(Request $request)
    {
        $quote = QuoteCustomer::find($request->id);
        if (isset($quote)) {
            $quote->delete();
        }
        return 'success';
    }

    public function copy_billing_address(Request $request)
    {
        $billing_address = Address::where([
            ['user_info_id', '=', Auth::user()->customer->user_info_id],
            ['address_type', '=', 1],
            ['address_index', '=', $request->index]
        ])->first();

        if (!isset($billing_address))
            return 'failed1';
        else {
            $old_delivery_address = Address::where([
                ['user_info_id', '=', Auth::user()->customer->user_info_id],
                ['address_type', '=', 2],
                ['address_index', '=', $request->index]
            ])->first();

            if (isset($old_delivery_address))
                $old_delivery_address->delete();

            $address = new Address;
            $address->user_info_id = $billing_address->user_info_id;
            $address->zip = $billing_address->zip;
            $address->comp_type = $billing_address->comp_type;
            $address->address1 = $billing_address->address1;
            $address->address2 = $billing_address->address2;
            $address->address3 = $billing_address->address3;
            $address->address4 = $billing_address->address4;
            $address->part_name = $billing_address->part_name;
            $address->address_type = 2;
            $address->address_index = $request->index;
            $address->customer_name = $billing_address->customer_name;
            $address->tel = $billing_address->tel;
            $address->fax = $billing_address->fax;
            $address->save();

            return 'success';
        }
        return 'failed2';
    }

    public function shipment_list(Request $request)
    {
        $date = date('Y-m-d', strtotime("-". $request->order_period ."months"));
        $order_list = ImportGoods::with('order_detail')
            ->where('is_send_mail', '=', 1)
            ->whereDate('export_date', '>', $date);

        if ($request->has('ship_date') && $request->ship_date != null) {
            $order_list = $order_list->where('export_date', '=', "$request->ship_date%");
        }

        if ($request->has('order_number') && $request->order_number != null) {
            $order_list = $order_list->whereHas('order_detail', function ($query) use ($request) {
                $query->where('order_no_by_customer', 'LIKE', "$request->order_number%");
            });
        }

        if ($request->has('invoice_number') && $request->invoice_number != null) {
            $order_list = $order_list->where('invoice_code', 'LIKE', "$request->invoice_number%");
        }

        if ($request->has('model_number') && $request->model_number != null) {
            $order_list = $order_list->where('katashiki', 'LIKE', "$request->model_number%");
        }

        if ($request->has('billing_number') && $request->billing_number != null) {
            $order_list = $order_list->where('out_tr', 'LIKE', "$request->billing_number%");
        }

        return json_encode($order_list->get());
    }

    public function ship_pdf_generate(Request $request)
    {
        $import_info = ImportGoods::with([
            'order_detail', 'order_detail.send_address', 'order_header', 'order_header.tax_info',
            'quote_customer', 'quote_customer.request_vendors'
        ])->find($request->id);

        $attachmentFilePath = "shipped" . rand(0, 10000) . ".pdf";

        $pdf = PDF::loadView('pdf_templates.front_ship', [
            'import_info' => $import_info,
        ]);
        Storage::put($attachmentFilePath, $pdf->output());
        return json_encode($attachmentFilePath);
    }

    public function overseas_send_mail(Request $request)
    {
        $user = Auth::user();
        $today = date_create()->format('Y-m-d');
        $notification = Alert::whereDate('start_date', '<', $today)->whereDate('end_date', '>', $today)->orderBy('created_at')->first();
        $quote_template_jp = TemplateInfo::where('template_index', '=', TemplateInfo::$template_type['Confirmation email for overseas manufacturer product procurement request'])->first();
        $header_quarter_jp = HeaderQuarter::where('type', '=', HeaderQuarter::$language_type['JP'])->first();

        $mailData = [
            $user->customer->user_info->company_name,
            $user->customer->representative,
            (isset($notification)) ? $notification->message : '',
            $request->email,
            $request->content
        ];
        $params = json_decode($quote_template_jp->template_params);
        $email_text =  json_decode($quote_template_jp->template_content);
        $email_title = $quote_template_jp->template_name;
        foreach ($params as $key => $item) {
            $email_text = str_replace($item, $mailData[$key], $email_text);
        }
        Mail::to("hajime@foresky.co.jp")
            ->send(new \App\Mail\SendUpdatedOrderMail($email_text));
        return 'success';
    }

    public function parts_mass_production_mail(Request $request)
    {
        $user = Auth::user();
        $today = date_create()->format('Y-m-d');
        $notification = Alert::whereDate('start_date', '<', $today)->whereDate('end_date', '>', $today)->orderBy('created_at')->first();
        $quote_template_jp = TemplateInfo::where('template_index', '=', TemplateInfo::$template_type['Parts procurement request email for mass production'])->first();
        $header_quarter_jp = HeaderQuarter::where('type', '=', HeaderQuarter::$language_type['JP'])->first();

        $mailData = [
            $user->customer->user_info->company_name,
            $user->customer->representative,
            (isset($notification)) ? $notification->message : '',
            $request->projectName,
            $request->endUsername,
            $request->useApp,
            $request->annUsage,
            $request->startTime,
            $request->inqueryContent,
            $header_quarter_jp->company_name . '</br>' . $header_quarter_jp->tel . '</br>' . $header_quarter_jp->address
        ];

        $params = json_decode($quote_template_jp->template_params);
        $email_text =  json_decode($quote_template_jp->template_content);
        $email_title = $quote_template_jp->template_name;
        foreach ($params as $key => $item) {
            $email_text = str_replace($item, $mailData[$key], $email_text);
        }
        Mail::to("sales@foresky.co.jp")
        // Mail::to("miedom941@gmail.com")
            ->send(new \App\Mail\SendUpdatedOrderMail($email_text));
        return 'success';
    }

    public function purchase_request(Request $request)
    {        
        $list_ids = $request->idList;
        $cond = $request->payment;
        $payment_condition = Common::find($cond);
        $expect_ship_date = $request->orderDesired;
        $order_no = $request->orderYour;
        if ($order_no == null)
            $order_no = '';
        $type_money_sell = $request->typeMoneyList[0];

        $customer_id = Auth::user()->customer->id;
        $customer = Customer::with([
                'user_info', 
                'user_info.address', 
                'user_info.billing_address', 
                'user_info.deliver_address'
            ])
            ->find($customer_id);

        $type_money_sell = $request->typeMoneyList[0];
        $send_address = Address::find($request->sendAddress);
        $request_address = Address::find($request->requestAddress);
        
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
        
        $sql_tax = Tax::latest()->first(); // get the latest tax value.
        $tax = $sql_tax->tax;
        $code_quote = $order_no;
        
        $order_header = new OrderHeader();
        $order_header->customer_id = $customer_id;
        $order_header->tax_id = $sql_tax->id;
        $order_header->expect_ship_date = $expect_ship_date;
        $order_header->cond_payment = $payment_condition->common_name;
        $order_header->payment_cond_id = $cond;
        $order_header->type_cond_pay = $cond;
        $order_header->order_no_by_customer = $order_no;
        $order_header->sale_type_money = $type_money_sell;
        $order_header->type_money = $type_money_sell;
        
        $order_header->fee_shipping = $fee_shipping;
        $order_header->fee_daibiki = $fee_daibiki;
        $order_header->receive_order_date = date_create();
        if ($order_header->save()) {
            $count = 1;
            $total_order_qty = 0;
            $total_money_buy = 0;
            $list_ID = '0,';
            
            foreach ($list_ids as $key => $item) {
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
                $order_detail->order_no_by_customer = $request->orderNumList[$key];
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

                // $list_quote = QuoteCustomer::where('rank_quote', '=', $quote_info->rank_quote)->get();

                // if ($list_quote->count() > 0) {
                //     foreach($list_quote as $quote_item) {
                //         $quote_item->is_together = 1;
                //         $quote_item->save();
                //     }
                // }

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

            // //sending message
            // $mail_title = "発注確認メール";

            // $details = [
            //     'address' => $request_address,
            //     'quote_info'=>QuoteCustomer::with(['request_vendors', 'customer', 'customer.user_info'])->whereIn('id', $list_ids)->get(),
            //     'fee_shipping'=>$fee_shipping,
            //     'fee_daibiki'=>$fee_daibiki,
            //     'code_quote'=>$code_quote,
            //     'sub_total'=>$total_money_buy,
            //     'tax'=>$sql_tax->tax,
            //     'notice' => '',
            //     'mail_title' => $mail_title
            // ];

            // $mail_to = $customer->user_info->email1;
            // // $mail_to = "sandy815.dev@outlook.com";
            // Mail::to($mail_to)
            //     ->send(new \App\Mail\SendToCustomerOrderMail($details));
            return 'success';
        }
    }
}
