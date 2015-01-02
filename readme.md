# JM Twitter Cards #

Easy integration of Twitter cards in WordPress. All card types provided.

## Description ##

This is the github version of the official repository for JM Twitter Cards. It's hardly a fork but I'm working on ! I'm trying to improve code structure and I'm adding some cool stuffs.

Want to fork? Please fork the trunk version and not the master version, URL is here : https://github.com/TweetPressFr/jm-twitter-cards/tree/trunk

## Changelog ##

### 02 Jan 2015
* bugfixes
* delete fancy rwd menu in admin (not compatible with no js and not very handy) and replace it with WP UI
* improve phpdoc

### 5.4
* 22 Nov 2014
* Refactoring code
* Delte messy parts of it
* Kinda MVC structure
* Fix missing translations for documentation
* Remove useless functions from main file and put them into JM_TC_Init class
* No needs for inheritance actually, not even logical, options cannot inherit from utitilities 
* Get post object from get_queried_object() in markup class - seems safer considering global $post often get screwed
* Delete trailingslashit function (kinda heavy while doing nothing really helpful here)
* Small fixes on admin CSS

### 5.3.7 
* 12 Oct 2014
* Better PHP documentation
* Reorganize code
* Responsive web design for admin page and menu
* Update documentation and fix 404 on img

### 5.3.6
* 25 Sep 2014
* Fix bug with meta desc disapearing when img at the very top of content box
* Delete useless hook for admin scripts and use $hook_suffix

### 5.3.5
* add post excerpt option for meta desc

### 5.3.4
* bugfixes
* reorganize folders
* globalize options
* more hide with metabox js. In fact some parts such as img box are not needed for some card types (ex: gallery cards)

### 5.3.3
* change file names for classes
* change visibility for class properties, protected instead of public

### 5.3.2
* increment version

### 5.3.1
* fix bug over SSL
* reorganize code

### 5.3.0
* Fix notice with var $is_crop
* Replace wishlist Amazon with simple Paypal donation
* Delete metabox img size option, reduce the bloat. In fact it used to produce A LOT of images. 
* While it provided flexibility to a certain extent users were not so happy with this huge amount of img files
* To set img size, set it in option page, if you want to change, change the value in option page (img section) and use regenerate thumbnails plugin.
* Add js to meta box so users do not have to scroll to the bottom (they get only the option they need for each card type)

### 5.2.9
* 13 July 2014
* Add new crop options for WordPress 3.9++
* Indeed only works with latest versions of WordPress, later than 3.9
* Delete ajax call in option class

### 5.2.8
* 04 July 2014
* fix bug with NGG post box
* better init

### 5.2.7
* 22 June 2014
* make the og meta more compliant with W3C requirements (small fix HTML markup)

### 5.2.6
* 14 June 2014
* add option to import/export options quickly
* Fix missing translation for yes/no options

### 5.2.4 & 5.2.5
* 6 June 2014
* Add debug method in utilities to quickly show what is in $this ^^
* Fix notice appearing with new og param in preview class
* Improve multisite compatibility (default options, uninstall)
* Appropriate function restore_current_blog() instead of ~x2 switch_to_blog()
* Support for Open Graph

### 5.2.3 
* 20 May 2014
* No more backward compatibility before WP 3.6
* Rebuild preview because of the bug with overflow
* To do : add the js to metabox

### 5.2.2 
* 11 May 2014
* Fix wrong post meta key for player stream
* Fix robots.txt function 
* Add tabs to admin pages (menu on top for better UX with admin nav)


### 5.2.1 
* 07 May 2014
* Skip () for classes because we do not need it, actually no argument in constructor
* Removes notices, just uncheck option cards in WP SEO if enabled on plugins_loaded (soft way)
* Add app card type to meta box, add country field for meta country application not available on the US app store
* Add spanish translation for plugin & documentation so now full spanish version, huge thanks to Andrew Kurtis from WebHostingHub (http://www.webhostinghub.com/)

### 5.2.0 
* 03 May 2014
* Add confirmation message for option page when settings are saved
* Add translation in Spanish
* Fix bug with capability name
* Add 2nd footage, a video for troubleshooting
* Re-add preview feature, use only PHP for this
* Fix bug with preview WP SEO
* Add 3rd footage : multi-author video tutorial
* Update framework cmb
* Fix the issue with strip_shortcodes() not working


### 5.1.9 
* 20 Apr 2014
* Fix fallback All In One SEO title
* Put the card type selected in admin option page as default setting for meta box because it so a pain to select it on each post when meta box is enabled ^^
* Note that if you want to change card type for a particular post you'll need to use this select
* Everything that can be default is now set so even you do not have to set it if you do not want to
* Will save your time !
* Fix bug with get_post_meta and custom fields
* Fix escaping issue with desc and title from WP SEO by Yoast
* Can't wait for Spanish Version \o/
* Rebuild French Translation
* Add tutorial menu with videos explaining how to use the plugin

### 5.1.8 #
* 16 Apr 2014
* fix bug with All In One SEO 
* fix bug with older wordpress version before 3.5 : kinda of gracious degradation
* add the additional parameter src for image as default for every meta image, this should make the Twitterbot treat the image as a unique URL and re-fetches the image

### 5.1.7 #
* 15 Apr 2014
* fix PHP 5.4 warning when calling static method
* compatibility with older versions of PHP 
* fix jetpack error with PHP_INT_MAX BUG

### 5.1.4 #
* 12 Apr 2014
* fix notices undefined var
* last changes and fixes before pushing it on wordpress.org
* change version number for beta tester, they need to have the last fixes ^^

### 5.1.3 #
* 10 Apr 2014
* fix admin menu 
* add styles to about page
* beta version

### 5.1.2 #
* 09 Apr 2014
* add checking for featured image's weight
* reorganize code and pages

### 5.1.1 #
* 08 Apr 2014
* split admin into several pages to make it clearer for users

### 5.1 #
* 06 Apr 2014
* framework cmB update 
* bugfixes with metaboxes, thumbnails and notices

### 5.0 #
* 30 Mar 2014
* Reformat the entire code. Split the code into several files so it's now better for maintenance. 
* Keep the same keys so you do not have to re-set the plugin options
* Delete fancy design - I kinda loved it but it's too far from native WP UI, some users have difficulties in setting it.
* Use now a framework for metabox and other stuffs so I have not to rewrite all the things to add features
* Add src extra parameter to meta image so that the Twitterbot treats the image as a unique URL and re-fetches the image
