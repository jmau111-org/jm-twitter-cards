=== JM Twitter Cards ===
Contributors: jmlapam
Tags: twitter, cards, semantic markup, metabox, meta, photo, product, gallery, player
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7BJYYT486HEH6
Requires at least: 4.2
Tested up to: 4.5.1
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

contact@tweetpress.fr

= Translators =

The plugin is available in French, English and Spanish. Feel totally free to send me your translation in other languages.
You'll be added to the list here with your name and URL.
Thanks a lot to all translators. Can't wait to add your work to the project.

* Spanish version : [WebHostingHub](http://www.webhostinghub.com/) (thanks a lot ^^) 
* Catalan version : [SeoFreelance](http://www.seofreelance.es) (thanks Dude )

= Github =

*  Latest stable version : https://github.com/TweetPressFr/jm-twitter-cards
*  Trunk version : https://github.com/TweetPressFr/jm-twitter-cards/tree/trunk

This URL is the place where I improve the plugin according to support requests and stuffs like this. GitHub is the place !

[Follow me on Twitter](http://twitter.com/intent/user?screen_name=tweetpressfr)

== Installation ==

1. Upload plugin files to the /wp-content/plugins/ directory
2. Activate the plugin through the Plugins menu in WordPress
3. Then go to settings > JM Twitter Cards to configure the plugin
4. To display the metabox in edit section (posts, pages, attachments) just use default settings or enable only the post types you want

[youtube http://www.youtube.com/watch?v=8l4k3zrD4Z0]

*this is an outdated version of tutorial, I'll add a new one soon.*

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


== Screenshots ==


== Changelog ==

### 7.5.0
* 01 May 2016
* fix edge case player card, player not set
* full js preview in real time
* less code in js when possible
* CLI commands

= 7.4.0 =
* 27 Feb 2016
* No more Framework metabox build my own fields

= 7.3.0 =
* 21 Feb 2016
* less radical composer approach - if autoload not there then nothing - not 500
* bugfixes admin
* gulp css + js
* re-add charcount warning for textarea home desc in options

= 7.1.0 =
* 30 Jan 2016
* fix notices with plugins that use meta box plugin
* load meta box framework on init

= 7.0.2 =
* 13 dec 2015
* fix some bugs reported in support
* JM Twitter cards requires PHP 5.3 and WordPress 4.2 at least !
* fix missing infos readme.txt not taken into account by wordpress.org and SVN
* delete old files (useless now) kept by SVN during last update, don't know really why...
* fix catalan version, files had wrong names this is -ca.mo and not -ca_ES.mo

= 7.0.1 =
* 29 Nov 2015
* delete deprecated markup reference according to dev.twitter.com
* delete ref to gallery and product cards (these card types are deprecated)
* rebuild options + preview + markup
* fix bug title when Yoast is activated and Yoast title not set
* take ONLY public custom post types into account ( e.g no more metabox in ACF group fields)
* add new feature : choose post type where you want to show metabox
* less options

== Upgrade notice ==
Nothing
= 1.0 =


