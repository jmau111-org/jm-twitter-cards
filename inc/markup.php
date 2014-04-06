<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


// For now it's not OOP it is hardly a draft
// 31/01 : Ok now it is fairly a draft ^^
if( ! class_exists('JM_TC_Markup') ) {

	Class JM_TC_Markup extends JM_TC_Utilities {	
	
		var $opts;
		var $textdomain = 'jm-tc';

		function __construct() {
			$this->opts = get_option('jm_tc_options');
			add_action('wp_head', array(&$this, 'add_markup'), 2 );
			
		}

		/*
		* Add meta to head section
		*/			
		public function add_markup() {
			
			global $post;
			
			echo "\n" . '<!-- JM Twitter Cards by Julien Maury ' . JM_TC_VERSION . ' -->' . "\n";
			
			if( is_singular() && !is_front_page() && !is_home() && !is_404() && !is_tag() ) {

			
				/* most important meta */
				$this->cardType( $post->ID );
				$this->creatorUsername( $post->post_author );
				$this->siteUsername();
				$this->title( $post->ID );
				$this->description( $post->ID );
				$this->image( $post->ID );
				
				
				/* secondary meta */
				$this->cardDim( $post->ID );
				$this->product( $post->ID );
				$this->player( $post->ID );
				
				
			}
			
			elseif( is_category() || is_tax() ) {
				
				$this->cardType(); 
				$this->siteUsername();	
				$this->title( false, true );
				$this->description( false, true );	
				$this->image();
				$this->cardDim();	
				
			}
			
			elseif( is_home() || is_front_page() ) {
			
				$this->cardType(); 
				$this->siteUsername();
				$this->creatorUsername();
				$this->title();
				$this->description();	
				$this->image();	
				$this->cardDim();		
				
			}
			
			
			else {
			
				$this->display_markup( '', '', __('Twitter Cards are off for those pages.', $this->textdomain) );
			}
			
		
			$this->deeplinking();
			
			
			echo '<!-- /JM Twitter Cards -->' . "\n\n";
		
		}	
		
		
		
		
		/*
		* retrieve datas from SEO Plugins
		*/

		public static function get_seo_plugin_datas( $post_id = false, $type ) {
				
			$plugin = 'WPSEO_Frontend';
			
			switch ( class_exists( $plugin ) ) {
				case 'WPSEO_Frontend' :
					$object = new WPSEO_Frontend();
					$title  = $object->title(false);
					$desc   = $object->metadesc(false); 	
				break;
				
				case 'All_in_One_SEO_Pack' :
					$title = htmlspecialchars( stripcslashes( get_post_meta($post_id, '_aioseop_title', true) ) );
					$desc  = htmlspecialchars( stripcslashes( get_post_meta($post_id, '_aioseop_description', true) ) );					
				break;
				
				case 'acf_field' :
					$title = get_field( $opts['twitterCardTitle'], $post_id  );
					$desc  = get_field( $opts['twitterCardDesc'], $post_id  );
				
				default:
					$title = the_title_attribute( array( 'echo' => false));
					$desc  = static::get_excerpt_by_id($post_id);
				break;
			
			}
			
			switch( $type ) {
				
				case "title" :
					$data = $title;
				break;
				
				case "desc" :
					$data = $desc;
				break;
			
			}
			
			return $data;
			
		}
		

		/*
		* Display the different meta
		*/
		private function display_markup( $name, $metadata, $error = false ){
	
			if( !$error ) {
				echo '<meta name="twitter:'.$name.'" content="'.$metadata.'">' . "\n";
				
			} else {
				if( current_user_can('edit_posts') ) echo '<!-- [(-_-)@ '. $error .' @(-_-)] -->' . "\n";
			}
		}
		
		/*
		* Retrieve the meta card type
		*/		
		public function cardType( $post_id = false ) {
			
			$cardType = (  ($cardTypePost = get_post_meta($post_id, 'twitterCardType', true) ) != '' ) ? $cardTypePost  : $this->opts['twitterCardType'];

			$this->display_markup( 'card',  apply_filters('jm_tc_card_type', $cardType) );
		}
		
		/*
		* Retrieve the meta creator
		*/		
		public function creatorUsername( $post_author = false ) {
		
			if( $post_author ) {
		
			//to be modified or left with the value 'jm_tc_twitter'
				
				$cardUsernameKey 	= $this->opts['twitterCardUsernameKey'];
				$cardCreator 		= get_the_author_meta( $cardUsernameKey, $post_author );
				
				$cardCreator		= ($cardCreator != '') ? $cardCreator : $this->opts['twitterCardCreator'];
				$cardCreator 		=  '@' . static::remove_at( $cardCreator );
			
			} else {
			
				$cardCreator = '@' .  static::remove_at( $this->opts['twitterCardCreator'] );
			}
			
			
			$this->display_markup( 'creator',  apply_filters('jm_tc_card_creator', $cardCreator) );
		}
		
		/*
		* retrieve the meta site
		*/
		public function siteUsername() {
			
			$cardSite =  '@' . static::remove_at( $this->opts['twitterCardSite'] );
			$this->display_markup( 'site',  apply_filters('jm_tc_card_site', $cardSite) );
		}
		
		
		/*
		* retrieve the title
		*/
		public function title($post_id = false, $is_tax = false) {
		
			if($post_id) {
			
				if( $this->opts['twitterCardSEOTitle'] == 'yes'){
			
					$cardTitle = static::get_seo_plugin_datas($post_id, 'title');
				
				} else {
				
					$cardTitle = the_title_attribute( array( 'echo' => false));
					
				}	

			} elseif( !$post_id && $is_tax ) {
			
					$cardTitle 	= ( '' != ( $tax_name = get_queried_object()->name ) ) ? $tax_name : get_bloginfo('name');
				
				
			} else {
				
				$cardTitle = get_bloginfo('name');

			}			

			$this->display_markup( 'title',  apply_filters('jm_tc_get_title', $cardTitle) );
		
		}
		
		/*
		* retrieve the description
		*/
		public function description($post_id = false, $is_tax = false) {
		
			if($post_id) {
				
				if( $this->opts['twitterCardSEODesc'] == 'yes'){
			
					$cardDescription = static::get_seo_plugin_datas($post_id, 'desc') ;
				
				} else {
				
					$cardDescription = static::get_excerpt_by_id($post_id);
					
				}	
				
			} elseif( !$post_id && $is_tax ) {
				
					$cardDescription = ( '' != term_description() ) ? wp_strip_all_tags( term_description() ) :  $this->opts['twitterCardPostPageDesc'];
					
			} else {
				
				$cardDescription = $this->opts['twitterCardPostPageDesc'];
			}
			

			$this->display_markup( 'description',  apply_filters('jm_tc_get_excerpt', $cardDescription) );			
		
		}	
		
		

		/*
		* retrieve the images
		*/
		
		public function image( $post_id = false ) {
		
			$cardImage 			= get_post_meta($post_id, 'cardImage', true); 
		
				//gallery
				if( ($cardType  = get_post_meta($post_id, 'twitterCardType', true) ) != 'gallery' ) 
				{
					if (get_the_post_thumbnail($post_id ) != '')
					{
						if ($cardImage != '')
						{ // cardImage is set
							$image = $cardImage;
						}
						else
						{
							$image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id($post_id) , JM_TC_Thumbs::thumbnail_sizes() );
							$image =  $image_attributes[0];
						}

					}
					
					elseif (get_the_post_thumbnail($post_id ) == '' && $cardImage != '')
					{
						$image = $cardImage;
					}
					
					elseif ( 'attachment' == get_post_type() ) 
					{

						$image =  wp_get_attachment_url($post_id);
					}
					
					elseif( $post_id == false ) 
					{ 
	
						$image = $this->opts['twitterCardImage'];
					}
					
					else {
						//fallback
						$image = $this->opts['twitterCardImage'];
					}
					
					
					$this->display_markup( 'image', apply_filters( 'jm_tc_image_source', $image) );
					
				}
				else
				{ // markup will be different
				global $post;

						if (  is_a($post, 'WP_Post') && static::has_shortcode($post->post_content, 'gallery') )
						{

							// get attachment for gallery cards

							$args = array(
							'post_type' => 'attachment',
							'numberposts' => - 1,
							'exclude' => get_post_thumbnail_id() ,
							'post_mime_type' => 'image',
							'post_status' => null,
							'post_parent' => $post_id 
							);
							$attachments = get_posts($args);
							if ($attachments && count($attachments) > 3)
							{
								$i = 0;
								foreach($attachments as $attachment)
								{

									// get attachment array with the ID from the returned posts

									$pic = wp_get_attachment_url($attachment->ID);
									$this->display_markup( 'image' . $i, apply_filters( 'jm_tc_image_source', $pic ) );
									
									$i++;
									if ($i > 3) break; //in case there are more than 4 images in post, we are not allowed to add more than 4 images in our card by Twitter
								}
							}
						}
						
						else
						{
							$this->display_markup( '', '', __('Warning : Gallery Card is not set properly ! There is no gallery in this post !', $this->textdomain) );
						}
					
				}
		
		}
		
		
		
		/*
		* Product additional fields
		*/
		public function product($post_id = false){

			if( ($cardType = get_post_meta($post_id, 'twitterCardType', true) ) == 'product') {
			
				$data1 			= get_post_meta($post_id, 'cardData1', true);
				$label1		 	= get_post_meta($post_id, 'cardLabel1', true);
				$data2 			= get_post_meta($post_id, 'cardData2', true);
				$label2 		= get_post_meta($post_id, 'cardLabel2', true);
				
				$product		= array( 
									'data1'  => $data1, 
									'label1' => $label1, 
									'data2'  => $data2, 
									'label2' => $label2
								);
				
				
				if ( $data1 != '' && $label1 != '' && $data2 != '' && $label2 != '' )
				{
					foreach ($product as $field => $value ) $this->display_markup( $field,  apply_filters('jm_tc_product_field-'.$field, $value) );
				}		
				
				else 
				{
					 $this->display_markup( '', '', __('Warning : Product Card is not set properly ! There is no product datas !', $this->textdomain) );
				}
		
			} else {
				return;
			}
		}
		
		/*
		* Player additional fields
		*/
		public function player($post_id = false){	

			if( ($cardType = get_post_meta($post_id, 'twitterCardType', true) ) == 'player') {
			
				$playerUrl       	= get_post_meta($post_id, 'cardPlayer', true);
				$playerStreamUrl 	= get_post_meta($post_id, 'cardPlayer', true);
				$playerWidth 		= get_post_meta($post_id, 'cardPlayerWidth', true);
				$playerHeight 		= get_post_meta($post_id, 'cardPlayerHeight', true);
					
					//Player
					if ( $playerUrl != '' ) 
					{
						$this->display_markup( 'player',  apply_filters('jm_tc_player_url', $playerUrl) );
					} 
					
					else
					{
						$this->display_markup( '', '', __('Warning : Player Card is not set properly ! There is no URL provided for iFrame player !', $this->textdomain) );				
					}
					
					//Player stream
					if ( $playerStreamUrl != '' ) 
					{
					
					$codec = "video/mp4; codecs=&quot;avc1.42E01E1, mp4a.40.2&quot;";
					
						$this->display_markup( 'player:stream',  apply_filters('jm_tc_player_stream_url', $playerUrl) );
						$this->display_markup( 'player:stream:content_type',  apply_filters('jm_tc_player_stream_codec', $codec) );
					
					} else {
						return;
					}
					
					//Player width and height
					if ( $playerWidth != '' && $playerHeight != '' ) 
					{				
						$this->display_markup( 'player:width',  apply_filters('jm_tc_player_width', $playerWidth) );
						$this->display_markup( 'player:height',  apply_filters('jm_tc_player_height', $playerHeight) );
					}
					
					else 
					{
						$this->display_markup( 'player:width',  apply_filters('jm_tc_player_width', '435') );
						$this->display_markup( 'player:height',  apply_filters('jm_tc_player_height', '251') );	
					}
					
							
			} else {
				return;
			}
		
		}
		
		
		
		/*
		* Image Width and Height
		*/
		
		public function cardDim($post_id = false){	
		
		
			$type = (  ($cardTypePost = get_post_meta($post_id, 'twitterCardType', true) ) != '' ) ? $cardTypePost  : $this->opts['twitterCardType'];
		
					
			if(  in_array( $type, array('photo','product') )  ) {
			
				$width  = ( '' != ($cardWidth = get_post_meta($post_id, 'card'.ucwords($type).'Width', true) ) ) ? $cardWidth : $this->opts['twitterCardImageWidth'];
				$height = ( '' != ($cardHeight = get_post_meta($post_id, 'card'.ucwords($type).'Height', true) ) ) ? $cardHeight : $this->opts['twitterCardImageHeight'];
				
				$this->display_markup( 'image:width',  $width );
				$this->display_markup( 'image:height',  $height );
			
			} elseif( in_array( $type, array('photo','product') ) && !$post_id ) {
			
				$this->display_markup( 'image:width',  $this->opts['twitterCardWidth'] );
				$this->display_markup( 'image:height',  $this->opts['twitterCardHeight'] );
			}
			
			else {
				return;
			}

				 		
		}
		
		
		/*
		* retrieve the deep linking and app install meta
		*/		
		public function deeplinking(){
		
			if( $this->opts['twitterCardDeepLinking'] == 'yes' ) {
					
					if( $this->opts['twitterCardiPhoneName'] != '' ) $this->display_markup( 'app:name:iphone',  $this->opts['twitterCardiPhoneName'] );
					if( $this->opts['twitterCardiPadName'] != '' ) $this->display_markup( 'app:name:ipad', $this->opts['twitterCardiPadName'] );
					if( $this->opts['twitterCardGooglePlayName'] != '' ) $this->display_markup( 'app:name:googleplay', $this->opts['twitterCardGooglePlayName'] );
					if( $this->opts['twitterCardiPhoneUrl'] != '' ) $this->display_markup( 'app:url:iphone', $this->opts['twitterCardiPhoneUrl'] );
					if( $this->opts['twitterCardiPadUrl'] != '' ) $this->display_markup( 'app:url:ipad', $this->opts['twitterCardiPhoneUrl'] );
					if( $this->opts['twitterCardGooglePlayUrl'] != '' ) $this->display_markup( 'app:url:googleplay', $this->opts['twitterCardGooglePlayUrl'] );
					if( $this->opts['twitterCardiPhoneId'] != '' ) $this->display_markup( 'app:id:iphone', $this->opts['twitterCardiPhoneId'] );
					if( $this->opts['twitterCardiPadId'] != '' ) $this->display_markup( 'app:id:ipad', $this->opts['twitterCardiPadId'] );
					if( $this->opts['twitterCardGooglePlayId'] != '' ) $this->display_markup( 'app:id:googleplay', $this->opts['twitterCardGooglePlayId'] );
			
			}
		}
		

	}

}
