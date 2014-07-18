<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'JM_TC_Metabox' ) ) {

	class JM_TC_Metabox{

		var $opts;
		
		function __construct() {
	
			$this->opts = get_option('jm_tc');
			
			
			//render
			add_action( 'cmb_render_text_number', array($this, 'render_text_number'), 10, 2 );
			add_action( 'cmb_render_text_url_https', array($this, 'render_text_url_https'), 10, 2 );
			
			//alter desc and preview attributes
			add_filter( 'cmb_title_attributes', array($this,'cmb_update_title_description'), 10, 2 );
			
			//register meta box
			add_action( 'cmb_meta_boxes', array($this, 'register_meta_boxes' ), 10, 1 );
			
			//show on/off field in post
			add_filter( 'cmb_show_on', array($this, 'exclude_from_post'), 10, 2 );
			
			//show on/off field in profile
			add_filter( 'cmb_show_on', array($this, 'exclude_from_profile'), 10, 2 );
		}

		// Add number field
		function render_text_number( $field, $meta ) {
			echo '<input type="number" min="', $field['min'], '" max="', $field['max'], '" name="', $field['id'], '" id="', $field['id'], '" value="', $meta , '" style="width:170px;" />','<p class="cmb_metabox_description">', $field['desc'], '</p>';
		}

		// URL field
		function render_text_url_https( $field, $meta ) {
			echo '<input type="url" name="', $field['id'], '" id="', $field['id'], '" value="', $meta, '" style="width:97%" />','<p class="cmb_metabox_description">', $field['desc'], '</p>';
		}

		// Title field customized
		function render_title_custom( $field, $meta ) {
			echo '<input type="url" name="', $field['id'], '" id="', $field['id'], '" value="', $meta, '" style="width:97%" />','<p class="cmb_metabox_description">', $field['desc'], '</p>';
		}

		
		//cmb snippet props to jtsternberg 
		function cmb_update_title_description( $args, $field ) {
		
			if( $field->id() == 'twitter_featured_size' ) 
				$args['desc'] = JM_TC_Thumbs::get_post_thumbnail_weight( $field->object_id );
				
			if( $field->id() == 'preview_title' ) 
				$args['desc'] = JM_TC_Preview::show_preview($field->object_id);
		
			return $args;
		}
		
			
		//on/off 
		public function on_off( $context ) {
		
			switch ( $context ) {
			
			case 'profile' :
				$trigger = apply_filters('jm_tc_exclude_from_profile', $this->opts['twitterProfile'] == 'yes')? 'on' : 'off';
			break;
			
			case 'post' :
				$trigger = apply_filters('jm_tc_exclude_from_post', $this->opts['twitterCardMetabox'] == 'yes') ? 'on' : 'off';
			break;
			
			default:
				$trigger = 'on';
			break;
					
			}
			
			
			return $trigger;
		}
		

		/**
		 * Removes metabox from appearing acccording to meta box settings
		 *
		 * @author Julien Maury inspired by tips given by Thomas Griffin
		 *
		 * @param bool $display
		 * @param array $meta_box The array of metabox options
		 * @return bool $display on success, false on failure
		 */
		public function exclude_from_post( $display, $meta_box ) {

			global $pagenow;
			
			
			 if ( ! isset( $meta_box['show_on']['alt_key'] ) )
				return $display; // If the key isn't set, return

			if ( 'exclude_post' !== $meta_box['show_on']['alt_key'] )
				return $display; // If the key is set but not the one we want, return
			
			$meta_box['show_on']['alt_value'] = ! is_array( $meta_box['show_on']['alt_value'] ) ? array( $meta_box['show_on']['alt_value'] ) : $meta_box['show_on']['alt_value'];

			if ( in_array( $pagenow, array('post.php', 'post-new.php') ) && current_user_can('edit_posts') && in_array( 'on', $meta_box['show_on']['alt_value'] )  ) {

				return $display;
				
			 } else {
			 
				return false;
				
			}

		}		
			

		/**
		 * Removes metabox from appearing on profiles if option is disabled
		 *
		 * @author Julien Maury inspired by tips given by Thomas Griffin
		 *
		 * @param bool $display
		 * @param array $meta_box The array of metabox options
		 * @return bool $display on success, false on failure
		 */
		public function exclude_from_profile( $display, $meta_box ) {

			global $pagenow;
			
			
			 if ( ! isset( $meta_box['show_on']['alt_key'] ) )
				return $display; // If the key isn't set, return

			if ( 'exclude_profile' !== $meta_box['show_on']['alt_key'] )
				return $display; // If the key is set but not the one we want, return
			
			$meta_box['show_on']['alt_value'] = ! is_array( $meta_box['show_on']['alt_value'] ) ? array( $meta_box['show_on']['alt_value'] ) : $meta_box['show_on']['alt_value'];

			if ( 'profile.php' == $pagenow && current_user_can('edit_posts') && in_array( 'on', $meta_box['show_on']['alt_value'] )  ) {

				return $display;
				
			 } else {
			 
				return false;
				
			}

		}


		//Meta box
		function register_meta_boxes( array $meta_boxes )
		{
			if ( ! class_exists( 'cmb_Meta_Box' ) )
			return;
			
			$post_types = get_post_types();
		
			// 1st meta box
			$meta_boxes['jm_tc_metabox'] = array(
			'id'    => 'jm_tc_metabox',
			'title' => __( 'Twitter Cards', 'jm-tc' ),
			'pages' => $post_types,
			'context' => 'advanced',
			'priority' => 'high',
			'show_on' => array( 'alt_value' => self::on_off( 'post' ), 'alt_key' => 'exclude_post'),
			'fields' => array(

			
			// title
			array(
			'type' => 'title',
			'name' => __('Documentation', 'jm-tc'),
			'id'   => 'documentation_title', // Not used but needed for plugin
			'desc' => JM_TC_Admin::docu_links(1),
			),
			
			// title
			array(
			'type' => 'title',
			'name' => __('Preview', 'jm-tc'),
			'id'   => 'preview_title', // Not used but needed for plugin
			),
			
			// title
			array(
			'type' => 'title',
			'name' => __('Card type', 'jm-tc'),
			'id'   => 'type_title', // Not used but needed for plugin
			'desc' => '',
			),
			
			
			array(
			'name'     => __( 'Card Type', 'jm-tc' ),
			'id'       => 'twitterCardType',
			'type'     => 'select',

			'options'  => array(
			'summary' 				=> __( 'Summary', 'jm-tc' ),
			'summary_large_image' 	=> __( 'Summary below Large Image', 'jm-tc' ),
			'photo' 				=> __( 'Photo', 'jm-tc' ),
			'product'				=> __( 'Product', 'jm-tc' ),
			'player' 				=> __( 'Player', 'jm-tc' ),
			'gallery' 				=> __( 'Gallery', 'jm-tc' ),
			'app' 					=> __( 'Application', 'jm-tc' )
			),
			
			'std'	=> $this->opts['twitterCardType'],

			),
			
			// title
			array(
			'type' => 'title',
			'name' => __('Image', 'jm-tc'),
			'id'   => 'image_title', // Not used but needed for plugin
			),
			
			array(
			'id' 	=> 'cardImage',
			'name'  => __('Set another source as twitter image (enter URL)', 'jm-tc'),
			'type' 	=> 'file',
			//'std'	=> $this->opts['twitterImage'], ... probably a bad idea so we won't do this ^^
			),
			

			// title
			array(
			'type' => 'title',
			'name' => __('Product Cards', 'jm-tc'),
			'id'   => 'product_title', // Not used but needed for plugin
			'desc' => '',
			),
			
			array(
			// Field name - Will be used as label
			'name'  => __( 'Enter the first key data for product', 'jm-tc'),
			// Field ID, i.e. the meta key
			'id'    => "cardData1",
			'type'  => 'text_medium',

			),
			array(
			// Field name - Will be used as label
			'name'  => __('Enter the first key label for product', 'jm-tc'),
			// Field ID, i.e. the meta key
			'id'    => "cardLabel1",
			'type'  => 'text_medium',

			),
			
			array(
			// Field name - Will be used as label
			'name'  => __('Enter the second key data for product', 'jm-tc'),
			// Field ID, i.e. the meta key
			'id'    => "cardData2",
			'type'  => 'text_medium',

			),
			array(
			// Field name - Will be used as label
			'name'  => __( 'Enter the second key label for product', 'jm-tc'),
			// Field ID, i.e. the meta key
			'id'    => "cardLabel2",
			'type'  => 'text_medium',

			),
		

			
			// title
			array(
			'type' => 'title',
			'name' => __('Gallery Cards', 'jm-tc'),
			'id'   => 'gallery_title', // Not used but needed for plugin
			'desc' => __('Just use shortcode <strong>[gallery]</strong> and include at least 4 images.', 'jm-tc'),
			),
			

			
			
			// title
			array(
			'type' => 'title',
			'name' => __('Player Cards', 'jm-tc'),
			'id'   => 'player_title', // Not used but needed for plugin
			),
			
			
			array(
			'id'       => 'cardPlayer',
			'name'     => __('URL of iFrame player (MUST BE HTTPS)', 'jm-tc'),
			'type'     => 'text_url_https',

			),
			
			array(
			'name' => __( 'Player width', 'jm-tc'),
			'id'   => "cardPlayerWidth",
			'type' => 'text_number',
			'desc'	   => __('When setting this, make sure player dimension and image dimensions are exactly the same! Image MUST BE greater than 68,600 pixels (a 262x262 square image, or a 350x196 16:9 image)', 'jm-tc'),
			'min'  => 262,
			'max'  => 1000,
			),	
			
			array(
			'name' => __( 'Player height', 'jm-tc'),
			'id'   => "cardPlayerHeight",
			'type' => 'text_number',
			'min'  => 196,
			'max'  => 1000,
			),
			
			array(
			'id'       => 'cardPlayerStream',
			'name'     => __('URL of iFrame player (MUST BE HTTPS)', 'jm-tc') . '[STREAM]',
			'type'     => 'text_url_https',
			'desc'     => __('If you do not understand what is the following field then it is probably a bad idea to fulfill it!', 'jm-tc'),

			),
			
			
			)
			);

				
			$meta_boxes['twitter_creator'] = array(
			'id'            => 'twitter_creator',
			'title'         => __( 'Twitter Creator', 'jm-tc' ),
			'pages'         => array( 'user' ), // Tells CMB to use user_meta vs post_meta
			'show_names'    => true,
			'show_on' 		=> array( 'alt_value' => self::on_off( 'profile' ), 'alt_key' => 'exclude_profile'),
			'fields'        => array(
			array(
			'name'     => __( 'Twitter Creator', 'jm-tc' ),
			'desc'     => __( "Enter your Twitter Account (without @)", "jm-tc"),
			'id'       => 'jm_tc_twitter',
			'type'     => 'text_medium',
			'on_front' => true,
			)
			)
			);
			
			
			
			$meta_boxes['twitter_image_size'] = array(
			'id'            => 'twitter_image_size',
			'title'         => __( 'Twitter Image Size', 'jm-tc' ),
			'pages'         => $post_types,
			'context'       => 'side',
			'priority'      => 'low',
			'show_names'    => true,
			'show_on'	    => array( 'alt_value' => self::on_off( 'post' ), 'alt_key' => 'exclude_post'),
			'fields'        => array(
			
			
			array(
			 'id' 	=> 'twitter_image_width_height',
			 'type' => 'title',
			 'name'	=> 'Rendering on Twitter: width and height',		
			),
			
			
			 
			array(
			'name' => __( 'Image width', 'jm-tc'),
			'id'   => "cardImageWidth",
			'type' => 'text_number',
			'min'  => 280,
			'max'  => 1000,
			'std'	=> $this->opts['twitterImageWidth'],
			),	
			
			array(
			'name' => __('Image height', 'jm-tc'),
			'id'   => "cardImageHeight",
			'type' => 'text_number',
			'min'  => 150,
			'max'  => 1000,
			'std'	=> $this->opts['twitterImageHeight'],
			),	
						
			
			array(
			 'id' 	=> 'twitter_featured_size',
			 'type' => 'title',
			 'name'	=> 'Size of the current featured image',
			
			),
			
			
			
			)
			);

			
			return $meta_boxes;
			
		}

	}	
}	