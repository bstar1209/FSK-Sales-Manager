$(document).keydown(function (e) {
    // $('.datepicker.datepicker-dropdown.dropdown-menu').remove();
    switch (e.keyCode) {
        case 13: //enter
            var id = $("*:focus").attr('id');
            if (id == "model-number-search" && !$('#search-btn').prop('disabled')) {
                $('#search-btn').click();
            }
            break;
        case 8: //backspace
            var id = $("*:focus").attr('id');
            if (id == "model-number-search") {
                var search = $("#model-number-search").val();
                if (search.length > 3)
                    $('#search-btn').prop('disabled', false);
                else
                    $('#search-btn').prop('disabled', true);
            }
    }
    return;
})
