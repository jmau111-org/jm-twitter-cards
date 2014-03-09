<?php
/*
Plugin Name: JM Twitter Cards
Plugin URI: http://www.tweetpress.fr
Description: Meant to help users to implement and customize Twitter Cards easily
Author: Julien Maury
Author URI: http://www.tweetpress.fr
Version: 4.3
License: GPL2++
*/
/*
*    Sources:  - https://dev.twitter.com/docs/cards
*              - http://codex.wordpress.org/Function_Reference/wp_enqueue_style
*              - https://github.com/rilwis/meta-box [GREAT]
*              - http://codex.wordpress.org/Function_Reference/wp_get_attachment_image_src
*              - http://codex.wordpress.org/Function_Reference/get_user_meta
*              - http://codex.wordpress.org/Function_Reference/get_posts
*              - https://codex.wordpress.org/Function_Reference/has_shortcode
*              - http://codex.wordpress.org/AJAX_in_Plugins
*              - http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)
*              - http://byronyasgur.wordpress.com/2011/06/27/frontend-forward-facing-ajax-in-wordpress/
*              - https://dev.twitter.com/docs/cards/getting-started#open-graph
*              - https://dev.twitter.com/docs/cards/markup-reference
*			   - https://dev.twitter.com/docs/cards/types/player-card
*			   - https://dev.twitter.com/docs/cards/app-installs-and-deep-linking [GREAT]
*			   - http://docs.appthemes.com/tutorials/wordpress-check-user-role-function/
*			   - http://highlightjs.org/
*/

// Add some security, no direct load !

defined('ABSPATH') or die('No no, no no no no, there\'s a limit !');

// Version number
function jm_tc_plugin_get_version()
{
	if (!function_exists('get_plugins')) require_once (ABSPATH . 'wp-admin/includes/plugin.php');

	$plugin_folder = get_plugins('/' . plugin_basename(dirname(__FILE__)));
	$plugin_file = basename((__FILE__));
	return $plugin_folder[$plugin_file]['Version'];
}


// Plugin activation: create default values if they don't exist

register_activation_hook(__FILE__, 'jm_tc_init');

function jm_tc_init()
{
	$opts = get_option('jm_tc');
	if (!is_array($opts)) update_option('jm_tc', jm_tc_get_default_options());
}

// Plugin uninstall: delete option

register_uninstall_hook(__FILE__, 'jm_tc_uninstall');

function jm_tc_uninstall()
{
	delete_option('jm_tc');
}

// Remove any @ from input value

function jm_tc_remove_at($at)
{
	$noat = str_replace('@', '', $at);
	return $noat;
}

// Remove any line-breaks

function jm_tc_remove_lb($lb)
{
	$output = str_replace(array(
		"\r\n",
		"\r"
	) , "\n", $lb);
	$lines = explode("\n", $output);
	$nolb = array();
	foreach($lines as $key => $line)
	{
		if (!empty($line)) $nolb[] = trim($line);
	}

	return implode($nolb);
}

// Use of a WP 3.6 function has_shortcode and fallback

function jm_tc_has_shortcode($content, $tag)
{
	if (function_exists('has_shortcode'))
	{ //in this case we are in 3.6 at least
		return has_shortcode($content, $tag);
	}
	else
	{
		global $shortcode_tags;
		return array_key_exists($tag, $shortcode_tags);
		preg_match_all('/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER);
		if (empty($matches)) return false;
		foreach($matches as $shortcode)
		{
			if ($tag === $shortcode[2]) return true;
		}
	}

	return false;
}

// Add stuffs in init such as img size

add_action('init', 'jm_tc_initialize');

function jm_tc_initialize()
{
	$opts = jm_tc_get_options();
	if ($opts['twitterCardCrop'] == 'yes')
	{
		$crop = true;
	}
	else
	{
		$crop = false;
	}

	if (function_exists('add_theme_support')) add_theme_support('post-thumbnails');
	add_image_size('jmtc-small-thumb', 280, 150, $crop);
	/* the minimum size possible for Twitter Cards */
	add_image_size('jmtc-max-web-thumb', 435, 375, $crop);
	/* maximum web size for photo cards */
	add_image_size('jmtc-max-mobile-non-retina-thumb', 280, 375, $crop);
	/* maximum non retina mobile size for photo cards  */
	add_image_size('jmtc-max-mobile-retina-thumb', 560, 750, $crop);
	/* maximum retina mobile size for photo cards  */
}

// Get user choice and convert it into post thumbnail sizes
// I know there are much better ways but I want my free plugins to be easily modifiable

function jm_tc_thumbnail_sizes()
{
	$opts = jm_tc_get_options();
	global $post;
	$twitterCardCancel = get_post_meta($post->ID, 'twitterCardCancel', true);
	if ('' != ($thumbnail_size = get_post_meta($post->ID, 'cardImgSize', true)) && $twitterCardCancel != 'yes')
	{
		$size = $thumbnail_size;
	}
	else
	{
		$size = $opts['twitterCardImgSize'];
	}

	switch ($size)
	{
	case 'small':
		$twitterCardImgSize = 'jmtc-small-thumb';
		break;

	case 'web':
		$twitterCardImgSize = 'jmtc-max-web-thumb';
		break;

	case 'mobile-non-retina':
		$twitterCardImgSize = 'jmtc-max-mobile-non-retina-thumb';
		break;

	case 'mobile-retina':
		$twitterCardImgSize = 'jmtc-max-mobile-retina-thumb';
		break;

	default:
		$twitterCardImgSize = 'jmtc-small-thumb';
?><!-- @(-_-)] --><?php
		break;
	}

	return $twitterCardImgSize;
}

// get featured image

function jm_tc_get_post_thumbnail_size()
{
	global $post;
	$args = array(
		'post_type' => 'attachment',
		'post_mime_type' => array(
			'image/png',
			'image/jpeg',
			'image/gif'
		) ,
		'numberposts' => - 1,
		'post_status' => null,
		'post_parent' => $post->ID
	);
	$attachments = get_posts($args);
	foreach($attachments as $attachment)
	{
			$math = filesize(get_attached_file($attachment->ID)) / 1000000;
			return $math; //Am I bold enough to call it a math?
	}
}


// save post metas
function jm_tc_save_postmeta($post_id, $meta)
{
	$old = get_post_meta($post_id, $meta, true);
	$new = $_POST[$meta];
	if ($new && $new != $old)
	{
		update_post_meta($post_id, $meta, $new);
	}
	elseif ('' == $new && $old)
	{
		delete_post_meta($post_id, $meta, $old);
	}
}

// grab our datas
$opts = jm_tc_get_options();

