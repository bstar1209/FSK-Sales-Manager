@extends('layouts.frontend.page')

@section('title', '量産向け部品調達依頼')

@section('main-container')
    <div class="page-content mt-5" style="padding: 10px 10px 20px">
        <div class="row">
            <div class="col-12">
                <h4 class="page-title text-primary">@lang('Parts procurement request for mass production')</h4>
                <hr>
            </div>
        </div>
        <div class="page-detail">
            <div class="row">
                <div class="col-8">
                    <p class="page-highlight" style="font-weight: 300">
                        国内外の代理店より調達することによってお客様のメリットが最大になるようなご提案をさせていただきます. <br>
                        下記フォームからお問い合わせください. <br>
                        現段階で情報開示ができない項目は 開示不可 と記入してください.<br>
                        会員登録がお済みの方はログインしてください.<br>
                        会員登録がまだお済み出ない方は先に会員登録を行ってログインしてください.<br>
                    </p>
                </div>
            </div>
            <div id="overseas_manufacturer" class="row">
                <div class="col-6">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">プロジェクト名(*)</span>
                        </div>
                        <input type="text" class="form-control project_name">
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">エンドユーザー名(*)</span>
                        </div>
                        <input type="text" class="form-control end_username">
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">使用用途(*)</span>
                        </div>
                        <input type="text" class="form-control use_applications">
                    </div>
                    <div class="input-group input-group-sm mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">年間使用数(*)</span>
                        </div>
                        <input type="text" class="form-control annual_usage">
                    </div>
                    <div class="input-group input-group-sm mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">量産開始時期(*)</span>
                        </div>
                        <input type="text" class="form-control start_time">
                    </div>
                    <div class="input-group input-group-sm mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">お問い合わせ内容(*)</span>
                        </div>
                    </div>
                    <textarea class="form-control mt-2 inquiry_content" style="width:100%; font-size: 18px !important"
                        rows="5"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-6 d-flex align-items-end flex-column">
                    <a href="{{ route('frontend.terms.index') }}" class="mt-2">@lang('The terms are here')</a>
                    <button type="button" class="btn btn-primary btn-sm parts_mass_production_btn mt-1" title = "会員登録してログインしてください。" @guest disabled
                        @endguest>@lang('Agree to the terms and send an inquiry')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(function() {
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

            $('.parts_mass_production_btn').click(function() {
                $('.is-invalid').removeClass('is-invalid');
                var projectName = $('.project_name').val();
                var endUsername = $('.end_username').val();
                var useApp = $('.use_applications').val();
                var annUsage = $('.annual_usage').val();
                var startTime = $('.start_time').val();
                var inqueryContent = $('.inquiry_content').val();
                var validationFlag = false;

                if (projectName == '' || !projectName) {
                    $('.project_name').addClass('is-invalid');
                    toastr.warning('プロジェクト名が入力されておりません。');
                    return;
                }

                if (endUsername == '' || !endUsername) {
                    $('.end_username').addClass('is-invalid');
                    toastr.warning('エンドユーザー名が入力されておりません。');
                    return;
                }

                if (useApp == '' || !useApp) {
                    $('.use_applications').addClass('is-invalid');
                    toastr.warning('使用用途が入力されておりません。');
                    return;
                }

                if (annUsage == '' || !annUsage) {
                    $('.annual_usage').addClass('is-invalid');
                    toastr.warning('年間使用数が入力されておりません。');
                    return;
                }

                if (startTime == '' || !startTime) {
                    $('.start_time').addClass('is-invalid');
                    toastr.warning('量産開始時期が入力されておりません。');
                    return;
                }

                if (inqueryContent == '' || !inqueryContent) {
                    $('.inquiry_content').val();
                    toastr.warning('お問い合わせ内容が入力されておりません。');
                    return;
                }

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
                    }
                });
            });
        });
    </script>
@endsection
