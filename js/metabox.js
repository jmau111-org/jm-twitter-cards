jQuery(document).ready(function ($) {

    function hidePlayer() {
        $('#cardPlayer').parents(':eq(1)').hide();
        $('#cardPlayerWidth').parents(':eq(1)').hide();
        $('#cardPlayerHeight').parents(':eq(1)').hide();
        $('#cardPlayerStream').parents(':eq(1)').hide();
        $('#cardPlayerCodec').parents(':eq(1)').hide();
    }

    function showPlayer() {
        $('#cardPlayer').parents(':eq(1)').show();
        $('#cardPlayerWidth').parents(':eq(1)').show();
        $('#cardPlayerHeight').parents(':eq(1)').show();
        $('#cardPlayerStream').parents(':eq(1)').show();
        $('#cardPlayerCodec').parents(':eq(1)').show();
    }

    $('#twitterCardType').on('change', function () {
        if (this.value == 'player') {
            showPlayer();
        } else {
            hidePlayer();
        }
    }).change();
});