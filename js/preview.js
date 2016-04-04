'use strict';

/**
 * All these stuffs to not use jQuery
 * which is already loaded by WP
 * this is really lame !
 */

/**
 * @type {Element}
 */

var title = document.getElementById('title'),
    description = document.getElementById('title'),
    cardType = document.getElementById('twitterCardType'),
    postThumb = document.querySelectorAll('.attachment-post-thumbnail'),
    metaBoxImg = document.getElementById('cardImage');

/**
 * Handle backward compat for stupid IE browsers
 * @source: http://stackoverflow.com/a/25674763/1930236
 */
if (typeof Element.prototype.addEventListener === 'undefined') {
    Element.prototype.addEventListener = function (e, callback) {
        e = 'on' + e;
        return this.attachEvent(e, callback);
    };
}

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
   if( typeof(metaBoxImg) !== 'undefined' && metaBoxImg.value.length !== 0) {
       return metaBoxImg.value;
    } else {
       if (typeof(postThumb[0]) !== 'undefined' && postThumb[0].currentSrc.length !== 0) {
           return postThumb[0].currentSrc;
       } else {
           return 'https://g.twimg.com/Twitter_logo_blue.png';
       }
   }
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
        }, bgimgC = function(){
            iCc.removeAttribute("style");
            iCc.setAttribute("style", "-webkit-background-size: cover;background-size: cover; background-image: url(" + getImage() + ");");
        }, setIdC = function(obj){
            obj.id = 'tc-img-child';
        };

        /**
         * Change Markup
         */
        switch (evt.target.value) {
            case 'summary':
                bgimgC();
                iCc.parentNode.className = 'tc-summary tc-container';
                removeC();
                var imgSmall = document.createElement('img');
                imgSmall.setAttribute("src", getImage());
                setIdC(imgSmall);
                imgSmall.className = "tc-img summary-img onchange";
                iCc.appendChild(imgSmall);
                break;

            case 'summary_large_image':
                bgimgC();
                iCc.parentNode.className = 'summary_large_image tc-container';
                removeC();
                var imgLarge = document.createElement('img');
                imgLarge.setAttribute("src", getImage());
                imgLarge.className = "summary_large_image";
                setIdC(imgLarge);
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
                setIdC(video);
                iCc.appendChild(video);
                break;
        }

    }, false);
}
