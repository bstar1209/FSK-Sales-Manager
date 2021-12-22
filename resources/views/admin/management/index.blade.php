<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>販売管理 - @yield('title')</title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
        <link rel="stylesheet" href="//unpkg.com/bootstrap-select@1.12.4/dist/css/bootstrap-select.min.css" type="text/css" />
        <link rel="stylesheet" href="//unpkg.com/bootstrap-select-country@4.0.0/dist/css/bootstrap-select-country.min.css" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/spin/jquery.spin.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
        <link rel="stylesheet" href="{{ asset('css/flexigrid.pack.css') }}">
        <style>
            .dataTables_scrollHead {
                width: 100% !important;
            }
        </style>
        <script>
            var profileId = {!! json_encode((array)auth()->id()) !!};
            profile = {!! json_encode(auth()->user()) !!};
            var profileInfo = null;
            var getCustomerListUrl =  "{{ route('admin.customer.list') }}";
            var getSupplierListUrl =  "{{ route('admin.supplier.list') }}";
            var getCommonListUrl =  "{{ route('admin.common.list') }}";
            var getMakerListUrl = "{{ route('admin.maker.list') }}";
            var sendQuoteUrl = "{{ route('admin.mail.send_supplier') }}";
            var getCustomerLogUrl = "{{ route('admin.customer.get_log') }}";
            var manageIndexUrl = "{{ route('admin.management.main.index') }}";
            var getRfqListUrl = "{{ route('admin.rfq.get_list.unRfq') }}";
            var getQuoteFromSupplierUrl = "{{ route('admin.rfq.get_list.request_quote_vendor') }}";
            var getHistoryUrl = "{{ route('admin.rfq.get_list.history') }}";
            var getPaymentUrl = "{{ route('admin.common.payment.list') }}";
            var getCustomerLog = "{{ route('admin.customer.get_log') }}";
            var getQuoteListUrl = "{{ route('admin.quotation.get_list') }}";
            var getRateListUrl = "{{ route('admin.rate.list') }}";
            var getShipAndTransportlistUrl = "{{ route('admin.ship_transport.list') }}";
            var getOrderListUrl = "{{ route('admin.order.get_order_list') }}";
            var getShipOrderListUrl = "{{ route('admin.ship_order.get_list') }}";
            var getQuoteCustomerHistroyUrl = "{{ route('admin.quotation.get_list.history') }}";
            var getStockList = "{{ route('admin.stock.get_list') }}";
            var getShipmentListUrl = "{{ route('admin.shipment.get_list') }}";
            var autoShipOrderUrl = "{{ route('admin.ship_order.update') }}";
            var autoStockOrderUrl = "{{ route('admin.stock.update') }}";
            var autoShipmentOrderUrl = "{{ route('admin.shipment.update') }}";
            var managementSalseSummaryListUrl = "{{ route('admin.management.salse.summary.list') }}";
            var tableConfigEditUrl = "{{ route('admin.management.table_config.edit') }}";
            var getAddressFromPostCodeUrl = "{{ route('frontend.get_address_from_zip') }}";
        </script>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@v2.3.7/dist/latest/bootstrap-autocomplete.min.js"></script>
        <script src="//unpkg.com/bootstrap-select@1.12.4/dist/js/bootstrap-select.min.js"></script>
        <script src="//unpkg.com/bootstrap-select-country@4.0.0/dist/js/bootstrap-select-country.min.js"></script>
        <script src="{{ asset('vendor/spin/jquery.spin.js') }}"></script>
        <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ asset('vendor/ckeditor/adapters/jquery.js') }}"></script>
        <script src="{{ asset('vendor/chart.js') }}"></script>
        <script src="{{ asset('js/flexigrid.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/gh/jeffreydwalter/ColReorderWithResize@9ce30c640e394282c9e0df5787d54e5887bc8ecc/ColReorderWithResize.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
    </head>
    <body>
        @include('layouts/header')
        <div id="content-wrapper" class="pb-5 mb-5">
            <div class="container-fluid">
                <div class="row">
                    @include('admin.management.partials.tabs')
                </div>
                <hr class="mt-4 mb-5"/>
                <div id="main-container">
                </div>
            </div>
        </div>
        @include('layouts/footer')
        @include('admin/management/modals/update_rate')
        @include('admin/management/modals/delete_rate')
        @include('admin/management/modals/template_edit')
        @include('admin/management/modals/shipping_edit')
        @include('admin/management/modals/cash_on_delivery_edit')
        @include('admin/modals/register_manufacturer')
        @include('admin/management/modals/delivery_edit')
        @include('admin/management/modals/transport_edit')
        @include('admin/management/modals/headerquarter_edit')
        @include('admin/management/modals/delivery_edit')
        @include('admin/modals/register_supplier')
        @include('admin/modals/add_new_payment_term')
        @include('admin/management/modals/notification_edit')
        @include("admin.modals.confirm_message_manager")
        @include('admin/management/modals/sales_representative_edit')
        @include('admin/modals/update_customer_info')
        @include('admin/rfq/modals/billing_address')
    </body>
    <script src="{{ asset('js/admin/custom.js') }}"></script>
    <script src="{{ asset('js/admin/admin_shortkey.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script>
        $(function() {
            function ajaxCallFunction(renderType) {
                $('#main-container').slideUp();
                $.ajax({
                    url: manageIndexUrl,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        type: renderType,
                    },
                    success: function(data) {
                        $('#main-container').html(data);
                        $('#main-container').slideDown();
                    }
                });
            }

            $(document).on('click', '.m-sub', function() {
                $('.m-sub.text-warning').removeClass('text-warning').addClass('text-primary');
                ajaxCallFunction($(this).data('type'));
                $(this).addClass('text-warning').removeClass('text-primary');
            });
            
            if ('{{ $customer_flag }}' == 1)
                ajaxCallFunction('customer');
            else
                ajaxCallFunction('rate');
        })

    </script>
</html>
