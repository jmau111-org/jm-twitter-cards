<?php 
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div class="wrap">
<h2>JM Twitter Cards : <?php echo esc_html( get_admin_page_title() ); ?></h2>

<?php echo JM_TC_Tabs::admin_tabs();?>

<p><?php _e('Updated','jm-tc-doc');?> : 12/04/14</p>
<div id="menu"> <h3><?php _e('Table of content','jm-tc-doc');?></h3>
<ul> <li><a href="#3w"><?php echo __('What are Twitter Cards?','jm-tc-doc');?></a></li>
<li><a href="#getcards"><?php echo __('How to get Twitter Cards','jm-tc-doc');?></a></li>
<li> <a href="#types"><?php _e('Different type of cards','jm-tc-doc');?></a>
<ul> <li><a href="#summarycards"><?php echo __('Summary cards','jm-tc-doc');?></a></li>
<li><a href="#summarylargecards"><?php echo __('Photo cards','jm-tc-doc');?></a></li>
<li><a href="#photocards"><?php echo __('Photo cards','jm-tc-doc');?></a></li>
<li><a href="#productcards"><?php echo __('Product cards','jm-tc-doc');?></a></li>
<li><a href="#gallerycards"><?php echo __('Gallery cards','jm-tc-doc');?></a></li>
<li><a href="#playercards"><?php echo __('Player cards','jm-tc-doc');?></a></li> </ul> </li>
<li><a href="#deeplinking"><?php echo __('App install & Deep Linking','jm-tc-doc');?></a></li> <li><a href="#analytics"><?php _e('Analytics','jm-tc-doc');?></a></li>
<li> <a href="#use"><?php echo __('Common Issues','jm-tc-doc');?></a>
<ul> <li><a href="#faq-crawl"><?php _e('Twitter Card Validator does not work !','jm-tc-doc');?></a></li>
<li><a href="#faq-image"><?php echo __('I do not see images in tweets !','jm-tc-doc');?></a></li>
<li><a href="#faq-cf"><?php echo __('How do I use the custom fields option?','jm-tc-doc');?></a></li>
<li><a href="#faq-mb-option"><?php echo __('I do not see all options in meta box','jm-tc-doc');?></a></ul> </li>
<li> <a href="#settings"><?php echo __('Plugin settings','jm-tc-doc');?></a>
<ul> <li><a href="#general"><?php echo __('General','jm-tc-doc');?></a></li>
<li><a href="#seo"><?php echo __('SEO','jm-tc-doc');?></a></li>
<li><a href="#images"><?php echo __('Images','jm-tc-doc');?></a></li>
<li><a href="#pagehome"><?php echo __('Home settings','jm-tc-doc');?></a></li>
<li><a href="#metabox"><?php echo __('Meta Box settings','jm-tc-doc');?></a></li>
<li><a href="#extrasettings"><?php _e('Extrasettings','jm-tc-doc');?></a></li> </ul> </li>
<li><a href="#sources"><?php _e('Sources and Credits','jm-tc-doc');?></a></li> </ul> </div>
<h3 id="3w"><?php echo __('What are Twitter Cards?','jm-tc-doc');?></h3> <p><a href="https://dev.twitter.com/docs/cards/getting-started"><?php _e('To GET STARTED:','jm-tc-doc');?></a></p>
<blockquote class="quote-doc"> <?php _e('Twitter cards make it possible for you to attach media experiences to Tweets that link to your content. Simply add a few lines of HTML to your webpages, and users who Tweet links to your content will have a "card" added to the Tweet that is visible to all of their followers.','jm-tc-doc');?> </blockquote>
<h3 id="getcards"><?php echo __('How to get Twitter Cards','jm-tc-doc');?></h3>
<p><?php _e('Once you have added the specific markup with plugin <strong>do not forget to validate your website on dev.twitter :','jm-tc-doc');?></strong></p>
<ul class="jm-tools"> <li><a class="jm-preview" title="Twitter Cards Preview Tool" href= "https://dev.twitter.com/docs/cards/validation/validator" rel="nofollow" target= "_blank"><?php _e('Preview tool','jm-tc-doc');?></a></li>
<li><a class="jm-valid-card" title="Twitter Cards Application Form" href= "https://dev.twitter.com/docs/cards/validation/validator" rel="nofollow" target= "_blank"><?php _e('Validation form','jm-tc-doc');?></a></li> </ul>
<h3 id="types"><?php _e('Different types of cards','jm-tc-doc');?></h3>
<h3 id="summarycards"><?php echo __('Summary cards','jm-tc-doc');?></h3>
<img src= "https://dev.twitter.com/sites/default/files/images_documentation/web_summary_1.png" alt="" />
<h3 id="summarlargecards"><?php _e('Summary Large Image cards','jm-tc-doc');?></h3>
<img src= "https://dev.twitter.com/sites/default/files/images_documentation/web_summary_large.png" alt="" />
<p><?php _e('Almost the same card as Summary Card but with a large image. It is nice, isn\'t it?','jm-tc-doc');?></p>
<p><?php _e('This card requires <strong>a minimum width of 280, and a minimum height of 150</strong>, the same requirement as Photo cards, that is why it\'s the smallest size used by the plugin.','jm-tc-doc');?></p>
<p><?php _e('The image <strong>must not be more than 1mb in size</strong> that is why the custom Meta Box includes a checking system that assess your image size and display the result on each post edit. If you do not use cutom meta box be sure to upload image smaller than 1mb in size. If your image is heavier, your card will not break because meta image is not required for summary and summary large image cards but no image will be displayed in your tweets.','jm-tc-doc');?></p>
<h3 id="photocards"><?php echo __('Photo cards','jm-tc-doc');?></h3>
<img src= "https://dev.twitter.com/sites/default/files/images_documentation/web_photo.png" alt= "" />
<blockquote class="quote-doc"> <p><?php _e('The Photo Card puts the image front and center in the Tweet:','jm-tc-doc');?></p>
<p><?php _e('To define a photo card experience, set your card type to "photo" and provide a twitter:image. Twitter will resize images, maintaining original aspect ratio to fit the following sizes:','jm-tc-doc');?></p>
<ul class="jm-doc-photocard"> <li><strong>Web</strong>: <?php _e('maximum height of 375px, maximum width of 435px','jm-tc-doc');?></li>
<li><?php _e('<strong>Mobile (non-retina displays)</strong>: maximum height of 375px, maximum width of 280px','jm-tc-doc');?></li>
<li><?php _e('<strong>Mobile (retina displays)</strong>: maximum height of 750px, maximum width of 560px','jm-tc-doc');?></li>
<li><?php _e('Twitter will not create a photo card unless the twitter:image is of a minimum size of 280px wide by 150px tall. Images will not be cropped unless they have an exceptional aspect ratio','jm-tc-doc');?></li> </ul> </blockquote>
<h3 id="productcards"><?php echo __('Product cards','jm-tc-doc');?></h3>
<img src= "https://dev.twitter.com/sites/default/files/images_documentation/web_product.png" alt= "" />
<p><?php _e('The Product Card is a great way to represent retail items on Twitter, and to drive sales. This Card type is designed to showcase your products via an image, a description, and allow you to highlight two other key details about your product.','jm-tc-doc');?></p>
<p><?php _e('These fields are strings and can be used to show the price, list availability, list sizes, etc. This will require adding some new markup tags to your pages, which we will cover below.','jm-tc-doc');?></p>
<p><?php _e('Note: The product card requires an image of size 160 x 160 or greater. It prefers a square image but we can crop/resize oddly shaped images to fit as long as both dimensions are greater than or equal to 160 pixels.','jm-tc-doc');?></p>
<p><?php _e('To activate this mode you have to activate the <a href="#metabox">custom meta box</a>.','jm-tc-doc');?></p>
<h3 id="gallerycards"><?php echo __('Gallery cards','jm-tc-doc');?></h3>
<img src= "https://dev.twitter.com/sites/default/files/images_documentation/web_gallery.png" alt= "" />
<blockquote class="quote-doc"> <?php _e('The Gallery Card allows you to represent collections of photos within a Tweet. This Card type is designed to let the user know that there\'s more than just a single image at the URL shared, but rather a gallery of related images. You can specify up to 4 different images to show in the gallery card via the twitter:image[0-3] tags. You can also provide attribution to the photographer of the gallery by specifying the value of the twitter:creator tag.','jm-tc-doc');?> </blockquote>
<h3 id="playercards"><?php echo __('Player cards','jm-tc-doc');?></h3>
<img src= "https://dev.twitter.com/sites/default/files/images_documentation/web_player2.png" alt= "" />
<blockquote class="quote-doc"> <?php _e('A few simple rules for Player Cards','jm-tc-doc');?>
<p><strong><?php _e('Do:','jm-tc-doc');?></strong></p>
<ul> <li><?php _e('Build a responsive and equivalent experience that works within all Twitter clients (including twitter.com, mobile.twitter.com, Twitter for iPhone and Twitter for Android). Cards that do not work in all Twitter clients listed will not be approved.','jm-tc-doc');?></li>
<li><?php _e('Test your experience on the native browsers of Twitter clients before submitting for approval.','jm-tc-doc');?></li>
<li><?php _e('Provide a raw stream to video and audio content when possible.','jm-tc-doc');?></li>
<li><?php _e('Mark your Card as &#226;&#8364;&#732;true&#226;&#8364;&#8482; for sensitive media if such media could be displayed.','jm-tc-doc');?></li>
<li><?php _e('Default to &#226;&#8364;&#732;sound off&#226;&#8364;&#8482; for videos that automatically play content. Please note that videos greater than 10 seconds in length must not automatically play content.','jm-tc-doc');?></li>
<li><?php _e('If the media player includes sharing to third-party networks, you must provide an option to share to Twitter.','jm-tc-doc');?></li>
<li><?php _e('Use HTTPS for your iframe, stream, and all assets within your Card.','jm-tc-doc');?></li>
<li><?php _e('Use wmode=opaque if utilizing Flash for the twitter.com experience, so that the player renders at the correct z-index.','jm-tc-doc');?></li>
<li><?php _e('Link to a HTML page which falls back to mobile friendly content in case Flash is not available.','jm-tc-doc');?></li> </ul>
<p><strong><?php _e('Do not:','jm-tc-doc');?></strong></p>
<ul> <li><?php _e('Circumvent the intended use of the Card. Player Cards are reserved for linear audio and video consumption only.','jm-tc-doc');?></li>
<li><?php _e('Automatically play content that is greater than 10 seconds in length.','jm-tc-doc');?></li>
<li><?php _e('Require users to sign-in to your experience.','jm-tc-doc');?></li>
<li><?php _e('Attach additional interactivity outside the video or audio player (e.g., non-standard buttons or banners).','jm-tc-doc');?></li>
<li><?php _e('Build end-to-end interactive experiences inside the video or audio player unrelated to Player Card content, such as the following: purchasing, gaming, polling, messaging, and data entry. Instead, build these interactive experiences with our other Card types or enhance your Player Card content with links to your website or mobile application.','jm-tc-doc');?></li>
<li><?php _e('Generate active mixed content browser warnings at any point during the audio or video experience, either on load or during play. For more information, see the','jm-tc-doc');?> <a href= "https://dev.twitter.com/docs/cards/troubleshooting#Active_Mixed_ContentSSL_Issue"><?php _e('Troubleshooting Guide','jm-tc-doc');?></a>.</li>
<li><?php _e('Set twitter:player to point directly at a .swf movie file.','jm-tc-doc');?></li> </ul> </blockquote><p><?php _e('Also be sure to check all the following point before <a href= "https://dev.twitter.com/docs/cards/validation/validator">asking for approval</a>. Here are the common issues with player cards:','jm-tc-doc');?></p>
<blockquote class="quote-doc"> <ol> <li><?php _e('The HTTPS lock is showing active mixed content warnings','jm-tc-doc');?></li>
<li><?php _e('The content must have stop or pause controls','jm-tc-doc');?></li>
<li><?php _e('In Android/iOS browsers the experience must fall back gracefully (sized for mobile viewport, no broken Flash embeds, etc)','jm-tc-doc');?></li>
<li><?php _e('Content greater than 10 seconds in length must not play automatically','jm-tc-doc');?></li>
<li><?php _e('The content must not require sign-in','jm-tc-doc');?></li>
<li><?php _e('The content must not be entirely an ad','jm-tc-doc');?></li>
<li><?php _e('The player URL must not point directly to a .swf','jm-tc-doc');?></li>
<li><?php _e('Check if the player\'s z-index causes the content to overlap the page header','jm-tc-doc');?></li>
<li><?php _e('If the browser asks you whether you want to display insecure assets, you have an https problem','jm-tc-doc');?></li>
<li><?php _e('Make sure the image is at least 68,600 pixels (a 262x262 square image, or a 350x196 16:9 image) or bigger','jm-tc-doc');?></li>
<li><?php _e('The player URL is not HTTPS (did we mention this before?)','jm-tc-doc');?></li> </ol> </blockquote>  <h3 id="deeplinking"><?php echo __('App install & Deep Linking','jm-tc-doc');?></h3> <br /> <blockquote class="quote-doc">
<h3><?php _e('App Installs','jm-tc-doc');?></h3>

