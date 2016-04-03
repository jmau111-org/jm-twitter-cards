'use strict';

var title = document.getElementById('title'),
    description = document.getElementById('title'),
    cardType = document.getElementById('twitterCardType'),
    postThumb = document.querySelectorAll('.attachment-post-thumbnail'),
    metaBoxImg = document.getElementById('cardImage');

/**
 * Sync title
 */
if (typeof( title.addEventListener ) !== 'undefined') {
    title.addEventListener('keyup', function (evt) {

        var tcTitle = document.getElementById('tc-title');
        tcTitle.textContent = evt.target.value;

    }, false);
}

/**
 * Sync desc
 * It's a huge pain to get it from WP :/
 */
window.onload = function () {
    tinymce.get('content').on('keyup',function(e){
        var tcDesc = document.getElementById('tc-desc');
        tcDesc.textContent = this.getContent().replace(/(<([^>]+)>)/ig,"");
    });
};

var getImage = function () {
    return typeof(metaBoxImg) !== 'undefined' ? metaBoxImg.value : postThumb[0].currentSrc;
};

/**
 * Change card preview
 * according to card type
 */
if (typeof( cardType.addEventListener ) !== 'undefined') {

    cardType.addEventListener('change', function (evt) {

        /**
         * Change container ID and class
         * @type {Node}
         */
        var container = document.getElementById('tc-img-child').parentNode;
        container.id = evt.target.value + '-img-container';
        container.className = 'tc-' + evt.target.value + '-img-container';

        var iCc = document.getElementById(evt.target.value + '-img-container'),
            iChild = document.getElementById('tc-img-child');

        var removeC = function(){
            iCc.removeChild(iChild);
        };

        /**
         * Change Markup
         */
        switch (evt.target.value) {
            case 'summary':
                iCc.setAttribute("style", "background-image: url(" + getImage() + ");");
                iCc.parentNode.className = 'tc-summary tc-container';
                removeC();
                var imgSmall = document.createElement('img');
                imgSmall.setAttribute("src", getImage());
                imgSmall.id = 'tc-img-child';
                imgSmall.className = "tc-img summary-img onchange";
                iCc.appendChild(imgSmall);
                break;

            case 'summary_large_image':
                iCc.removeAttribute("style");
                iCc.parentNode.className = 'summary_large_image tc-container';
                removeC();
                var imgLarge = document.createElement('img');
                imgLarge.setAttribute("src", getImage());
                imgLarge.className = "summary_large_image";
                imgLarge.id = 'tc-img-child';
                iCc.appendChild(imgLarge);
                break;

            case 'player':
                iCc.removeAttribute("style");
                iCc.className = "player";
                iCc.parentNode.className = 'player tc-container';
                removeC();
                var video = document.createElement('video');
                video.setAttribute("poster", getImage());
                video.setAttribute("controls", "");
                video.id = 'tc-img-child';
                iCc.appendChild(video);
                break;
        }

    }, false);
}
