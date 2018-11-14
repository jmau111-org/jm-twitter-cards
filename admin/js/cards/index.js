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
import {Fragment} from "@wordpress/element";
import {__} from "@wordpress/i18n";

import {Attributes} from "./components/attributes";
import {Supports} from "./components/supports";
import {Type} from "./components/cardType";
import {Preview} from "./components/preview";

import "./style.scss";

registerBlockType('jm-tc/cards', {
    title: __('Twitter Cards', 'jm-tc'),
    icon: 'twitter',
    category: 'common',
    supports: Supports,
    attributes: Attributes,

    edit({attributes, setAttributes, className}) {

        const {
            twitterCardType,
            cardImageID,
            cardDesc,
            cardImage,
            cardImageAlt,
            cardPlayer,
            cardPlayerWidth,
            cardPlayerHeight,
            cardPlayerStream,
            cardPlayerCodec,
        } = attributes;

        const updateCardType = twitterCardType => setAttributes({twitterCardType});
        const updateCardImageAlt = cardImageAlt => setAttributes({cardImageAlt});
        const updateCardDesc = cardDesc => setAttributes({cardDesc});
        const updateCardPlayer = cardPlayer => setAttributes({cardPlayer});
        const updateCardPlayerWidth = cardPlayerWidth => setAttributes({cardPlayerWidth});
        const updateCardPlayerHeight = cardPlayerHeight => setAttributes({cardPlayerHeight});
        const updateCardPlayerStream = cardPlayerStream => setAttributes({cardPlayerStream});
        const updateCardPlayerCodec = cardPlayerCodec => setAttributes({cardPlayerCodec});

        return (
            <Fragment>
                <Preview key={className} attributes={attributes}/>
                <InspectorControls>
                    <PanelBody title={__("General Settings", "jm-tc")}>

                        <SelectControl
                            label={__("Card Type", "jm-tc")}
                            value={Type(attributes)}
                            options={[
                                {label: __('Summary', 'jm-tc'), value: 'summary'},
                                {label: __('Summary Large Image', 'jm-tc'), value: 'summary_large_image'},
                                {label: __('Player', 'jm-tc'), value: 'player'},
                                {label: __('Application', 'jm-tc'), value: 'app'},
                            ]}
                            onChange={updateCardType}
                        />
                        <TextareaControl
                            label={__("Card description", "jm-tc")}
                            help={__("By default this will be automatically generated or retrieved from a SEO plugin such as Yoast or All in One SEO but you can override this here", "jm-tc")}
                            className={className}
                            value={cardDesc}
                            onChange={updateCardDesc}
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
                                    onChange={updateCardPlayer}
                                />
                                <RangeControl
                                    label={__('Player Width', 'jm-tc')}
                                    value={Number(cardPlayerWidth)}
                                    min={262}
                                    max={1000}
                                    onChange={updateCardPlayerWidth}
                                />
                                <RangeControl
                                    label={__('Player Height', 'jm-tc')}
                                    value={Number(cardPlayerHeight)}
                                    min={196}
                                    max={1000}
                                    onChange={updateCardPlayerHeight}
                                />
                                <TextControl
                                    type="url"
                                    label={__('Player Stream URL', 'jm-tc')}
                                    value={cardPlayerStream}
                                    placeholder={__('Enter URL…', 'jm-tc')}
                                    onChange={updateCardPlayerStream}
                                />
                                <TextControl
                                    type="url"
                                    label={__('Player codec URL', 'jm-tc')}
                                    value={cardPlayerCodec}
                                    placeholder={__('Enter URL…', 'jm-tc')}
                                    onChange={updateCardPlayerCodec}
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
                                    onSelect={(media) => setAttributes({cardImage: media.url, cardImageID: media.id})}
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
                                            onSelect={(media) => setAttributes({
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
                            className={className}
                            value={cardImageAlt}
                            onChange={updateCardImageAlt}
                        />
                    </PanelBody>
                </InspectorControls>
            </Fragment>
        );
    },

    save: function () {
        return null;// we need this to prevent our stuffs from appearing in post content, we want meta here
    },
});