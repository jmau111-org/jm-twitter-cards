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
yarn && yarn build
```

or use [the wordpress.org version](https://fr.wordpress.org/plugins/jm-twitter-cards/).

## Changelog ##

### 11.1.9
* January 2022
* update npm/composer dependencies
* new filter `jm_tc_display_html_comments` to hide HTML comments (credits)
* css preview, align fields and preview on desktop to enhance the experience
* toggle details - instructions by default

### 11.1.7 & 11.1.8
* October 2021
* update npm/composer dependencies

### 11.1.5 && 11.1.6
* July 2021
* update npm dependencies
* security fixes
* new filters for sections settings
### 11.1.4
* April 2021
* fix indentation
* security fixes

### 11.1.1
* November 2020
* the plugin has now an italian version props to Chema Bescós
* fix issue reported with reusable Gutenberg blocks in edge cases

### 11.1.0
* October 2020
* add tests
* update @wordpress/scripts - fix security issues

### 11.0.2
* October 2020
* fix issue reported on support with non Gutenberg installations

### 11.0.1
* October 2020
* a missing warning on fresh install wp

### 11.0.0
* October 2020
* fix deprecations
* update sidebar gutenberg
* use [the amazing core js setup](https://developer.wordpress.org/block-editor/tutorials/javascript/js-build-setup/)
* bufixes with default values
