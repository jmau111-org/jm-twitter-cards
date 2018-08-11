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

$card = '<div class="tc-summary tc-container">';
$card .= '<div id="' . esc_attr( $type ) . '-img-container" class="tc-' . esc_attr( $type ) . '-img-container onchange" ' . $style . '>';
$card .= 'player' === $type
	? '<video id="tc-img-child" width="100%" height="200" class="tc-img onchange" style="-webkit-user-drag: none;" controls poster="' . esc_url( $image ) . '"></video>'
	: '<img id="tc-img-child" class="tc-img ' . esc_attr( $type ) . '-img onchange" src="' . esc_url( $image ) . '" alt="">';
$card .= '</div>';
$card .= '<div id="card-content" class="' . esc_attr( $type ) . '-content">
		  <p class="' . esc_attr( $type ) . '-title tc-title onchange" id="tc-title">' . esc_html( $title ) . '</p>
          <p class="' . esc_attr( $type ) . '-desc tc-desc onchange" id="tc-desc">' . esc_html( $desc ) . '</p>
          <span class="tc-domain ' . esc_attr( $type ) . '-domain onchange">' . _( $domain ) . '</span></div>';
$card .= '</div>';

$preview .= $card;
$preview .= '</div>';