<div id="quote-wait" class="account-tab d-none">
    <div class="row d-flex justify-content-between mt-2">
        <div class="col-2">
            <div class="input-group input-group-sm mb-1">
                <div class="input-group-prepend">
                    <a class="input-group-text text-truncate quote-search-btn">
                        <img src="{{ asset('images/search.png') }}" alt="" style="width: 15px; padding-top:1px">
                    </a>
                </div>
                <input type="text" class="form-control quote-search" placeholder="型番">
            </div>
        </div>

        <div class="col-2">
            <select class="form-control quote-period">
                <option value="1">過去一ヶ月のデータ</option>
                <option value="3">過去三ヶ月のデータ</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 table-responsive">
            <table class="table table-bordered table-striped table-sm mb-2" id="quote-wait-table" cellspacing="0"
                tabindex="0">
                <thead>
                    <tr>
                        <th>依頼日</th>
                        <th>受付番号</th>
                        <th>型番</th>
                        <th>メーカー</th>
                        <th>DC</th>
                        <th>地域</th>
                        <th>希望数</th>
                        <th>希望単価</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
