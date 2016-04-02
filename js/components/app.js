'use strict';

 var React = require('react'),
     ReactDOM = require('react-dom'),
     h = React.createElement,
     Card = require('./card.js'),
     Top = require('./top.js'),
     Localized = tcData;

function getCardType(){
    if ( typeof(Localized.options.type) !== 'undefined' ) {
        return Localized.options.type;
    } else {
        return 'summary';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    ReactDOM.render(
        h('div', {className: 'global-' + getCardType() + ' tc-preview'},
            h( Top, {data:Localized, class: getCardType()} ),
            h('div', {className: 'tc-text u-pullLeft'}, Localized.message),
            h( Card, {data:Localized, class: getCardType()} )
        ),
        document.getElementById('app')
    )
});
