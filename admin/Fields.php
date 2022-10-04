<?php

namespace TokenToMe\TwitterCards\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class Fields
{

    /**
     * @param $array
     */
    public function generate_fields($array)
    {

        foreach ($array as $options) {
            $method = array_shift($options);
            echo is_callable([$this, $method]) ? $this->{$method}($options) : '';
        }
    }

    /**
     * Simple wrapper div
     *
     * @param array $murray
     *
     * @author jmau111
     * @return string|bool
     */
    public function wrapper($murray = [])
    {

        if (empty($murray['tag'])) {
            return false;
        }

        $class = !empty($murray['class']) ? sanitize_html_class($murray['class']) : '';

        return 'start' === $murray['mod'] ? '<' . esc_attr($murray['tag']) . ' class="' . $class . '">' : '</' . esc_attr($murray['tag']) . '>';
    }

    /**
     * Basic field
     *
     * @param array $murray
     *
     * @return string
     * @author jmau111
     */
    public function text_field($murray)
    {

        $type = !empty($murray['type']) ? esc_attr($murray['type']) : '';

        $output = '<tr class="' . esc_attr($murray['field_id']) . '">';
        $output .= '<th scope="row"><label for="' . esc_attr($murray['field_id']) . '">' . esc_html($murray['label']) . '</label></th>';
        $output .= '<td><input size="60" class="tc-field-' . $type . '-url" id="' . esc_attr($murray['field_id']) . '" name="' . esc_attr($murray['field_id']) . '" type="text" value="' . esc_attr($murray['value']) . '"></td>';
        $output .= '</tr>';

        return $output;
    }

    /**
     * Basic textarea
     *
     * @param array $murray
     *
     * @return string
     * @author jmau111
     */
    public function textarea_field($murray)
    {

        $type      = !empty($murray['type']) ? esc_attr($murray['type']) : '';
        $charcount = empty($murray['charcount']) ? '' : 'data-count="' . $murray['charcount'] . '"';

        $output = '<tr class="' . esc_attr($murray['field_id']) . '">';
        $output .= '<th scope="row"><label for="' . esc_attr($murray['field_id']) . '">' . esc_html($murray['label']) . '</label></th>';
        $output .= '<td><textarea rows="5" cols="80" class="textarea tc-field-' . $type . '-url" ' . $charcount . ' id="' . esc_attr($murray['field_id']) . '" name="' . esc_attr($murray['field_id']) . '">' . esc_attr($murray['value']) . '</textarea></td>';
        $output .= '</tr>';

        return $output;
    }

    /**
     * Url field
     *
     * @param array $murray
     *
     * @return string
     * @author jmau111
     */
    public function url_field($murray)
    {

        $type = !empty($murray['type']) ? esc_attr($murray['type']) : '';

        $output = '<tr class="' . esc_attr($murray['field_id']) . '">';
        $output .= '<th scope="row"><label for="' . esc_attr($murray['field_id']) . '">' . esc_html($murray['label']) . '</label></th>';
        $output .= '<td><input size="60" class="tc-field-' . $type . '-url" id="' . esc_attr($murray['field_id']) . '" name="' . esc_attr($murray['field_id']) . '" type="url" value="' . esc_attr($murray['value']) . '" placeholder="https://"></td>';
        $output .= '</tr>';

        return $output;
    }

    /**
     * Num field
     *
     * @param array $murray
     *
     * @return string
     * @author jmau111
     */
    public function num_field($murray)
    {

        $type = !empty($murray['type']) ? esc_attr($murray['type']) : '';

        $output = '<tr class="' . esc_attr($murray['field_id']) . '">';
        $output .= '<th scope="row"><label for="' . esc_attr($murray['field_id']) . '">' . esc_html($murray['label']) . '</label></th>';
        $output .= '<td><input size=60" step="' . esc_attr($murray['step']) . '" min="' . esc_attr($murray['min']) . '" max="' . esc_attr($murray['max']) . '" class="tc-field-' . $type . '-url" id="' . esc_attr($murray['field_id']) . '" name="' . esc_attr($murray['field_id']) . '" type="number" value="' . esc_attr($murray['value']) . '"></td>';
        $output .= '</tr>';

        return $output;
    }

    /**
     * Select field
     *
     * @param array $murray
     *
     * @author jmau111
     * @return string
     */
    public function select_field($murray)
    {

        $output = '<tr class="' . esc_attr($murray['field_id']) . '">';
        $output .= '<th scope="row"><label for="' . esc_attr($murray['field_id']) . '">' . esc_html($murray['label']) . '</label></th>';
        $output .= '<td><select class="' . esc_attr($murray['field_id']) . '" id="' . esc_attr($murray['field_id']) . '" name="' . esc_attr($murray['field_id']) . '">';

        foreach ($murray['options'] as $value => $label) {
            $output .= '<option value="' . esc_attr($value) . '"' . selected($murray['value'], $value, false) . '>' . esc_html($label) . '</option>';
        }

        $output .= '</select></td>';
        $output .= '</tr>';

        return $output;
    }

    /**
     * Image field
     *
     * @param array $murray
     *
     * @author jmau111
     * @return string
     */
    public function image_field($murray)
    {

        $output = '<tr class="' . esc_attr($murray['field_id']) . '">';
        $output .= '<th scope="row"><label for="' . esc_attr($murray['field_id']) . '">' . esc_html($murray['label']) . '</label></th>';
        $output .= '<td><input size="60" type="text" class="tc-file-input" name="' . esc_attr($murray['field_id']) . '" id="' . esc_attr($murray['field_id']) . '" value="' . esc_attr($murray['value']) . '">';
        $output .= '<a href="#" class="tc-file-input-select button-primary">' . esc_html__('Select', 'jm-tc') . '</a>' . "\r";
        $output .= '<a href="#" class="tc-file-input-reset button-secondary">' . esc_html__('Remove', 'jm-tc') . '</a></td>';
        $output .= '</tr>';

        return $output;
    }

    public function __toString()
    {
        return esc_html__('Not found', 'jm-tc');
    }
}
