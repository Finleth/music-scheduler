$(function() {
    "strict";

    const select2Options = {
        placeholder: 'Select a batch',
        ...defaultSelect2Options // from select2.js
    }

    $('#batch-selector').select2(select2Options);

    $('#batch-selector').on('change', function(event) {
        const eventCount = event.target.selectedOptions[0].getAttribute('data-event-count');
        $('#batch-event-count').text(eventCount);
    });
});
