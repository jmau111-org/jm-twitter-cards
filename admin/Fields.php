<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class Fields
{
    public function generate_fields(array $array): self
    {

        foreach ($array as $options) {
            $method = array_shift($options);
            echo is_callable([$this, $method]) ? $this->tr( $this->{$method}($options) ) : '';
        }
        return $this;
    }

    private function wrapper($array = []): string
    {

        if (empty($array['tag'])) {
            return '';
        }

        $class = !empty($array['class']) ? sanitize_html_class($array['class']) : '';

        return 'start' === $array['mod'] ? '<' . esc_attr($array['tag']) . ' class="' . $class . '">' : '</' . esc_attr($array['tag']) . '>';
    }

    private function text_field(array $array): string
    {

        $type = !empty($array['type']) ? esc_attr($array['type']) : '';
        $output  = '<input size="60" class="tc-field-' . $type . '-url" id="' . esc_attr($array['field_id']) . '" name="' . esc_attr($array['field_id']) . '" type="text" value="' . esc_attr($array['value']) . '">';

        return $output;
    }

    private function textarea_field(array $array): string
    {

        $type      = !empty($array['type']) ? esc_attr($array['type']) : '';
        $charcount = empty($array['charcount']) ? '' : 'data-count="' . $array['charcount'] . '"';
        $output  = '<textarea rows="5" cols="80" class="textarea tc-field-' . $type . '-url" ' . $charcount . ' id="' . esc_attr($array['field_id']) . '" name="' . esc_attr($array['field_id']) . '">' . esc_attr($array['value']) . '</textarea>';

        return $output;
    }

    private function url_field(array $array): string
    {

        $type = !empty($array['type']) ? esc_attr($array['type']) : '';
        $output = '<input size="60" class="tc-field-' . $type . '-url" id="' . esc_attr($array['field_id']) . '" name="' . esc_attr($array['field_id']) . '" type="url" value="' . esc_attr($array['value']) . '" placeholder="https://">';

        return $output;
    }

    private function num_field(array $array): string
    {

        $type = !empty($array['type']) ? esc_attr($array['type']) : '';
        $output = '<input size=60" step="' . esc_attr($array['step']) . '" min="' . esc_attr($array['min']) . '" max="' . esc_attr($array['max']) . '" class="tc-field-' . $type . '-url" id="' . esc_attr($array['field_id']) . '" name="' . esc_attr($array['field_id']) . '" type="number" value="' . esc_attr($array['value']) . '">';

        return $output;
    }

    private function select_field(array $array): string
    {
        $output  = '<select class="' . esc_attr($array['field_id']) . '" id="' . esc_attr($array['field_id']) . '" name="' . esc_attr($array['field_id']) . '">';

        foreach ($array['options'] as $value => $label) {
            $output .= '<option value="' . esc_attr($value) . '"' . selected($array['value'], $value, false) . '>' . esc_html($label) . '</option>';
        }

        $output .= '</select>';

        return $output;
    }

    private function image_field(array $array): string
    {

        $output  = '<input size="60" type="text" class="tc-file-input" name="' . esc_attr($array['field_id']) . '" id="' . esc_attr($array['field_id']) . '" value="' . esc_attr($array['value']) . '">';
        $output .= '<a href="#" class="tc-file-input-select button-primary">' . esc_html__('Select', 'jm-tc') . '</a>' . "\r";
        $output .= '<a href="#" class="tc-file-input-reset button-secondary">' . esc_html__('Remove', 'jm-tc') . '</a></td>';

        return $output;
    }

    private function tr(array $array, string $str): string {
        $output  = '<tr class="' . esc_attr($array['field_id']) . '">';
        $output  = '<th scope="row"><label for="' . esc_attr($array['field_id']) . '">' . esc_html($array['label']) . '</label></th>';
        $output .= '<td>' . $str . '<td>';
        $output .= '</tr>';
        
        return $output;
    }
}
