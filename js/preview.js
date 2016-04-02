'use strict';


var cardType   = document.getElementById('twitterCardType'),
    postThumb  = document.querySelectorAll('#set-post-thumbnail img'),
    metaBoxImg = document.getElementById('cardImage');

if (typeof( cardType.addEventListener ) !== 'undefined') {
    cardType.addEventListener("change", function (evt) {
        var type = evt.target.value;
    });
}

