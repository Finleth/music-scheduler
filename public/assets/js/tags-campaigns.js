$(function () {
    'use strict';
    $('#qualifiers').tagsInput({
        'width': '100%',
        'height': '75%',
        'interactive': true,
        'delimiter': ['|'],
        'defaultText': 'Add More',
        'removeWithBackspace': true,
        'minChars': 0,
        'maxChars': 255,
        'placeholderColor': '#666666',
    });
});
