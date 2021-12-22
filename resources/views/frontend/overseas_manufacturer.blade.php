@extends('layouts.frontend.page')

@section('title', '海外メーカー品調達依頼')

@section('header-container')
@endsection

@section('main-container')
    <div class="page-content mt-5" style="padding: 10px 10px 20px">
        <div class="row">
            <div class="col-12">
                <h4 class="page-title text-primary">海外メーカー品調達依頼</h4>
                <hr>
            </div>
        </div>
        <div class="page-detail">
            <div class="row">
                <div class="col-8">
                    <p class="page-highlight" style="font-weight: 300">
                        日本に代理店がない、購入ルートが分からないような海外メーカー品の調達も弊社では得意としております.<br>
                        下記フォームからお問い合わせください.<br>
                        会員登録がお済みの方はログインしてください.<br>
                        会員登録がまだお済み出ない方は先に会員登録を行ってログインしてください.<br>
                    </p>
                </div>
            </div>
            <div id="overseas_manufacturer" class="row">
                <div class="col-6">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">お問い合わせメーカー名(*)</span>
                        </div>
                        <input type="email" class="form-control email_title" name="email_title">
                    </div>
                    <div class="input-group input-group-sm mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-truncate">お問い合わせ内容：必須項目(*)</span>
                        </div>
                    </div>
                    <textarea class="form-control mt-2 email_content" name="email_content"
                        style="width:100%; font-size: 18px !important" rows="5"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-6 d-flex align-items-end flex-column">
                    <a href="{{ route('frontend.terms.index') }}" class="mt-2">@lang('The terms are here')</a>
                    <button type="button" class="btn btn-primary btn-sm overseas_send_mail_btn mt-1" title = "会員登録してログインしてください。" @guest disabled
                        @endguest>@lang('Agree to the terms and send an inquiry')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(function() {

            $('.overseas_send_mail_btn').click(function() {
                var email = $('.email_title').val();
                var content = $('.email_content').val();

                if (!email || email == '' || email == undefined) {
                    toastr.warning('お問い合わせメーカー名が入力されておりません。')
                    return;
                }

                if (!content || content == '' || content == undefined) {
                    toastr.warning('お問い合わせ内容が入力されておりません。')
                    return;
                }

                $.ajax({
                    url: "{{ route('frontend.overseas.send_mail') }}",
                    method: 'POST',
                    data: {
                        email: $('.email_title').val(),
                        content: $('.email_content').val()
                    },
                    success: function(data) {
                        toastr.success('お問い合わせを送信しました。');
                    }
                });
            });

            // $('.overseas_send_mail_btn').hover(function() {
            //     toastr.warning('会員登録してログインしてください。');
            //     return;
            // })
            // $(document).on('hover', '.overseas_send_mail_btn:disabled', function() {
            //     toastr.warning('会員登録してログインしてください。');
            //     return;
            // })
        });
    </script>
@endsection
