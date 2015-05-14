<?php
namespace TokenToMe\twitter_cards;

if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Tabs {

	/**
	 * Create WP Admin Tabs on-the-fly.
	 *
	 * @param array $tabs
	 *
	 * @return string
	 */
	public static function admin_tabs( $tabs = array() ) {

		if ( empty( $tabs ) ) {

			$tabs = array(
				'jm_tc'               => __( 'General' ),
				'jm_tc_import_export' => __( 'Import/Export', 'jm-tc' ),
				'jm_tc_tutorial'      => __( 'Tutorial', 'jm-tc' ),
				'jm_tc_images'        => __( 'Images', 'jm-tc' ),
				'jm_tc_cf'            => __( 'Custom fields', 'jm-tc' ),
				'jm_tc_home'          => __( 'Home Settings', 'jm-tc' ),
				'jm_tc_deep_linking'  => __( 'Deep Linking', 'jm-tc' ),
				'jm_tc_doc'           => __( 'Documentation', 'jm-tc' ),
				'jm_tc_about'         => __( 'About' ),

			);

		}

		$menu = '<h2 class="tc-menu nav-tab-wrapper">';

		foreach ( $tabs as $page => $menu_page ) {
			$url   = esc_url( admin_url() . 'admin.php?page=' . $page );
			$class = ( isset( $_GET['page'] ) && $_GET['page'] === $page ) ? 'nav-tab-active' : '';
			$menu .= '<a class="nav-tab ' . esc_attr( $class ) . '" href="' . esc_url( $url ) . '">' . $menu_page . '</a>';
		}

		$menu .= '</h2>';

		return $menu;
	}


}