if ($opts['twitterCardMetabox'] == 'yes')
{
	add_action('add_meta_boxes', 'jm_tc_meta_box_add');
	function jm_tc_meta_box_add()
	{
		if (current_user_can('publish_posts'))
		{
			$post_type = get_post_type(); // add support for CPT
			add_meta_box('jm_tc-meta-box-id', 'Twitter Cards', 'jm_tc_meta_box_cb', $post_type, 'advanced', 'high');
		}
	}

	function jm_tc_meta_box_cb($post)
	{
		$opts = jm_tc_get_options(); //grab default option
		$regex = '~(https://|www.)(.+?)~';
		$values = get_post_custom($post->ID);
		$deactivated = isset($values['twitterCardCancel']) ? esc_attr($values['twitterCardCancel'][0]) : '';
		$selectedType = isset($values['twitterCardType']) ? esc_attr($values['twitterCardType'][0]) : $opts['twitterCardType']; //just make the meta box more comfortable ^^
		$selectedSize = isset($values['cardImgSize']) ? esc_attr($values['cardImgSize'][0]) : '';
		wp_nonce_field('jm_tc_meta_box_nonce', 'meta_box_nonce');
?>

<div class="tc-metabox">
<!-- documentation -->
<a class="button docu" target="_blank" href="<?php
		echo esc_url(admin_url().'admin.php?page=jm_tc_doc');
?>"><?php
		_e('Documentation', 'jm-tc');
?></a>
<a class="button docu" target="_blank" href="<?php
		echo esc_url('https://dev.twitter.com/docs/cards/validation/validator');
?>"><?php
		_e('Validator', 'jm-tc');
?></a>

<!-- Preview -->
<section class="preview-card feature">
<h1><?php
		_e('Preview', 'jm-tc');
?></h1>
<pre>
<code class="html">
<?php 
 echo esc_html( _jm_tc_markup() );
 ?>
 </code>
</pre>
</section>
<!-- /Preview -->

<section class="feature">
<h1><?php
		_e('Disable metabox ?', 'jm-tc');
?></h1>
<p>
<label class="labeltext" for="twitterCardCancel"><?php
		_e('Do you want to deactivate twitter cards metabox on this post?', 'jm-tc');
?></label>
<select name="twitterCardCancel" id="twitterCardCancel">
<option value="no" <?php
		selected($deactivated, 'no');
?>><?php
		_e('not at all !', 'jm-tc');
?></option>
<option value="yes" <?php
		selected($deactivated, 'yes');
?>><?php
		_e('yes please, deactivate it', 'jm-tc');
?></option>
</select>
</p>
<p class="description"><?php
		_e('(A simple way to opt out meta box on particular posts.)', 'jm-tc');
?></p>
</section>

<?php
		if ($deactivated != "yes"):
?>

<!-- select card type -->

<?php

			// make the card type setting the selected Type by default

?>
<section class="feature further cardTypes">
<h1><?php
			_e('Card types', 'jm-tc');
?></h1>
<p>
<label class="labeltext" for="twitterCardType"><?php
			_e('Choose what type of card you want to use', 'jm-tc');
?></label>
<select name="twitterCardType" id="twitterCardType">
<option value="summary" <?php
			selected($selectedType, 'summary');
?>><?php
			_e('summary', 'jm-tc');
?></option>
<option value="summary_large_image" <?php
			selected($selectedType, 'summary_large_image');
?>><?php
			_e('summary_large_image', 'jm-tc');
?></option>
<option value="photo" <?php
			selected($selectedType, 'photo');
?>><?php
			_e('photo', 'jm-tc');
?></option>
<option value="product" <?php
			selected($selectedType, 'product');
?>><?php
			_e('product', 'jm-tc');
?></option>
<option value="gallery" <?php
			selected($selectedType, 'gallery');
?>><?php
			_e('gallery', 'jm-tc');
?></option>
<option value="player" <?php
			selected($selectedType, 'player');
?>><?php
			_e('player', 'jm-tc');
?></option>
</select>
</p>
</section>





<!-- set img from another source -->
<section class="feature further furthermore-non-gallery">
<h1><?php
			_e('External URL for twitter:image ?', 'jm-tc');
?></h1>
<p>
<label class="labeltext" for="twitterCardImage"><?php
			_e('Set another source as twitter image (enter URL)', 'jm-tc');
?> :</label>
<input id="twitterCardImage" type="url" name="cardImage" style="padding:.3em;" size="120" class="regular-text" value="<?php
			echo esc_url( get_post_meta($post->ID, 'cardImage', true) );
?>" /> 
<input class="tc-upload-button" type="button" value="<?php _e('Upload', 'jm-tc');?>" />
</p>
</section>


<!-- set img dimensions -->
<?php if ( 'attachment' != get_post_type() ) : ?>
<section class="feature further furthermore-non-gallery resizer nochange">
<h1><?php
			_e('Define specific size for twitter:image display', 'jm-tc');
?></h1>
<label class="labeltext" for="cardImgSize"><?php
			_e('Set featured image dimensions', 'jm-tc');
?> :</label>
<select class="styled-select" id="cardImgSize" name="cardImgSize">
<option value="mobile-non-retina" <?php
			selected($selectedSize, 'mobile-non-retina');
?>><?php
			_e('Max mobile non retina (width: 280px - height: 375px)', 'jm-tc');
?></option>
<option value="mobile-retina" <?php
			selected($selectedSize, 'mobile-retina');
?>><?php
			_e('Max mobile retina (width: 560px - height: 750px)', 'jm-tc');
?></option>
<option value="web" <?php
			selected($selectedSize, 'web');
?>><?php
			_e('Max web size(width: 435px - height: 375px)', 'jm-tc');
?></option>
<option value="small" <?php
			selected($selectedSize, 'small');
?>><?php
			_e('Small (width: 280px - height: 150px)', 'jm-tc');
?></option>
</select>

<?php if( jm_tc_get_post_thumbnail_size() >= 1 ) : ?>
	<span class="card-error"><?php  _e('Image is equal to or greater than 1MB. This will break Twitter Cards. Optimize it please, this should also improve your page load.', 'jm-tc');?></span>
<?php endif;?>
</section>
<?php endif ;?>
<section class="feature further further-photo">
<h1><?php
			_e('Photo Cards', 'jm-tc');
?></h1>
<p>
<label class="labeltext" for="twitterPhotoImageWidth"><?php
			_e('Image width', 'jm-tc');
?> :</label>
<input id="twitterPhotoImageWidth" type="number" min="280" name="cardPhotoWidth" class="small-number" value="<?php
			echo get_post_meta($post->ID, 'cardPhotoWidth', true);
?>" />
</p>
<p>
<label class="labeltext" for="twitterPhotoImageHeight"><?php
			_e('Image height', 'jm-tc');
?> :</label>
<input id="twitterPhotoImageHeight" type="number" min="150" name="cardPhotoHeight" class="small-number" value="<?php
			echo get_post_meta($post->ID, 'cardPhotoHeight', true);
?>" />
</p>
</section>



<!-- set product card -->
<section class="feature further further-product">
<h1><?php
			_e('Product Cards', 'jm-tc');
?></h1>
<p>
<label class="labeltext" for="cardData1"><?php
			_e('Enter the first key data for product', 'jm-tc');
?> :</label>
<input id="cardData1" type="text" name="cardData1" style="padding:.3em;" class="regular-text" value="<?php
			echo get_post_meta($post->ID, 'cardData1', true);
?>" />
</p>
<p>
<label class="labeltext" for="cardLabel1"><?php
			_e('Enter the first key label for product', 'jm-tc');
?> :</label>
<input id="cardLabel1" type="text" name="cardLabel1" style="padding:.3em;" class="regular-text" value="<?php
			echo get_post_meta($post->ID, 'cardLabel1', true);
?>" />
</p>
<p>
<label class="labeltext" for="cardData2"><?php
			_e('Enter the second key data for product', 'jm-tc');
?> :</label>
<input id="cardData2" type="text" name="cardData2" style="padding:.3em;" class="regular-text" value="<?php
			echo get_post_meta($post->ID, 'cardData2', true);
?>" />
</p>
<p>
<label class="labeltext" for="cardLabel2"><?php
			_e('Enter the second key label for product', 'jm-tc');
?> :</label>
<input id="cardLabel2" type="text" name="cardLabel2" style="padding:.3em;" class="regular-text" value="<?php
			echo get_post_meta($post->ID, 'cardLabel2', true);
?>" />
</p>
<p>
<label class="labeltext" for="twitterProductImageWidth"><?php
			_e('Image width', 'jm-tc');
?> :</label>
<input id="twitterProductImageWidth" type="number" min="280" name="cardProductWidth" class="small-number" value="<?php
			echo get_post_meta($post->ID, 'cardProductWidth', true);
?>" />
</p>
<p>
<label class="labeltext" for="twitterProductImageHeight"><?php
			_e('Image height', 'jm-tc');
?> :</label>
<input id="twitterProductImageHeight" type="number" min="150" name="cardProductHeight" class="small-number" value="<?php
			echo get_post_meta($post->ID, 'cardProductHeight', true);
?>" />
</p>
</section>





<section class="feature further further-gallery">
<h1><?php
			_e('Gallery Cards', 'jm-tc');
?></h1>
<p>
<?php
			_e('Just use shortcode <strong>[gallery]</strong> and include at least 4 images.', 'jm-tc');
?>
</p>
</section>




<section class="feature further further-player">
<h1><?php
			_e('Player Cards', 'jm-tc');
?></h1>

<?php if( jm_tc_get_post_thumbnail_size() >= 1 ) : ?>
	<span class="card-error"><?php  _e('Image is equal to or greater than 1MB. This will break Twitter Cards. Optimize it please, this should also improve your page load.', 'jm-tc');?></span>
<?php endif;?>

<p>
<label class="labeltext" for="twitterCardPlayer"><?php
			_e('URL of iFrame player (MUST BE HTTPS)', 'jm-tc');
?> :</label><br />
<input id="twitterCardPlayer" type="url" name="cardPlayer" style="padding:.3em;" size="120" class="regular-text" value="<?php
			echo esc_url( get_post_meta($post->ID, 'cardPlayer', true) );
?>" /> 
<?php if( get_post_meta($post->ID, 'cardPlayer', true) == '' ) : ?>
	<span class="card-error mandatory"><?php _e('Mandatory for Twitter player card!', 'jm-tc');?></span>
<?php endif;?>
<?php if( get_post_meta($post->ID, 'cardPlayer', true) != '' && !preg_match( $regex, get_post_meta($post->ID, 'cardPlayer', true) )  ) : ?>
	<span class="card-error"><?php _e('URL must be https!', 'jm-tc');?></span>
<?php endif;?>
</p>
<p>
<em><?php _e('When setting this, make sure player dimension and image dimensions are exactly the same! Image MUST BE greater than 68,600 pixels (a 262x262 square image, or a 350x196 16:9 image)', 'jm-tc');?></em><br />
</p>
<p>
<label class="labeltext" for="twitterPlayerWidth"><?php
			_e('Player width', 'jm-tc');
?> :</label>
<input id="twitterPlayerWidth" type="number" min="262" max="435" name="cardPlayerWidth" class="small-number" value="<?php
			echo get_post_meta($post->ID, 'cardPlayerWidth', true);
?>" />
<?php if( get_post_meta($post->ID, 'cardPlayerWidth', true) == '' ) : ?>
	<span class="card-error  mandatory"><?php _e('Mandatory for Twitter player card!', 'jm-tc');?></span>
<?php endif;?>
</p>
<p>
<label class="labeltext" for="twitterPlayerHeight"><?php
			_e('Player height', 'jm-tc');
?> :</label>
<input id="twitterPlayerHeight" type="number" min="196" name="cardPlayerHeight" class="small-number" value="<?php
			echo get_post_meta($post->ID, 'cardPlayerHeight', true);
?>" />
<?php if( get_post_meta($post->ID, 'cardPlayerHeight', true) == '' ) : ?>
	<span class="card-error  mandatory"><?php _e('Mandatory for Twitter player card!', 'jm-tc');?></span>
<?php endif;?>
<?php if( jm_tc_get_post_thumbnail_size() >= 1 ) : ?>
	<span class="card-error"><?php  _e('Image is equal to or greater than 1MB. This will break Twitter Cards. Optimize it please, this should also improve your page load.', 'jm-tc');?></span>
<?php endif;?>
</p>
<p>
<em><?php _e('If you do not understand what is the following field then it is probably a bad idea to fulfill them!', 'jm-tc');?></em>
</p>
<label class="labeltext" for="twitterCardPlayerStream"><?php
			_e('URL to raw stream that will be rendered (MUST BE HTTPS)', 'jm-tc');
?> :</label><br />
<input id="twitterCardPlayerStream" type="url" name="cardPlayerStream" style="padding:.3em;" size="120" class="regular-text" value="<?php
			echo esc_url( get_post_meta($post->ID, 'cardPlayerStream', true) );
?>" /> 
<?php if( get_post_meta($post->ID, 'cardPlayerStream', true) != '' && !preg_match( $regex, get_post_meta($post->ID, 'cardPlayerStream', true) )  ) : ?>
	<span class="card-error"><?php _e('URL must be https!', 'jm-tc');?></span>
<?php endif;?>
</section>


<?php
		endif;
?>

</div><!-- end of meta box -->
<?php
	}

	add_action('edit_attachment','jm_tc_meta_box_save');//save also for attachment
	add_action('save_post', 'jm_tc_meta_box_save');
	function jm_tc_meta_box_save($post_id)
	{

		// Bail if we're doing an auto save

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

		// if our nonce isn't there, or we can't verify it, bail

		if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'jm_tc_meta_box_nonce')) return;

		// if our current user can't edit this post, bail

		if (!current_user_can('edit_post')) return;
		jm_tc_save_postmeta($post_id, 'twitterCardCancel');
		jm_tc_save_postmeta($post_id, 'twitterCardType');
		jm_tc_save_postmeta($post_id, 'cardImage');
		jm_tc_save_postmeta($post_id, 'cardPhotoWidth');
		jm_tc_save_postmeta($post_id, 'cardPhotoHeight');
		jm_tc_save_postmeta($post_id, 'cardProductWidth');
		jm_tc_save_postmeta($post_id, 'cardProductHeight');
		jm_tc_save_postmeta($post_id, 'cardPlayer');
		jm_tc_save_postmeta($post_id, 'cardPlayerWidth');
		jm_tc_save_postmeta($post_id, 'cardPlayerHeight');
		jm_tc_save_postmeta($post_id, 'cardPlayerStream');
		jm_tc_save_postmeta($post_id, 'cardData1');
		jm_tc_save_postmeta($post_id, 'cardLabel1');
		jm_tc_save_postmeta($post_id, 'cardData2');
		jm_tc_save_postmeta($post_id, 'cardLabel2');
		jm_tc_save_postmeta($post_id, 'cardImgSize');
	}
}

