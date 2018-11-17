/**
 * WordPress dependencies
 */
import {
    withSelect,
    withDispatch,
} from '@wordpress/data';

import {registerBlockType} from "@wordpress/blocks";

import {BlockControls, InspectorControls, MediaUpload, mediaUpload} from "@wordpress/editor";

import {
    Button,
    IconButton,
    PanelBody,
    Placeholder,
    RangeControl,
    SelectControl,
    TextareaControl,
    TextControl,
    ToggleControl
} from "@wordpress/components";

import {
    Fragment,
    Component
} from "@wordpress/element";

import {compose} from '@wordpress/compose';

import {__} from "@wordpress/i18n";

import {
    PluginSidebar,
    PluginSidebarMoreMenuItem,
} from '@wordpress/editPost';

import {registerPlugin} from '@wordpress/plugins';

/**
 * Custom dependencies
 */
import {Type} from "./components/cardType";
import {Preview} from "./components/preview";

import "./style.scss";

class JmTc extends Component {

    render() {
        const {
            meta: {
                twitterCardType: twitterCardType,
                cardDesc: cardDesc,
                cardImageID: cardImageID,
                cardImage: cardImage,
                cardImageAlt: cardImageAlt,
                cardPlayer: cardPlayer,
                cardPlayerWidth: cardPlayerWidth,
                cardPlayerHeight: cardPlayerHeight,
                cardPlayerStream: cardPlayerStream,
                cardPlayerCodec: cardPlayerCodec,
            },
            updatePostMeta,
        } = this.props;

        return (
            <Fragment>
                <PluginSidebarMoreMenuItem
                    name="jm-tc-sidebar"
                    target="jm-tc-sidebar"
                >
                    {__('Set your Twitter Cards', 'jm-tc')}
                </PluginSidebarMoreMenuItem>
                <PluginSidebar
                    icon="twitter"
                    name="jm-tc-sidebar"
                    title={__('Twitter Cards', 'jm-tc')}>

                    <Preview props={this.props} />

                        <PanelBody title={__("General Settings", "jm-tc")}>

                            <SelectControl
                                label={__("Card Type", "jm-tc")}
                                value={Type(this.props)}
                                options={[
                                    {label: __('Summary', 'jm-tc'), value: 'summary'},
                                    {label: __('Summary Large Image', 'jm-tc'), value: 'summary_large_image'},
                                    {label: __('Player', 'jm-tc'), value: 'player'},
                                    {label: __('Application', 'jm-tc'), value: 'app'},
                                ]}
                                onChange={ ( value ) => {
                                    updatePostMeta( { twitterCardType: value || '' } );
                                } }
                            />
                            <TextareaControl
                                label={__("Card description", "jm-tc")}
                                help={__("By default this will be automatically generated or retrieved from a SEO plugin such as Yoast or All in One SEO but you can override this here", "jm-tc")}
                                value={cardDesc}
                                onChange={ ( value ) => {
                                    updatePostMeta( { cardDesc: value || '' } );
                                } }
                            />
                        </PanelBody>
                        {'player' === twitterCardType && (
                            <PanelBody title={__("Player Settings", "jm-tc")}>
                                <Fragment>
                                    <TextControl
                                        type="url"
                                        label={__('Player URL', 'jm-tc')}
                                        value={cardPlayer}
                                        placeholder={__('Enter URL…', 'jm-tc')}
                                        onChange={ ( value ) => {
                                            updatePostMeta( { cardPlayer: value || '' } );
                                        } }
                                    />
                                    <RangeControl
                                        label={__('Player Width', 'jm-tc')}
                                        value={Number(cardPlayerWidth)}
                                        min={262}
                                        max={1000}
                                        onChange={ ( value ) => {
                                            updatePostMeta( { cardPlayerWidth: value || '' } );
                                        } }
                                    />
                                    <RangeControl
                                        label={__('Player Height', 'jm-tc')}
                                        value={Number(cardPlayerHeight)}
                                        min={196}
                                        max={1000}
                                        onChange={ ( value ) => {
                                            updatePostMeta( { cardPlayerHeight: value || '' } );
                                        } }
                                    />
                                    <TextControl
                                        type="url"
                                        label={__('Player Stream URL', 'jm-tc')}
                                        value={cardPlayerStream}
                                        placeholder={__('Enter URL…', 'jm-tc')}
                                        onChange={ ( value ) => {
                                            updatePostMeta( { cardPlayerStream: value || '' } );
                                        } }
                                    />
                                    <TextControl
                                        type="url"
                                        label={__('Player codec URL', 'jm-tc')}
                                        value={cardPlayerCodec}
                                        placeholder={__('Enter URL…', 'jm-tc')}
                                        onChange={ ( value ) => {
                                            updatePostMeta( { cardPlayerCodec: value || '' } );
                                        } }
                                    />
                                </Fragment>
                            </PanelBody>
                        )}

                        <PanelBody title={__("Image Settings", "jm-tc")}>
                            {!cardImage && (
                                <Placeholder
                                    instructions={__("Using featured image is highly recommended but you can override this here. Upload image here or insert from media library to set another source for twitter image than featured image", "jm-tc")}
                                    icon="format-image"
                                    label={"Image"}
                                >
                                    <MediaUpload
                                        onSelect={(media) => updatePostMeta({
                                            cardImage: media.url,
                                            cardImageID: media.id
                                        })}
                                        type="image"
                                        render={({open}) => (
                                            <Button isLarge onClick={open}>
                                                {__(
                                                    "Insert from Media Library",
                                                    "jm-tc"
                                                )}
                                            </Button>
                                        )}
                                    />
                                </Placeholder>)}
                            {cardImage && (
                                <Placeholder
                                    instructions={__("Change twitter Image source", "jm-tc")}
                                    icon="format-image"
                                    label={"Image"}>
                                    <div className="thumbnail">
                                        <div className="centered">
                                            <MediaUpload
                                                onSelect={(media) => updatePostMeta({
                                                    cardImage: media.url,
                                                    cardImageID: media.id
                                                })}
                                                type="image"
                                                value={cardImageID}
                                                render={({open}) => (
                                                    <img src={cardImage} alt={cardImageAlt || ''}
                                                         className="tc-image-overview" onClick={open}/>
                                                )}
                                            />
                                        </div>
                                    </div>
                                </Placeholder>
                            )}

                            <TextareaControl
                                label={__("Card image alt text", "jm-tc")}
                                value={cardImageAlt}
                                onChange={ ( value ) => {
                                    updatePostMeta( { cardImageAlt: value || '' } );
                                } }
                            />
                        </PanelBody>
                </PluginSidebar>
            </Fragment>
        );
    }
}


/**
 * This is how it's done in core
 */
const applyWithSelect = withSelect((select) => {
    return {
        meta: select('core/editor').getEditedPostAttribute('meta'),
    };
});

const applyWithDispatch = withDispatch((dispatch, {meta}) => {
    return {
        updatePostMeta(Meta) {
            dispatch('core/editor').editPost({meta: {...meta, ...Meta}}); // merge
        },
    };
});

/**
 * Combine components
 */
const render = compose(
    applyWithSelect,
    applyWithDispatch
)(JmTc);

/**
 * Custom plugin register in GUT
 */
registerPlugin('jm-tc', {
    render,
});