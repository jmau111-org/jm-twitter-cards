<?php
if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

?>
<div class="wrap">
    <h2>JM Twitter Cards : <?php echo esc_html(get_admin_page_title()); ?></h2>

    <?php echo JM_TC_Tabs::admin_tabs(); ?>

    <p><?php _e('Updated', JM_TC_DOC_TEXTDOMAIN); ?> : 12/10/2014</p>

    <div id="menu" class="tc-doc-menu">
        <ul class="menu">
            <li><a href="#3w"><?php echo __('What are Twitter Cards?', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
            <li><a href="#getcards"><?php echo __('How to get Twitter Cards', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
            <li><a href="#types"><?php _e('Different type of cards', JM_TC_DOC_TEXTDOMAIN); ?></a>
                <ul>
                    <li><a href="#summarycards"><?php echo __('Summary cards', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
                    <li><a href="#summarylargecards"><?php echo __('Photo cards', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
                    <li><a href="#photocards"><?php echo __('Photo cards', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
                    <li><a href="#productcards"><?php echo __('Product cards', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
                    <li><a href="#gallerycards"><?php echo __('Gallery cards', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
                    <li><a href="#playercards"><?php echo __('Player cards', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
                </ul>
            </li>
            <li><a href="#deeplinking"><?php echo __('App install & Deep Linking', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
            <li><a href="#analytics"><?php _e('Analytics', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
            <li><a href="#use"><?php echo __('Common Issues', JM_TC_DOC_TEXTDOMAIN); ?></a>
                <ul>
                    <li>
                        <a href="#faq-crawl"><?php _e('Twitter Card Validator does not work !', JM_TC_DOC_TEXTDOMAIN); ?></a>
                    </li>
                    <li>
                        <a href="#faq-image"><?php echo __('I do not see images in tweets !', JM_TC_DOC_TEXTDOMAIN); ?></a>
                    </li>
                    <li>
                        <a href="#faq-cf"><?php echo __('How do I use the custom fields option?', JM_TC_DOC_TEXTDOMAIN); ?></a>
                    </li>
                    <li>
                        <a href="#faq-mb-option"><?php echo __('I do not see all options in meta box', JM_TC_DOC_TEXTDOMAIN); ?></a>
                </ul>
            </li>
            <li><a href="#settings"><?php echo __('Plugin settings', JM_TC_DOC_TEXTDOMAIN); ?></a>
                <ul>
                    <li><a href="#general"><?php echo __('General', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
                    <li><a href="#seo"><?php echo __('SEO', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
                    <li><a href="#images"><?php echo __('Images', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
                    <li><a href="#pagehome"><?php echo __('Home settings', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
                    <li><a href="#metabox"><?php echo __('Meta Box settings', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
                    <li><a href="#extrasettings"><?php _e('Extrasettings', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
                </ul>
            </li>
            <li><a href="#sources"><?php _e('Sources and Credits', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
        </ul>
    </div>
    <h3 id="3w"><?php echo __('What are Twitter Cards?', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p>
        <a href="https://dev.twitter.com/docs/cards/getting-started"><?php _e('To GET STARTED:', JM_TC_DOC_TEXTDOMAIN); ?></a>
    </p>
    <blockquote
        class="quote-doc"> <?php _e('Twitter cards make it possible for you to attach media experiences to Tweets that link to your content. Simply add a few lines of HTML to your webpages, and users who Tweet links to your content will have a "card" added to the Tweet that is visible to all of their followers.', JM_TC_DOC_TEXTDOMAIN); ?> </blockquote>
    <h3 id="getcards"><?php echo __('How to get Twitter Cards', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p><?php _e('Once you have added the specific markup with plugin <strong>do not forget to validate your website on dev.twitter :', JM_TC_DOC_TEXTDOMAIN); ?></strong></p>
    <ul class="jm-tools">
        <li><a class="jm-preview" title="Twitter Cards Preview Tool" href="https://cards-dev.twitter.com/validator"
               rel="nofollow" target="_blank"><?php _e('Preview tool', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
        <li><a class="jm-valid-card" title="Twitter Cards Application Form"
               href="https://cards-dev.twitter.com/validator" rel="nofollow"
               target="_blank"><?php _e('Validation form', JM_TC_DOC_TEXTDOMAIN); ?></a></li>
    </ul>
    <h3 id="types"><?php _e('Different types of cards', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <h3 id="summarycards"><?php echo __('Summary cards', JM_TC_DOC_TEXTDOMAIN); ?></h3>
    <blockquote class="twitter-tweet" lang="fr"><p>Join us at Twitter <a href="https://twitter.com/Flight">@Flight</a>
            and learn how we can help you build the best mobile apps. October 22nd, in SF. Visit <a
                href="http://t.co/LyCCvuvn2R">http://t.co/LyCCvuvn2R</a></p>&mdash; TwitterDev (@TwitterDev) <a
            href="https://twitter.com/TwitterDev/status/509752513981988864">10 Septembre 2014</a></blockquote>
    <h3 id="summarlargecards"><?php _e('Summary Large Image cards', JM_TC_DOC_TEXTDOMAIN); ?></h3>
    <blockquote class="twitter-tweet" lang="fr"><p>Everything you need to know about the Amazon Fire Phone <a
                href="http://t.co/uULA47di9M">http://t.co/uULA47di9M</a></p>&mdash; TechCrunch (@TechCrunch) <a
            href="https://twitter.com/TechCrunch/status/491955931719434241">23 Juillet 2014</a></blockquote>
    <p><?php _e('Almost the same card as Summary Card but with a large image. It is nice, isn\'t it?', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('This card requires <strong>a minimum width of 280, and a minimum height of 150</strong>, the same requirement as Photo cards, that is why it\'s the smallest size used by the plugin.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('The image <strong>must not be more than 1mb in size</strong> that is why the custom Meta Box includes a checking system that assess your image size and display the result on each post edit. If you do not use cutom meta box be sure to upload image smaller than 1mb in size. If your image is heavier, your card will not break because meta image is not required for summary and summary large image cards but no image will be displayed in your tweets.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <h3 id="photocards"><?php echo __('Photo cards', JM_TC_DOC_TEXTDOMAIN); ?></h3>
    <blockquote class="twitter-tweet" lang="fr"><p>Stunning photo of mountainous landscape and reflections <a
                href="http://t.co/esvsXTy7Tq">http://t.co/esvsXTy7Tq</a></p>&mdash; Flickr (@Flickr) <a
            href="https://twitter.com/Flickr/status/423511451970445312">15 Janvier 2014</a></blockquote>
    <blockquote class="quote-doc">
        <p><?php _e('The Photo Card puts the image front and center in the Tweet:', JM_TC_DOC_TEXTDOMAIN); ?></p>

        <p><?php _e('To define a photo card experience, set your card type to "photo" and provide a twitter:image. Twitter will resize images, maintaining original aspect ratio to fit the following sizes:', JM_TC_DOC_TEXTDOMAIN); ?></p>
        <ul class="jm-doc-photocard">
            <li>
                <strong>Web</strong>: <?php _e('maximum height of 375px, maximum width of 435px', JM_TC_DOC_TEXTDOMAIN); ?>
            </li>
            <li><?php _e('<strong>Mobile (non-retina displays)</strong>: maximum height of 375px, maximum width of 280px', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('<strong>Mobile (retina displays)</strong>: maximum height of 750px, maximum width of 560px', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Twitter will not create a photo card unless the twitter:image is of a minimum size of 280px wide by 150px tall. Images will not be cropped unless they have an exceptional aspect ratio', JM_TC_DOC_TEXTDOMAIN); ?></li>
        </ul>
    </blockquote>
    <h3 id="productcards"><?php echo __('Product cards', JM_TC_DOC_TEXTDOMAIN); ?></h3>
    <blockquote class="twitter-tweet" lang="fr"><p>Today marks 50 years since <a
                href="https://twitter.com/hashtag/The?src=hash">#The</a> Beatles took the US by storm! Listen to
            non-stop Beatles songs on 24/7 Beatles Radio: <a href="http://t.co/SMCaQalY4Y">http://t.co/SMCaQalY4Y</a>
        </p>&mdash; iHeartRadio (@iHeartRadio) <a href="https://twitter.com/iHeartRadio/status/431829482291212288">7
            Février 2014</a></blockquote>
    <p><?php _e('The Product Card is a great way to represent retail items on Twitter, and to drive sales. This Card type is designed to showcase your products via an image, a description, and allow you to highlight two other key details about your product.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('These fields are strings and can be used to show the price, list availability, list sizes, etc. This will require adding some new markup tags to your pages, which we will cover below.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('Note: The product card requires an image of size 160 x 160 or greater. It prefers a square image but we can crop/resize oddly shaped images to fit as long as both dimensions are greater than or equal to 160 pixels.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('To activate this mode you have to activate the <a href="#metabox">custom meta box</a>.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <h3 id="gallerycards"><?php echo __('Gallery cards', JM_TC_DOC_TEXTDOMAIN); ?></h3>
    <blockquote class="twitter-tweet" lang="fr"><p><a
                href="https://twitter.com/hashtag/Calistoga?src=hash">#Calistoga</a>, California - named one of America&#39;s
            Best Small Towns by <a href="https://twitter.com/fodorstravel">@fodorstravel</a> <a
                href="http://t.co/Hb5dwjhZHP">http://t.co/Hb5dwjhZHP</a> <a
                href="https://twitter.com/hashtag/VisitCA?src=hash">#VisitCA</a></p>&mdash; Visit California (@VisitCA)
        <a href="https://twitter.com/VisitCA/status/477565323206336512">13 Juin 2014</a></blockquote>
    <blockquote
        class="quote-doc"> <?php _e('The Gallery Card allows you to represent collections of photos within a Tweet. This Card type is designed to let the user know that there\'s more than just a single image at the URL shared, but rather a gallery of related images. You can specify up to 4 different images to show in the gallery card via the twitter:image[0-3] tags. You can also provide attribution to the photographer of the gallery by specifying the value of the twitter:creator tag.', JM_TC_DOC_TEXTDOMAIN); ?> </blockquote>
    <h3 id="playercards"><?php echo __('Player cards', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <div class="inbl">
        <blockquote class="twitter-tweet" lang="fr"><p>Sometimes I think my days can&#39;t get any more rediculous until
                today. <a href="https://t.co/x8HoullQMT">https://t.co/x8HoullQMT</a></p>&mdash; Gareth Paul Jones (@gpj)
            <a href="https://twitter.com/gpj/status/413394813854040064">18 Décembre 2013</a></blockquote>
    </div>
    <div class="inbl">
        <blockquote class="twitter-tweet" lang="fr"><p><a href="https://twitter.com/hashtag/SCOTD?src=hash">#SCOTD</a>
                is Stockholm, Sweden-based Daniel Rosenholm, aka <a
                    href="https://twitter.com/dubiousquip">@dubiousquip</a> who creates a blend of blues, soul and pop.
                <a href="https://t.co/sVchJg3EgU">https://t.co/sVchJg3EgU</a></p>&mdash; SoundCloud (@SoundCloud) <a
                href="https://twitter.com/SoundCloud/status/459443749769453568">24 Avril 2014</a></blockquote>
    </div>
    <blockquote class="quote-doc"> <?php _e('A few simple rules for Player Cards', JM_TC_DOC_TEXTDOMAIN); ?>
        <p><strong><?php _e('Do:', JM_TC_DOC_TEXTDOMAIN); ?></strong></p>
        <ul>
            <li><?php _e('Build a responsive and equivalent experience that works within all Twitter clients (including twitter.com, mobile.twitter.com, Twitter for iPhone and Twitter for Android). Cards that do not work in all Twitter clients listed will not be approved.', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Test your experience on the native browsers of Twitter clients before submitting for approval.', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Provide a raw stream to video and audio content when possible.', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Mark your Card as &#226;&#8364;&#732;true&#226;&#8364;&#8482; for sensitive media if such media could be displayed.', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Default to &#226;&#8364;&#732;sound off&#226;&#8364;&#8482; for videos that automatically play content. Please note that videos greater than 10 seconds in length must not automatically play content.', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('If the media player includes sharing to third-party networks, you must provide an option to share to Twitter.', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Use HTTPS for your iframe, stream, and all assets within your Card.', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Use wmode=opaque if utilizing Flash for the twitter.com experience, so that the player renders at the correct z-index.', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Link to a HTML page which falls back to mobile friendly content in case Flash is not available.', JM_TC_DOC_TEXTDOMAIN); ?></li>
        </ul>
        <p><strong><?php _e('Do not:', JM_TC_DOC_TEXTDOMAIN); ?></strong></p>
        <ul>
            <li><?php _e('Circumvent the intended use of the Card. Player Cards are reserved for linear audio and video consumption only.', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Automatically play content that is greater than 10 seconds in length.', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Require users to sign-in to your experience.', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Attach additional interactivity outside the video or audio player (e.g., non-standard buttons or banners).', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Build end-to-end interactive experiences inside the video or audio player unrelated to Player Card content, such as the following: purchasing, gaming, polling, messaging, and data entry. Instead, build these interactive experiences with our other Card types or enhance your Player Card content with links to your website or mobile application.', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Generate active mixed content browser warnings at any point during the audio or video experience, either on load or during play. For more information, see the', JM_TC_DOC_TEXTDOMAIN); ?>
                <a href="https://dev.twitter.com/docs/cards/troubleshooting#Active_Mixed_ContentSSL_Issue"><?php _e('Troubleshooting Guide', JM_TC_DOC_TEXTDOMAIN); ?></a>.
            </li>
            <li><?php _e('Set twitter:player to point directly at a .swf movie file.', JM_TC_DOC_TEXTDOMAIN); ?></li>
        </ul>
    </blockquote>
    <p><?php _e('Also be sure to check all the following point before <a href= "https://cards-dev.twitter.com/validator">asking for approval</a>. Here are the common issues with player cards:', JM_TC_DOC_TEXTDOMAIN); ?></p>
    <blockquote class="quote-doc">
        <ol>
            <li><?php _e('The HTTPS lock is showing active mixed content warnings', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('The content must have stop or pause controls', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('In Android/iOS browsers the experience must fall back gracefully (sized for mobile viewport, no broken Flash embeds, etc)', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Content greater than 10 seconds in length must not play automatically', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('The content must not require sign-in', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('The content must not be entirely an ad', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('The player URL must not point directly to a .swf', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Check if the player\'s z-index causes the content to overlap the page header', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('If the browser asks you whether you want to display insecure assets, you have an https problem', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('Make sure the image is at least 68,600 pixels (a 262x262 square image, or a 350x196 16:9 image) or bigger', JM_TC_DOC_TEXTDOMAIN); ?></li>
            <li><?php _e('The player URL is not HTTPS (did we mention this before?)', JM_TC_DOC_TEXTDOMAIN); ?></li>
        </ol>
    </blockquote>
    <h3 id="deeplinking"><?php echo __('App install & Deep Linking', JM_TC_DOC_TEXTDOMAIN); ?></h3> <br/>
    <blockquote class="quote-doc">
        <h3><?php _e('App Installs', JM_TC_DOC_TEXTDOMAIN); ?></h3>

        <?php _e('By adding these new footer tags to your markup, you\'ll be able to specify downloads for users who\'ve not yet installed your app on their device. This will work across iPhone, iPad, and Android (Google Play). Please note that if you have an iPhone app, but no iPad-optimized app, you should include the iPhone app id, name, and url for both iPhone and iPad-related tags. When no value is provided, the Cards will simply render a "View on web" link pointing to website of the card. Below is an example of what the prompt will look like if the user does not have the app installed:', JM_TC_DOC_TEXTDOMAIN); ?>
    </blockquote>
    <br/>
    <img src="<?php echo esc_url(JM_TC_IMG_URL . 'doc/app_install.png'); ?>" width="600" height="400" alt=""/><br/><br/>
    <blockquote class="quote-doc">
        <h3><?php _e('Deep-Linking', JM_TC_DOC_TEXTDOMAIN); ?></h3>
        <?php _e('If a user does have the application installed, you can specify a deep-link into the correlated resource within your own application. When a user clicks on the "Open in app" tap target, Twitter will send that user out into your application. This value is specified in the "twitter:app:name:(iphone|ipad|googleplay)" tags. The app url should be your app-specific URL Scheme (requires registration within your app) that launches your app on the appropriate client.', JM_TC_DOC_TEXTDOMAIN); ?>
    </blockquote>
    <br/> <img src="<?php echo esc_url(JM_TC_IMG_URL . 'doc/deep_linking.png'); ?>" width="600" height="400"
               alt=""/><br/>
    <br/>
    <blockquote class="quote-doc">
        <?php _e('Here is example markup to enable app install prompts and deep-linking, and note that this metadata can be appended to any card type. Additionally the App ID value for iPhone and iPad should be the numeric App Store ID, while the App ID for Google Play should be the Android package name:', JM_TC_DOC_TEXTDOMAIN); ?>
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
</pre>
    <p><?php _e('Source', JM_TC_DOC_TEXTDOMAIN); ?> : <a
            href="https://dev.twitter.com/cards/mobile"><?php _e('App Installs and Deep-Linking', JM_TC_DOC_TEXTDOMAIN); ?></a>
    </p>

    <h3 id="analytics"><?php _e('Analytics', JM_TC_DOC_TEXTDOMAIN); ?></h3>
    <blockquote
        class="quote-doc"> <?php _e('Twitter Cards allow you to attach rich media experiences to Tweets about your content; Twitter Card analytics gives you related insights into how your content is being shared on Twitter. Through personalized data and best practices,Twitter Card analytics reveals how you can improve key metrics such as URL clicks, app install attempts and Retweets.', JM_TC_DOC_TEXTDOMAIN); ?> </blockquote>
    <p><?php _e('This new feature allows a fine set up for Twitter Cards Experience. You can analyse your tweets and see what works and what fails. You can also check which card type fits your content.<br /> In a nutshell you can improve your Twitter engagement, monitor clicks and retweets and see what is the best experience for your followers. <strong>AMAZING</strong>!', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p>
        <a href="https://dev.twitter.com/docs/cards/analytics"><?php _e('See on dev.twitter.com', JM_TC_DOC_TEXTDOMAIN); ?></a>
    </p>

    <p class="sub-bold"><?php _e('Be careful, it is not available in all countries ! Not yet', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <h3 id="use"><?php echo __('Common Issues', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p><?php _e('If you have any issue, the first thing you have to do is to go to <a class= "button button-secondary" href= "https://dev.twitter.com/docs/cards/troubleshooting">Twitter Cards troubleshooting</a>.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <h3 id="faq-crawl"><?php _e('Twitter card validator does not work with my site !', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p><?php _e('Don\'t panic ! It\'s probably you did not set your robots.txt file according to Twitter\'s recommandation', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('This file is used to set specific rules for web crawlers.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('Twitter\'s bot needs to have access to your website to fetch your content, so just insert this in the file ::', JM_TC_DOC_TEXTDOMAIN); ?></p> <pre>
User-agent: Twitterbot
Disallow:
</pre>
    <h3 id="faq-image"><?php echo __('I do not see images in tweets !', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p><?php _e('Check that image does not exceed 1mb in size and/or is compatible with Twitter requirements.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <h3 id="faq-cf"><?php echo __('How do I use the custom fields option?', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p><?php _e('Basically you provide your custom field keys in plugin option page and then it will grab datas.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <h3 id="faq-mb-option"><?php echo __('I do not see all options in meta box', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p><?php _e('Just select card type. You will see additional fields if they exist.', JM_TC_DOC_TEXTDOMAIN); ?></p>
    <h4 id="settings"><?php _e('Plugin options', JM_TC_DOC_TEXTDOMAIN); ?></h4>

    <p><?php _e('In 3.3.6 Plugin UI has been renewed with some flat design and in a simpler way. Only option and all explanations in documentation. All sections includind meta box on post edit has now a link to documentation.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <h3 id="general"><?php echo __('General', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p><?php _e('This is the first section. You can set which type of card will be the default type used in your website. You must define twitter card creator (username) and twitter card site (username). If they are the same just enter username twice.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('You can also set how many words the plugin has to grab to set a description by default. This allows to avoid empty twitter meta description which often breaks twitter cards.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('Last setting is for multi-author blogs. It allows your authors to get an additional field in their profile. They can provide their Twitter Account. This is meant to improve authorship. If they publish a post, their twitter usernames will be set as twitter meta creator.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('Since 3.3.4 there is a new setting that allows you to use your own field for Twitter\'s field in profiles. A lot of theme have this feature so you probably want to keep only one field.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('Be careful the key you have to provide MUST BE the key your theme use to set the Twitter\'s field in profile ! In addition to this, THE VALUE MUST BE A USERNAME not a URL such as http://twitter.com/user <strong>it could break the cards !</strong>', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p class="sub-bold"><?php _e('If you experience any issue or if you mess with this option just recover with the key :', JM_TC_DOC_TEXTDOMAIN); ?>
        <br/> <strong>jm_tc_twitter</strong></p>

    <h3 id="seo"><?php echo __('SEO', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p><?php _e('WordPress is great but we often need to add some SEO plugins such as <a href= "http://yoast.com/wordpress/seo/">WP SEO by Yoast</a> or <a href= "http://wordpress.org/plugins/all-in-one-seo-pack/">All In One SEO</a>. These plugins are very very popular and you can find them in a lot of WP installations. JM Twitter Cards checks if one of the two plugins is activated and grabs meta title and description you have set. This allows you not to waste your time writting things twice for each post. If you forget to fulfill SEO fields do not panic, there are fallback (post title and the first words of your post as description). Do not forget to choose how many words the plugin has to grab for twitter meta description(max 200).', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('And if you do not use one of this two plugins you can use your own custom fields instead. Just provide meta keys (advanced users).', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <h3 id="images"><?php echo __('Images', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p><?php _e('Use the dropdown menu to choose which size you want to set by default (you can override this with the metabox fields). You can now choose (3.3.0) to force crop or not. Do not forget to enter a fallback image in case no option is set. You can also set twitter image with and height to get a better control of cards display (only for photo and product cards).', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <h3 id="pagehome"><?php echo __('Home settings', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p><?php _e('If home page is posts page (which is often the case), this setting allows you to define a Twitter Card title and description. This prevent from getting the datas from the first post in the loop.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <h3 id="metabox"><?php _e('Meta Box', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p><?php _e('This is one of the best features of the plugin. It allows to customize your cards for each post and you get extra features...', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('Once it\'s activated you\'ll get a custom meta box in your post edit :', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('With version 3.3.1 the plugin allows you to dismiss metabox if you do not want or need to use it. This introduces more flexibility for users who do not need to use metabox on each post. If option is set to "yes, please deactivate it", the datas will be set by general options.', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('To use new feature (in 3.3.6) Gallery cards, just use the WordPress Gallery System', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <p><?php _e('This is meant to let the user know that there is more than just a single image at the URL shared, but rather a gallery of related images. So these images have to be part of a WordPress gallery. Please ensure you use the shortcode [gallery] in your post to enjoy Gallery cards', JM_TC_DOC_TEXTDOMAIN); ?></p>

    <h3 id="extrasettings"><?php _e('Extra settings', JM_TC_DOC_TEXTDOMAIN); ?></h3>

    <p><?php _e('If you select photo, or summary large image or product cards then (<strong>save draft</strong> if JavaScript is disabled) you\'ll see additional fields you can set such as :', JM_TC_DOC_TEXTDOMAIN); ?></p>
    <ul>
        <li><?php _e('<strong>Retina display</strong>: Be careful with Retina displays, image must be tall enough. Also make sure your image is not heavier than 1 MB if used for summary large cards', JM_TC_DOC_TEXTDOMAIN); ?></li>
        <li><?php _e('<strong>Image options</strong> : This is optional but some users wanted alternatives for featured image', JM_TC_DOC_TEXTDOMAIN); ?></li>
        <li><?php _e('<strong>Product card options</strong> : You can set 2 key details for your product (e.g price, size, etc)', JM_TC_DOC_TEXTDOMAIN); ?></li>
    </ul>
    <div class="footer"><h4 id="sources"><?php _e('Sources', JM_TC_DOC_TEXTDOMAIN); ?></h4>
        <ul>
            <li>
                <a href="https://dev.twitter.com/docs/cards"><?php _e('Twitter cards documentation', JM_TC_DOC_TEXTDOMAIN); ?></a>
            </li>
            <li>
                <a href="https://dev.twitter.com/docs/cards/markup-reference"><?php _e('Meta tag references', JM_TC_DOC_TEXTDOMAIN); ?></a>
            </li>
        </ul>
        <p class="right pa1">Julien Maury, 16/02/2014</p>

        <p class="right pa1"><a href="#menu"><?php _e('Go To Table of Contents', JM_TC_DOC_TEXTDOMAIN); ?></a></p></div>

</div>
