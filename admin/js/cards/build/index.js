this["jm-tc"] = this["jm-tc"] || {}; this["jm-tc"]["cards"] =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["editor"]; }());

/***/ }),
/* 1 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_blocks__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_blocks___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__wordpress_blocks__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__wordpress_editor__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__wordpress_editor___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__wordpress_editor__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__wordpress_components__ = __webpack_require__(3);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__wordpress_components___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__wordpress_components__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__wordpress_element__ = __webpack_require__(4);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__wordpress_element___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3__wordpress_element__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__ = __webpack_require__(5);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__wordpress_i18n___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__components_title__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__style_scss__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__style_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_6__style_scss__);
/**
 * TODO: tidy... player part too
 */















Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_blocks__["registerBlockType"])('jm-tc/cards', {
    title: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Twitter Cards', 'jm-tc'),
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
        }
    },

    edit: function edit(_ref) {
        var attributes = _ref.attributes,
            setAttributes = _ref.setAttributes,
            className = _ref.className;
        var twitterCardType = attributes.twitterCardType,
            cardImageID = attributes.cardImageID,
            cardDesc = attributes.cardDesc,
            cardImage = attributes.cardImage,
            cardImageAlt = attributes.cardImageAlt,
            cardPlayer = attributes.cardPlayer,
            cardPlayerWidth = attributes.cardPlayerWidth,
            cardPlayerHeight = attributes.cardPlayerHeight,
            cardPlayerStream = attributes.cardPlayerStream,
            cardPlayerCodec = attributes.cardPlayerCodec;


        var updateCardType = function updateCardType(twitterCardType) {
            return setAttributes({ twitterCardType: twitterCardType });
        };
        var updateCardImageAlt = function updateCardImageAlt(cardImageAlt) {
            return setAttributes({ cardImageAlt: cardImageAlt });
        };
        var updateCardDesc = function updateCardDesc(cardDesc) {
            return setAttributes({ cardDesc: cardDesc });
        };
        var updateCardPlayer = function updateCardPlayer(cardPlayer) {
            return setAttributes({ cardPlayer: cardPlayer });
        };
        var updateCardPlayerWidth = function updateCardPlayerWidth(cardPlayerWidth) {
            return setAttributes({ cardPlayerWidth: cardPlayerWidth });
        };
        var updateCardPlayerHeight = function updateCardPlayerHeight(cardPlayerHeight) {
            return setAttributes({ cardPlayerHeight: cardPlayerHeight });
        };
        var updateCardPlayerStream = function updateCardPlayerStream(cardPlayerStream) {
            return setAttributes({ cardPlayerStream: cardPlayerStream });
        };
        var updateCardPlayerCodec = function updateCardPlayerCodec(cardPlayerCodec) {
            return setAttributes({ cardPlayerCodec: cardPlayerCodec });
        };

        var imageWrapperStyles = {
            backgroundImage: 'url(' + cardImage + ')',
            backgroundSize: '3em'
        };

        return wp.element.createElement(
            __WEBPACK_IMPORTED_MODULE_3__wordpress_element__["Fragment"],
            null,
            'app' !== twitterCardType && wp.element.createElement(
                "div",
                { className: "EmbeddedTweet" },
                wp.element.createElement(
                    "div",
                    { className: "EmbeddedTweet-author u-cf" },
                    wp.element.createElement("img", { className: "EmbeddedTweet-author-avatar",
                        src: tcDataMetabox.avatar }),
                    wp.element.createElement(
                        "div",
                        {
                            className: "EmbeddedTweet-author-name u-pullLeft" },
                        Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])("Your Twitter account name", "jm-tc")
                    ),
                    wp.element.createElement(
                        "div",
                        { className: "EmbeddedTweet-author-handle u-pullLeft" },
                        "@",
                        tcDataMetabox.twitterSite
                    )
                ),
                wp.element.createElement(
                    "div",
                    { className: "EmbeddedTweet-text" },
                    wp.element.createElement(
                        "p",
                        null,
                        Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])("The card for your website will look a little something like this!", "jm-tc")
                    )
                ),
                wp.element.createElement(
                    "div",
                    { className: "CardPreview u-marginVm", id: "CardPreview" },
                    wp.element.createElement(
                        "div",
                        { className: "CardPreview-preview js-cardPreview" },
                        wp.element.createElement(
                            "div",
                            { className: "TwitterCardsGrid TwitterCard TwitterCard--animation" },
                            wp.element.createElement(
                                "div",
                                {
                                    className: "TwitterCardsGrid-col--12 TwitterCardsGrid-col--spacerBottom CardContent" },
                                wp.element.createElement(
                                    "div",
                                    {
                                        className: "js-openLink u-block TwitterCardsGrid-col--12 TwitterCard-container " + twitterCardType + "--small " + twitterCardType + "--noImage" },
                                    wp.element.createElement(
                                        "div",
                                        { className: twitterCardType + "-image TwitterCardsGrid-float--prev" },
                                        wp.element.createElement(
                                            "div",
                                            { className: "tcu-imageContainer tcu-imageAspect--1to1" },
                                            wp.element.createElement(
                                                "div",
                                                { className: "tcu-imageWrapper", style: imageWrapperStyles },
                                                wp.element.createElement("img", { className: "u-block",
                                                    alt: "",
                                                    src: cardImage })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        "div",
                                        {
                                            className: twitterCardType + "-contentContainer TwitterCardsGrid-float--prev" },
                                        wp.element.createElement(
                                            "div",
                                            { className: twitterCardType + "-content TwitterCardsGrid-ltr" },
                                            wp.element.createElement(__WEBPACK_IMPORTED_MODULE_5__components_title__["a" /* Title */], null),
                                            wp.element.createElement(
                                                "p",
                                                { className: "tcu-resetMargin u-block TwitterCardsGrid-col--spacerTop tcu-textEllipse--multiline" },
                                                cardDesc
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        wp.element.createElement(
                            "div",
                            null,
                            tcDataMetabox.domain
                        )
                    )
                )
            ),
            wp.element.createElement(
                __WEBPACK_IMPORTED_MODULE_1__wordpress_editor__["InspectorControls"],
                null,
                wp.element.createElement(
                    __WEBPACK_IMPORTED_MODULE_2__wordpress_components__["PanelBody"],
                    { title: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])("General Settings", "jm-tc") },
                    wp.element.createElement(__WEBPACK_IMPORTED_MODULE_2__wordpress_components__["SelectControl"], {
                        label: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])("Card Type", "jm-tc"),
                        value: twitterCardType,
                        options: [{ label: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Summary', 'jm-tc'), value: 'summary' }, { label: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Summary Large Image', 'jm-tc'), value: 'summary_large_image' }, { label: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Player', 'jm-tc'), value: 'player' }, { label: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Application', 'jm-tc'), value: 'app' }],
                        onChange: updateCardType
                    }),
                    wp.element.createElement(__WEBPACK_IMPORTED_MODULE_2__wordpress_components__["TextareaControl"], {
                        label: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])("Card description", "jm-tc"),
                        help: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])("By default this will be automatically generated or retrieved from a SEO plugin such as Yoast or All in One SEO but you can override this here", "jm-tc"),
                        className: className,
                        value: cardDesc,
                        onChange: updateCardDesc
                    })
                ),
                wp.element.createElement(
                    __WEBPACK_IMPORTED_MODULE_2__wordpress_components__["PanelBody"],
                    { title: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])("Image Settings", "jm-tc") },
                    !cardImage && wp.element.createElement(
                        __WEBPACK_IMPORTED_MODULE_2__wordpress_components__["Placeholder"],
                        {
                            instructions: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])("Upload image here or insert from media library to set another source for twitter image than featured image", "jm-tc"),
                            icon: "format-image",
                            label: "Image"
                        },
                        wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__wordpress_editor__["MediaUpload"], {
                            onSelect: function onSelect(media) {
                                return setAttributes({ cardImage: media.url, cardImageID: media.id });
                            },
                            type: "image",
                            render: function render(_ref2) {
                                var open = _ref2.open;
                                return wp.element.createElement(
                                    __WEBPACK_IMPORTED_MODULE_2__wordpress_components__["Button"],
                                    { isLarge: true, onClick: open },
                                    Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])("Insert from Media Library", "jm-tc")
                                );
                            }
                        })
                    ),
                    cardImage && wp.element.createElement(
                        __WEBPACK_IMPORTED_MODULE_2__wordpress_components__["Placeholder"],
                        {
                            instructions: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])("Change twitter Image source", "jm-tc"),
                            icon: "format-image",
                            label: "Image"
                        },
                        wp.element.createElement(
                            "div",
                            { className: "thumbnail" },
                            wp.element.createElement(
                                "div",
                                { className: "centered" },
                                wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__wordpress_editor__["MediaUpload"], {
                                    onSelect: function onSelect(media) {
                                        return setAttributes({
                                            cardImage: media.url,
                                            cardImageID: media.id
                                        });
                                    },
                                    type: "image",
                                    value: cardImageID,
                                    render: function render(_ref3) {
                                        var open = _ref3.open;
                                        return wp.element.createElement("img", { src: cardImage, className: "tc-image-overview", onClick: open });
                                    }
                                })
                            )
                        )
                    ),
                    wp.element.createElement(__WEBPACK_IMPORTED_MODULE_2__wordpress_components__["TextareaControl"], {
                        label: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])("Card image alt text", "jm-tc"),
                        className: className,
                        value: cardImageAlt,
                        onChange: updateCardImageAlt
                    })
                ),
                'player' === twitterCardType && wp.element.createElement(
                    __WEBPACK_IMPORTED_MODULE_2__wordpress_components__["PanelBody"],
                    { title: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])("Player Settings", "jm-tc") },
                    wp.element.createElement(
                        __WEBPACK_IMPORTED_MODULE_3__wordpress_element__["Fragment"],
                        null,
                        wp.element.createElement(__WEBPACK_IMPORTED_MODULE_2__wordpress_components__["TextControl"], {
                            type: "url",
                            label: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Player URL', 'jm-tc'),
                            value: cardPlayer,
                            placeholder: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Enter URL…', 'jm-tc'),
                            onChange: updateCardPlayer
                        }),
                        wp.element.createElement(__WEBPACK_IMPORTED_MODULE_2__wordpress_components__["RangeControl"], {
                            label: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Player Width', 'jm-tc'),
                            value: cardPlayerWidth,
                            min: 262,
                            max: 1000,
                            onChange: updateCardPlayerWidth
                        }),
                        wp.element.createElement(__WEBPACK_IMPORTED_MODULE_2__wordpress_components__["RangeControl"], {
                            label: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Player Height', 'jm-tc'),
                            value: cardPlayerHeight,
                            min: 196,
                            max: 1000,
                            onChange: updateCardPlayerHeight
                        }),
                        wp.element.createElement(__WEBPACK_IMPORTED_MODULE_2__wordpress_components__["TextControl"], {
                            type: "url",
                            label: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Player Stream URL', 'jm-tc'),
                            value: cardPlayerStream,
                            placeholder: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Enter URL…', 'jm-tc'),
                            onChange: updateCardPlayerStream
                        }),
                        wp.element.createElement(__WEBPACK_IMPORTED_MODULE_2__wordpress_components__["TextControl"], {
                            type: "url",
                            label: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Player codec URL', 'jm-tc'),
                            value: cardPlayerCodec,
                            placeholder: Object(__WEBPACK_IMPORTED_MODULE_4__wordpress_i18n__["__"])('Enter URL…', 'jm-tc'),
                            onChange: updateCardPlayerCodec
                        })
                    )
                )
            )
        );
    },


    save: function save() {
        return null; // we need this to prevent our stuffs from appearing in post content, we want meta here
    }
});

/***/ }),
/* 2 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["blocks"]; }());

/***/ }),
/* 3 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["components"]; }());

/***/ }),
/* 4 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["element"]; }());

/***/ }),
/* 5 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["i18n"]; }());

/***/ }),
/* 6 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 7 */,
/* 8 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Title; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_data__ = __webpack_require__(9);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_data___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__wordpress_data__);


/**
 * @link https://stackoverflow.com/a/51792096
 * @link https://riad.blog/2018/06/07/efficient-client-data-management-for-wordpress-plugins/
 * @param props
 * @returns {*}
 * @constructor
 */
var GetTitle = function GetTitle(props) {
  return wp.element.createElement(
    "h2",
    { className: "TwitterCard-title js-cardClick tcu-textEllipse--multiline" },
    props.title
  );
};

var selectTitle = Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_data__["withSelect"])(function (select) {
  return {
    title: select("core/editor").getEditedPostAttribute('title')
  };
});

var Title = selectTitle(GetTitle);

/***/ }),
/* 9 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["data"]; }());

/***/ })
/******/ ]);
//# sourceMappingURL=index.js.map