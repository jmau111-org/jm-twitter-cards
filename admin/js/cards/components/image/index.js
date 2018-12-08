import {Type} from "../cardType";
import {select} from '@wordpress/data';

function theImageUrl(props) {

    let featuredImageId = select('core/editor').getEditedPostAttribute('featured_media');
    let media = select('core').getMedia(featuredImageId);
    let featuredImageUrl = typeof(media) !== 'undefined' ? media.source_url : '';

    return props.meta.cardImage || featuredImageUrl || tcDataMetabox.defaultImage;
}

export const Image = ({props}) => (
    <div className="tcu-imageWrapper"
         style={{backgroundImage: "url(" + theImageUrl(props) + ")"}}>
        {'player' === Type(props) && (
            <div className="PlayerCard-playButton"
                 style={{backgroundImage: "url(" + tcDataMetabox.pluginUrl + "img/player.svg)"}}></div>
        )}
        <img className="u-block"
             alt={props.meta.cardImageAlt || ''}
             src={theImageUrl(props)}/>
    </div>
);
