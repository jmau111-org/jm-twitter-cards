/**
 * TODO: tidy... player part too
 */
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

import {Title} from "./components/title";

import "./style.scss";

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
        cardDesc: {
            type: 'string',
            source: 'meta',
            meta: 'cardDesc'
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
        },
    },

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

        const imageWrapperStyles = {
            backgroundImage: 'url(' + cardImage + ')',
            backgroundSize: '3em'
        };

        return (
            <Fragment>
                {'app' !== twitterCardType && (
                    <div className="EmbeddedTweet">
                        <div className="EmbeddedTweet-author u-cf">
                            <img className="EmbeddedTweet-author-avatar"
                                 src={tcDataMetabox.avatar}/>
                            <div
                                className="EmbeddedTweet-author-name u-pullLeft">{__("Your Twitter account name", "jm-tc")}</div>
                            <div className="EmbeddedTweet-author-handle u-pullLeft">@{tcDataMetabox.twitterSite}</div>
                        </div>
                        <div className="EmbeddedTweet-text">
                            <p>{__("The card for your website will look a little something like this!", "jm-tc")}</p>
                        </div>

                        <div className="CardPreview u-marginVm" id="CardPreview">
                            <div className="CardPreview-preview js-cardPreview">
                                <div className="TwitterCardsGrid TwitterCard TwitterCard--animation">
                                    <div
                                        className="TwitterCardsGrid-col--12 TwitterCardsGrid-col--spacerBottom CardContent">
                                        <div
                                            className={"js-openLink u-block TwitterCardsGrid-col--12 TwitterCard-container " + twitterCardType + "--small " + twitterCardType + "--noImage"}>
                                            <div className={twitterCardType + "-image TwitterCardsGrid-float--prev"}>
                                                <div className="tcu-imageContainer tcu-imageAspect--1to1">
                                                    <div className="tcu-imageWrapper" style={imageWrapperStyles}>
                                                        <img className="u-block"
                                                             alt=""
                                                             src={cardImage}/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                className={twitterCardType + "-contentContainer TwitterCardsGrid-float--prev"}>
                                                <div className={twitterCardType + "-content TwitterCardsGrid-ltr"}>
                                                    <Title/>
                                                    <p className="tcu-resetMargin u-block TwitterCardsGrid-col--spacerTop tcu-textEllipse--multiline">{cardDesc}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>{tcDataMetabox.domain}</div>
                            </div>
                        </div>
                    </div>
                )}

                <InspectorControls>
                    <PanelBody title={__("General Settings", "jm-tc")}>

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
                        <TextareaControl
                            label={__("Card description", "jm-tc")}
                            help={__("By default this will be automatically generated or retrieved from a SEO plugin such as Yoast or All in One SEO but you can override this here", "jm-tc")}
                            className={className}
                            value={cardDesc}
                            onChange={updateCardDesc}
                        />
                    </PanelBody>
                    <PanelBody title={__("Image Settings", "jm-tc")}>
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