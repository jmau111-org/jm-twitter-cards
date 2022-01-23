=== JM Twitter Cards ===
Contributors: jmlapam
Tags: twitter, cards, semantic markup, metabox, meta, photo, product, gallery, player
Donate Link: https://don.fondationabbepierre.org/b/mon-don?_ga=2.25482229.995513841.1594242995-131201139.1594242995&_cv=1
Requires at least: 4.2
Tested up to: 5.9
Requires PHP: 7.0
License: GPLv2 or later
Stable tag: 11.1.9
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy integration of Twitter cards in WordPress. All card types provided. 

== Description ==

Once activated the plugin adds appropriate meta on your WordPress website allowing you to get Twitter cards for your posts according to your settings. Enjoy !

= Features =

* Gutenberg compatible with a custom sidebar
* meta box : customize Twitter Cards experience (per each post)
* preview : preview the rendering on Twitter.
* WP SEO by Yoast and All in One SEO compatible (no conflict with SEO title and desc set with these plugins)

= First image found instead of featured image =

If you want to get first image found in post content as twitter image, I've created a free add-on here [https://github.com/jmau111/jm-twitter-cards-first-image](https://github.com/jmau111/jm-twitter-cards-first-image)

= Force refresh Twitter image =

If you want to get your Twitter image to refresh, I’ve created a free add-on here [https://github.com/jmau111/jm-twitter-cards-refresh-image](https://github.com/jmau111/jm-twitter-cards-refresh-image)

= Translators =

The plugin is available in French, English and Spanish. Feel totally free to send me your translation in other languages.
You'll be added to the list here with your name and URL.
Thanks a lot to all translators. Can't wait to add your work to the project.

* Spanish version : [WebHostingHub](http://www.webhostinghub.com/) (thanks a lot ^^)
* Catalan version : [SeoFreelance](http://www.seofreelance.es) (thanks Dude )
* Italian version : [IBIDEM GROUP](https://www.ibidemgroup.com) (thanks a lot)

= Github =

*  Latest stable version : [https://github.com/jmau111/jm-twitter-cards](https://github.com/jmau111/jm-twitter-cards)
*  Trunk version : [https://github.com/jmau111/jm-twitter-cards/tree/trunk](https://github.com/jmau111/jm-twitter-cards/tree/trunk)

This URL is the place where I improve the plugin according to support requests and stuffs like this. GitHub is the place !

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

### 11.1.4
* April 2021
* fix indentation
* security fixes

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
* use js core setup for gut sidebar
* bufixes with default values

== Upgrade notice ==
Nothing
= 1.0 =
