# JM Twitter Cards #

Easy integration of Twitter cards in WordPress. All card types provided.

## Description ##

This is the github version of the official repository for JM Twitter Cards. It's hardly a fork but I'm working on ! I'm trying to improve code structure and I'm adding some cool stuffs.

Want to fork? Please fork the trunk version and not the master version, URL is here : https://github.com/jmau111/jm-twitter-cards/tree/trunk

## Important to know ##

The plugin uses libraries such as API Settings wrapper or Rilwis Metabox Framework.

To get this work, use composer :

```
git clone https://github.com/jmau111/jm-twitter-cards.git && cd jm-twitter-cards
composer install
```

## Changelog ##

### 10.0
* August 2018
* Prepare for Gutenberg
* There won't be preview anymore for installation without Gutenberg 
but metabox will be still there
* If you enable Gutenberg then you will have a custom block meta \O/
* Reorganize deeply the plugin structure
* fix preview props to [tomdxw](https://github.com/tomdxw)
* fix default size props [tomdxw](https://github.com/tomdxw)

### 9.4
* June 2018
* remove bad bad method get_keys()
* remove preview js so now you'll have to save post as draft or publish to see preview, need to prepare for Gutenberg
* refactor code
* fix images sizes never added (don't forget to regenerate thumbnails)
* remove deprecated code (PHP 7.2)

### 9.3
* April 2018
* fix bug wrong ternaries in class options (props @ioneill)
* fix sanitize metabox card type not taking into account app card

### 9.2
* Feb 2018
* fix error in about.php
* fix warnings in case very first use
* remove get_keys() cause slow on large site, this feature is useless for now
* fix incompatibility with NGG (nextgen gallery)

### 9.1
* Jan 2018
* multiple js bugfixes especially in preview
* reorganize + enhance code, this is a major release but it won't break anything, filters are still there
* add twitter meta image alt
* add new dropdown menu to select custom meta key (advanced users).

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
