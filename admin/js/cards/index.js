import "./style.scss";

import {registerBlockType} from "@wordpress/blocks";
import {
    InspectorControls,
    BlockControls
} from "@wordpress/editor";
import {
    TextareaControl,
    Placeholder,
    RangeControl,
    PanelBody,
    ToggleControl,
    SelectControl,
    TextControl,
    IconButton,
    Button
} from "@wordpress/components";

import {Fragment} from "@wordpress/element";

import {MediaUpload, mediaUpload} from "@wordpress/editor";

import {__} from "@wordpress/i18n";

registerBlockType('jm-tc/cards', {
    title: __('Twitter Cards', 'jm-tc'),
    icon: 'twitter',
    category: 'common',

    attributes: {
        twitterCardType: {
            type: 'string',
            source: 'meta',
            meta: 'twitterCardType'
        },
        cardImageID: {
            type: 'integer',
            source: 'meta',
            meta: 'cardImageID'
        },
        cardImage: {
            type: 'string',
            source: 'meta',
            meta: 'cardImage'
        },
        cardImageAlt: {
            type: 'string',
            source: 'meta',
            meta: 'cardImageAlt'
        },
        cardPlayer: {
            type: 'string',
            source: 'meta',
            meta: 'cardPlayer'
        },
        cardPlayerWidth: {
            type: 'integer',
            source: 'meta',
            meta: 'cardPlayerWidth'
        },
        cardPlayerHeight: {
            type: 'integer',
            source: 'meta',
            meta: 'cardPlayerHeight'
        },
        cardPlayerStream: {
            type: 'string',
            source: 'meta',
            meta: 'cardPlayerStream'
        },
        cardPlayerCodec: {
            type: 'string',
            source: 'meta',
            meta: 'cardPlayerCodec'
        }
    },

    edit({attributes, setAttributes, className}) {

        const {
            twitterCardType,
            cardImageID,
            cardImage,
            cardImageAlt,
            cardPlayer,
            cardPlayerWidth,
            cardPlayerHeight,
            cardPlayerStream,
            cardPlayerCodec
        } = attributes;

        console.log(attributes)

        const updateCardType = twitterCardType => setAttributes({twitterCardType});
        const updateCardImageAlt = cardImageAlt => setAttributes({cardImageAlt});
        const updateCardPlayer = cardPlayer => setAttributes({cardPlayer});
        const updateCardPlayerWidth = cardPlayerWidth => setAttributes({cardPlayerWidth});
        const updateCardPlayerHeight = cardPlayerHeight => setAttributes({cardPlayerHeight});
        const updateCardPlayerStream = cardPlayerStream => setAttributes({cardPlayerStream});
        const updateCardPlayerCodec = cardPlayerCodec => setAttributes({cardPlayerCodec});

        return (
            <Fragment>
                <div className="tc-preview">
                    TODO: preview, use template to lock the thing


                </div>
                <SelectControl
                    label={__("Card Type", "jm-tc")}
                    value={twitterCardType}
                    options={[
                        {label: __('Summary', 'jm-tc'), value: 'summary'},
                        {label: __('Summary Large Image', 'jm-tc'), value: 'summary_large_image'},
                        {label: __('Player', 'jm-tc'), value: 'player'},
                        {label: __('Application', 'jm-tc'), value: 'app'},
                    ]}
                    onChange={updateCardType}
                />

                {!cardImage && (
                    <Placeholder
                        instructions={__("Upload image here or insert from media library to set another source for twitter image than featured image", "jm-tc")}
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

                <InspectorControls>
                    <PanelBody title={__("Image Settings", "gutextra")}>
                        {cardImage && (
                            <Placeholder
                                instructions={__("Change twitter Image source", "jm-tc")}
                                icon="format-image"
                                label={"Image"}
                            >
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
                                                <img src={cardImage} className="tc-image-overview" onClick={open}/>
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
                    {'player' === twitterCardType && (
                        <PanelBody title={__("Player Settings", "gutextra")}>
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
                                    value={cardPlayerWidth}
                                    min={262}
                                    max={1000}
                                    onChange={updateCardPlayerWidth}
                                />
                                <RangeControl
                                    label={__('Player Height', 'jm-tc')}
                                    value={cardPlayerHeight}
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


                </InspectorControls>

            </Fragment>
        );
    },

    save: function () {
        return null;// we need this to prevent our stuffs from appearing in post content, we want meta here
    },
});