<?php _e('By adding these new footer tags to your markup, you\'ll be able to specify downloads for users who\'ve not yet installed your app on their device. This will work across iPhone, iPad, and Android (Google Play). Please note that if you have an iPhone app, but no iPad-optimized app, you should include the iPhone app id, name, and url for both iPhone and iPad-related tags. When no value is provided, the Cards will simply render a "View on web" link pointing to website of the card. Below is an example of what the prompt will look like if the user does not have the app installed:','jm-tc-doc');?> </blockquote><br />
<img src="https://dev.twitter.com/sites/default/files/images_documentation/blog-image_1.png" alt=""/><br /><br /> <blockquote class="quote-doc">
<h3><?php _e('Deep-Linking','jm-tc-doc');?></h3>

<?php _e('If a user does have the application installed, you can specify a deep-link into the correlated resource within your own application. When a user clicks on the "Open in app" tap target, Twitter will send that user out into your application. This value is specified in the "twitter:app:name:(iphone|ipad|googleplay)" tags. The app url should be your app-specific URL Scheme (requires registration within your app) that launches your app on the appropriate client.','jm-tc-doc');?> </blockquote><br /> <img src="https://dev.twitter.com/sites/default/files/images_documentation/blog-image_2.png" alt=""/><br />
<br />
<blockquote class="quote-doc">
<?php _e('Here is example markup to enable app install prompts and deep-linking, and note that this metadata can be appended to any card type. Additionally the App ID value for iPhone and iPad should be the numeric App Store ID, while the App ID for Google Play should be the Android package name:','jm-tc-doc');?> 
</blockquote>	
<pre>
&lt;meta name="twitter:app:name:iphone" content="Example App"/&gt;
&lt;meta name="twitter:app:id:iphone" content="306934135"/&gt;
&lt;meta name="twitter:app:url:iphone" content="example://action/5149e249222"/&gt;
&lt;meta name="twitter:app:name:ipad" content="Example App"/&gt;
&lt;meta name="twitter:app:id:ipad" content="306934135"/&gt;
&lt;meta name="twitter:app:url:ipad" content="example://action/5149e249222"/&gt;
&lt;meta name="twitter:app:name:googleplay" content="Example App"/&gt;
&lt;meta name="twitter:app:id:googleplay" content="com.example.app"/&gt;
&lt;meta name="twitter:app:url:googleplay" content="http://example.com/action/5149e249222"/&gt;
</pre>  <p><?php _e('Source','jm-tc-doc');?> : <a href="https://dev.twitter.com/docs/cards/app-installs-and-deep-linking"><?php _e('App Installs and Deep-Linking','jm-tc-doc');?></a></p>
<h3 id="analytics"><?php _e('Analytics','jm-tc-doc');?></h3>
<blockquote class="quote-doc"> <?php _e('Twitter Cards allow you to attach rich media experiences to Tweets about your content; Twitter Card analytics gives you related insights into how your content is being shared on Twitter. Through personalized data and best practices,Twitter Card analytics reveals how you can improve key metrics such as URL clicks, app install attempts and Retweets.','jm-tc-doc');?> </blockquote>
<p><?php _e('This new feature allows a fine set up for Twitter Cards Experience. You can analyse your tweets and see what works and what fails. You can also check which card type fits your content.<br /> In a nutshell you can improve your Twitter engagement, monitor clicks and retweets and see what is the best experience for your followers. <strong>AMAZING</strong>!','jm-tc-doc');?></p>
<p><a href="https://dev.twitter.com/docs/cards/analytics"><?php _e('See on dev.twitter.com','jm-tc-doc');?></a></p>
<p class="sub-bold"><?php _e('Be careful, it is not available in all countries ! Not yet','jm-tc-doc');?></p>
<h3 id="use"><?php echo __('Common Issues','jm-tc-doc');?></h3>
<p><?php _e('If you have any issue, the first thing you have to do is to go to <a class= "button button-secondary" href= "https://dev.twitter.com/docs/cards/troubleshooting">Twitter Cards troubleshooting</a>.','jm-tc-doc');?></p>
<h3 id="faq-crawl"><?php _e('Twitter card validator does not work with my site !','jm-tc-doc');?></h3>
<p><?php _e('Don\'t panic ! It\'s probably you did not set your robots.txt file according to Twitter\'s recommandation','jm-tc-doc');?></p>
<p><?php _e('This file is used to set specific rules for web crawlers.','jm-tc-doc');?></p>
<p><?php _e('Twitter\'s bot needs to have access to your website to fetch your content, so just insert this in the file ::','jm-tc-doc');?></p> <pre>
User-agent: Twitterbot
Disallow:
</pre>
<h3 id="faq-image"><?php echo __('I do not see images in tweets !','jm-tc-doc');?></h3>
<p><?php _e('Check that image does not exceed 1mb in size and/or is compatible with Twitter requirements.','jm-tc-doc');?></p>
<h3 id="faq-cf"><?php echo __('How do I use the custom fields option?','jm-tc-doc');?></h3>
<p><?php _e('Basically you provide your custom field keys in plugin option page and then it will grab datas.','jm-tc-doc');?></p>
<h3 id="faq-mb-option"><?php echo __('I do not see all options in meta box','jm-tc-doc');?></h3>
<p><?php _e('Just select card type. You will see additional fields if they exist.','jm-tc-doc');?></p>
<h4 id="settings"><?php _e('Plugin options','jm-tc-doc');?></h4>
<p><?php _e('In 3.3.6 Plugin UI has been renewed with some flat design and in a simpler way. Only option and all explanations in documentation. All sections includind meta box on post edit has now a link to documentation.','jm-tc-doc');?></p>
<h3 id="general"><?php echo __('General','jm-tc-doc');?></h3>
<p><?php _e('This is the first section. You can set which type of card will be the default type used in your website. You must define twitter card creator (username) and twitter card site (username). If they are the same just enter username twice.','jm-tc-doc');?></p>
<p><?php _e('You can also set how many words the plugin has to grab to set a description by default. This allows to avoid empty twitter meta description which often breaks twitter cards.','jm-tc-doc');?></p>
<p><?php _e('Last setting is for multi-author blogs. It allows your authors to get an additional field in their profile. They can provide their Twitter Account. This is meant to improve authorship. If they publish a post, their twitter usernames will be set as twitter meta creator.','jm-tc-doc');?></p>
<p><?php _e('Since 3.3.4 there is a new setting that allows you to use your own field for Twitter\'s field in profiles. A lot of theme have this feature so you probably want to keep only one field.','jm-tc-doc');?></p>
<p><?php _e('Be careful the key you have to provide MUST BE the key your theme use to set the Twitter\'s field in profile ! In addition to this, THE VALUE MUST BE A USERNAME not a URL such as http://twitter.com/user <strong>it could break the cards !</strong>','jm-tc-doc');?></p>
<p class="sub-bold"><?php _e('If you experience any issue or if you mess with this option just recover with the key :','jm-tc-doc');?><br /> <strong>jm_tc_twitter</strong></p>
<h3 id="seo"><?php echo __('SEO','jm-tc-doc');?></h3>
<p><?php _e('WordPress is great but we often need to add some SEO plugins such as <a href= "http://yoast.com/wordpress/seo/">WP SEO by Yoast</a> or <a href= "http://wordpress.org/plugins/all-in-one-seo-pack/">All In One SEO</a>. These plugins are very very popular and you can find them in a lot of WP installations. JM Twitter Cards checks if one of the two plugins is activated and grabs meta title and description you have set. This allows you not to waste your time writting things twice for each post. If you forget to fulfill SEO fields do not panic, there are fallback (post title and the first words of your post as description). Do not forget to choose how many words the plugin has to grab for twitter meta description(max 200).','jm-tc-doc');?></p>
<p><?php _e('And if you do not use one of this two plugins you can use your own custom fields instead. Just provide meta keys (advanced users).','jm-tc-doc');?></p>
<h3 id="images"><?php echo __('Images','jm-tc-doc');?></h3>
<p><?php _e('Use the dropdown menu to choose which size you want to set by default (you can override this with the metabox fields). You can now choose (3.3.0) to force crop or not. Do not forget to enter a fallback image in case no option is set. You can also set twitter image with and height to get a better control of cards display (only for photo and product cards).','jm-tc-doc');?></p>
<h3 id="pagehome"><?php echo __('Home settings','jm-tc-doc');?></h3>
<p><?php _e('If home page is posts page (which is often the case), this setting allows you to define a Twitter Card title and description. This prevent from getting the datas from the first post in the loop.','jm-tc-doc');?></p>
<h3 id="metabox"><?php _e('Meta Box','jm-tc-doc');?></h3>
<p><?php _e('This is one of the best features of the plugin. It allows to customize your cards for each post and you get extra features...','jm-tc-doc');?></p>
<p><?php _e('Once it\'s activated you\'ll get a custom meta box in your post edit :','jm-tc-doc');?></p>
<p><?php _e('With version 3.3.1 the plugin allows you to dismiss metabox if you do not want or need to use it. This introduces more flexibility for users who do not need to use metabox on each post. If option is set to "yes, please deactivate it", the datas will be set by general options.','jm-tc-doc');?></p>
<p><?php _e('To use new feature (in 3.3.6) Gallery cards, just use the WordPress Gallery System','jm-tc-doc');?></p>
<p><?php _e('This is meant to let the user know that there is more than just a single image at the URL shared, but rather a gallery of related images. So these images have to be part of a WordPress gallery. Please ensure you use the shortcode [gallery] in your post to enjoy Gallery cards','jm-tc-doc');?></p>
<h3 id="extrasettings"><?php _e('Extra settings','jm-tc-doc');?></h3>
<p><?php _e('If you select photo, or summary large image or product cards then (<strong>save draft</strong> if JavaScript is disabled) you\'ll see additional fields you can set such as :','jm-tc-doc');?></p>
<img src=""/>
<ul> <li><?php _e('<strong>Retina display</strong>: Be careful with Retina displays, image must be tall enough. Also make sure your image is not heavier than 1 MB if used for summary large cards','jm-tc-doc');?></li>
<li><?php _e('<strong>Image options</strong> : This is optional but some users wanted alternatives for featured image','jm-tc-doc');?></li>
<li><?php _e('<strong>Product card options</strong> : You can set 2 key details for your product (e.g price, size, etc)','jm-tc-doc');?></li> </ul>
<div class="footer"> <h4 id="sources"><?php _e('Sources','jm-tc-doc');?></h4>
<ul> <li><a href="https://dev.twitter.com/docs/cards"><?php _e('Twitter cards documentation','jm-tc-doc');?></a></li>
<li><a href="https://dev.twitter.com/docs/cards/markup-reference"><?php _e('Meta tag references','jm-tc-doc');?></a></li> </ul>
<p class="right pa1">Julien Maury, 16/02/2014</p>
<p class="right pa1"><a href="#menu"><?php _e('Go To Table of Contents','jm-tc-doc');?></a></p> </div>

</div>