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
		var $textdomain = 'jm-tc';

		function __construct() {
			
			self::$_this = $this;
			$this->opts = get_option('jm_tc');
			add_action('wp_head', array(&$this, 'add_markup'), 2 );
			
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
			
			echo "\n" . '<!-- JM Twitter Cards by Julien Maury ' . JM_TC_VERSION . ' -->' . "\n";
			
			if( 
					is_singular() 
					&& !is_front_page() 
					&& !is_home() 
					&& !is_404() 
					&& !is_tag()
					
					) {
				
				/* most important meta */
				$this->display_markup( parent::cardType( $post->ID ) );
				$this->display_markup( parent::creatorUsername( true ) );
				$this->display_markup( parent::siteUsername() );
				$this->display_markup( parent::title( $post->ID ) );
				$this->display_markup( parent::description( $post->ID ) );
				$this->display_markup( parent::image( $post->ID ) );
				
				
				/* secondary meta */
				$this->display_markup( parent::cardDim( $post->ID ) );
				$this->display_markup( parent::product( $post->ID ) );
				$this->display_markup( parent::player( $post->ID ) );
				
				
			}
			
			elseif( is_home() || is_front_page() ) {
				
				$this->display_markup( parent::cardType() ); 
				$this->display_markup( parent::siteUsername() );
				$this->display_markup( parent::creatorUsername() );
				$this->display_markup( parent::title() );
				$this->display_markup( parent::description() );	
				$this->display_markup( parent::image() );	
				$this->display_markup( parent::cardDim() );		
				
			}
			
			
			else {
				
				parent::error( __('Twitter Cards are off for those pages.', $this->textdomain) );
			}
			
			
			$this->display_markup( parent::deeplinking() );
			
			
			echo '<!-- /JM Twitter Cards -->' . "\n\n";
			
		}	

		/*
		* Display the different meta
		*/
		private function display_markup( $datas ){
			
			
			if ( is_array( $datas ) ) {
				
				foreach ( $datas as $name => $value ) {
					
					if( !empty($value) ) {
						
						echo $meta = '<meta name="twitter:'.$name.'" content="'.$value.'">' . "\n";
						
					} else{
						
						return;
					}				
					
				}
				
			} elseif ( is_string( $datas ) ) {
				
				echo $meta = '<!-- [(-_-)@ '. $datas.' @(-_-)] -->' . "\n";
				
			} else {
				
				return;
			}

			
			//var_dump($datas);
			
		}


		

	}

}
