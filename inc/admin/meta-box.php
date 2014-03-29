<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'JM_TC_Metabox' ) ) {
	class JM_TC_Metabox {

		
		function hooks() {
			//Don't show if users do not want it
			add_filter( 'cmb_show_on', array(&$this, 'metabox_exclude'), 10, 2 );
			
			//render
			add_action( 'cmb_render_text_number', array(&$this, 'render_text_number'), 10, 2 );
			add_action( 'cmb_render_text_url_https', array(&$this, 'render_text_url_https'), 10, 2 );
			
			//validate
			add_filter( 'cmb_validate_textarea_small', array(&$this, 'validate_textarea_small') );
			add_filter( 'cmb_validate_text_url_https', array(&$this, 'validate_text_url_https') );
			add_filter( 'cmb_validate_text_number', array(&$this, 'validate_text_number') );
			add_filter( 'cmb_validate_text', array(&$this, 'validate_text') );
			add_filter( 'cmb_validate_text_medium', array(&$this, 'validate_text') );
			add_filter( 'cmb_validate_text_small', array(&$this, 'validate_text') );
			
			
			//register meta box
			add_action( 'cmb_meta_boxes', array(&$this, 'register_meta_boxes' ) );
		}
		
		function metabox_exclude( $display, $meta_box ) {
		
			$opts = get_option('jm_tc_options');

			// we want this for people that are allowed to set posts
			if ( !current_user_can('publish_posts') || $opts['twitterCardMetabox'] == 'no' ) return;
			
			return $display;
			
		}
		

		// Add number field
		function render_text_number( $field, $meta ) {
			echo '<input type="number" min="', $field['min'], '" name="', $field['id'], '" id="', $field['id'], '" value="', $meta , '" style="width:170px;" />','<p class="cmb_metabox_description">', $field['desc'], '</p>';
		}

		// URL field
		function render_text_url_https( $field, $meta ) {
			echo '<input type="url" name="', $field['id'], '" id="', $field['id'], '" value="', $meta, '" style="width:97%" />','<p class="cmb_metabox_description">', $field['desc'], '</p>';
		}




		function validate_text( $new ) {

		   if ( '' == $new ) {
				$new = '';
		   }  
		   
		   if ( !is_string ($new) ) $new = '';
			 
		   return $new;
		}



		//check textarea 
		function validate_textarea_small( $new ) {
		   
		   if( '' == $new || str_word_count( $new ) > 200 || str_word_count( $new ) < 10 ) { 
			   $new = __('Welcome to', 'jm-tc') . ' ' . get_bloginfo('name') . ' - ' . __('see blog posts', 'jm-tc'); 
		   } 
			
			return $new;
		}


		//check number
		function validate_text_number( $new ) {

			if ( '' == $new ) { 
				$new = '';
			} 
			
			if ( !is_int( $new ) ) {
				$new = '';
			} 
			
			return abs($new);
		}

		//check url

		function validate_text_url_https( $new ) {
			if ( '' == $new ) {
				$new = ''; 
			}

			if ( !preg_match('/https:\/\//', $new) ) {
				$new = 'https://' . $new;
			}

			return esc_url($new);
		}

		//Meta box
		function register_meta_boxes( array $meta_boxes )
		{
			if ( ! class_exists( 'cmb_Meta_Box' ) )
				return;

			$post_types = get_post_types();
			$opts		= get_option('jm_tc_options');


			// 1st meta box
			$meta_boxes['jm_tc_metabox'] = array(
				'id'    => 'jm_tc_metabox',
				'title' => __( 'Twitter Cards', 'jm-tc' ),
				'pages' => $post_types,
				'context' => 'advanced',
				'priority' => 'high',
				'fields' => array(
					array(
						'name' => __( 'Do you want to deactivate twitter cards metabox on this post?', 'jm-tc' ),
						'id'   => "twitterCardCancel",
						'type' => 'select',
						'options'  => array(
							'no' 	=> __( 'No', 'jm-tc' ),	
							'yes' 	=> __( 'Yes', 'jm-tc' ),			
						),
					),
										
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
							'summary_large_image' 	=> __( 'Summary Large Card', 'jm-tc' ),
							'photo' 				=> __( 'Photo', 'jm-tc' ),
							'product'				=> __( 'Product', 'jm-tc' ),
							'player' 				=> __( 'Player', 'jm-tc' ),
							'gallery' 				=> __( 'Gallery', 'jm-tc' ),
						),

					),
					
					// title
					array(
						'type' => 'title',
						'name' => __('Image', 'jm-tc'),
						'id'   => 'image_title', // Not used but needed for plugin
						'desc' => '',
					),
					
					array(
						'id' => 'cardImage',
						'name'  => __('Set another source as twitter image (enter URL)', 'jm-tc'),
						'type' => 'file',
					),
					
					array(
						'name'     => __( 'Define specific size for twitter:image display', 'jm-tc'),
						'id'       => "cardImgSize",
						'type'     => 'select',

						'options'  => array(
							'mobile-non-retina' => __('Max mobile non retina (width: 280px - height: 375px)', 'jm-tc'),
							'mobile-retina' => __('Max mobile retina (width: 560px - height: 750px)', 'jm-tc'),
							'web' => __('Max web size(width: 435px - height: 375px)', 'jm-tc'),
							'small' => __('Small (width: 280px - height: 150px)', 'jm-tc'),
						),

					),
					
					
					
					// title
					array(
						'type' => 'title',
						'name' => __('Photo Cards', 'jm-tc'),
						'id'   => 'photo_title', // Not used but needed for plugin
						'desc' => '',
					),
					array(
						'name' => __( 'Image width', 'jm-tc'),
						'id'   => "cardPhotoWidth",
						'type' => 'text_number',
						'min'  => 280,
					),	
					
					array(
						'name' => __('Image height', 'jm-tc'),
						'id'   => "cardPhotoHeight",
						'type' => 'text_number',
						'min'  => 150,
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
					
					array(
						'name' => __('Image width', 'jm-tc'),
						'id'   => "cardProductWidth",
						'type' => 'text_number',
						'min'  => 280,
					),	
					
					array(
						'name' => __('Image height', 'jm-tc'),
						'id'   => "cardProductHeight",
						'type' => 'text_number',
						'min'  => 150,
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
						'name' => __( 'Image width', 'jm-tc'),
						'id'   => "cardPlayerWidth",
						'type' => 'text_number',
						'desc'	   => __('When setting this, make sure player dimension and image dimensions are exactly the same! Image MUST BE greater than 68,600 pixels (a 262x262 square image, or a 350x196 16:9 image)', 'jm-tc'),
						'min'  => 262,
					),	
					
					array(
						'name' => __( 'Image height', 'jm-tc'),
						'id'   => "cardPlayerHeight",
						'type' => 'text_number',
						'min'  => 196,
					),
					
					array(
						'id'       => 'cardPlayerStream',
						'name'     => __('URL of iFrame player (MUST BE HTTPS)', 'jm-tc'),
						'type'     => 'text_url_https',
						'desc'     => __('If you do not understand what is the following field then it is probably a bad idea to fulfill them!', 'jm-tc'),

					),
					
					
				)
			);
			
			// 2nd meta box			
			if(	 $opts['twitterCardProfile'] == 'yes' ) {
			
				$meta_boxes['twitter_creator'] = array(
					'id'            => 'twitter_creator',
					'title'         => __( 'Twitter Creator', 'jm-tc' ),
					'pages'         => array( 'user' ), // Tells CMB to use user_meta vs post_meta
					'show_names'    => true,
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
			
			
			}
			
			return $meta_boxes;
			
		}

	}	
}	