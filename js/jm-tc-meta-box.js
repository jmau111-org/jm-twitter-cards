// DOM ready
jQuery(document).ready(function ($) {

    /* we do this not the simpliest way but it's readable.
     next time I'll add some checking for fields
     */

    //hide
    function hidePlayer() {
        $('.cmb_id_player_title').hide();
        $('.cmb_id_cardPlayer').hide();
        $('.cmb_id_cardPlayerWidth').hide();
        $('.cmb_id_cardPlayerHeight').hide();
        $('.cmb_id_cardPlayerStream').hide();
    }

    function showPlayer() {
        $('.cmb_id_player_title').show(500);
        $('.cmb_id_cardPlayer').show(500);
        $('.cmb_id_cardPlayerWidth').show(500);
        $('.cmb_id_cardPlayerHeight').show(500);
        $('.cmb_id_cardPlayerStream').show(500);
    }

    function showBoxImg() {
        $('.cmb_id_image_title').show(500);
        $('#twitter_image_size').show(500);
        $('.cmb_id_cardImage').show(500);
    }

    function hideBoxImg() {
        $('.cmb_id_image_title').hide();
        $('#twitter_image_size').hide();
        $('.cmb_id_cardImage').hide();
    }

    hidePlayer();

    //see
    $('#twitterCardType').on('change', function () {

        if (this.value == 'summary') {
            hidePlayer();
            showBoxImg();
            //console.log('summary');
        } else if (this.value == 'summary_large_image') {
            hidePlayer();
            showBoxImg();
            //console.log('summary large image');
        } else if (this.value == 'photo') {
            hidePlayer();
            showBoxImg();
            //console.log('photo');
        } else if (this.value == 'player') {
            showPlayer();
            showBoxImg();
            //console.log('player');
        } else if (this.value == 'application') {
            hidePlayer();
            hideBoxImg();
            //console.log('application');
        } else {
            console.log('oO');
        }

    }).change();
});
		
