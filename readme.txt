=== JM Twitter Cards ===
Contributors: jmlapam
Tags: twitter, cards, semantic markup, metabox, meta, photo, product, gallery, player
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7BJYYT486HEH6
Requires at least: 3.6
Tested up to: 4.0-beta2
License: GPLv2 or later
Stable tag: trunk
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy integration of Twitter cards in WordPress. All card types provided.

== Description ==

Once activated the plugin adds appropriate meta on your WordPress website allowing you to get Twitter cards for your posts according to your settings. Enjoy !

= Features =

*  meta box : customize Twitter Cards experience (per each post)
*  preview : preview the rendering on Twitter (on no account this could replace the Twitter's validator but it rocks). 
*  WP SEO by Yoast and All in One SEO compatible (no conflict with SEO title and desc set with these plugins)
*  fine crop for images (WordPress 3.9 ++)

contact@tweetpress.fr

= Translators =

The plugin is available in French, English and Spanish. Feel totally free to send me your translation in other languages.
You'll be added to the list here with your name and URL.
Thanks a lot to all translators. Can't wait to add your work to the project.

Spanish version : [WebHostingHub](http://www.webhostinghub.com/) (thanks dude ^^) 

= Github =

*  Latest stable version : https://github.com/TweetPressFr/jm-twitter-cards
*  Trunk version : https://github.com/TweetPressFr/jm-twitter-cards/tree/trunk

This URL is the place where I improve the plugin according to support requests and stuffs like this. Github is the place !

[Follow me on Twitter](http://twitter.com/intent/user?screen_name=tweetpressfr)

== Installation ==

1. Upload plugin files to the /wp-content/plugins/ directory
2. Activate the plugin through the Plugins menu in WordPress
3. Then go to settings > JM Twitter Cards to configure the plugin
4. To display the metabox in edit section (posts, pages, attachments), enable option in section called **Custom Twitter Cards**


[youtube http://www.youtube.com/watch?v=8l4k3zrD4Z0]

== Frequently asked questions ==

= I got problem with Instagram =

It's a known issue due to Instagram. Twitter said it recently : Users are experiencing issues with viewing Instagram photos on Twitter. Issues include cropped images.This is due to Instagram disabling its Twitter cards integration, and as a result, photos are being displayed using a pre-cards experience. 
So, when users click on Tweets with an Instagram link, photos appear cropped.

= Plugin is fine but Twitter cards doesn't appear in my tweets =

1. Make sure you correctly fulfilled fields in option page according to [Twitter documentation](https://dev.twitter.com/docs/cards "Twitter cards documentation")
2. Make sure you have correctly [submitted your website to Twitter](https://dev.twitter.com/docs/cards/validation/validator "Twitter cards submit")
3. Wait for Twitter's answer (a mail that tells you your site has been approved)
4. Be careful with your robots.txt and put some rules to allow Twitter to fetch your website :

`	
	User-agent: Twitterbot
	Disallow:
`

If it still doesn't work please open a thread on support.

= Use of new feature product cards =

Just activate meta box, select **card type product** and save draft. 4 new fields will appear and you'll be able to set your product card.

= The plugin generates a lot of images (different sizes) =

I can be a problem when you work with HD and/or a lot of images.

**How do I use the custom fields option?**

Basically you provide your custom field keys in plugin option page and then it will grab datas.

= How do I use gallery cards ? =

Just use the WordPress media manager to set a WordPress Gallery and the plugin will grab the first 4 images to set the gallery card. You have to use the shortcode [gallery] to enjoy this feature ( that's what the WordPress media manager does, it inserts the shortcode [gallery]).

**Please avoid using images heavier than 1 MB.**

= I get a fatal error ! =

`Call to undefined function cmb_metabox_form()` >> if you get this it's not due to the plugin it comes from another plugin or a theme using the same framework for meta boxes but in a very old version.
So do not yell at me ^^


/**************
*			  *
* En Français *
* 			  *
************/

= Problème avec Instagram =
C'est un problème connu. Cela vient d'Instagram lui-même qui préfère que ses utilisateurs partagent les photos chez lui plutôt que sur Twitter. Instagram a supprimé ses Twitter Cards.

= Le plugin marche mais je n'obtiens pas de Twitter Cards dans mes tweets =

1. Assurez-vous bien d'avoir rempli correctement les champs dans la page d'options suivant <a href="https://dev.twitter.com/docs/cards" title="Twitter cards documentation">la documentation Twitter</a>
2. Assurez-vous bien d'avoir <a href="https://dev.twitter.com/docs/cards/validation/validator" title="Twitter cards formulaire de validation">soumis votre site à Twitter</a>
3. Attendez la réponse de Twitter (un mail qui vous indique que votre site a été approuvé)
4. Attention avec le fichier robots.txt, vous devez autoriser le robot de Twitter à crawler votre site:

`
	User-agent: Twitterbot
	Disallow:
`

Si cela ne marche toujours pas SVP ouvrez un topic sur le support.

= Utilisez la nouvelle card product =

Activez la meta box et sélectionnez **le type product** pour la card. Sauvez le brouillon et 4 champs apparaîtront pour mettre en place la card product.

= Le plugin génère beaucoup trop de fichiers images = 

Cela peut poser problème si vous travaillez avec de la HD et/ou beaucoup d'images. Vous pouvez donc utiliser un plugin qui va effacer les fichiers images non utilisés.

= Comment utiliser l'option custom fields? =

Il suffit de renseigner les clés de vos custom fields en page d'option et le plugin s'occupera de récupérer les datas correspondantes.

= Comment mettre en place des cards galerie ? =

Utiliser le gestionnaire de médias WordPress pour créer une galerie WordPress et le plugin prendra les 4 premières pour constituer la card galerie.
Vous devez utiliser le shortcode [gallery] pour obtenir une card galerie (c'est ce que fait le gestionnaire de médias, il insère le shortcode [gallery]).

*SVP évitez d'utliser des images de plus d'1 MB*.

= Hey j'ai une fatal error ! =

`Call to undefined function cmb_metabox_form()` : ça vient d'un thème ou d'un plugin qui use du même framework pour ses meta boxes mais dans une version très ancienne, donc ne me criez pas dessus ^^


== Screenshots ==
1. admin
2. confirmation mail
3. meta box
4. player cards validator ( I did not apply for player cards cause I do not have SSL )
5. gallery cards validator
6. preview in meta box


== Other notes ==

= 4.3 brings new filter for your convenience if you're a developer =
* `jm_tc_get_excerpt`
* `jm_tc_image_source`
* `jm_tc_card_type`

== Here is a snippet you can use with new filters ==

**Get native excerpt, some themes use them **

	`add_filter('jm_tc_get_excerpt','_jm_tc_modify_excerpt');
	function _jm_tc_modify_excerpt() {
	    global $post;
		return get_excerpt_from_far_far_away($post->ID);
	}

	function get_excerpt_from_far_far_away( $post_id )
	{
		global $wpdb;
		$query = 'SELECT post_excerpt FROM '. $wpdb->posts .' WHERE ID = '. $post_id .' LIMIT 1';
		$result = $wpdb->get_results($query, ARRAY_A);
		$post_excerpt = $result[0]['post_excerpt'];
		return $post_excerpt;
	}`

**Hack source image e.g if you use relative paths**

	`add_filter('jm_tc_image_source', '_jm_tc_relative_paths');
	function _jm_tc_relative_paths($content) {
		return trailingslashit( home_url() ).$content;
	}`

**BE CAREFUL WITH THIS! DO NOT USE IF YOU DO NOT KNOW WHAT YOU ARE DOING, YOU CAN BREAK YOUR CARDS WITH THIS !!!**


== There are a lot of new filters since 5.2 ==

Meant to help developers only. You'll have to code to use them.

== Changelog ==


= 5.3.4 =
* 12 Aug 2014
* bugfixes
* reorganize folders
* globalize options
* more hide with metabox js. In fact some parts such as img box are not needed for some card types (ex: gallery cards)


= 5.3.3 =
* 04 Aug 2014
* Change file names for classes
* Bugfixes and security enhancements


= 5.3.2 =
* 26 July 2014
* Add support for SSL

= 5.3.0 =
* 18 July 2014
* Fix notices with new super crop option
* Delete useless img for menu icon and use dashicon instead
* Delete metabox img size option, it will reduce the bloat. In fact it used to produce A LOT of files. 
* While it provided some flexibility (to a certain extent) users were not so happy with this huge amount of img files
* To set img size, set it in option page, if you want to change, change the value in option page (img section) and use regenerate thumbnails plugin. Plus it reduces the amount of options to set per each post.
* Add js to meta box so users do not have to scroll down too much (you get only the option you need for each card type)
* Add paypal donation form in about section (only if you want to help me to keep this free) instead of wishlist people do not want to use ^^

= 5.2.9 =
* 13 July 2014
* Add new crop options for WordPress 3.9++, it allows you to make fine crops. You can define crop x (in width) and crop y ( in height)
* Indeed only works with latest versions of WordPress, later than 3.9


= 5.2.8 =
* 04 July 2014
* fix bug with NGG (Next Gen Gallery) in post edit
* better init for markup
* remove unecessary class extends
* bugfixes in admin

= 5.2.7 =
* 22 June 2014
* Small fix: HTML meta name og does not exists ! Now the plugin uses meta property

= 5.2.6 =
* 14 June 2014
* add option to import/export options quickly, this will let you quickly export/import settings in JSON format
* could save a lot of time especially in multisite mod
* I decided to add this feature instead of adding some network-wide options that could be tricky
* Fix missing translation for yes/no options
* Fix some bug in preview
* Remove loop through network in uninstall because it doesn't scale actually 

= 5.2.5 =
* 7 June 2014
* Fix preview with undefined index (new option open graph)
* Add debug method in utilities

= 5.2.4 =
* 6 June 2014
* Suppport for Open Graph
* Improve multisite compatibility (default options, uninstall)
* Fix some mistakes in spanish
* Fix notices regarding default options when activating

= 5.2.3 =
* 19 May 2014
* Fix issue when shortcode is at the top of posts thanks to <a href="https://github.com/jrthib">jrthib</a>
* No more backward compatibility before WP 3.6, plugin won't break but gallery card won't work for these old old versions of WP
* Rebuild preview because of the bug with overflow and replace it with a visual preview
* Be careful this is just a hint, this shows how your card could look not the real result with validator
* To do : add the js to metabox and improve multisite compatibility

= 5.2.2 =
* 11 May 2014
* Fix wrong post meta key for player stream
* Fix robots.txt function 
* Add tabs to admin pages (menu on top for better UX with admin nav)

= 5.2.1 =
* 07 May 2014
* Skip () for classes because we do not need it, actually no argument in constructor
* Removes notices, just uncheck option cards in WP SEO if enabled on plugins_loaded (soft way)
* Add app card type to meta box, add country field for meta country application not available on the US app store
* Add spanish translation for plugin & documentation so now full spanish version, huge thanks to Andrew Kurtis from WebHostingHub (http://www.webhostinghub.com/)

= 5.2.0 =
* 03 May 2014
* Add confirmation message for option page when settings are saved
* Add translation in Spanish for documentation
* Fix bug with capability name
* Add 2nd footage, a video for troubleshooting
* Re-add preview feature, use only PHP for this
* Fix bug with preview WP SEO
* Add 3rd footage : multi-author video tutorial
* Update framework cmb
* Fix the issue with strip_shortcodes() not working

= 5.1.9 =
* 21 Apr 2014
* Fix fallback All In One SEO title
* Put the card type selected in admin option page as default setting for meta box because it so a pain to select it on each post when meta box is enabled ^^
* Note that if you want to change card type for a particular post you'll need to use this select
* Everything that can be default is now set so even you do not have to set it if you do not want to
* Will save your time !
* Fix bug with get_post_meta and custom fields
* Fix escaping quotes and stuffs like this with WP SEO by Yoast desc and title
* Rebuild French Translation
* Add tutorial menu with videos explaining how to use the plugin

= 5.1.8 =
* 16 Apr 2014
* Fix bug with SEO plugins detection
* Fix bug with older versions of WorPress where wp_enqueue_media() does not exist (just not use it for those installations)
* Add src parameter to all meta image, this should make the Twitterbot treat the image as a unique URL and re-fetches the image
* Fix PHP 5.4++ issue with static method called non statistically

= 5.1.6 =
* 14 Apr 2014
* Fix PHP warning when calling static method
* Make it compatible with older versions of PHP
* it's a small fix and it can help users running PHP under 5.3 
* Fix super weird bug with PHP_INT_MAX and jetpack (jetpack menu was hidden)
* Some users reported errors on update and it was due to the fact they're using old versions of PHP under 5.3
* The fix can be done in the next update but I encourage you to migrate from PHP 5.2 to 5.3 in any case
* Improve User interface by splitting admin into pages : only important settings, the plugin does a lot of checking on its own
* Rebuild the entire code for maintenance
* Use now a robust framework for metaboxes, this allows some new features such as preview for images and less html markup in PHP files
* 2 metaboxes in post edit now, one for images and one for other markup
* Thanks a lot to the beta tester who have tested this new version and reported bugs and so helped me a lot with this new version

= 1.0 =
* 5 dec 2012
* Initial release
* thanks GregLone for his great help regarding optimization

== Upgrade notice ==
Nothing
= 1.0 =


