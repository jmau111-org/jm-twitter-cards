<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if( ! class_exists('JM_TC_Tabs') ) {

 
	class JM_TC_Tabs {
	
		// Create WP Admin Tabs on-the-fly.
		public static function admin_tabs($tabs = array(), $current = NULL){
			
			if( !$tabs ) {
			
			$tabs = array(
					'jm_tc' => __('General'), 
					'jm_tc_import_export' => __('Import/Export','jm-tc'),
					'jm_tc_tutorial' => __('Tutorial','jm-tc'),
					'jm_tc_images' => __('Images','jm-tc'),
					'jm_tc_cf' => __('Custom fields','jm-tc'),
					'jm_tc_robots' => __('Robots.txt','jm-tc'),
					'jm_tc_home' => __('Home Settings','jm-tc'),
					'jm_tc_meta_box' => __('Meta Box','jm-tc'),
					'jm_tc_deep_linking' => __('Deep Linking','jm-tc'),
					'jm_tc_doc' => __('Documentation','jm-tc'),
					'jm_tc_analytics' => __('Analytics','jm-tc'),
					'jm_tc_about' => __('About'),		

				);
			
			}
		
		
			if(is_null($current)){
				if(isset($_GET['page'])){
					$current = $_GET['page'];
				}
			}
			
			$menu = '';
			$menu .= '<ul class="nav-tab-wrapper">';
			
			foreach($tabs as $page => $menu_page){
				if($current == $page){
					$class = ' nav-tab-active';
				} else{
					$class = '';    
				}
				$menu .= '<li><a class="nav-tab'.$class.'" href="?page='.$page.'">'.$menu_page.'</a></li>';
			}
			
			$menu .= '</ul>';
			
			return $menu;
		}
		
	
	}
	
	
}