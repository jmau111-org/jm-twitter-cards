=== JM Twitter Cards ===
Contributors: jmlapam
Tags: twitter, cards, semantic markup, metabox, meta, photo, product, gallery, player
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7BJYYT486HEH6
Requires at least: 4.2
Tested up to: 4.9.5
Requires PHP: 5.4
License: GPLv2 or later
Stable tag: trunk
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy integration of Twitter cards in WordPress. All card types provided. 

== Description ==

Once activated the plugin adds appropriate meta on your WordPress website allowing you to get Twitter cards for your posts according to your settings. Enjoy !

= Features =

*  meta box : customize Twitter Cards experience (per each post)
*  preview : preview the rendering on Twitter. 
*  WP SEO by Yoast and All in One SEO compatible (no conflict with SEO title and desc set with these plugins)

contact@julien-maury.com

= First image found instead of featured image =

If you want to get first image found in post content as twitter image, I've created a free add-on here https://github.com/jmau111/jm-twitter-cards-first-image

= Translators =

The plugin is available in French, English and Spanish. Feel totally free to send me your translation in other languages.
You'll be added to the list here with your name and URL.
Thanks a lot to all translators. Can't wait to add your work to the project.

* Spanish version : [WebHostingHub](http://www.webhostinghub.com/) (thanks a lot ^^) 
* Catalan version : [SeoFreelance](http://www.seofreelance.es) (thanks Dude )

= Github =

*  Latest stable version : https://github.com/jmau111/jm-twitter-cards
*  Trunk version : https://github.com/jmau111/jm-twitter-cards/tree/trunk

This URL is the place where I improve the plugin according to support requests and stuffs like this. GitHub is the place !

[Follow me on Twitter](http://twitter.com/intent/user?screen_name=tweetpressfr)

== Installation ==

1. Upload plugin files to the /wp-content/plugins/ directory
2. Activate the plugin through the Plugins menu in WordPress
3. Then go to settings > JM Twitter Cards to configure the plugin
4. To display the metabox in edit section (posts, pages, attachments) just use default settings or enable only the post types you want

== Frequently asked questions ==

= I got problem with Instagram =

It's a known issue due to Instagram. Twitter said it recently : Users are experiencing issues with viewing Instagram photos on Twitter. Issues include cropped images.This is due to Instagram disabling its Twitter cards integration, and as a result, photos are being displayed using a pre-cards experience. 
So, when users click on Tweets with an Instagram link, photos appear cropped.

= Plugin is fine but Twitter cards doesn't appear in my tweets =

1. Make sure you correctly fulfilled fields in option page according to [Twitter documentation](https://dev.twitter.com/docs/cards "Twitter cards documentation")
2. Be careful with your robots.txt and put some rules to allow Twitter to fetch your website :

`	
	User-agent: Twitterbot
	Disallow:
`

If it still doesn't work please open a thread on support.

= The plugin generates a lot of images (different sizes) =

I can be a problem when you work with HD and/or a lot of images.

**How do I use the custom fields option?**

Basically you provide your custom field keys in plugin option page and then it will grab datas.


/**************
*			  *
* En Français *
* 			  *
************/

= Problème avec Instagram =
C'est un problème connu. Cela vient d'Instagram lui-même qui préfère que ses utilisateurs partagent les photos chez lui plutôt que sur Twitter. Instagram a supprimé ses Twitter Cards.

= Le plugin marche mais je n'obtiens pas de Twitter Cards dans mes tweets =

1. Assurez-vous bien d'avoir rempli correctement les champs dans la page d'options suivant <a href="https://dev.twitter.com/docs/cards" title="Twitter cards documentation">la documentation Twitter</a>
2. Attention avec le fichier robots.txt, vous devez autoriser le robot de Twitter à crawler votre site:

`
	User-agent: Twitterbot
	Disallow:
`

Si cela ne marche toujours pas SVP ouvrez un topic sur le support.

= Le plugin génère beaucoup trop de fichiers images = 

Cela peut poser problème si vous travaillez avec de la HD et/ou beaucoup d'images. Vous pouvez donc utiliser un plugin qui va effacer les fichiers images non utilisés.

= Comment utiliser l'option custom fields? =

Il suffit de renseigner les clés de vos custom fields en page d'option et le plugin s'occupera de récupérer les datas correspondantes.

== Changelog ==

### 10.0.0
* November 2018
* Prepare for Gutenberg
* There won't be metabox in Gutenberg context, I made a custom sidebar panel.
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
* test up compat to WordPress 4.8.1
* fix display cars according to post types options
* fix missing loading class
* fix empty description when excerpt option is on and there's no excerpt


== Upgrade notice ==
Nothing
= 1.0 =


