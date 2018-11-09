// TODO use withSelect from data gut for featured media

export function getImage(url) {
    return url || tcDataMetabox.defaultImage;
}
