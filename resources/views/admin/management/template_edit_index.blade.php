<div class="row mt-5">
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="font-weight-bold text-warning float-left">メール管理</h6>
        </div>
        <table class="table table-bordered table-striped" cellspacing="0" id="template-table">
            <thead>
                <tr>
                    <th>メール</th>
                    <th>更新日</th>
                    <th colspan="2">@lang('Management')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($templates as $template)
                <tr>
                    <td>{{ $template->template_name }}</td>
                    <td>{{ date_create($template->updated_at)->format('Y-m-d') }}</td>
                    <td class="d-flex justify-content-center">
                        <button class="btn btn-sm btn-primary update-template-btn mr-2" data-template="{{ $template }}" data-toggle="modal" data-target="#edit-template-modal">@lang('Edit')</button>
                        <button class="btn btn-sm btn-success send-mail-btn ml-2" data-id="{{ $template->id }}">テストメール送信</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<script>
// commonList = {};
$(function() {
    $('#template-content').ckeditor();

    $(document).on('click', '#template-params option', function() {
        var ckObj = CKEDITOR.instances['template-content'];
        var range = ckObj.getSelection().getRanges()[0];
        if (range) {
            var cursorPos = range.startOffset;
            currContent = range.startContainer;
            var updatedString = currContent.substring(0, cursorPos)+' '+$(this).text()+''+currContent.substring(cursorPos, currContent.getLength()); //in current row, updating content by string type.
            currContent.setText(updatedString); //insert option's value into content
            ckObj.focus();
            range.select();
        }
    })

    $("#edit-template-modal").on('show.bs.modal', function(e) {
        $('#cke_1_top').remove();
        $('#template-table').find('tr.selected').removeClass('selected');
        $(e.relatedTarget).parents('tr').addClass('selected');

        var dataTarget = $(e.relatedTarget).data('template');
        $('#template-name').val(dataTarget.template_name);
        $('#template-content').val(JSON.parse(dataTarget.template_content))
        var params = JSON.parse(dataTarget.template_params);
        var paramOptions = '';
        $.each(params, function(index, item) {
            paramOptions += '<option value="'+index+'">'+item+'</option>';
        })
        $('#template-params').html(paramOptions);
        $('#edit-template-btn').data('id', dataTarget.id);
    });

    $('#edit-template-btn').click(function() {
        $.ajax({
            url: "{{ route('admin.management.template.edit') }}",
            method: 'POST',
            data: {
                id: $(this).data('id'),
                templateName: $('#template-name').val(),
                templateContent: JSON.stringify($('#template-content').val()),
            },
            success: function(result) {
                var jsonData = JSON.parse(result);
                var selectedRow = $('#template-table').find('tr.selected');
                selectedRow.find('td:eq(0)').text(jsonData.template_name);
                selectedRow.find('td:eq(1)').text(changeDateFormat(new Date(jsonData.updated_at)));
                selectedRow.find('.update-template-btn').data('template', jsonData).removeClass('selected');
                $("#edit-template-modal").modal('hide');
            }
        });
    })

    $(document).on('click', '.send-mail-btn', function() {
        $.ajax({
            url: "{{ route('admin.management.template.test') }}",
            method: 'POST',
            data: {
                id: $(this).data('id'),
            },
            success: function(result) {
                toastr.success('テストメールが伝送されました。');
            }
        });
    })
});
</script>