// add twitter infos

$opts = jm_tc_get_options();

if ($opts['twitterProfile'] == 'yes')
{
	add_action('show_user_profile', 'jm_tc_add_field_user_profile');
	add_action('edit_user_profile', 'jm_tc_add_field_user_profile');
	function jm_tc_add_field_user_profile($user)
	{
		if (current_user_can('publish_posts'))
		{
			wp_nonce_field('jm_tc_twitter_field_update', 'jm_tc_twitter_field_update', false);
?>
<h3><?php
			_e("Twitter Card Creator", "jm-tc");
?></h3>    
<table class="form-table">
<tr>
<th>
<label class="labeltext" for="jm_tc_twitter"><?php
			_e("Twitter Account", "jm_tc");
?></label>
</th>
<td>
<input type="text" name="jm_tc_twitter" id="jm_tc_twitter" value="<?php
			echo esc_attr(get_the_author_meta('jm_tc_twitter', $user->ID));
?>" class="regular-text" /><br />
<span class="description"><?php
			_e("Enter your Twitter Account (without @)", "jm-tc");
?></span>
</td>
</tr>
</table>
<?php
		}
	}

	// save value for extra field in user profile

	add_action('personal_options_update', 'jm_tc_save_extra_user_profile_field', 10, 1);
	add_action('edit_user_profile_update', 'jm_tc_save_extra_user_profile_field', 10, 1);
	function jm_tc_save_extra_user_profile_field($user_id)
	{
		if (!current_user_can('edit_user', $user_id) || !isset($_POST['jm_tc_twitter_field_update']) || !wp_verify_nonce($_POST['jm_tc_twitter_field_update'], 'jm_tc_twitter_field_update'))
		{
			return false;
		}

		$tc_twitter = wp_filter_nohtml_kses($_POST['jm_tc_twitter']);
		update_user_meta($user_id, 'jm_tc_twitter', $tc_twitter);
	}

	// apply a filter on input to delete any @

	add_filter('user_profile_update_errors', 'jm_tc_check_at', 10, 3); // wp-admin/includes/users.php, thanks Greglone for this great hint
	function jm_tc_check_at($errors, $update, $user)
	{
		if ($update)
		{

			// let's save it but in case there's a @ just remove it before saving

			update_user_meta($user->ID, 'jm_tc_twitter', jm_tc_remove_at($_POST['jm_tc_twitter']));
		}
	}
}

// grab excerpt

if (!function_exists('get_excerpt_by_id'))
{
	function get_excerpt_by_id($post_id)
	{
		$the_post = get_post($post_id);
		$the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt

		// SET LENGTH

		$excerpt_length = jm_tc_get_options();
		$excerpt_length = $excerpt_length['twitterExcerptLength'];
		$the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
		$words = explode(' ', $the_excerpt, $excerpt_length + 1);
		if (count($words) > $excerpt_length):
			array_pop($words);
			array_push($words, '…');
			$the_excerpt = implode(' ', $words);
		endif;
		return esc_attr($the_excerpt); // to prevent meta from being broken by ""
	}
}

// function to add markup in head section of post types
if (!function_exists('_jm_tc_markup_home'))
{
	function _jm_tc_markup_home()
    {
	/* get options */
	$opts = jm_tc_get_options();

		$output  = '<meta name="twitter:card" content="' . $opts['twitterCardType'] . '"/>' . "\n";
		$output .= '<meta name="twitter:creator" content="@' . $opts['twitterCreator'] . '"/>' . "\n";
		$output .= '<meta name="twitter:site" content="@' . $opts['twitterSite'] . '"/>' . "\n";
		$output .= '<meta name="twitter:title" content="' . $opts['twitterPostPageTitle'] . '"/>' . "\n";
		$output .= '<meta name="twitter:description" content="' . $opts['twitterPostPageDesc'] . '"/>' . "\n";
		$output .= '<meta name="twitter:image" content="' . $opts['twitterImage'] . '"/>' . "\n";
		
			//Deep linking
			if ($opts['twitterCardDeepLinking'] == 'yes') 
			{
				
				if( $opts['twitteriPhoneName'] != '' ) $output .='<meta name="twitter:app:name:iphone" content="' . $opts['twitteriPhoneName'] . '">'. "\n";
				if( $opts['twitteriPadName'] != '' ) $output .='<meta name="twitter:app:name:ipad" content="' . $opts['twitteriPadName'] . '">'. "\n";
				if( $opts['twitterGooglePlayName'] != '' ) $output .='<meta name="twitter:app:name:googleplay" content="' . $opts['twitterGooglePlayName'] . '">'. "\n";
				if( $opts['twitteriPhoneUrl'] != '' ) $output .='<meta name="twitter:app:url:iphone" content="' . $opts['twitteriPhoneUrl'] .'">'. "\n";
				if( $opts['twitteriPadUrl'] != '' ) $output .='<meta name="twitter:app:url:ipad" content="' . $opts['twitteriPhoneUrl'] . '">'. "\n";
				if( $opts['twitterGooglePlayUrl'] != '' ) $output .='<meta name="twitter:app:url:googleplay" content="' . $opts['twitterGooglePlayUrl'] . '">'. "\n";
				if( $opts['twitteriPhoneId'] != '' ) $output .='<meta name="twitter:app:id:iphone" content="' . $opts['twitteriPhoneId'] . '">'. "\n";
				if( $opts['twitteriPadId'] != '' ) $output .='<meta name="twitter:app:id:ipad" content="' . $opts['twitteriPadId'] . '">'. "\n";
				if( $opts['twitterGooglePlayId'] != '' ) $output .='<meta name="twitter:app:id:googleplay" content="' . $opts['twitterGooglePlayId'] . '">'. "\n";
			}
		
		return apply_filters('jmtc_markup_home', $output); // provide filter for developers.
			
	}
}

