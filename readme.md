# JM Twitter Cards #

[![WordPress Download Count](https://img.shields.io/wordpress/plugin/dt/jm-twitter-cards.svg?style=flat-square)](https://wordpress.org/plugins/jm-twitter-cards/)
[![WordPress Rating](https://img.shields.io/wordpress/plugin/r/jm-twitter-cards.svg?style=flat-square)](https://wordpress.org/support/plugin/jm-twitter-cards/reviews/)

Easy integration of Twitter cards in WordPress. All card types provided.

## Description ##

This is the GitHub/dev version of the JM Twitter Cards plugin.

Want to fork? Please fork the trunk version and not the master version, URL is here : https://github.com/jmau111/jm-twitter-cards/tree/trunk

## Important to know ##

To get this work:

```
git clone https://github.com/jmau111/jm-twitter-cards.git && cd jm-twitter-cards
```

If Gutenberg is enabled then run :

```
npm i && npm run build
```

or use [the wordpress.org version](https://fr.wordpress.org/plugins/jm-twitter-cards/).

## Add-ons

You can create custom add-ons based on custom filters and other hooks available in the main plugin.

### Use first image found in post content as Twitter image

```PHP
add_filter('jm_tc_image_source', function($url)
{
    global $post;

    if (!isset($post->post_content)) {
        return $url;
    }

    if (preg_match_all('`<img [^>]+>`', $post->post_content, $matches)) {
        $_matches = reset($matches);
        foreach ($_matches as $image) {
            if (preg_match('`src=(["\'])(.*?)\1`', $image, $_match)) {
                return $_match[2];
            }
        }
    }

    return $url;
});
```

### Force refresh image

```PHP
add_filter('jm_tc_image_source', function($image)
{

    if (empty($image)) {
        return false;
    }

    $params = (array) apply_filters('jm_tc_refresh_image_query_string_params', ['tc' => strtotime('now')], $image);

    return add_query_arg($params, $image);
});
```

## Changelog ##

[See all modifications](changelog.txt)