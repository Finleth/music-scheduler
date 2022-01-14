$(function() {
    "strict";

    $('#auto_schedule').on('change', function(event) {
        if (event.target.value === 'no') {
            $('.auto_schedule').hide();
        } else {
            $('.auto_schedule').show();
        }
    });
});