if (!function_exists('_jm_tc_markup'))
{
	function _jm_tc_markup()
	{
		global $post;
		/* get options */
		$opts = jm_tc_get_options();

			// get current post meta data

			$creator 			= get_the_author_meta('jm_tc_twitter', $post->post_author);
			$cardType 			= get_post_meta($post->ID, 'twitterCardType', true);
			$cardPhotoWidth	    = get_post_meta($post->ID, 'cardPhotoWidth', true);
			$cardPhotoHeight 	= get_post_meta($post->ID, 'cardPhotoHeight', true);
			$cardProductWidth 	= get_post_meta($post->ID, 'cardProductWidth', true);
			$cardProductHeight 	= get_post_meta($post->ID, 'cardProductHeight', true);
			$cardPlayerWidth 	= get_post_meta($post->ID, 'cardPlayerWidth', true);
			$cardPlayerHeight 	= get_post_meta($post->ID, 'cardPlayerHeight', true);
			$cardPlayer 		= get_post_meta($post->ID, 'cardPlayer', true);
			$cardPlayerStream	= get_post_meta($post->ID, 'cardPlayerStream', true);
			$cardImage 			= get_post_meta($post->ID, 'cardImage', true);
			$cardData1 			= get_post_meta($post->ID, 'cardData1', true);
			$cardLabel1		 	= get_post_meta($post->ID, 'cardLabel1', true);
			$cardData2 			= get_post_meta($post->ID, 'cardData2', true);
			$cardLabel2 		= get_post_meta($post->ID, 'cardLabel2', true);
			$cardImgSize 		= get_post_meta($post->ID, 'cardImgSize', true);
			$twitterCardCancel 	= get_post_meta($post->ID, 'twitterCardCancel', true);
			
			//regex for player cards
			$regex = '~(https://|www.)(.+?)~';

			// from option page

			$cardTitleKey = $opts['twitterCardTitle'];
			$cardDescKey = $opts['twitterCardDesc'];
			$cardUsernameKey = $opts['twitterUsernameKey'];
			/* custom fields */
			$tctitle = get_post_meta($post->ID, $cardTitleKey, true);
			$tcdesc = get_post_meta($post->ID, $cardDescKey, true);
			$username = get_user_meta(get_current_user_id() , $cardUsernameKey, true);

			// support for custom meta description WordPress SEO by Yoast or All in One SEO

			if (class_exists('WPSEO_Frontend'))
			{ // little trick to check if plugin is here and active :)
				$object = new WPSEO_Frontend();
				if ($opts['twitterCardSEOTitle'] == 'yes' && $object->title(false))
				{
					$cardTitle = $object->title(false);
				}
				else
				{
					$cardTitle = the_title_attribute(array(
						'echo' => false
					));
				}

				if ($opts['twitterCardSEODesc'] == 'yes' && $object->metadesc(false))
				{
					$cardDescription = $object->metadesc(false);
				}
				else
				{
					$cardDescription = apply_filters('jm_tc_get_excerpt', get_excerpt_by_id($post->ID) );
				}
			}
			elseif (class_exists('All_in_One_SEO_Pack'))
			{
				global $post;
				$post_id = $post;
				if (is_object($post_id)) $post_id = $post_id->ID;
				if ($opts['twitterCardSEOTitle'] == 'yes' && get_post_meta(get_the_ID() , '_aioseop_title', true))
				{
					$cardTitle = htmlspecialchars(stripcslashes(get_post_meta($post_id, '_aioseop_title', true)));
				}
				else
				{
					$cardTitle = the_title_attribute(array(
						'echo' => false
					));
				}

				if ($opts['twitterCardSEODesc'] == 'yes' && get_post_meta(get_the_ID() , '_aioseop_description', true))
				{
					$cardDescription = htmlspecialchars(stripcslashes(get_post_meta($post_id, '_aioseop_description', true)));
				}
				else
				{
					$cardDescription = apply_filters('jm_tc_get_excerpt', get_excerpt_by_id($post->ID) );
				}
			}
			elseif ($tctitle && $tcdesc && $cardTitleKey != '' && $cardDescKey != '')
			{

				// avoid array to string notice on title and desc

				$cardTitle = $tctitle;
				$cardDescription = $tcdesc;
			}
			else
			{ //default (I'll probably make a switch next time)
				$cardTitle = the_title_attribute(array(
					'echo' => false
				));
				$cardDescription = apply_filters('jm_tc_get_excerpt', get_excerpt_by_id($post->ID) );
			}

			if (($opts['twitterCardMetabox'] == 'yes') && $cardType != '' && $twitterCardCancel != 'yes')
			{
				$output = '<meta name="twitter:card" content="' . apply_filters('jm_tc_card_type', $cardType ). '"/>' . "\n";
			}
			else
			{
				$output = '<meta name="twitter:card" content="' . apply_filters('jm_tc_card_type', $opts['twitterCardType'] ). '"/>' . "\n";
			}

			if ($opts['twitterProfile'] == 'yes' && $creator != '' )
			{ // this part has to be optional, this is more for guest bltwitterging but it's no reason to bother everybody.
				$output .= '<meta name="twitter:creator" content="@' . $creator . '"/>' . "\n";
			}
			elseif ($opts['twitterProfile'] == 'no' && $username != '' && !is_array($username))
			{ // http://codex.wordpress.org/Function_Reference/get_user_meta#Return_Values
				$output .= '<meta name="twitter:creator" content="@' . $username . '"/>' . "\n";
			}
			else
			{
				$output .= '<meta name="twitter:creator" content="@' . $opts['twitterCreator'] . '"/>' . "\n";
			}

			// these next 4 parameters should not be editable in post admin

			$output .= '<meta name="twitter:site" content="@' . $opts['twitterSite'] . '"/>' . "\n";
			$output .= '<meta name="twitter:title" content="' . $cardTitle . '"/>' . "\n"; // filter used by plugin to customize title
			$output .= '<meta name="twitter:description" content="' . jm_tc_remove_lb($cardDescription) . '"/>' . "\n";
		
			
			//gallery
			if ($cardType != 'gallery')
			{
				if (get_the_post_thumbnail($post->ID) != '')
				{
					if ($cardImage != '' && $twitterCardCancel != 'yes')
					{ // cardImage is set
						$output .= '<meta name="twitter:image" content="' .  apply_filters( 'jm_tc_image_source', $cardImage ). '"/>' . "\n";
					}
					else
					{
						$image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , jm_tc_thumbnail_sizes());
						$output .= '<meta name="twitter:image" content="' . apply_filters( 'jm_tc_image_source', $image_attributes[0] ) . '"/>' . "\n";
					}
				}
				elseif (get_the_post_thumbnail($post->ID) == '' && $cardImage != '' && $twitterCardCancel != 'yes')
				{
					$output .=  '<meta name="twitter:image" content="' . apply_filters( 'jm_tc_image_source', $cardImage ) . '"/>' . "\n";
				}
				
				elseif ( 'attachment' == get_post_type() ) 
				{
				
					$output .= '<meta name="twitter:image" content="' . apply_filters( 'jm_tc_image_source', wp_get_attachment_url( $post->ID ) ) . '"/>' . "\n";
				
				}
				
				else
				{ //fallback
					$output .= '<meta name="twitter:image" content="' . apply_filters( 'jm_tc_image_source', $opts['twitterImage'] ). '"/>' . "\n";
				}
			}
			else
			{ // markup will be different
				if ($twitterCardCancel != 'yes')
				{
					if (jm_tc_has_shortcode($post->post_content, 'gallery'))
					{

						// get attachment for gallery cards

						$args = array(
							'post_type' => 'attachment',
							'numberposts' => - 1,
							'exclude' => get_post_thumbnail_id() ,
							'post_mime_type' => 'image',
							'post_status' => null,
							'post_parent' => $post->ID
						);
						$attachments = get_posts($args);
						if ($attachments && count($attachments) > 3)
						{
							$i = 0;
							foreach($attachments as $attachment)
							{

								// get attachment array with the ID from the returned posts

								$pic = wp_get_attachment_url($attachment->ID);
								$output .= '<meta name="twitter:image' . $i . '" content="' . apply_filters( 'jm_tc_image_source', $pic ). '"/>' . "\n";
								$i++;
								if ($i > 3) break; //in case there are more than 4 images in post, we are not allowed to add more than 4 images in our card by Twitter
							}
						}
					}
					else
					{
						$output .=  '<!-- ' . __('Warning : Gallery Card is not set properly ! There is no gallery in this post !', 'jm-tc')  .'@(-_-)] -->' . "\n";
					}
				}
				else
				{
					if (has_post_thumbnail())
					{
						$image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , jm_tc_thumbnail_sizes());
						$output .= '<meta name="twitter:image" content="' . apply_filters( 'jm_tc_image_source',$image_attributes[0] ). '"/>' . "\n";
					}
					else
					{
						$output .= '<meta name="twitter:image" content="' .apply_filters( 'jm_tc_image_source', $opts['twitterImage'] ). '"/>' . "\n";
					}
				}
			}
			
			//photo
			if ($opts['twitterCardType'] == 'photo' || $cardType == 'photo'  )
			{
				if ( $cardPhotoWidth != '' && $cardPhotoHeight != '' && $twitterCardCancel != 'yes' )
				{
					$output .= '<meta name="twitter:image:width" content="' . $cardPhotoWidth . '"/>' . "\n";
					$output .= '<meta name="twitter:image:height" content="' . $cardPhotoHeight . '"/>' . "\n";
				}
				elseif ($opts['twitterCardType'] == 'photo' && $twitterCardCancel != 'yes' && $opts['twitterCardMetabox'] != 'yes')
				{
					$output .= '<meta name="twitter:image:width" content="' . $opts['twitterImageWidth'] . '"/>' . "\n";
					$output .= '<meta name="twitter:image:height" content="' . $opts['twitterImageHeight'] . '"/>' . "\n";
				}
			}
			
			//product
			if ($cardType == 'product' && $twitterCardCancel != 'yes')
			{
				if ( $cardData1 != '' &&  $cardLabel1 != '' && $cardData2 != '' && $cardLabel2 != '' )
				{
					$output .= '<meta name="twitter:data1" content="' . $cardData1 . '"/>' . "\n";
					$output .= '<meta name="twitter:label1" content="' . $cardLabel1 . '"/>' . "\n";
					$output .= '<meta name="twitter:data2" content="' . $cardData2 . '"/>' . "\n";
					$output .= '<meta name="twitter:label2" content="' . $cardLabel2 . '"/>' . "\n";
				}
				else
				{
					$output .=   '<!-- ' .__('Warning : Product Card is not set properly ! There is no product datas !', 'jm-tc').'@(-_-)] -->' . "\n";
				}

				if ( $cardProductWidth != '' && $cardProductHeight != '' && $cardType == 'product')
				{
					$output .= '<meta name="twitter:image:width" content="' . $cardProductWidth . '"/>' . "\n";
					$output .= '<meta name="twitter:image:height" content="' . $cardProductHeight . '"/>' . "\n";
				}
				else
				{
					$output .= '<meta name="twitter:image:width" content="' . $opts['twitterImageWidth'] . '"/>' . "\n";
					$output .= '<meta name="twitter:image:height" content="' . $opts['twitterImageHeight'] . '"/>' . "\n";
				}
			}
			
			
			if ($cardType == 'player' && $twitterCardCancel != 'yes' )
			{
				if ( $cardPlayer != '' ) {
					$output .= '<meta name="twitter:player" content="' . $cardPlayer . '"/>' . "\n";
				} 
				else
				{
					$output .=  '<!-- ' .__('Warning : Player Card is not set properly ! There is no URL provided for iFrame player !', 'jm-tc') .'@(-_-)] -->' . "\n";					
				}
				
				if (  $cardPlayer != '' && !preg_match( $regex,$cardPlayer) )
				{
					$output .= '<!-- ' .__('Warning : Player Card is not set properly ! The URL of iFrame Player MUST BE https!', 'jm-tc') .'@(-_-)] -->' . "\n";
				}
								
			
				if ( $cardPlayerWidth != '' && $cardPlayerHeight != ''  )
				{
					$output .= '<meta name="twitter:player:width" content="' . $cardPlayerWidth . '"/>' . "\n";
					$output .= '<meta name="twitter:player:height" content="' . $cardPlayerHeight . '"/>' . "\n";
				}
				else 
				{
					$output .= '<meta name="twitter:player:width" content="435"/>' . "\n";
					$output .= '<meta name="twitter:player:height" content="251"/>' . "\n";		
				}
				
				//Player stream
				if ( $cardPlayerStream != '' ) 
				{
					$output .= '<meta name="twitter:player:stream" content="'.$cardPlayerStream.'">'. "\n";
					$output .= '<meta name="twitter:player:stream:content_type" content="video/mp4; codecs=&quot;avc1.42E01E1, mp4a.40.2&quot;">'. "\n";
				}
				
				if ( $cardPlayerStream != '' && !preg_match( $regex,$cardPlayerStream) )
				{
					$output .= '<!-- ' .__('Warning : Player Card is not set properly ! The URL of raw stream Player MUST BE https!', 'jm-tc') . "\n";
				}
				
				
				//Deep linking
				if ($opts['twitterCardDeepLinking'] == 'yes') 
				{
					
					if( $opts['twitteriPhoneName'] != '' ) $output .='<meta name="twitter:app:name:iphone" content="' . $opts['twitteriPhoneName'] . '">'. "\n";
					if( $opts['twitteriPadName'] != '' ) $output .='<meta name="twitter:app:name:ipad" content="' . $opts['twitteriPadName'] . '">'. "\n";
					if( $opts['twitterGooglePlayName'] != '' ) $output .='<meta name="twitter:app:name:googleplay" content="' . $opts['twitterGooglePlayName'] . '">'. "\n";
					if( $opts['twitteriPhoneUrl'] != '' ) $output .='<meta name="twitter:app:url:iphone" content="' . $opts['twitteriPhoneUrl'] .'">'. "\n";
					if( $opts['twitteriPadUrl'] != '' ) $output .='<meta name="twitter:app:url:ipad" content="' . $opts['twitteriPhoneUrl'] . '">'. "\n";
					if( $opts['twitterGooglePlayUrl'] != '' ) $output .='<meta name="twitter:app:url:googleplay" content="' . $opts['twitterGooglePlayUrl'] . '">'. "\n";
					if( $opts['twitteriPhoneId'] != '' ) $output .='<meta name="twitter:app:id:iphone" content="' . $opts['twitteriPhoneId'] . '">'. "\n";
					if( $opts['twitteriPadId'] != '' ) $output .='<meta name="twitter:app:id:ipad" content="' . $opts['twitteriPadId'] . '">'. "\n";
					if( $opts['twitterGooglePlayId'] != '' ) $output .='<meta name="twitter:app:id:googleplay" content="' . $opts['twitterGooglePlayId'] . '">'. "\n";
				}
			}
	

		return apply_filters('jmtc_markup', $output); // provide filter for developers.
	}
}	

