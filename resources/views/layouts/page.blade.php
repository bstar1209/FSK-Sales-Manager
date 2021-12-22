@extends('layouts.app')

@section('custom_css')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @yield('custom_style')
@endsection

@section('content')
    @include('layouts/header')

    <div id="content-wrapper" class="pb-5 mb-5">
        <div class="container-fluid">
        @section('main-container')
            @yield('header-container')
            @yield('table-container')
            @yield('other-container')
        @show
    </div>
</div>
@include('layouts/footer')
@endsection

@section('custom_js')
<script src="{{ asset('js/admin/custom.js') }}"></script>
<script src="{{ asset('js/admin/admin_shortkey.js') }}"></script>
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/utils.js') }}"></script>
<script>
    var manageIndexUrl = "{{ route('admin.management.main.index') }}";
    var getRfqListUrl = "{{ route('admin.rfq.get_list.unRfq') }}";
    var getQuoteFromSupplierUrl = "{{ route('admin.rfq.get_list.request_quote_vendor') }}";
    var getQuoteFromSupplierMoreUrl = "{{ route('admin.rfq.get_request_quote_vendor_more_data') }}";
    var getHistoryUrl = "{{ route('admin.rfq.get_list.history') }}";
    var getHistoryMoreUrl = "{{ route('admin.rfq.get_history_more_data') }}";
    var getPaymentUrl = "{{ route('admin.common.payment.list') }}";
    var getCustomerLog = "{{ route('admin.customer.get_log') }}";
    var getQuoteListUrl = "{{ route('admin.quotation.get_list') }}";
    var getRateListUrl = "{{ route('admin.rate.list') }}";
    var getShipAndTransportlistUrl = "{{ route('admin.ship_transport.list') }}";
    var getOrderListUrl = "{{ route('admin.order.get_order_list') }}";
    var getShipOrderListUrl = "{{ route('admin.ship_order.get_list') }}";
    var getQuoteCustomerHistoryUrl = "{{ route('admin.quotation.get_list.history') }}";
    var getQuoteCustomerHistoryMoreUrl = "{{ route('admin.quotation.get_history_more_data') }}";
    var getStockList = "{{ route('admin.stock.get_list') }}";
    var getShipmentListUrl = "{{ route('admin.shipment.get_list') }}";
    var autoShipOrderUrl = "{{ route('admin.ship_order.update') }}";
    var autoStockOrderUrl = "{{ route('admin.stock.update') }}";
    var autoShipmentOrderUrl = "{{ route('admin.shipment.update') }}";
</script>
@yield('custom_script')
@endsection
