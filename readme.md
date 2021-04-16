# JM Twitter Cards #

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

### 11.1.2
* April 2021
* fix indentation
* refactor

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
