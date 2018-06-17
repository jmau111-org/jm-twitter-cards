<?php

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

$preview .= '<div class="ttc-' . esc_attr( $type ) . ' global-' . esc_attr( $type ) . ' tc-preview onchange">';
$preview .= '<div class="tc-author u-cf">';
$preview .= '<img class="tc-author-avatar" src="' . esc_url( $avatar ) . '">';
$preview .= '<div class="tc-author-name u-pullLeft">' . __( 'Name' ) . '</div>';
$preview .= '<div class="tc-author-handle u-pullLeft">@' . __( 'account' ) . '</div>';
$preview .= '</div>';
$preview .= '<div class="tc-text u-pullLeft"><p>' . esc_html__( 'The card for your website will look a little something like this!', 'jm-tc' ) . '</p> </div>';

$card = '';
require_once( JM_TC_DIR . 'views/card.php' );

$preview .= $card;
$preview .= '</div>';