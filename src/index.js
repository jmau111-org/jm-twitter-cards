/**
 * WordPress dependencies
 */
import { withSelect, withDispatch } from "@wordpress/data";

import { MediaUpload } from "@wordpress/block-editor";

import {
  Button,
  Modal,
  PanelBody,
  Placeholder,
  RangeControl,
  SelectControl,
  TextareaControl,
  TextControl,
} from "@wordpress/components";

import { Fragment, Component } from "@wordpress/element";

import { PluginSidebar, PluginSidebarMoreMenuItem } from "@wordpress/edit-post";

import { compose } from "@wordpress/compose";

import { __ } from "@wordpress/i18n";

import { registerPlugin } from "@wordpress/plugins";

/**
 * Custom dependencies
 */
import { Type } from "./components/cardType";
import { Preview } from "./components/preview";

import "./style.scss";

class JM_Twitter_Cards extends Component {
  constructor() {
    super(...arguments);
    this.state = {
      isOpen: false,
    };
  }

  render() {
    const {
      meta: {
        twitterCardType: twitterCardType,
        cardTitle: cardTitle,
        cardDesc: cardDesc,
        cardImageID: cardImageID,
        cardImage: cardImage,
        cardImageAlt: cardImageAlt,
        cardPlayer: cardPlayer,
        cardPlayerWidth: cardPlayerWidth,
        cardPlayerHeight: cardPlayerHeight,
      },
      updatePostMeta,
    } = this.props;

    const { isOpen } = this.state;

    return (
      <Fragment>
        <PluginSidebar
          icon="twitter"
          name="jm-tc-sidebar"
          title={__("Twitter Cards settings", "jm-tc-gut")}
        >
          <PanelBody title={__("Main settings & preview", "jm-tc-gut")}>
            <p className="description smaller">
              {__(
                "The preview button allows you to change main twitter cards settings and see what it might look like on Twitter.",
                "jm-tc-gut"
              )}
            </p>
            <p className="description smaller">
              {__(
                "On no account this could replace the Twitter cards validator",
                "jm-tc-gut"
              )}
            </p>

            <Placeholder
              instructions={__("Preview and set your cards", "jm-tc-gut")}
              icon={isOpen ? "hidden" : "visibility"}
              label={__("preview", "jm-tc-gut")}
            >
              <div className="buttons">
                <Button
                  isDefault
                  onClick={() => this.setState({ isOpen: true })}
                >
                  {__("open modal", "jm-tc-gut")}
                </Button>

                <Button
                  isDefault
                  href="https://cards-dev.twitter.com/validator"
                >
                  {__("check validator", "jm-tc-gut")}
                </Button>
              </div>
            </Placeholder>
            {isOpen ? (
              <Modal
                title={__("Twitter Cards", "jm-tc-gut")}
                closeButtonLabel={"close"}
                onRequestClose={() => this.setState({ isOpen: false })}
              >
                <Preview props={this.props} />

                <div className="tc-fields-container">
                  <SelectControl
                    label={__("Card Type", "jm-tc-gut")}
                    value={Type(this.props)}
                    options={[
                      { label: __("Summary", "jm-tc-gut"), value: "summary" },
                      {
                        label: __("Summary Large Image", "jm-tc-gut"),
                        value: "summary_large_image",
                      },
                      { label: __("Player", "jm-tc-gut"), value: "player" },
                      { label: __("Application", "jm-tc-gut"), value: "app" },
                    ]}
                    onChange={(value) => {
                      updatePostMeta({ twitterCardType: value || "" });
                    }}
                  />
                </div>
                <div className="tc-fields-container">
                  <TextControl
                    type="text"
                    label={__("Custom title", "jm-tc-gut")}
                    help={__(
                      "Best is under 55 chars. If no set default card title would be post title",
                      "jm-tc-gut"
                    )}
                    value={cardTitle}
                    placeholder={__("Enter custom title…", "jm-tc-gut")}
                    onChange={(value) => {
                      updatePostMeta({ cardTitle: value || "" });
                    }}
                  />
                </div>
                <div className="tc-fields-container">
                  <TextareaControl
                    label={__("Card description", "jm-tc-gut")}
                    help={__(
                      "200 chars max but it is better to keep it short, 120-130 chars is fine. By default description will be automatically generated or retrieved from a SEO plugin such as Yoast or All in One SEO but you can override this here.",
                      "jm-tc-gut"
                    )}
                    value={cardDesc}
                    onChange={(value) => {
                      updatePostMeta({ cardDesc: value || "" });
                    }}
                  />
                </div>

                {"player" === twitterCardType && (
                  <div className="tc-fields-container">
                    <TextControl
                      type="url"
                      label={__("Player URL", "jm-tc-gut")}
                      value={cardPlayer}
                      placeholder={__("Enter URL…", "jm-tc-gut")}
                      onChange={(value) => {
                        updatePostMeta({ cardPlayer: value || "" });
                      }}
                    />
                    <RangeControl
                      label={__("Player Width", "jm-tc-gut")}
                      value={Number(cardPlayerWidth)}
                      min={262}
                      max={1000}
                      onChange={(value) => {
                        updatePostMeta({ cardPlayerWidth: value || "" });
                      }}
                    />
                    <RangeControl
                      label={__("Player Height", "jm-tc-gut")}
                      value={Number(cardPlayerHeight)}
                      min={196}
                      max={1000}
                      onChange={(value) => {
                        updatePostMeta({ cardPlayerHeight: value || "" });
                      }}
                    />
                  </div>
                )}

                <div className="tc-mb buttons">
                  <Button
                    isDefault
                    onClick={() => this.setState({ isOpen: false })}
                  >
                    {__("Close", "jm-tc-gut")}
                  </Button>
                  <Button
                    isDefault
                    href="https://cards-dev.twitter.com/validator"
                  >
                    {__("check validator", "jm-tc-gut")}
                  </Button>
                </div>
              </Modal>
            ) : null}
          </PanelBody>
          <PanelBody title={__("Image Settings", "jm-tc-gut")}>
            <p className="description smaller">
              {__(
                "Depending on your card type, please use appropriate ratio. Best is :",
                "jm-tc-gut"
              )}
            </p>
            <ul className="image-instructions">
              <li>{__("1:1 for summary card (square)", "jm-tc-gut")}</li>
              <li>
                {__("2:1 (rectangle) for summary large image", "jm-tc-gut")}
              </li>
            </ul>
            <p className="description smaller">
              {__(
                "Using featured image is highly recommended (if supported by your post type which is the case for posts) but you can override this here.",
                "jm-tc-gut"
              )}
            </p>
            {!cardImage && (
              <Placeholder
                instructions={__(
                  "Override featured image and default image for this post",
                  "jm-tc-gut"
                )}
                icon="format-image"
                label={"Image"}
              >
                <MediaUpload
                  onSelect={(media) =>
                    updatePostMeta({
                      cardImage: media.url,
                      cardImageID: media.id,
                    })
                  }
                  type="image"
                  render={({ open }) => (
                    <Button onClick={open}>
                      {__("Insert from Media Library", "jm-tc-gut")}
                    </Button>
                  )}
                />
              </Placeholder>
            )}
            {cardImage && (
              <Placeholder
                instructions={__("Change twitter Image source", "jm-tc-gut")}
                icon="format-image"
                label={"Image"}
              >
                <div className="thumbnail">
                  <div className="centered">
                    <MediaUpload
                      onSelect={(media) =>
                        updatePostMeta({
                          cardImage: media.url,
                          cardImageID: media.id,
                        })
                      }
                      type="image"
                      value={cardImageID}
                      render={({ open }) => (
                        <img
                          src={cardImage}
                          alt={cardImageAlt || ""}
                          className="tc-image-overview"
                          onClick={open}
                        />
                      )}
                    />
                  </div>
                </div>
              </Placeholder>
            )}

            <div className="tc-mb">
              <TextareaControl
                label={__("Card Image alt", "jm-tc-gut")}
                help={__(
                  "Alt text - accessibility for your Twitter Image",
                  "jm-tc-gut"
                )}
                value={cardImageAlt}
                onChange={(value) => {
                  updatePostMeta({ cardImageAlt: value || "" });
                }}
              />
            </div>
          </PanelBody>
        </PluginSidebar>
        <PluginSidebarMoreMenuItem icon="twitter" target="jm-tc-sidebar">
          {__("Twitter Cards", "jm-tc-gut")}
        </PluginSidebarMoreMenuItem>
      </Fragment>
    );
  }
}

/**
 * This is how it's done in core
 */
const applyWithSelect = withSelect((select) => {
  return {
    meta: select("core/editor").getEditedPostAttribute("meta"),
  };
});

const applyWithDispatch = withDispatch((dispatch, { meta }) => {
  return {
    updatePostMeta(Meta) {
      dispatch("core/editor").editPost({ meta: { ...meta, ...Meta } }); // merge
    },
  };
});

/**
 * Combine components
 */
const render = compose(applyWithSelect, applyWithDispatch)(JM_Twitter_Cards);

/**
 * Custom plugin register in GUT
 */
registerPlugin("jm-tc-sidebar", {
  icon: "twitter",
  render,
});
