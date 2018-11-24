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
    Modal,
    Panel,
    PanelRow,
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

import {compose, withState} from '@wordpress/compose';

import {__} from "@wordpress/i18n";

import {
    PluginSidebar
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

        const TC_Modal = withState({
            isOpen: false,
        })(({isOpen, setState}) => (
            <Fragment>
                <Button isDefault
                        onClick={() => setState({isOpen: true})}>{__('Click to set your Twitter Cards', 'jm-tc')}</Button>
                {isOpen ?
                    <Modal
                        title={__('Twitter Cards', 'jm-tc')}
                        closeButtonLabel={'close'}
                        onRequestClose={() => setState({isOpen: false})}>

                        <Preview props={this.props}/>

                        <SelectControl
                            label={__('Card Type', 'jm-tc')}
                            value={Type(this.props)}
                            options={[
                                {label: __('Summary', 'jm-tc'), value: 'summary'},
                                {label: __('Summary Large Image', 'jm-tc'), value: 'summary_large_image'},
                                {label: __('Player', 'jm-tc'), value: 'player'},
                                {label: __('Application', 'jm-tc'), value: 'app'},
                            ]}
                            onChange={(value) => {
                                updatePostMeta({twitterCardType: value || ''});
                            }}
                        />
                        <TextareaControl
                            label={__('Card description', 'jm-tc')}
                            help={__('By default this will be automatically generated or retrieved from a SEO plugin such as Yoast or All in One SEO but you can override this here', 'jm-tc')}
                            value={cardDesc}
                            onChange={(value) => {
                                updatePostMeta({cardDesc: value || ''});
                            }}
                        />
                        {'player' === twitterCardType && (
                            <Fragment>

                                <TextControl
                                    type="url"
                                    label={__('Player URL', 'jm-tc')}
                                    value={cardPlayer}
                                    placeholder={__('Enter URL…', 'jm-tc')}
                                    onChange={(value) => {
                                        updatePostMeta({cardPlayer: value || ''});
                                    }}
                                />
                                <RangeControl
                                    label={__('Player Width', 'jm-tc')}
                                    value={Number(cardPlayerWidth)}
                                    min={262}
                                    max={1000}
                                    onChange={(value) => {
                                        updatePostMeta({cardPlayerWidth: value || ''});
                                    }}
                                />
                                <RangeControl
                                    label={__('Player Height', 'jm-tc')}
                                    value={Number(cardPlayerHeight)}
                                    min={196}
                                    max={1000}
                                    onChange={(value) => {
                                        updatePostMeta({cardPlayerHeight: value || ''});
                                    }}
                                />
                                <TextControl
                                    type="url"
                                    label={__('Player Stream URL', 'jm-tc')}
                                    value={cardPlayerStream}
                                    placeholder={__('Enter URL…', 'jm-tc')}
                                    onChange={(value) => {
                                        updatePostMeta({cardPlayerStream: value || ''});
                                    }}
                                />
                                <TextControl
                                    type="url"
                                    label={__('Player codec URL', 'jm-tc')}
                                    value={cardPlayerCodec}
                                    placeholder={__('Enter URL…', 'jm-tc')}
                                    onChange={(value) => {
                                        updatePostMeta({cardPlayerCodec: value || ''});
                                    }}
                                />
                            </Fragment>
                        )}
                    </Modal>
                    : null}
            </Fragment>
        ));

        return (
            <Fragment>
                <PluginSidebar
                    icon="twitter"
                    name="jm-tc-sidebar"
                    title={__('Twitter Cards', 'jm-tc')}>
                    <PanelBody>
                        <PanelRow>
                            <TC_Modal/>
                        </PanelRow>
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