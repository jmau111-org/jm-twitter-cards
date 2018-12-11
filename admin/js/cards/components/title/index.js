import {select} from "@wordpress/data";

export const Title = ({props}) => (
    <h2 className="TwitterCard-title js-cardClick tcu-textEllipse--multiline">
        {props.meta.cardTitle || select('core/editor').getEditedPostAttribute('title') }
    </h2>
);