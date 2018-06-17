(function ($) {
    /**
     * TODO: rebuild this crap
     * and prepare for Gutenberg \0/
     */

    "use strict";

    /**
     * @type {Element}
     */
    var title = $('#title'),
        cardType = $('#twitterCardType'),
        metaBoxImg = $('#cardImage'),
        postThumb = $('#set-post-thumbnail img');

    /**
     * Sync title
     */
    $(title).keyup(function (evt) {
        evt.preventDefault();
        $('#tc-title').text(this.value);
    });

    /**
     * Sync desc
     * It's a huge pain to get it from WP :/
     */
    window.onload = function () {
        tinymce.get('content').on('keyup', function () {
            $('#tc-desc').text(this.getContent().replace(/(<([^>]+)>)/ig, ""));
        });
    };

    /**
     * Get img from different inputs
     * @returns {*}
     */
    function getImage() {
        if (typeof($(metaBoxImg)) !== 'undefined' && $(metaBoxImg).val() !== '') {
            return $(metaBoxImg).val();
        } else {
            if (typeof($(postThumb)[0]) !== 'undefined' && $(postThumb)[0].currentSrc !== '') {
                return $(postThumb)[0].currentSrc;
            } else {
                return tcStrings.default_image;
            }
        }
    }


    /**
     * Change card preview
     * according to card type
     */
    $(cardType).on('change', function (evt) {

        /**
         * Change container ID and class
         * @type {Node}
         */
        var container = document.getElementById('tc-img-child').parentNode;
        container.id = evt.target.value + '-img-container';
        container.className = 'tc-' + evt.target.value + '-img-container';

        var iCc = document.getElementById(evt.target.value + '-img-container'),
            iChild = document.getElementById('tc-img-child');

        var removeC = function () {
            iCc.removeChild(iChild);
        }, bgimgC = function () {
            iCc.removeAttribute("style");
            iCc.setAttribute("style", "background-size: auto 100%;background-position: center center;background-repeat: no-repeat; background-image: url(" + getImage() + ");");
        }, setIdC = function (obj) {
            obj.id = 'tc-img-child';
        };

        /**
         * Change Markup
         */
        switch (evt.target.value) {
            case 'summary':
                var contentainer = document.getElementById('card-content');
                contentainer.className = "summary-content";
                iCc.removeAttribute("style");
                iCc.parentNode.className = evt.target.value + ' tc-container';
                removeC();
                iCc.setAttribute("style", "background-repeat: no-repeat; background-image: url(" + getImage() + ");");
                var imgSmall = document.createElement('img');
                imgSmall.setAttribute("src", getImage());
                setIdC(imgSmall);
                imgSmall.className = "tc-img summary-img onchange";
                iCc.appendChild(imgSmall);
                break;
            case 'summary_large_image':
                bgimgC();
                iCc.parentNode.className = evt.target.value + ' tc-container';
                removeC();
                var imgLarge = document.createElement('img');
                imgLarge.setAttribute("src", getImage());
                imgLarge.className = evt.target.value;
                setIdC(imgLarge);
                iCc.appendChild(imgLarge);
                break;

            case 'player':
                iCc.removeAttribute("style");
                iCc.className = evt.target.value;
                iCc.parentNode.className = evt.target.value + ' tc-container';
                removeC();
                var video = document.createElement('video');
                video.setAttribute("poster", getImage());
                video.setAttribute("controls", "");
                video.setAttribute("height", '200');
                video.setAttribute("width", "100%");
                setIdC(video);
                iCc.appendChild(video);
                break;
        }

    }).change();

})(jQuery);