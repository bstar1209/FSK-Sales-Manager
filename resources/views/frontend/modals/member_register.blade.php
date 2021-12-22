<div class="modal fade" id="member-register-modal" tabindex="-1" role="dialog"
    aria-labelledby="MemberRegisterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 500px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="MemberRegisterModalLabel" class="modal-title text-warning">
                    会員登録
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">会社名*</span>
                            </div>
                            <input type="text" class="form-control company-name">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">お名前*</span>
                            </div>
                            <input type="text" class="form-control name">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">eメール*</span>
                            </div>
                            <input type="text" class="form-control email">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">eメールの確認*</span>
                            </div>
                            <input type="text" class="form-control email-confirm">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">パスワード*</span>
                            </div>
                            <input type="password" class="form-control password">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">パスワードの確認*</span>
                            </div>
                            <input type="password" class="form-control confirm-password">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">納品先郵便番号*</span>
                            </div>
                            <input type="text" class="form-control zip">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">規約</span>
                            </div>
                        </div>
                        <div class="form-control mt-2 page-detail"
                            style="width:100%; overflow-y:scroll; height: 200px !important">
                            @include('frontend.partials.terms_partial')
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-check-block float-right mt-2">
                            <input type="checkbox" class="form-check-input" id="agree-to-terms"
                                name="inlineMaterialRadiosExample" style="margin-top: 7px">
                            <label class="form-check-label" for="agree-to-terms" style="margin-top: 3px">
                                @lang('Agree to Terms')</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" id="member-register-btn" class="btn btn-primary btn-sm" disabled>
                    @lang('Agree to the terms and register as a member')</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">@lang('Cancel')</button>
            </div>
        </div>
    </div>
</div>
