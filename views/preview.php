<?php

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

$type = $this->options->card_type();
$title = $this->options->title();
$desc = $this->options->description();
$image = $this->options->image();

if ( ! is_array( $type ) || ! is_array( $title ) || ! is_array( $desc ) || ! is_array( $image ) ) {
	$preview .= '';

	return;
}

$type   = reset( $type );
$title  = reset( $title );
$desc   = reset( $desc );
$image  = reset( $image );
$domain = $_SERVER['HTTP_HOST'];
$avatar = get_avatar_url( get_avatar( get_current_user_id(), 36, 36 ) );
$style  = 'summary' === $type ? 'style="background-image:url( ' . esc_url( $image ) . ');"' : '';

$preview .= '<div class="global-' . esc_attr( $type ) . ' tc-preview col1">';
$preview .= '<div class="tc-author u-cf">';
$preview .= '<img class="tc-author-avatar" src="' . esc_url( $avatar ) . '">';
$preview .= '<div class="tc-author-name u-pullLeft">' . __( 'Name' ) . '</div>';
$preview .= '<div class="tc-author-handle u-pullLeft">@' . __( 'account' ) . '</div>';
$preview .= '</div>';
$preview .= '<div class="tc-text u-pullLeft"><p>' . esc_html__( 'The card for your website will look a little something like this!', 'jm-tc' ) . '</p> </div>';

$card = '<div class="tc-summary tc-container">';
$card .= '<div class="tc-' . esc_attr( $type ) . '-img-container" ' . $style . '>';
$card .= 'player' ===  $type ? '<video  class="tc-img" style="-webkit-user-drag: none;" controls poster="' . esc_url( $image ) . '"></video>' : '<img class="tc-img ' . esc_attr( $type ) . '-img" src="' . esc_url( $image ) . '" alt="">';
$card .= '</div>';
$card .= '<div class="' . esc_attr( $type ) . '-content"><p class="' . esc_attr( $type ) . '-title tc-title">' . esc_html( $title ) . '</p>
          <p class="' . esc_attr( $type ) . '-desc tc-desc">' . esc_html( $desc ) . '</p>
          <span class="tc-domain ' . esc_attr( $type ) . '-domain">' . _( $domain ) . '</span></div>';
$card .= '</div>';

$preview .= $card;
$preview .= '</div>';