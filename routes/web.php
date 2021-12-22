<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\RfqController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ManagementController;
use App\Http\Controllers\Admin\RateController;
use App\Http\Controllers\Admin\MakerController;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\MailsController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ImportGoodsController;
use App\Http\Controllers\Admin\RequestQuoteVendorController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\TableConfigController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('admin/', function () {
    return view('auth/login');
});
Route::get('/', [AccountController::class, 'index'])->name('frontend.index');

Route::prefix('admin')->name('admin.')->middleware(['across.check'])->group(function () {
    Route::prefix('rfq')->name('rfq.')->group(function () {
        Route::get('', [RfqController::class, 'index'])->name('index');
        Route::post('get_list/unRfq', [RfqController::class, 'get_unRfq'])->name('get_list.unRfq');
        Route::post('get_list/request_quote_vendor', [RfqController::class, 'get_request_quote_vendor'])->name('get_list.request_quote_vendor');
        Route::post('get_list/history', [RfqController::class, 'get_history'])->name('get_list.history');
        Route::post('change_rfq_status', [RfqController::class, 'change_rfq_status'])->name('status.change');
        Route::post('daily', [RfqController::class, 'daily_rfq'])->name('daily');
        Route::post('date', [RfqController::class, 'date_rfq'])->name('date');
        Route::post('get_more_data', [RfqController::class, 'get_more_data'])->name('get_more_data');
        Route::post('get_history_more_data', [RfqController::class, 'get_history_more_data'])->name('get_history_more_data');
        Route::post('get_request_quote_vendor_more_data', [RfqController::class, 'get_request_quote_vendor_more_data'])->name('get_request_quote_vendor_more_data');
        Route::post('send_quote', [RequestQuoteVendorController::class, 'send_quote'])->name('send.quote');
        Route::post('message_from_customer_for_rfq', [MessageController::class, 'rfq_customer_message'])->name('message.rfq_customer');
    });

    Route::post('customer/list', [CustomerController::class, 'list'])->name('customer.list');
    Route::post('customer/ajax_list', [CustomerController::class, 'ajax_list'])->name('customer.ajax_list');
    Route::post('customer/get_customer_log', [CustomerController::class, 'get_customer_log'])->name('customer.get_log');

    Route::post('supplier/list', [SupplierController::class, 'list'])->name('supplier.list');
    Route::post('maker/list', [MakerController::class, 'list'])->name('maker.list');
    Route::post('ship_transport/list', [OrderController::class, 'ship_transport_list'])->name('ship_transport.list');

    Route::post('common/get_payment_list', [CommonController::class, 'get_payment_list'])->name('common.payment.list');
    Route::post('common/get_common_list', [CommonController::class, 'get_common_list'])->name('common.list');

    Route::post('quotation/get_quote_list', [QuotationController::class, 'get_quote_list'])->name('quotation.get_list');
    Route::post('quotation/get_quote_more_list', [QuotationController::class, 'get_quote_more_list'])->name('quotation.get_quote_more_list');
    Route::post('quotation/re_investigation_request', [QuotationController::class, 're_investigation_request'])->name('quotation.re_investigation');
    Route::post('quotation/change_quote_status', [QuotationController::class, 'change_quote_status'])->name('quotation.change_status');
    Route::post('quotation/duplicated_quote', [QuotationController::class, 'duplicated_quote'])->name('quotation.duplicated_quote');
    Route::post('quotation/sold_out', [QuotationController::class, 'sold_out'])->name('quotation.sold_out');
    Route::post('quotation/order_to', [QuotationController::class, 'order_to'])->name('quotation.order_to');
    Route::post('quotation/get_history', [QuotationController::class, 'get_history'])->name('quotation.get_list.history');
    Route::post('quotation/get_history_more_data', [QuotationController::class, 'get_history_more_data'])->name('quotation.get_history_more_data');

    Route::post('order/get_order_list', [OrderController::class, 'get_order_list'])->name('order.get_order_list');
    Route::post('order/get_order_more_list', [OrderController::class, 'get_order_more_list'])->name('order.get_order_more_list');
    Route::post('order/update_kbn', [OrderController::class, 'update_kbn'])->name('order.update_kbn');
    Route::post('order/invoice', [OrderController::class, 'invoice'])->name('order.invoice');
    Route::post('order/change_status', [OrderController::class, 'change_status'])->name('order.change_status');
    Route::post('order/cancel_order', [OrderController::class, 'cancel_order'])->name('order.cancel_order');

    Route::prefix('ship_order')->name('ship_order.')->group(function () {
        Route::get('index', [OrderController::class, 'ship_index'])->name('index');
        Route::post('get_order_list', [OrderController::class, 'get_ship_order_list'])->name('get_list');
        Route::post('get_order_more_list', [OrderController::class, 'get_ship_order_more_list'])->name('get_more_list');
        Route::post('update_order', [OrderController::class, 'update_ship_order'])->name('update');
        Route::post('generate_order_pdf', [OrderController::class, 'generate_order_pdf'])->name('generate_order_pdf');
        Route::post('change_status', [OrderController::class, 'ship_change_status'])->name('change_status');
        Route::post('return_to_order', [OrderController::class, 'return_to_order'])->name('return_to_order');
    });

    Route::post('send_to_supplier_mail', [MailsController::class, 'send_to_supplier'])->name('mail.send_supplier');
    Route::post('send_order_to_supplier_mail', [MailsController::class, 'send_order_to_supplier'])->name('mail.send_order_to_supplier');
    Route::post('send_to_customer_mail', [MailsController::class, 'send_to_customer'])->name('mail.send_customer');
    Route::post('send_update_order_mail', [MailsController::class, 'send_update_order'])->name('mail.send_update_order');
    Route::post('send_shipment_customer_mail', [MailsController::class, 'send_shipment_customer_mail'])->name('mail.send_shipment_customer');
    Route::GET('test', [MailsController::class, 'generate_pdf_test'])->name('mail.generate_pdf');

    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('index', [ImportGoodsController::class, 'stock_index'])->name('index');
        Route::post('get_stock_list', [ImportGoodsController::class, 'get_stock_list'])->name('get_list');
        Route::post('get_order_more_list', [ImportGoodsController::class, 'get_stock_more_list'])->name('get_more_list');
        Route::post('update_stock', [ImportGoodsController::class, 'update_stock'])->name('update');
        Route::post('actual_slip', [ImportGoodsController::class, 'actual_slip'])->name('actual_slip');
        Route::post('change_status', [ImportGoodsController::class, 'change_status'])->name('change_status');
        Route::post('to_shipping', [ImportGoodsController::class, 'to_shipping'])->name('to_shipping');
        Route::post('return_to', [ImportGoodsController::class, 'return_to'])->name('return_to');
        Route::post('sold_out', [ImportGoodsController::class, 'sold_out'])->name('sold_out');
    });

    Route::prefix('shipment')->name('shipment.')->group(function () {
        Route::get('index', [ImportGoodsController::class, 'shipment_index'])->name('index');
        Route::post('get_list', [ImportGoodsController::class, 'get_shipment_list'])->name('get_list');
        Route::post('update', [ImportGoodsController::class, 'shipment_update'])->name('update');
        Route::post('envelope', [ImportGoodsController::class, 'envelope'])->name('envelope');
        Route::post('voucher', [ImportGoodsController::class, 'voucher'])->name('voucher');
        Route::post('change_status', [ImportGoodsController::class, 'shipment_change_status'])->name('change_status');
        Route::post('update_fee', [ImportGoodsController::class, 'update_fee'])->name('update_fee');
        Route::post('export_excel', [ImportGoodsController::class, 'export_excel'])->name('export_excel');
        Route::post('import_excel', [ImportGoodsController::class, 'import_excel'])->name('import_excel');
        Route::post('get_more_list', [ImportGoodsController::class, 'shipment_get_more'])->name('get_more_list');
    });

    Route::get('management/sales_summary/index', [ManagementController::class, 'sales_summary_index'])->name('management.salse.summary');
    Route::post('management/get_sales_summary', [ManagementController::class, 'get_summary_list'])->name('management.salse.summary.list');
    Route::post('mamangement/summary_pdf/generate', [ManagementController::class, 'summary_pdf_generation'])->name('management.summary.pdf');
    Route::get('management/shipping_cash_fee', [ManagementController::class, 'shipping_cash_fee'])->name('management.shipping.fee');
    Route::get('management/maker/index', [ManagementController::class, 'maker_index'])->name('management.maker.index');
    Route::get('management/notification/index', [ManagementController::class, 'notification_index'])->name('management.notification.index');
    Route::post('management/get_notification_list', [ManagementController::class, 'notification_list'])->name('management.notification.list');
    Route::post('management/get_shipping_list', [ManagementController::class, 'get_shipping_list'])->name('management.shipping.list');
    Route::post('management/maker_list', [ManagementController::class, 'get_maker_list'])->name('management.maker.list');
    Route::post('management/register_shipping', [ManagementController::class, 'edit_shipping'])->name('management.shipping.register');
    Route::post('management/delete_shipping', [ManagementController::class, 'delete_shipping'])->name('management.shipping.delete');
    Route::post('management/delete_cash', [ManagementController::class, 'delete_cash'])->name('management.cash.delete');
    Route::post('management/get_daibiki_list', [ManagementController::class, 'get_daibiki_list'])->name('management.daibiki.list');
    Route::post('management/register_daibiki', [ManagementController::class, 'edit_daibiki'])->name('management.daibiki.register');
    Route::post('management/notification_create', [ManagementController::class, 'notification_create'])->name('management.notification.create');
    Route::post('management/notification_delete', [ManagementController::class, 'notification_delete'])->name('management.notification.delete');
    Route::get('management/delivery_carrier/index', [ManagementController::class, 'delivery_carrier_index'])->name('management.delivery_carrier.index');
    Route::post('management/delivery_carrier/ship_list', [ManagementController::class, 'delivery_carrier_ship_list'])->name('management.delivery_carrier.ship.list');
    Route::post('management/delivery_carrier/ship/create', [ManagementController::class, 'delivery_carrier_ship_create'])->name('management.delivery.ship.create');
    Route::post('management/delivery_carrier/ship/delete', [ManagementController::class, 'delivery_ship_delete'])->name('management.delivery.ship.delete');
    Route::post('management/delivery_carrier/get_transport_list', [ManagementController::class, 'delivery_transport_list'])->name('management.delivery.transport.list');
    Route::post('management/delivery_carrier/transport/edit', [ManagementController::class, 'delivery_carrier_transport_edit'])->name('management.delivery.transport.edit');
    Route::post('management/delivery_carrier/transport/delete', [ManagementController::class, 'delivery_carrier_transport_delete'])->name('management.delivery.transport.delete');
    Route::post('management/delivery_carrier/company_address/get_list', [ManagementController::class, 'get_company_address'])->name('management.get_company_address');
    Route::post('management/delivery_carrier/company_address/edit', [ManagementController::class, 'edit_company_address'])->name('management.company_address.edit');
    Route::get('management/inventory/index', [ManagementController::class, 'inventory_index'])->name('management.inventory_index');
    Route::post('management/inventory/list', [ManagementController::class, 'inventory_list'])->name('management.inventory.list');
    Route::post('management/inventory/excel_import', [ManagementController::class, 'inventory_excel_import'])->name('management.inventory.excel_import');
    Route::get('management/sales_representative/index', [ManagementController::class, 'sales_representative_index'])->name('management.sales_representative.index');
    Route::post('management/sales_representative/list', [ManagementController::class, 'sales_representative_list'])->name('management.sales_representative.list');
    Route::post('management/sales_representative/edit', [ManagementController::class, 'sales_representative_edit'])->name('management.sales_representative.edit');
    Route::post('management/sales_representative/delete', [ManagementController::class, 'sales_representative_delete'])->name('management.sales_representative.delete');
    Route::get('management/supplier/index', [ManagementController::class, 'supplier_index'])->name('management.supplier.index');
    Route::post('management/supplier/list', [ManagementController::class, 'supplier_list'])->name('management.supplier.list');
    Route::post('management/supplier/log', [SupplierController::class, 'get_supplier_log'])->name('management.supplier.log');
    Route::get('management/statistics/index', [ManagementController::class, 'statistics_index'])->name('management.statistics.index');
    Route::post('management/statistics/get_statistics', [ManagementController::class, 'get_statistics_periode'])->name('management.get_statistics_periode');
    Route::post('management/main/index', [ManagementController::class, 'management_index'])->name('management.main.index');
    Route::get('management/index/{flag?}', [ManagementController::class, 'index'])->name('management.index');
    Route::get('management/rate/index', [ManagementController::class, 'rate_index'])->name('management.rate.index');

    Route::post('management/table_config/edit', [TableConfigController::class, 'table_config_edit'])->name('management.table_config.edit');
    Route::post('management/template/edit', [ManagementController::class, 'template_edit'])->name('management.template.edit');
    Route::post('management/template/test', [MailsController::class, 'template_test'])->name('management.template.test');

    Route::post('rate/store', [RateController::class, 'store'])->name('rate.store');
    Route::post('rate/log', [RateController::class, 'log'])->name('rate.log');
    Route::post('rate/delete', [RateController::class, 'delete'])->name('rate.delete');
    Route::post('rate/list', [RateController::class, 'get_list'])->name('rate.list');
    Route::post('tax/update', [TaxController::class, 'update'])->name('tax.update');

    Route::resource('message', MessageController::class);
    Route::resource('rfq', RfqController::class);
    Route::resource('order', OrderController::class);
    Route::resource('request_quote_vendor', RequestQuoteVendorController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('maker', MakerController::class);
    Route::resource('address', AddressController::class);
    Route::resource('common', CommonController::class);
    Route::resource('quotation', QuotationController::class);
});

