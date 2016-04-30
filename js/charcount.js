(function ($) {
    var textArea = $('textarea');
    textArea.after('<div id="tc-charcount"></div>');
    $(textArea).keyup(function () {
        var max = 200,len = $(this).val().length, div = $('#tc-charcount');
        if (len >= max) {
            div.text(max - len );
            div.css('color', 'red');
        } else {
            div.text(max - len + ' ' + tcStrings.charcount_message);
        }
    });
})(jQuery);