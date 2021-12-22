@extends('layouts.frontend.page')

@section('title', '利用規約')

@section('main-container')
    <div class="page-content mt-5" style="padding: 10px 10px 20px">
        <div class="row">
            <div class="col-12">
                <h4 class="page-title text-primary">規約</h4>
                <hr>
            </div>
        </div>
        <div class="page-detail" style="font-size: 16px;">
            <div class="row">
                <div class="col-8">
                    @include('frontend.partials.terms_partial')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/adapters/jquery.js') }}"></script>
    <script>
        $(function() {
            $('.inquiry_content').ckeditor();
            // $('#cke_1_top').addClass('d-none');

            $('.start_time').datepicker({
                format: 'yyyy-mm-dd',
                inline: false,
            }).keydown(function(event) {
                var code = event.keyCode || event.which;
                // If key is not TAB
                var parts = $(this).val().split("/"),
                    currentDate = new Date(parts[2], parts[0] - 1, parts[1]);
                switch (code) {
                    case 27:
                        $(this).datepicker('hide');
                        return false;
                        break;
                    case 113:
                        $(this).datepicker('show');
                        return false;
                        break;
                    case 37:
                        event.preventDefault();
                        event.stopPropagation();
                        currentDate.setDate(currentDate.getDate() - 1);
                        break;
                    case 38:
                        event.preventDefault();
                        event.stopPropagation();
                        currentDate.setDate(currentDate.getDate() - 7);
                        break;
                    case 39:
                        event.preventDefault();
                        event.stopPropagation();
                        currentDate.setDate(currentDate.getDate() + 1);
                        break;
                    case 40:
                        event.preventDefault();
                        event.stopPropagation();
                        currentDate.setDate(currentDate.getDate() + 7);
                        break;
                };
            });

            $(document).on('click', '.parts_mass_production_btn', function() {
                var projectName = $('.project_name').val();
                var endUsername = $('.end_username').val();
                var useApp = $('.use_applications').val();
                var annUsage = $('.annual_usage').val();
                var startTime = $('.start_time').val();
                var inqueryContent = $('.inquiry_content').val();

                $.ajax({
                    url: "{{ route('frontend.parts.send_mail') }}",
                    method: 'POST',
                    data: {
                        projectName: projectName,
                        endUsername: endUsername,
                        useApp: useApp,
                        annUsage: annUsage,
                        startTime: startTime,
                        inqueryContent: inqueryContent,
                    },
                    success: function(data) {
                        toastr.success('お問い合わせを送信しました。');
                    }
                });
            });
        });
    </script>
@endsection
