jQuery(document).ready(function (e) {

    var tc_uploader;

    //Extend the wp.media object
    tc_uploader = wp.media.frames.file_frame = wp.media({
        multiple: false
    });

    jQuery('.tc-file-input-select').click(function (e) {

        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (tc_uploader) {
            tc_uploader.open();
            return;
        }

        //Extend the wp.media object
        tc_uploader = wp.media.frames.file_frame = wp.media({
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        tc_uploader.on('select', function () {
            attachment = tc_uploader.state().get('selection').first().toJSON();
            jQuery('.tc-file-input').val(attachment.url);
        });

        //Open the uploader dialog
        tc_uploader.open();

    });

    function a() {
        e('*[class^="cardPlayer"]').hide()
    }

    function r() {
        e('*[class^="cardPlayer"]').show()
    }

    e("#twitterCardType").on("change", function () {
        "player" == this.value ? r() : a()
    }).change()
});