Route::get('/overseas_manufacturer', [AccountController::class, 'overseas_manufacturer_index'])->name('frontend.overseas_manufacturer');
Route::get('/terms', [AccountController::class, 'terms_index'])->name('frontend.terms.index');
Route::get('/parts_mass_production', [AccountController::class, 'parts_mass_production_index'])->name('frontend.parts_mass_production');
Route::get('/search', [AccountController::class, 'search_index'])->name('frontend.search.index');
Route::get('/company_profile', [AccountController::class, 'company_profile_index'])->name('frontend.company_profile');
Route::get('/shipping_cash_on_delivery', [AccountController::class, 'shipping_cash_on_delivery_index'])->name('frontend.shipping_cash_on_delivery');
Route::post('frontend/login', [AccountController::class, 'login'])->name('frontend.login');
Route::post('frontend/member_register', [AccountController::class, 'member_register'])->name('frontend.member_register');
Route::post('frontend/log/update', [AccountController::class, 'customer_log_update'])->name('frontend.log.update');
Route::post('frontend/search', [AccountController::class, 'model_search'])->name('frontend.model_search');
Route::post('/cart/store', [CartController::class, 'store'])->name('frontend.cart.store');
Route::post('/cart/update', [CartController::class, 'update'])->name('frontend.cart.update');
Route::post('/cart/destroy', [CartController::class, 'destroy'])->name('frontend.cart.destroy');
Route::post('/cart/list', [CartController::class, 'list'])->name('frontend.cart.list');

