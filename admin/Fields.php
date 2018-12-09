<?php

namespace TokenToMe\TwitterCards\Admin;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Fields {

	/**
	 * @param $array
	 */
	public function generate_fields( $array ) {

		foreach ( $array as $field => $options ) {
			$method = array_shift( $options );
			echo method_exists( $this, $method ) ? $this->{$method}( $options ) : '';
		}
	}

	/**
	 * Simple wrapper div
	 *
	 * @param array $aar
	 *
	 * @author Julien Maury
	 * @return string|bool
	 */
	public function wrapper( $aar = [] ) {

		if ( empty( $aar['tag'] ) ) {
			return false;
		}

		$class = ! empty( $aar['class'] ) ? sanitize_html_class( $aar['class'] ) : '';

		return 'start' === $aar['mod'] ? '<' . esc_attr( $aar['tag'] ) . ' class="' . $class . '">' : '</' . esc_attr( $aar['tag'] ) . '>';
	}

	/**
	 * Basic field
	 *
	 * @param array $aar
	 *
	 * @return string
	 * @author Julien Maury
	 */
	public function text_field( $aar ) {

		$type = ! empty( $aar['type'] ) ? esc_attr( $aar['type'] ) : '';

		$output = '<tr class="' . esc_attr( $aar['field_id'] ) . '">';
		$output .= '<th scope="row"><label for="' . esc_attr( $aar['field_id'] ) . '">' . esc_html( $aar['label'] ) . '</label></th>';
		$output .= '<td><input size="60" class="tc-field-' . $type . '-url" id="' . esc_attr( $aar['field_id'] ) . '" name="' . esc_attr( $aar['field_id'] ) . '" type="text" value="' . esc_attr( $aar['value'] ) . '"></td>';
		$output .= '</tr>';

		return $output;
	}

	/**
	 * Basic textarea
	 *
	 * @param array $aar
	 *
	 * @return string
	 * @author Julien Maury
	 */
	public function textarea_field( $aar ) {

		$type      = ! empty( $aar['type'] ) ? esc_attr( $aar['type'] ) : '';
		$charcount = empty( $aar['charcount'] ) ? '' : 'data-count="' . $aar['charcount'] . '"';

		$output = '<tr class="' . esc_attr( $aar['field_id'] ) . '">';
		$output .= '<th scope="row"><label for="' . esc_attr( $aar['field_id'] ) . '">' . esc_html( $aar['label'] ) . '</label></th>';
		$output .= '<td><textarea rows="5" cols="80" class="textarea tc-field-' . $type . '-url" ' . $charcount . ' id="' . esc_attr( $aar['field_id'] ) . '" name="' . esc_attr( $aar['field_id'] ) . '">' . esc_attr( $aar['value'] ) . '</textarea></td>';
		$output .= '</tr>';

		return $output;
	}

	/**
	 * Url field
	 *
	 * @param array $aar
	 *
	 * @return string
	 * @author Julien Maury
	 */
	public function url_field( $aar ) {

		$type = ! empty( $aar['type'] ) ? esc_attr( $aar['type'] ) : '';

		$output = '<tr class="' . esc_attr( $aar['field_id'] ) . '">';
		$output .= '<th scope="row"><label for="' . esc_attr( $aar['field_id'] ) . '">' . esc_html( $aar['label'] ) . '</label></th>';
		$output .= '<td><input size="60" class="tc-field-' . $type . '-url" id="' . esc_attr( $aar['field_id'] ) . '" name="' . esc_attr( $aar['field_id'] ) . '" type="url" value="' . esc_attr( $aar['value'] ) . '" placeholder="https://"></td>';
		$output .= '</tr>';

		return $output;
	}

	/**
	 * Num field
	 *
	 * @param array $aar
	 *
	 * @return string
	 * @author Julien Maury
	 */
	public function num_field( $aar ) {

		$type = ! empty( $aar['type'] ) ? esc_attr( $aar['type'] ) : '';

		$output = '<tr class="' . esc_attr( $aar['field_id'] ) . '">';
		$output .= '<th scope="row"><label for="' . esc_attr( $aar['field_id'] ) . '">' . esc_html( $aar['label'] ) . '</label></th>';
		$output .= '<td><input size=60" step="' . esc_attr( $aar['step'] ) . '" min="' . esc_attr( $aar['min'] ) . '" max="' . esc_attr( $aar['max'] ) . '" class="tc-field-' . $type . '-url" id="' . esc_attr( $aar['field_id'] ) . '" name="' . esc_attr( $aar['field_id'] ) . '" type="number" value="' . esc_attr( $aar['value'] ) . '"></td>';
		$output .= '</tr>';

		return $output;
	}

	/**
	 * Select field
	 *
	 * @param array $aar
	 *
	 * @author Julien Maury
	 * @return string
	 */
	public function select_field( $aar ) {

		$output = '<tr class="' . esc_attr( $aar['field_id'] ) . '">';
		$output .= '<th scope="row"><label for="' . esc_attr( $aar['field_id'] ) . '">' . esc_html( $aar['label'] ) . '</label></th>';
		$output .= '<td><select class="' . esc_attr( $aar['field_id'] ) . '" id="' . esc_attr( $aar['field_id'] ) . '" name="' . esc_attr( $aar['field_id'] ) . '">';

		foreach ( $aar['options'] as $value => $label ) {
			$output .= '<option value="' . esc_attr( $value ) . '"' . selected( $aar['value'], $value, false ) . '>' . esc_html( $label ) . '</option>';
		}

		$output .= '</select></td>';
		$output .= '</tr>';

		return $output;
	}

	/**
	 * Image field
	 *
	 * @param array $aar
	 *
	 * @author Julien Maury
	 * @return string
	 */
	public function image_field( $aar ) {

		$output = '<tr class="' . esc_attr( $aar['field_id'] ) . '">';
		$output .= '<th scope="row"><label for="' . esc_attr( $aar['field_id'] ) . '">' . esc_html( $aar['label'] ) . '</label></th>';
		$output .= '<td><input size="60" type="text" class="tc-file-input" name="' . esc_attr( $aar['field_id'] ) . '" id="' . esc_attr( $aar['field_id'] ) . '" value="' . esc_attr( $aar['value'] ) . '">';
		$output .= '<a href="#" class="tc-file-input-select button-primary">' . __( 'Select', 'jm-tc' ) . '</a>' . "\r";
		$output .= '<a href="#" class="tc-file-input-reset button-secondary">' . __( 'Remove', 'jm-tc' ) . '</a></td>';
		$output .= '</tr>';

		return $output;
	}

	public function __toString() {
		return __( 'Not found', 'jm-tc' );
	}
}