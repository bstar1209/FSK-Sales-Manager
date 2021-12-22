<table id="carts-list-table" class="table table-bordered table-striped table-sm mt-1">
    <thead>
        <tr>
            <th>型番</th>
            <th>希望数</th>
            <th>削除</th>
        </tr>
    </thead>
    <tbody class="carts-list-tbody">
    </tbody>
</table>

<script>
    $.ajax({
        url: getCardListUrl,
        type: 'post',
        dataType: 'json',
        data: {
            uuid: localStorage.getItem('uuid'),
        },
        success: function(data) {
            $('.carts-list-tbody').empty();
            if (data.length == 0) {
                $('.quote-request-btn').prop('disabled', true);
                return;
            }
            $.each(data, function(index, elem) {
                $('.carts-list-tbody').append(`<tr data-rowinfo='` + JSON.stringify(elem) +
                    `' class="` + elem.part.katashiki + `">
                    <td>` + elem.part.katashiki + `</td>
                    <td><input class="form-control form-control-sm part-qty-input" value="` + elem.qty + `"></td>
                    <td><a class="delete-cart" href="#"><img src="` + trashImg + `"></a></td>
                </tr>`);
            })
        }
    });
</script>
