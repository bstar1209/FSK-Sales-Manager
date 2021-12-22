<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>販売管理 - @yield('title')</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/spin/jquery.spin.css') }}">
    @yield('custom_css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@v2.3.7/dist/latest/bootstrap-autocomplete.min.js">
    </script>
    <script src="{{ asset('vendor/spin/jquery.spin.js') }}"></script>
    <script>
        var getCardListUrl = "{{ route('frontend.cart.list') }}";
        var trashImg = "{{ asset('images/Trash.png') }}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function generateUUID() {
            var d = new Date().getTime();
            var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = (d + Math.random() * 16) % 16 | 0;
                d = Math.floor(d / 16);
                return (c == 'x' ? r : (r & 0x7 | 0x8)).toString(16);
            });
            return uuid;
        };
    </script>

    @yield('content')

    <script>
        //backend url
        var profileId = {!! json_encode((array) auth()->id()) !!};
        profile = {!! json_encode(auth()->user()) !!};
        var profileInfo = null;
        var getCustomerListUrl = "{{ route('admin.customer.list') }}";
        var getSupplierListUrl = "{{ route('admin.supplier.list') }}";
        var getCommonListUrl = "{{ route('admin.common.list') }}";
        var getMakerListUrl = "{{ route('admin.maker.list') }}";
        var sendQuoteUrl = "{{ route('admin.mail.send_supplier') }}";
        var getCustomerLogUrl = "{{ route('admin.customer.get_log') }}";

        var customerId = null;
        if (localStorage.getItem('uuid') == null) {
            customerId = generateUUID();
            localStorage.setItem('uuid', customerId);
        } else
            customerId = localStorage.getItem('uuid');

        //frontend url
        var customerLoginUrl = "{{ route('frontend.login') }}";
        var customerResetUrl = "{{ route('frontend.password.reset') }}";
        var memberRegistertUrl = "{{ route('frontend.member_register') }}";
        var customerUpdateLogUrl = "{{ route('frontend.log.update') }}";
        var modelSearchUrl = "{{ route('frontend.model_search') }}";
        var searchUrl = "{{ route('frontend.search.index') }}";
        var accountUrl = "{{ route('frontend.account.index') }}";

        var getQuoteWaitUrl = "{{ route('frontend.get_quote_wait') }}";
        var getEstimateAnswerUrl = "{{ route('frontend.get_estimate_answer') }}";

        var createRFQUrl = "{{ route('frontend.rfq.create') }}";
        var createAddressUrl = "{{ route('admin.address.store') }}";
        var createCartUrl = "{{ route('frontend.cart.store') }}";
        var destroyCartUrl = "{{ route('frontend.cart.destroy') }}";
        var updateCardUrl = "{{ route('frontend.cart.update') }}";
        var getCardListUrl = "{{ route('frontend.cart.list') }}";
        var getAddressFromPostCodeUrl = "{{ route('frontend.get_address_from_zip') }}";

        $('.spin').spin();
        $('.spin').spin('hide');
    </script>

    @yield('custom_js')
    {!! Toastr::message() !!}
</body>

</html>
