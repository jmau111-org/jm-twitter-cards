'use strict';

var React = require('react'),
    h = React.createElement;

module.exports = React.createClass({

    'display_name': 'Top',
    render: function () {

        var props = this.props;

        return h('div', {className: 'tc-author u-cf'},
                    h('img', {className: 'tc-author-avatar', src: props.data.avatar}),
                    h('div', {className: 'tc-author-name u-pullLeft'}, props.data.options.site),
                    h('div', {className: 'tc-author-handle u-pullLeft'}, '@' + props.data.options.site)
                )
    }
});


