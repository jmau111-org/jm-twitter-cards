export function getType(props) {
  return props.meta.twitterCardType !== undefined || tcData.defaultType;
}