//Add markup according to which page is displayed
add_action('wp_head', '_jm_tc_add_markup', PHP_INT_MAX); // it's actually better to load twitter card meta at the very end (SEO desc is more important)
function _jm_tc_add_markup() {

        $begin =  "\n" . '<!-- JM Twitter Cards by Julien Maury ' . jm_tc_plugin_get_version() . ' -->' . "\n";
		$end   =  '<!-- /JM Twitter Cards -->' . "\n\n";


		if ( is_home() || is_front_page() ) //detect post page or home (could be the same)
		{
		  echo $begin;
		  echo _jm_tc_markup_home();
		  echo $end;
		}
		
		if( is_singular() && !is_front_page() && !is_home() && !is_404() && !is_tag() ) // avoid pages that do not need cards
		{
		  echo $begin;
		  echo _jm_tc_markup();
		  echo $end;
		}
		
}


/*
* ADMIN OPTION PAGE
*/

// Language support
add_action('plugins_loaded', 'jm_tc_lang_init');
function jm_tc_lang_init()
{
	load_plugin_textdomain('jm-tc', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}



// Add a "Settings" link in the plugins list
add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'jm_tc_settings_action_links', 10, 2);

function jm_tc_settings_action_links($links, $file)
{
	$settings_link = '<a href="' . admin_url('admin.php?page=jm_tc_options') . '">' . __("Settings") . '</a>';
	array_unshift($links, $settings_link);
	return $links;
}

// The add_action to add onto the WordPress menu.
add_action('admin_menu', 'jm_tc_add_options');

function jm_tc_add_options()
{
	$tcpage    = add_menu_page('JM Twitter Cards Options', __('Twitter Cards','jm-tc'), 'manage_options', 'jm_tc_options', 'jm_tc_options_page', plugins_url('admin/img/bird_blue_16.png', __FILE__) , 99);
	//add submenu
	$tcdocpage = add_submenu_page( 'jm_tc_options', __( 'Documentation', 'jm-tc' ), __( 'Documentation', 'jm-tc' ) , 'manage_options', 'jm_tc_doc', 'jm_tc_docu_page' );
	register_setting('jm-tc', 'jm_tc', 'jm_tc_sanitize');

	// Load the CSS conditionally
	add_action('load-' . $tcpage, 'jm_tc_load_admin_scripts');
	add_action('load-' . $tcdocpage, 'jm_tc_load_doc_scripts');
}

