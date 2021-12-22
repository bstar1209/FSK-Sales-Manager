<div id="estimate-answer" class="account-tab d-none">
    <div class="row d-flex justify-content-between mt-2">
        <div class="col-2">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <a class="input-group-text text-truncate estimate-answer-search-btn">
                        <img src="{{ asset('images/search.png') }}" alt="" style="width: 15px; padding-top:1px">
                    </a>
                </div>
                <input type="text" class="form-control estimate-answer-search" placeholder="型番">
            </div>
        </div>

        <div class="col-2">
            <select class="form-control estimate-answer-period">
                <option value="1">過去一ヶ月のデータ</option>
                <option value="3">過去三ヶ月のデータ</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 table-responsive">
            <table class="table table-bordered table-sm mb-2" id="estimate-answer-table" cellspacing="0" tabindex="0">
                <thead>
                    <tr>
                        <th style="min-width:15px !important"></th>
                        <th style="min-width:80px !important">見積日</th>
                        <th style="min-width:80px !important">見積番号</th>
                        <th style="min-width:80px !important">型番</th>
                        <!-- <th style="min-width:80px !important">メーカー</th>
                        <th style="min-width:80px !important">DC</th>
                        <th style="min-width:80px !important">地域</th> -->
                        <th style="min-width:80px !important">見積数</th>
                        <th style="min-width:80px !important">見積単位</th>
                        <th style="min-width:80px !important">単価</th>
                        <!-- <th style="min-width:80px !important">めやす納期</th>
                        <th style="min-width:120px !important">Rohs ステータス</th> -->
                        <th style="min-width:320px !important"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
