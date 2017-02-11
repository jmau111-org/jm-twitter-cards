# JM Twitter Cards #

Easy integration of Twitter cards in WordPress. All card types provided.

## Description ##

This is the github version of the official repository for JM Twitter Cards. It's hardly a fork but I'm working on ! I'm trying to improve code structure and I'm adding some cool stuffs.

Want to fork? Please fork the trunk version and not the master version, URL is here : https://github.com/TweetPressFr/jm-twitter-cards/tree/trunk

## Important to know ##

The plugin uses libraries such as API Settings wrapper or Rilwis Metabox Framework.

To get this work, use composer :

```
git clone https://github.com/TweetPressFr/jm-twitter-cards.git && cd jm-twitter-cards
composer install
```

## Changelog ##

### 7.8
* 11 feb 2017
* fix local storage collision props to xwolfoverride
* also load js settings only on page settings

### 7.7
* 10 dec 2016
* fix error preview (WP preview)
* reaorganize file
* no more framework, delete old code
* fix issues img

### 7.5.0
* 07 May 2016
* fix default opts metabox card type
* fix admin referer issue that trigger error with other plugins

### 7.5.0
* 05 April 2016
* CLI commands
* full js preview in real time
* less code in js when possible
* fix edge case player card, player not set

### 7.4.0
* 26 Feb 2016
* sick and tired of external resources not working
* do my own fields

### 7.2.0
* 31 Jan 2016
* update framework Rilwis + small fixes loading that should solve some display issue

### 7.1.0
* 10 Jan 2016
* fix notices with plugins that use meta box plugin
* load meta box framework on init

### 7.0.2
* 13 Dec 2015
* fix bugs reported

### 7.0.1
* 29 Nov 2015
* delete deprecated markup reference according to dev.twitter.com
* delete ref to gallery and product cards (these card types are deprecated)
* rebuild options + preview + markup
* fix bug title when Yoast is activated and Yoast title not set
* take ONLY public custom post types into account ( e.g no more metabox in ACF group fields)
* add new feature : choose post type where you want to show metabox
* less options

### 7.0
* 11 Nov 2015
* composer, namespace and simplification
* use now settings API for options
* use Rilwis metabox so any need to filter read https://github.com/rilwis/meta-box/wiki
* https://dev.twitter.com/cards/markup delete deprecated markup

### 6.0.1
* 02 august 2015
* fix notices by fixing init class call

### 6.0
* 25 July 2015
* autoload resources
* requires now PHP 5.3++
* deactivate plugin if PHP version is not compatible
* improve namespace
* use admin default font not the font I like
* delete product and gallery cards cause they're no longer supported by Twitter

### 5.5
* 02 May 2015
* code inspector
* namespace + composer cMb
* bugfixes
* delete fancy rwd menu in admin (not compatible with no js and not very handy) and replace it with WP UI
* improve phpdoc
* no need for documentation or validator any more