function jm_tc_load_admin_scripts()
{
	add_action('admin_enqueue_scripts', 'jm_tc_admin_script');
}
function jm_tc_load_doc_scripts()
{
	add_action('admin_enqueue_scripts', 'jm_tc_doc_scripts');
	/* In fact I do not want to load this everywhere on the website (huge) */
	load_plugin_textdomain('jm-tc-doc', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}


function jm_tc_admin_script()
{
	wp_enqueue_style('jm-tc-admin-style', plugins_url('admin/css/jm-tc-admin.css', __FILE__));
	wp_enqueue_script('jm-tc-admin-script', plugins_url('admin/js/jm-tc-admin.js', __FILE__) , array(
		'jquery'
	) , '1.0', true);
	wp_localize_script('jm-tc-admin-script', 'jmTcObject', array(
		'ajaxurl' => esc_url(admin_url('/admin-ajax.php')) ,
		'_tc_ajax_saving_nonce' => wp_create_nonce('tc-ajax-saving-nonce')
	));
}
function jm_tc_doc_scripts()
{
	wp_enqueue_style('jm-tc-doc-style', plugins_url('admin/css/jm-tc-documentation.css', __FILE__));
}

// Ajax saving options

add_action('wp_ajax_jm-tc-ajax-saving', 'jm_tc_ajax_saving_process');

function jm_tc_ajax_saving_process()
{

	// security for our AJAX request

	if (!isset($_POST['_tc_ajax_saving_nonce']) || !wp_verify_nonce($_POST['_tc_ajax_saving_nonce'], 'tc-ajax-saving-nonce')) die('No no, no no no no, there\'s a limit !');
	if (current_user_can('manage_options'))
	{
		$response = __('Options have been saved.', 'jm-tc');
		echo $response;
	}
	else
	{
		echo __('No way :/', 'jm-tc');
	}

	// IMPORTANT: don't forget to "exit"

	exit;
}

// Add styles to post edit the WordPress Way >> http://codex.wordpress.org/Function_Reference/wp_enqueue_style#Load_stylesheet_only_on_a_plugin.27s_options_page
add_action('admin_enqueue_scripts', 'jm_tc_metabox_scripts'); // the right hook to add style in admin area
function jm_tc_metabox_scripts($hook_suffix)
{
	$opts = jm_tc_get_options();
	if (('post.php' == $hook_suffix || 'post-new.php' == $hook_suffix) && $opts['twitterCardMetabox'] == 'yes')
	{
		wp_enqueue_style('jm-tc-style-metabox', plugins_url('admin/css/jm-tc-metabox.css', __FILE__));
		wp_enqueue_script('jm-tc-script-metabox', plugins_url('admin/js/jm-tc-metabox.js', __FILE__) , array(
			'jquery'
		) ); 
		
		global $post_type;
		if( get_post_type() == $post_type) {
			if(function_exists('wp_enqueue_media')) {
				wp_enqueue_media();
			}
			else {
				wp_enqueue_script('media-upload');
				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
			}
		}
	}
}

// Add dismissible notice

add_action('admin_notices', 'jm_tc_admin_notice');

if (!function_exists('jm_tc_admin_notice'))
{
	function jm_tc_admin_notice()
	{
		global $current_user;
		$user_id = $current_user->ID;

		// WP SEO Card Option

		if (!get_user_meta($user_id, 'jm_tc_ignore_notice') && current_user_can('install_plugins') && class_exists('WPSEO_Frontend'))
		{
			echo '<div class="error"><p>';
			printf(__('WordPress SEO by Yoast is activated, please uncheck Twitter Card option in this plugin if it is enabled to avoid adding markup twice | <a href="%1$s">Hide Notice</a>') , '?jm_tc_ignore_this=0', 'jm-tc');
			echo "</p></div>";
		}

		// Jetpack Open Graph - Jet pack has been updated yeah ! Thanks guys.

	}
}

add_action('admin_init', 'jm_tc_ignore_this');

if (!function_exists('jm_tc_ignore_this'))
{
	function jm_tc_ignore_this()
	{
		global $current_user;
		$user_id = $current_user->ID;
		/* If user clicks to ignore the notice, add that to their user meta */
		if (isset($_GET['jm_tc_ignore_this']) && '0' == $_GET['jm_tc_ignore_this'])
		{
			add_user_meta($user_id, 'jm_tc_ignore_notice', 'true', true);
		}
	}
}

// Settings page
function jm_tc_docu_links($n = 0)
{
	$anchor = array(
		'#general',
		'#seo',
		'#images',
		'#metabox',
		'#pagehome',
		'#deeplinking',
		'#analytics'

	);
	$docu  = '<a class="button button-secondary docu" target="_blank" href="' . esc_url(admin_url().'admin.php?page=jm_tc_doc') . $anchor[$n] . '">' . __('Documentation', 'jm-tc') . '</a>';
	$docu .= '&nbsp;<a class="button button-secondary docu" target="_blank" href="' . esc_url('https://dev.twitter.com/docs/cards/validation/validator') . '">' . __('Validator', 'jm-tc') . '</a>';
	return $docu;
}

//Call our documentation page
function jm_tc_docu_page() {
		if ( isset( $_GET['page'] ) && 'jm_tc_doc' == $_GET['page'] ) { 
				require( dirname(__FILE__) .'/documentation.php' );	
		 }
			
	}


//Call our option page
function jm_tc_options_page()
{
	$loader = '<div class="form-status"></div>';
	$loader.= '<div class="form-loading hide" style="background-image:url(' . plugins_url('admin/img/loading.gif', __FILE__) . ')"><span class="text-loading">' . __('SAVING...', 'jm-tc') . '</span></div>';
	$loader.= '<input type="submit" name="jm_tc_submit" class="submit" value="' . __('Save changes', 'jm-tc') . '"  />';
	$opts = jm_tc_get_options();
?>
<div class="jm-tc" id="pluginwrapper">
<!-- column-1 -->
<div class="column column-1">
<aside class="header">
<div class="box">
<h1><?php
	_e('JM Twitter Cards', 'jm-tc');
?></h1>
<h2 class="white"><?php
	_e('Get more <br />from 140 characters </br> with Twitter Cards', 'jm-tc');
?></h2>
<p class="plugin-desc"><?php
	_e('Twitter Cards help you richly represent your content within Tweets across the web and on mobile devices. This gives users greater context and insight into the URLs shared on Twitter, which in turn allows Twitter to send more engaged traffic to your site or app.', 'jm-tc');
?></p>
<p class="plugin-desc white"><?php
	_e('With this plugin you can grab summary, summary large image, product, photo and gallery cards.', 'jm-tc');
?></p>
</div>    
<div class="notification hide"></div>
</aside>
</div><!-- /.column-1 -->

<!-- div column-2 -->
<div class="column column-2">        

<form id="jm-tc-form" method="POST" action="options.php">
<?php
	settings_fields('jm-tc');
?>

<section class="postbox" id="tab1" >                         
<h1 class="hndle"><i class="dashicons dashicons-admin-settings"></i> <?php
	_e('General', 'jm-tc');
?></h1>
<?php
	echo jm_tc_docu_links(0);
?>
<p>
<label class="labeltext" for="twitterCardType"><?php
	_e('Choose what type of card you want to use', 'jm-tc');
?> :</label>
<select class="styled-select"  id="twitterCardType" name="jm_tc[twitterCardType]">
<option value="summary" <?php
	echo $opts['twitterCardType'] == 'summary' ? 'selected="selected"' : '';
?> ><?php
	_e('summary', 'jm-tc');
?></option>
<option value="summary_large_image" <?php
	echo $opts['twitterCardType'] == 'summary_large_image' ? 'selected="selected"' : '';
?> ><?php
	_e('summary_large_image', 'jm-tc');
?></option>
<option value="photo" <?php
	echo $opts['twitterCardType'] == 'photo' ? 'selected="selected"' : '';
?> ><?php
	_e('photo', 'jm-tc');
?></option>
<option value="app" <?php
	echo $opts['twitterCardType'] == 'app' ? 'selected="selected"' : '';
?> ><?php
	_e('app', 'jm-tc');
?></option>
</select>
</p>

<p>
<label class="labeltext" for="twitterCreator"><?php
	_e('Enter your Personal Twitter account', 'jm-tc');
?> :</label>
<input id="twitterCreator" type="text" name="jm_tc[twitterCreator]" class="regular-text" value="<?php
	echo jm_tc_remove_at($opts['twitterCreator']);
?>" />
</p>

<p>
<label class="labeltext" for="twitterSite"><?php
	_e('Enter Twitter account for your Website', 'jm-tc');
?> :</label>
<input id="twitterSite" type="text" name="jm_tc[twitterSite]" class="regular-text" value="<?php
	echo jm_tc_remove_at($opts['twitterSite']);
?>" />
</p>

<p>
<label class="labeltext" for="twitterExcerptLength"><?php
	_e('Set description according to excerpt length (words count)', 'jm-tc');
?> :</label>
<input id="twitterExcerptLength" type="number" min="10" max="200" name="jm_tc[twitterExcerptLength]" class="small-number" value="<?php
	echo $opts['twitterExcerptLength'];
?>" />
</p>

<p>
<label class="labeltext" for="twitterProfile"><?php
	_e('Add a field Twitter to profiles', 'jm-tc');
?> :</label>
<select class="styled-select"  id="twitterProfile" name="jm_tc[twitterProfile]">
<option value="yes" <?php
	echo $opts['twitterProfile'] == 'yes' ? 'selected="selected"' : '';
?> ><?php
	_e('yes', 'jm-tc');
?></option>
<option value="no" <?php
	echo $opts['twitterProfile'] == 'no' ? 'selected="selected"' : '';
?> ><?php
	_e('no', 'jm-tc');
?></option>
</select>
</p>

<?php
	if ($opts['twitterProfile'] == 'no'):
?>
<p>
<label class="labeltext" for="twitterUsernameKey"><?php
		_e('Modify user meta key associated with Twitter Account in profiles :', 'jm-tc');
?></label>
<input id="twitterUsernameKey" type="text" name="jm_tc[twitterUsernameKey]" class="regular-text" value="<?php
		echo $opts['twitterUsernameKey'];
?>" />
</p>
<?php
	endif;
?>
<?php
	echo $loader;
?>    
</section>

<section class="postbox"  id="tab2" >  
<h1 class="hndle"><i class="dashicons dashicons-search"></i><?php
	_e('SEO', 'jm-tc');
?></h1>      
<?php
	echo jm_tc_docu_links(1);
?>
<h2><?php
	_e('Grab datas from SEO plugins', 'jm-tc');
?></h2>                                
<p>
<label class="labeltext" for="twitterCardSEOTitle"><?php
	_e('Use SEO by Yoast or All in ONE SEO meta title for your cards (<strong>default is yes</strong>)', 'jm-tc');
?> :</label>
<select class="styled-select"  id="twitterCardSEOTitle" name="jm_tc[twitterCardSEOTitle]">
<option value="yes" <?php
	echo $opts['twitterCardSEOTitle'] == 'yes' ? 'selected="selected"' : '';
?>><?php
	_e('yes', 'jm-tc');
?></option>
<option value="no" <?php
	echo $opts['twitterCardSEOTitle'] == 'no' ? 'selected="selected"' : '';
?> ><?php
	_e('no', 'jm-tc');
?></option>
</select>
</p> 

<p>
<label class="labeltext" for="twitterCardSEODesc"><?php
	_e('Use SEO by Yoast or All in ONE SEO meta description for your cards (<strong>default is yes</strong>)', 'jm-tc');
?> :</label>
<select class="styled-select"  id="twitterCardSEODesc" name="jm_tc[twitterCardSEODesc]">
<option value="yes" <?php
	echo $opts['twitterCardSEODesc'] == 'yes' ? 'selected="selected"' : '';
?> ><?php
	_e('yes', 'jm-tc');
?></option>
<option value="no" <?php
	echo $opts['twitterCardSEODesc'] == 'no' ? 'selected="selected"' : '';
?> ><?php
	_e('no', 'jm-tc');
?></option>
</select>
</p> 
<h2><?php
	_e('If you prefer to use your own fields', 'jm-tc');
?></h2>
<p>
<label class="labeltext" for="twitterCardTitle"><?php
	_e('Enter key for card title', 'jm-tc');
?> :</label>
<input id="twitterCardTitle" type="text" name="jm_tc[twitterCardTitle]" class="regular-text" value="<?php
	echo $opts['twitterCardTitle'];
?>" />
</p>

<p>
<label class="labeltext" for="twitterCardDesc"><?php
	_e('Enter key for card description', 'jm-tc');
?> :</label>
<input id="twitterCardDesc" type="text" name="jm_tc[twitterCardDesc]" class="regular-text" value="<?php
	echo $opts['twitterCardDesc'];
?>" />
</p>
<?php
	echo $loader;
?>
</section>


<section class="postbox"  id="tab3" >
<h1 class="hndle"><i class="dashicons dashicons-format-image"></i><?php
	_e('Thumbnails', 'jm-tc');
?></h1>
<?php
	echo jm_tc_docu_links(2);
?>
<p>
<label class="labeltext" for="twitterCardImgSize"><?php
	_e('Set thumbnail size', 'jm-tc');
?> :</label>
<select class="styled-select"  id="twitterCardImgSize" name="jm_tc[twitterCardImgSize]">
<option value="mobile-non-retina" <?php
	echo $opts['twitterCardImgSize'] == 'mobile-non-retina' ? 'selected="selected"' : '';
?> ><?php
	_e('Max mobile non retina (width: 280px - height: 375px)', 'jm-tc');
?></option>
<option value="mobile-retina" <?php
	echo $opts['twitterCardImgSize'] == 'mobile-retina' ? 'selected="selected"' : '';
?> ><?php
	_e('Max mobile retina (width: 560px - height: 750px)', 'jm-tc');
?></option>
<option value="web" <?php
	echo $opts['twitterCardImgSize'] == 'web' ? 'selected="selected"' : '';
?> ><?php
	_e('Max web size(width: 435px - height: 375px)', 'jm-tc');
?></option>
<option value="small" <?php
	echo $opts['twitterCardImgSize'] == 'small' ? 'selected="selected"' : '';
?> ><?php
	_e('Small (width: 280px - height: 150px)', 'jm-tc');
?></option>
</select>
</p>

<p>
<label class="labeltext" for="twitterCardCrop"><?php
	_e('Do you want to force crop on card Image?', 'jm-tc');
?> :</label>
<select class="styled-select"  id="twitterCardCrop" name="jm_tc[twitterCardCrop]">
<option value="yes" <?php
	echo $opts['twitterCardCrop'] == 'yes' ? 'selected="selected"' : '';
?> ><?php
	_e('Yes', 'jm-tc');
?></option>
<option value="no" <?php
	echo $opts['twitterCardCrop'] == 'no' ? 'selected="selected"' : '';
?> ><?php
	_e('No', 'jm-tc');
?></option>    
</select>
</p>

<p>
<label class="labeltext" for="twitterImage"><?php
	_e('Enter URL for fallback image (image by default)', 'jm-tc');
?> :</label>
<input id="twitterImage" type="url" name="jm_tc[twitterImage]" class="regular-text" value="<?php
	echo $opts['twitterImage'];
?>" />
</p>

<p>
<label class="labeltext" for="twitterImageWidth"><?php
	_e('Image width', 'jm-tc');
?> :</label>
<input id="twitterImageWidth" type="number" min="280" name="jm_tc[twitterImageWidth]" class="small-number" value="<?php
	echo $opts['twitterImageWidth'];
?>" />
</p>

<p>
<label class="labeltext" for="twitterImageHeight"><?php
	_e('Image height', 'jm-tc');
?> :</label>
<input id="twitterImageHeight" type="number" min="150" name="jm_tc[twitterImageHeight]" class="small-number" value="<?php
	echo $opts['twitterImageHeight'];
?>" />
</p>
<?php
	echo $loader;
?>
</section>

<section class="postbox"  id="tab4" >                     
<h1 class="hndle"><i class="dashicons dashicons-welcome-widgets-menus"></i><?php
	_e('Meta Box', 'jm-tc');
?></h1>
<?php
	echo jm_tc_docu_links(3);
?>
<p>
<label class="labeltext" for="twitterCardMetabox"><?php
	_e('Get a <strong>custom metabox</strong> on each post type admin', 'jm-tc');
?> :</label>
<select class="styled-select"  id="twitterCardMetabox" name="jm_tc[twitterCardMetabox]">
<option value="yes" <?php
	echo $opts['twitterCardMetabox'] == 'yes' ? 'selected="selected"' : '';
?> ><?php
	_e('yes', 'jm-tc');
?></option>
<option value="no" <?php
	echo $opts['twitterCardMetabox'] == 'no' ? 'selected="selected"' : '';
?> ><?php
	_e('no', 'jm-tc');
?></option>
</select>
</p>    
<?php
	echo $loader;
?>
</section>

<section class="postbox"  id="tab5" >                     
<h1 class="hndle"><i class="dashicons dashicons-admin-home"></i>Home - <?php
	_e('Posts page', 'jm-tc');
?></h1>
<?php
	echo jm_tc_docu_links(4);
?>
<p>
<label class="labeltext" for="twitterPostPageTitle"><strong><?php
	_e('Enter title for Posts Page :', 'jm-tc');
?> </strong></label><br />
<input id="twitterPostPageTitle" type="text" name="jm_tc[twitterPostPageTitle]" class="regular-text" value="<?php
	echo $opts['twitterPostPageTitle'];
?>" />
</p>

<p>
<label class="labeltext" for="twitterPostPageDesc"><strong><?php
	_e('Enter description for Posts Page (max: 200 words)', 'jm-tc');
?> </strong>:</label><br />
<textarea id="twitterPostPageDesc" rows="4" cols="80" name="jm_tc[twitterPostPageDesc]" class="regular-text"><?php
	echo $opts['twitterPostPageDesc'];
?></textarea>
</p>
<?php
	echo $loader;
?>    
</section>

<!-- the deep linking part -->
<section class="postbox"  id="tab6" >                     
<h1 class="hndle"><i class="dashicons dashicons-admin-links"></i><?php
	_e('Deep Linking', 'jm-tc');
?></h1>
<?php
	echo jm_tc_docu_links(5);
?>
<p>
<label class="labeltext" for="twitterCardDeepLinking"><?php
	_e('Deep linking? ', 'jm-tc');
?> </label>
<select class="styled-select"  id="twitterCardDeepLinking" name="jm_tc[twitterCardDeepLinking]">
<option value="yes" <?php
	echo $opts['twitterCardDeepLinking'] == 'yes' ? 'selected="selected"' : '';
?> ><?php
	_e('yes', 'jm-tc');
?></option>
<option value="no" <?php
	echo $opts['twitterCardDeepLinking'] == 'no' ? 'selected="selected"' : '';
?> ><?php
	_e('no', 'jm-tc');
?></option>
</select>
</p>   


<div id="further-deep-linking">

<em><?php _e('For all the following fields, if you do not want to use leave it blank but be careful with the required markup for your app. Read the documentation please.','jm-tc');?></em>
<!-- iPhone -->
<p>
<label class="labeltext" for="twitteriPhoneName"><strong><?php
	_e('Enter iPhone Name ', 'jm-tc');
?> </strong>:</label><br />
<input id="twitteriPhoneName" type="text" name="jm_tc[twitteriPhoneName]" class="regular-text" value="<?php
	echo $opts['twitteriPhoneName'];
?>" />
</p>

<p>
<label class="labeltext" for="twitteriPhoneUrl"><strong><?php
	_e('Enter iPhone URL ', 'jm-tc');
?> </strong>:</label><br />
<input id="twitteriPhoneUrl" type="url" name="jm_tc[twitteriPhoneUrl]" class="regular-text" value="<?php
	echo esc_url($opts['twitteriPhoneUrl']);
?>" />
</p>

<p>
<label class="labeltext" for="twitteriPhoneId"><strong><?php
	_e('Enter iPhone ID ', 'jm-tc');
?> </strong>:</label><br />
<input id="twitteriPhoneId" type="text" name="jm_tc[twitteriPhoneId]" class="regular-text" value="<?php
	echo $opts['twitteriPhoneId'];
?>" />
</p>
<!-- iPad -->
<p>
<label class="labeltext" for="twitteriPadName"><strong><?php
	_e('Enter iPad Name ', 'jm-tc');
?> </strong>:</label><br />
<input id="twitteriPadName" type="text" name="jm_tc[twitteriPadName]" class="regular-text" value="<?php
	echo $opts['twitteriPadName'];
?>" />
</p>

<p>
<label class="labeltext" for="twitteriPadUrl"><strong><?php
	_e('Enter iPad URL ', 'jm-tc');
?> </strong>:</label><br />
<input id="twitteriPadUrl" type="url" name="jm_tc[twitteriPadUrl]" class="regular-text" value="<?php
	echo esc_url($opts['twitteriPadUrl']);
?>" />
</p>

<p>
<label class="labeltext" for="twitteriPadId"><strong><?php
	_e('Enter iPad ID ', 'jm-tc');
?> </strong>:</label><br />
<input id="twitteriPadId" type="text" name="jm_tc[twitteriPadId]" class="regular-text" value="<?php
	echo $opts['twitteriPadId'];
?>" />
</p>

<!-- Google Play-->

<p>
<label class="labeltext" for="twitterGooglePlayName"><strong><?php
	_e('Enter Google Play Name ', 'jm-tc');
?> </strong>:</label><br />
<input id="twitterGooglePlayName" type="text" name="jm_tc[twitterGooglePlayName]" class="regular-text" value="<?php
	echo $opts['twitterGooglePlayName'];
?>" />
</p>

<p>
<label class="labeltext" for="twitteriGooglePlayUrl"><strong><?php
	_e('Enter Google Play URL ', 'jm-tc');
?> </strong>:</label><br />
<input id="twitterGooglePlayUrl" type="url" name="jm_tc[twitterGooglePlayUrl]" class="regular-text" value="<?php
	echo esc_url($opts['twitterGooglePlayUrl']);
?>" />
</p>

<p>
<label class="labeltext" for="twitteriGooglePlayId"><strong><?php
	_e('Enter Google Play ID ', 'jm-tc');
?> </strong>:</label><br />
<input id="twitteriGooglePlayId" type="text" name="jm_tc[twitterGooglePlayId]" class="regular-text" value="<?php
	echo $opts['twitterGooglePlayId'];
?>" />
</p>
 </div>
<?php 
	echo $loader;
?>
</section>


</form><!-- /#jm-tc-form -->


<!-- the analytics part -->
<div id="tab6" class="postbox link">
<h1 class="hndle"><i class="dashicons dashicons-chart-area"></i><?php
	_e('Analytics', 'jm-tc');
?></h1>
<?php
	echo jm_tc_docu_links(6);
?>
<p>
<?php
	_e('Now you can combine Twitter Cards with', 'jm-tc');
?> <a href="https://analytics.twitter.com/">Twitter Card analytics</a>. <?php
	_e('It allows you to make some tests and then you can choose "top performing Twitter Cards that drove clicks".', 'jm-tc');
?> 
</p>
<p>
<?php
	_e('You can test sources, links, influencers and devices. It is awesome and you should enjoy these new tools.', 'jm-tc');
?>
</p>

<p><?php
	_e('This will help you to set the best card type experience and it will probably improve your marketing value.', 'jm-tc');
?></p>
</div>


<!-- the about part -->
<div class="postbox"> 
<h1 class="hndle"><i class="dashicons dashicons-businessman"></i><?php
	_e('About the developer', 'jm-tc');
?></h1>
<p><img src="http://www.gravatar.com/avatar/<?php
	echo md5('tweetpressfr' . '@' . 'gmail' . '.' . 'com');
?>" style="float:left;margin-right:10px;"/>
<strong>Julien Maury</strong><br />
<?php
	_e('I am a WordPress Developer, I like to make it simple.', 'jm-tc');
?> <br />
<a href="http://www.tweetpress.fr" target="_blank" title="TweetPress.fr - WordPress and Twitter tips">www.tweetpress.fr</a> <br />
<i class="link-like dashicons dashicons-twitter"></i> <a href="http://twitter.com/intent/user?screen_name=tweetpressfr" >@TweetPressFR</a>
</p>
</div>

<div class="postbox">    
<h2 class="hndle"><span><?php
	_e('Help me keep this free', 'jm-tc');
?></span></h2>
<p><?php
	_e('Please help me keep this plugin free.', 'jm-tc');
?></p>
<i class="link-like va-bottom dashicons dashicons-cart"></i><a target="_blank" href="http://www.amazon.fr/registry/wishlist/1J90JNIHBBXL8"><?php
	_e('WishList Amazon', 'jm-ltsc');
?></a>
</div>
<div class="postbox">    
<h2 class="hndle"><span><?php
	_e('Plugin', 'jm-tc');
?></span></h2>
<p><?php
	_e('Maybe you will like this plugin too: ', 'jm-tc');
?><a target="_blank" class="button button-secondary docu" href="http://wordpress.org/plugins/jm-dashicons-shortcode/"><?php
	_e('JM Dashicons Shortcode', 'jm-tc');
?></a></p>
</div>
<!-- /the about part -->
</div><!-- /.column 2 -->
</div><!-- /#pluginwrapper -->

<?php
}

/*
* OPTIONS TREATMENT
*/

// Process options when submitted

function jm_tc_sanitize($options)
{
	return array_merge(jm_tc_get_options() , jm_tc_sanitize_options($options));
}

// Sanitize options

function jm_tc_sanitize_options($options)
{
	$new = array();
	if (!is_array($options)) return $new;
	
	
	if (isset($options['twitterCardType'])) $new['twitterCardType'] = $options['twitterCardType'];
	if (isset($options['twitterCreator'])) $new['twitterCreator'] = esc_attr(strip_tags(jm_tc_remove_at($options['twitterCreator'])));
	if (isset($options['twitterSite'])) $new['twitterSite'] = esc_attr(strip_tags(jm_tc_remove_at($options['twitterSite'])));
	if (isset($options['twitterExcerptLength'])) $new['twitterExcerptLength'] = (int)$options['twitterExcerptLength'];
	if (isset($options['twitterImage'])) $new['twitterImage'] = esc_url($options['twitterImage']);
	if (isset($options['twitterImageWidth'])) $new['twitterImageWidth'] = (int)$options['twitterImageWidth'];
	if (isset($options['twitterImageHeight'])) $new['twitterImageHeight'] = (int)$options['twitterImageHeight'];
	if (isset($options['twitterCardMetabox'])) $new['twitterCardMetabox'] = $options['twitterCardMetabox'];
	if (isset($options['twitterProfile'])) $new['twitterProfile'] = $options['twitterProfile'];
	if (isset($options['twitterPostPageTitle'])) $new['twitterPostPageTitle'] = esc_attr(strip_tags($options['twitterPostPageTitle']));
	if (isset($options['twitterPostPageDesc'])) $new['twitterPostPageDesc'] = esc_attr(strip_tags($options['twitterPostPageDesc']));
	if (isset($options['twitterCardSEOTitle'])) $new['twitterCardSEOTitle'] = $options['twitterCardSEOTitle'];
	if (isset($options['twitterCardSEODesc'])) $new['twitterCardSEODesc'] = $options['twitterCardSEODesc'];
	if (isset($options['twitterCardImgSize'])) $new['twitterCardImgSize'] = $options['twitterCardImgSize'];
	if (isset($options['twitterCardTitle'])) $new['twitterCardTitle'] = esc_attr(strip_tags($options['twitterCardTitle']));
	if (isset($options['twitterCardDesc'])) $new['twitterCardDesc'] = esc_attr(strip_tags($options['twitterCardDesc']));
	if (isset($options['twitterCardCrop'])) $new['twitterCardCrop'] = $options['twitterCardCrop'];
	if (isset($options['twitterUsernameKey'])) $new['twitterUsernameKey'] = esc_attr(strip_tags($options['twitterUsernameKey']));
	
	
	//deep linking	
	if (isset($options['twitterCardDeepLinking'])) $new['twitterCardDeepLinking'] = $options['twitterCardDeepLinking'];
	if (isset($options['twitteriPhoneName'])) $new['twitteriPhoneName'] = esc_attr(strip_tags($options['twitteriPhoneName']));
	if (isset($options['twitteriPadName'])) $new['twitteriPadName'] = esc_attr(strip_tags($options['twitteriPadName']));
	if (isset($options['twitterGooglePlayName'])) $new['twitterGooglePlayName'] = esc_attr(strip_tags($options['twitterGooglePlayName']) );
	if (isset($options['twitteriPhoneUrl'])) $new['twitteriPhoneUrl'] = esc_url(strip_tags($options['twitteriPhoneUrl']));
	if (isset($options['twitteriPadUrl'])) $new['twitteriPadUrl'] = esc_url(strip_tags($options['twitteriPadUrl']));
	if (isset($options['twitterGooglePlayUrl'])) $new['twitterGooglePlayUrl'] = esc_url(strip_tags($options['twitterGooglePlayUrl']));
	if (isset($options['twitteriPhoneId'])) $new['twitteriPhoneId'] = esc_attr(strip_tags($options['twitteriPhoneId']));
	if (isset($options['twitteriPadId'])) $new['twitteriPadId'] = esc_attr(strip_tags($options['twitteriPadId']));
	if (isset($options['twitterGooglePlayId'])) $new['twitterGooglePlayId'] = esc_attr(strip_tags($options['twitterGooglePlayId']));
	
	return $new;
}

// Return default options

function jm_tc_get_default_options()
{
	return array(
		'twitterCardType' => 'summary',
		'twitterCreator' => 'TweetPressFr',
		'twitterSite' => 'TweetPressFr',
		'twitterExcerptLength' => 35,
		'twitterImage' => '',
		'twitterImageWidth' => '280',
		'twitterImageHeight' => '150',
		'twitterCardMetabox' => 'no',
		'twitterProfile' => 'no',
		'twitterPostPageTitle' => get_bloginfo('name') , // filter used by plugin to customize title
		'twitterPostPageDesc' => __('Welcome to', 'jm-tc') . ' ' . get_bloginfo('name') . ' - ' . __('see blog posts', 'jm-tc') ,
		'twitterCardSEOTitle' => 'yes',
		'twitterCardSEODesc' => 'yes',
		'twitterCardImgSize' => 'small',
		'twitterCardTitle' => '',
		'twitterCardDesc' => '',
		'twitterCardCrop' => 'yes',
		'twitterUsernameKey' => 'jm_tc_twitter',
		'twitterCardDeepLinking' => 'no',
		'twitteriPhoneName' => '',
		'twitteriPadName' => '',
		'twitterGooglePlayName' => '',
		'twitteriPhoneUrl' => '',
		'twitteriPadUrl' => '',
		'twitterGooglePlayUrl' => '',
		'twitteriPhoneId' => '',
		'twitteriPadId' => '',
		'twitterGooglePlayId' => ''
	);
}

// Retrieve and sanitize options

function jm_tc_get_options()
{
	$options = get_option('jm_tc');
	return array_merge(jm_tc_get_default_options() , jm_tc_sanitize_options($options));
}