(function ($) {

    $('.tc-file-input-select').click(function(e) {
        e.preventDefault();
        var image = wp.media({
            title: tcStrings.upload_message,
            multiple: false
        }).open()
            .on('select', function(e){
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $('.tc-file-input').val(image_url);
            });
    });

    $('.tc-file-input-reset').click(function(e) {
        e.preventDefault();
        $('.tc-file-input').val('');
    });

    function a() {
        $('*[class^="cardPlayer"]').hide()
    }

    function r() {
        $('*[class^="cardPlayer"]').show()
    }

    $("#twitterCardType").on("change", function () {
        "player" == this.value ? r() : a()
    }).change()
})(jQuery);