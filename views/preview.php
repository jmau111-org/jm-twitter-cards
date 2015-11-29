<?php

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

$a = esc_url( get_avatar_url( get_avatar( null, 36 ) ) );

if ( ! is_array( $this->options->card_type() ) || ! is_array( $this->options->title() ) || ! is_array( $this->options->description() ) || ! is_array( $this->options->image() ) ) {
	$preview .= '';

	return;
}

$type   = reset( $this->options->card_type() );
$title  = reset( $this->options->title() );
$desc   = reset( $this->options->description() );
$image  = reset( $this->options->image() );
$domain = $_SERVER['HTTP_HOST'];
$avatar = get_avatar_url( get_avatar( null, 36 ) );
$style  = 'summary' === $type ? 'style="background-image:url( ' . esc_url( $image ) . ');"' : '';

$preview .= '<div class="global-' . esc_attr( $type ) . ' tc-preview">';
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