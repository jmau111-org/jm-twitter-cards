import { getType } from "../cardType";
import { select } from "@wordpress/data";

function getImageUrl(props) {
  let fallback = props.meta.cardImage || tcData.defaultImage;
  const { getPostType } = select("core");
  let postTypeCheck = getPostType(
    select("core/editor").getEditedPostAttribute("type")
  ); // no use if no support for thumbnail

  if (!postTypeCheck || !postTypeCheck.supports["thumbnail"]) {
    return fallback;
  }
  let featuredImageId = select("core/editor").getEditedPostAttribute(
    "featured_media"
  );

  if (featuredImageId === 0) {
    return fallback;
  }

  let media = select("core").getMedia(featuredImageId);

  if (typeof media !== "undefined") {
    return props.meta.cardImage || media.source_url;
  }

  return fallback;
}

export const Image = ({ props }) => (
  <div
    className="tcu-imageWrapper"
    style={{ backgroundImage: "url(" + getImageUrl(props) + ")" }}
  >
    {"player" === getType(props) && (
      <div
        className="PlayerCard-playButton"
        style={{
          backgroundImage: "url(" + tcData.pluginUrl + "img/player.svg)",
        }}
      ></div>
    )}
    <img
      className="u-block"
      alt={props.meta.cardImageAlt || ""}
      src={getImageUrl(props)}
    />
  </div>
);
