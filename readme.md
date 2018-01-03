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

### 9.0
* Jan 2018
* multiple js bugfixes especially in preview
* reorganize + enhance code, this is a major release but it won't break anything, filters are still there
* add twitter meta image alt

### 8.2
* 30 Oct 2017
* fix markup issue that causes setting error
* fix bug first page not saving options anymore

### 8.1
* 28 Oct 2017
* fix admin screens & reported incompatibilities with themes
* delete crappy localstorage

### 8.0
* 09 Sept 2017
* test up to 4.8.1
* fix load markup according to post types option
