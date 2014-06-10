<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if( class_exists('JM_TC_Utilities') ) {

	class JM_TC_Markup extends JM_TC_Options {	
		
		private static $_this;
		var $opts;
		var $muti_opts;
		var $textdomain = 'jm-tc';

		function __construct() {
			
			self::$_this     = $this;
			$this->opts  	 = get_option('jm_tc');
			$this->muti_opts = get_site_option('jm_tc_network'); 
			add_action('wp_head', array( $this, 'add_markup'), 2 );
			
		}
		
		
		//being nicer with removers !
		static function this() {
			
			return self::$_this;
		}
		

		/*
		* Add meta to head section
		*/			
		public function add_markup() {
			
			global $post;
			
			$options = new JM_TC_Options;
			
			echo "\n" . '<!-- JM Twitter Cards by Julien Maury ' . JM_TC_VERSION . ' -->' . "\n";
			
			if( 
					is_singular() 
					&& !is_front_page() 
					&& !is_home() 
					&& !is_404() 
					&& !is_tag()
					
					) {
				
				/* most important meta */
				$this->display_markup( $options->cardType( $post->ID ) );
				$this->display_markup( $options->creatorUsername( true ) );
				$this->display_markup( $options->siteUsername() );
				$this->display_markup( $options->title( $post->ID ) );
				$this->display_markup( $options->description( $post->ID ) );
				$this->display_markup( $options->image( $post->ID ) );
				
				
				/* secondary meta */
				$this->display_markup( $options->cardDim( $post->ID ) );
				$this->display_markup( $options->product( $post->ID ) );
				$this->display_markup( $options->player( $post->ID ) );
				
				
			}
			
			elseif( is_home() || is_front_page() ) {
				
				$this->display_markup( $options->cardType() ); 
				$this->display_markup( $options->siteUsername() );
				$this->display_markup( $options->creatorUsername() );
				$this->display_markup( $options->title() );
				$this->display_markup( $options->description() );	
				$this->display_markup( $options->image() );	
				$this->display_markup( $options->cardDim() );		
				
			}
			
			
			else {
				
				$options->error( __('Twitter Cards are off for those pages.', $this->textdomain) );
			}
			
			
			$this->display_markup( $options->deeplinking() );
			
			
			echo '<!-- /JM Twitter Cards -->' . "\n\n";
			
		}	

		/*
		* Display the different meta
		*/
		private function display_markup( $datas ){
		
			
			if ( is_array( $datas ) ) {
				
				foreach ( $datas as $name => $value ) {
					
					if( $value != '' ) {
					
					$default  = ( is_multisite() ) ? $this->multi_opts['twitterNetworkCardOg'] : $this->opts['twitterCardOg'];
					
						if ( $default = 'yes' && in_array(  $name, array('title','description','image','image:width','image:height' ) ) ) {
							
							$is_og = 'og';
							
						} else $is_og = 'twitter';
						
						
						echo $meta = '<meta name="'.$is_og.':'.$name.'" content="'.$value.'">' . "\n";
						
					} 				
					
				}
				
			} elseif ( is_string( $datas ) ) {
				
				echo $meta = '<!-- [(-_-)@ '. $datas.' @(-_-)] -->' . "\n";
				
			} 
			
		}
		

	}

}
