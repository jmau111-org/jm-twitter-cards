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
        console.log(evt);

        var tcTitle = document.getElementById('tc-title');
        tcTitle.textContent = evt.target.value;

    }, false);
}

/**
 * Sync desc
 */
if (typeof tinymce !== 'undefined') {

    tinymce.on('SetupEditor', function (editor) {
        if (editor.id === 'contentLeft') {
            editor.on('keyup', function (evt) {
                var tcDesc = document.getElementById('tc-desc');
                tcDesc.textContent = evt.target.value;
            });
        }
    });
}

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

        /**
         * Change Markup
         */
        switch (evt.target.value) {
            case 'summary':
                iCc.setAttribute("style", "background-image: url(" + getImage() + ");");
                iCc.parentNode.className = 'tc-summary tc-container';
                iCc.removeChild(iChild);
                var imgSmall = document.createElement('img');
                imgSmall.setAttribute("src", getImage());
                imgSmall.id = 'tc-img-child';
                imgSmall.className = "tc-img summary-img onchange";
                iCc.appendChild(imgSmall);
                break;

            case 'summary_large_image':
                iCc.removeAttribute("style");
                iCc.parentNode.className = 'summary_large_image tc-container';
                iCc.removeChild(iChild);
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
                iCc.removeChild(iChild);
                var video = document.createElement('video');
                video.setAttribute("poster", getImage());
                video.setAttribute("controls", "");
                video.id = 'tc-img-child';
                iCc.appendChild(video);
                break;
        }

    }, false);
}