Route::post('frontend/password/reset', [AccountController::class, 'password_reset'])->name('frontend.password.reset');

Route::prefix('frontend')->name('frontend.')->middleware(['across.check'])->group(function () {
    Route::get('/account', [AccountController::class, 'account_index'])->name('account.index');
    Route::post('/purchase', [AccountController::class, 'purchase_index'])->name('purchase.index');

    Route::post('/order_index', [AccountController::class, 'order_index'])->name('order.index');

    Route::post('/purchase/request', [AccountController::class, 'purchase_request'])->name('purchase.request');
    // Route::post('password/reset', [AccountController::class, 'password_reset'])->name('password.reset');
    Route::post('password/change', [AccountController::class, 'password_change'])->name('password.change');

    Route::post('/get_quote_wait', [AccountController::class, 'get_quote_wait_list'])->name('get_quote_wait');
    Route::post('/get_estimate_answer', [AccountController::class, 'get_estimate_answer'])->name('get_estimate_answer');

    Route::post('/rfq_cancel', [AccountController::class, 'cancel_rfq'])->name('rfq.cancel');
    Route::post('/copy_billing_address', [AccountController::class, 'copy_billing_address'])->name('billing_address.copy');
    Route::post('/rfq_create', [AccountController::class, 'create_rfq'])->name('rfq.create');
    Route::post('/re_quote_request', [AccountController::class, 're_quote_request'])->name('re_quote_request');
    Route::post('/quote_customer_delete', [AccountController::class, 'quote_customer_delete'])->name('quote_customer.delete');

    Route::post('/overseas_send_mail', [AccountController::class, 'overseas_send_mail'])->name('overseas.send_mail');
    Route::post('/parts_mass_production_mail', [AccountController::class, 'parts_mass_production_mail'])->name('parts.send_mail');

    Route::post('/shipment/pdf_generation', [AccountController::class, 'ship_pdf_generate'])->name('ship.pdf');
    Route::post('/shipment/list', [AccountController::class, 'shipment_list'])->name('shipment.list');
    Route::post('/get_address_from_post_code', [AccountController::class, 'get_address_from_post_code'])->name('get_address_from_zip');

    Route::resource('customer', CustomerController::class);
});

require __DIR__ . '/auth.php';
