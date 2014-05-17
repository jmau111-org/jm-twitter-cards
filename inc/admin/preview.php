<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if( class_exists('JM_TC_Options') ) {


	class JM_TC_Preview extends JM_TC_Options {
		
		
		function show_preview($post_ID){
			
			/* most important meta */
			$cardType = self::get_datas( parent::cardType( $post_ID ) );
			$creator = self::get_datas( parent::creatorUsername( true ) );
			$site = self::get_datas( parent::siteUsername() );
			$title = self::get_datas( parent::title( $post_ID ) );
			$description = self::get_datas( parent::description( $post_ID ) );
			$img = self::get_datas( parent::image( $post_ID ) );
			
			
			/* secondary meta */
			$product = self::get_datas( parent::product( $post_ID ) );
			$player = self::get_datas( parent::player( $post_ID ) );
			
			
			$app 	= '';
			$size 	= 16;
			$class  = 'featured-image';
			$tag	= 'img';
			$close_tag = '';
			$src	= 'src';
			
			if( in_array($cardType ,array( 'summary_large_image') ) ) {
				
				$styles = "width:100%;";
				$size   = "100%";	
			}
			
			elseif( in_array($cardType ,array( 'photo') ) ) {
				
				$styles = "width:100%;";
				$size   = "100%";
				
			}

			elseif( in_array($cardType ,array( 'player') ) ) {
				
				$styles 	= "width:100%;";
				$src		= "controls poster";
				$tag    	= "video";
				$close_tag 	= "</video>";
				$size   	= "100%";
				
			}
			
			elseif( in_array($cardType ,array( 'summary' ) ) ) {
				
				$styles = "float:right; width: 60px; height: 60px; margin-left:.6em;";
				$size   = 60;
				
			}
			
			elseif( in_array($cardType ,array( 'product' ) ) ) {
				
				$styles 	= "float:left; width: 120px; height: 120px; margin-right:.6em;";
				$size    = 120;
			}
			
			elseif( in_array($cardType ,array( 'app') ) ) {
				
				$app = '<div class="gray" style="postion:relative;">Get app</div>';
			}
			
			else {
				
				$styles = "position: absolute; width: 120px; height: 120px; left: 0px; top: 0px;";
			}
			
			
			$output  = '<div class="fake-twt">';
			$output .= '<div class="fake-twt-timeline">';
			$output .= '<div class="fake-twt-tweet">'; 
			
			
			$output .= '<div class="e-content">

							'.get_avatar( false, 16 ).'	
							
							<span>'.__('Name associated with ','jm-tc').$site.'</span>

							<div style="position:relative;">
								<'.$tag.' class="'.$class.'" width="'.$size.'" height="'.$size.'" style="'.$styles.' -webkit-user-drag: none; " '.$src.'="'.$img.'">'.$close_tag.'
							</div>

							'
			.$product.
			'
							
							<div style= "position:relative;"><strong>'.$title.'</strong></div>
							<div style= "position:relative;"><em>By '.__('Name associated with ','jm-tc').$creator.'</em></div><div>'.$description.'</div>
							
							'
			.$app.
			'
							<div style="position:relative;" class="gray"><strong>'.__('View on the web','jm-tc').'<strong></div>
						
						</div>';
			
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
			
			return $output;
			
		}
		
		// get datas
		private function get_datas( $datas ) {
			
			$data = '';
			
			if ( is_array( $datas ) ) {
				
				foreach ( $datas as $name => $value ) {
					
					if( !empty($value) ) {
						
						$data .= $value;
						
					} else{
						
						$data = '';
					}				
					
				}
				
				return $data;
				
			} else {
				
				return;
			}
			
		}
		
		
	}
	
	

	
	
}