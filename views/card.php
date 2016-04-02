<?php

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

$card = '<div class="tc-summary tc-container">';
$card .= '<div id="' . esc_attr( $type ) . '-img-container" class="tc-' . esc_attr( $type ) . '-img-container onchange" ' . $style . '>';
$card .= 'player' ===  $type
	? '<video id="tc-img-child" class="tc-img onchange" style="-webkit-user-drag: none;" controls poster="' . esc_url( $image ) . '"></video>'
	: '<img id="tc-img-child" class="tc-img ' . esc_attr( $type ) . '-img onchange" src="' . esc_url( $image ) . '" alt="">';
$card .= '</div>';
$card .= '<div class="' . esc_attr( $type ) . '-content">
		  <p class="' . esc_attr( $type ) . '-title tc-title onchange" id="tc-title">' . esc_html( $title ) . '</p>
          <p class="' . esc_attr( $type ) . '-desc tc-desc onchange">' . esc_html( $desc ) . '</p>
          <span class="tc-domain ' . esc_attr( $type ) . '-domain onchange">' . _( $domain ) . '</span></div>';
$card .= '</div>';
