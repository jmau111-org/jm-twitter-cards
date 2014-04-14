# JM Twitter Cards #

Easy integration of Twitter cards in WordPress. Most useful card types provided.

## Description ##

This is the github version of the official repository for JM Twitter Cards. It's hardly a fork but I'm working on ! I'm trying to improve code structure and I'm adding some cool stuffs.

**THIS IS NOW A BETA VERSION WITH ALMOST ALL THE FEATURES FOR THE NEXT UPDATE ON WORDPRESS.ORG. I NEED YOU TO TEST IT AND TELL ME IF IT'S OK BEFORE I PUSH THIS ON WORDPRESS.ORG**


## Changelog ##

# 5.1.7 #
* 15 Apr 2014
* fix PHP 5.4 warning when calling static method
* compatibility with older versions of PHP 
* fix jetpack error with PHP_INT_MAX BUG

# 5.1.4 #
* 12 Apr 2014
* fix notices undefined var
* last changes and fixes before pushing it on wordpress.org
* change version number for beta tester, they need to have the last fixes ^^


# 5.1.3 #
* 10 Apr 2014
* fix admin menu 
* add styles to about page
* beta version

# 5.1.2 #
* 09 Apr 2014
* add checking for featured image's weight
* reorganize code and pages

# 5.1.1 #
* 08 Apr 2014
* split admin into several pages to make it clearer for users

# 5.1 #
* 06 Apr 2014
* framework cmB update 
* bugfixes with metaboxes, thumbnails and notices

# 5.0 #
* 30 Mar 2014
* Reformat the entire code. Split the code into several files so it's now better for maintenance. 
* Keep the same keys so you do not have to re-set the plugin options
* Delete fancy design - I kinda loved it but it's too far from native WP UI, some users have difficulties in setting it.
* Use now a framework for metabox and other stuffs so I have not to rewrite all the things to add features
* Add src extra parameter to meta image so that the Twitterbot treats the image as a unique URL and re-fetches the image
