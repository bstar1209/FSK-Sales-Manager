<div class="modal fade" id="notification-edit-modal" tabindex="-1" role="dialog" aria-labelledby="notificationEditModalLabel"
aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 600px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="notificationEditModalLabel" class="modal-title text-warning">お知らせの編集</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="send-to-supplier-form" class="col-12">
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang('Title')</span>
                            </div>
                            <input type="text" class="form-control notification_title" name="notification_title">
                        </div>
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang('Notiication Content')</span>
                            </div>
                        </div>
                        <textarea class="form-control mt-2 notification_content" name="notification_content" style="width:100%; font-size: 18px !important" rows="10"></textarea>
                        <div class="input-group input-group-sm mt-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang('Publication start date')</span>
                            </div>
                            <input type="text" class="form-control post_start_date" name="post_start_date">
                        </div>
                        <div class="input-group input-group-sm mt-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-truncate">@lang('Posting end date')</span>
                            </div>
                            <input type="text" class="form-control post_end_date" name="post_end_date">
                        </div>
                    </div>
                    <div class="col-4">
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary btn-sm notification-edit-btn">送信</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</div>
