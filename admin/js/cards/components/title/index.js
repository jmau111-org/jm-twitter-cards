import {withSelect} from "@wordpress/data";
import {Fragment} from "@wordpress/element";

/**
 * @link https://stackoverflow.com/a/51792096
 * @link https://riad.blog/2018/06/07/efficient-client-data-management-for-wordpress-plugins/
 * @param props
 * @returns {*}
 * @constructor
 */
const GetTitle = props => props.title;

const selectTitle = withSelect(select => ({
    title: select("core/editor").getEditedPostAttribute('title')
}));

export const Title = ({props}) => (
    <h2 className="TwitterCard-title js-cardClick tcu-textEllipse--multiline">
        {props.meta.cardTitle || selectTitle(GetTitle)}
    </h2>
);