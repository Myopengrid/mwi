$(document).ready(function() {
    $('#application_keywords').tagit({
        availableTags: keywords,
        singleField: true,
        caseSensitive: false,
        singleFieldNode: $('#application_keywords'),
        allowSpaces: false
    });
});