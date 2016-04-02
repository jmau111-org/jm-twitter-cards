'use strict';

var React = require('react'),
    h = React.createElement;

module.exports = React.createClass({

    'display_name': 'Card',
    render: function(){

        var props = this.props,
            visual = props.class === 'player' ?
            h('video', {style: {'WebkitUserDrag': 'none'}, controls: 'yes', poster: props.data.options.image})
            : h('img', {className:'tc-img ' + props.class + '-img', src: props.data.options.image});

        return h( 'div', {className:'tc-' + props.class + ' tc-container'},
            h( 'div', {className:'tc-' + props.class + '-img-container', backgroundImage: 'url(' + props.data.options.image + ')'}, visual ),
            h( 'div', {className: props.class + '-content'},
                h('p', {className: props.class + '-title tc-title' }, props.data.options.title),
                h('p', {className: props.class + '-desc tc-desc' }, props.data.options.desc),
                h('span', {className: 'tc-domain ' + props.class + '-domain'}, props.data.options.domain)
            )
        );
    }
});
