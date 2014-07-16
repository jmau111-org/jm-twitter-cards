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
			
			self::$_this     = $this;
			$this->opts  	 = get_option('jm_tc'); 
			
		}
		
		
		//being nicer with removers !
		static function this() {
			
			return self::$_this;
		}
		
		/*
		* Add just one line before meta
		*/
		public function html_comments( $end = false ) {
		
			if( !$end )
				echo "\n" . '<!-- JM Twitter Cards by Julien Maury ' . JM_TC_VERSION . ' -->' . "\n";
			else	
				echo '<!-- /JM Twitter Cards ' . JM_TC_VERSION . ' -->' . "\n\n";
		}
		

		/*
		* Add meta to head section
		*/			
		public function add_markup() {
			
			global $post;
			
			$options = new JM_TC_Options;
			
			if( 
					is_singular() 
					&& !is_front_page() 
					&& !is_home() 
					&& !is_404() 
					&& !is_tag()
					
					) {
					
				$this->html_comments();
				
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
				$this->display_markup( $options->deeplinking() );
				
				$this->html_comments(true);
				
				
			}
			
			if( is_home() || is_front_page() ) {
			
				$this->html_comments();
				
				$this->display_markup( $options->cardType() ); 
				$this->display_markup( $options->siteUsername() );
				$this->display_markup( $options->creatorUsername() );
				$this->display_markup( $options->title() );
				$this->display_markup( $options->description() );	
				$this->display_markup( $options->image() );	
				$this->display_markup( $options->cardDim() );	
				$this->display_markup( $options->deeplinking() );	
				
				$this->html_comments(true);
			}
			
		}	

		/*
		* Display the different meta
		*/
		private function display_markup( $datas ){
		
			
			if ( is_array( $datas ) ) {
				
				foreach ( $datas as $name => $value ) {
					
					if( $value != '' ) {
					
						if ( $this->opts['twitterCardOg'] == 'yes' && in_array(  $name, array('title','description','image','image:width','image:height' ) ) ) {
							
							$is_og 		= 'og';
							$name_tag 	= 'property';
							
						} else {
						
							$is_og 		= 'twitter';
							$name_tag 	= 'name';
						}
						
						echo $meta = '<meta '.$name_tag.'="'.$is_og.':'.$name.'" content="'.$value.'">' . "\n";
						
					} 				
					
				}
				
			} elseif ( is_string( $datas ) ) {
				
				echo $meta = '<!-- [(-_-)@ '. $datas.' @(-_-)] -->' . "\n";
				
			} 
			
		}
		

	}

}
