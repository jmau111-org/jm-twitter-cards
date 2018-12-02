import {Type} from "../cardType";

/**
 * TODO featured image
 * @param props
 * @returns {*}
 */
function theImageUrl(props) {
    return props.meta.cardImage || tcDataMetabox.defaultImage;
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

