<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class Settings
{
    private $settings_sections = [];
    private $settings_fields = [];

    public function __construct(array $sections, array $fields) {
        $this->settings_sections = $sections;
        $this->settings_fields = $fields;
    }

    public function get_settings_sections() {
        return $this->settings_sections;
    }

    private function add_field($section, $field)
    {
        $defaults = [
            'name'  => '',
            'label' => '',
            'desc'  => '',
            'type'  => 'text',
        ];

        $arg                               = wp_parse_args($field, $defaults);
        $this->settings_fields[$section][] = $arg;

        return $this;
    }

    public function admin_init(): void
    {
        foreach ($this->settings_sections as $section) {
            if (false == get_option($section['id'])) {
                add_option($section['id']);
            }

            if (isset($section['desc']) && !empty($section['desc'])) {
                $section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
                $callback        = function () use ($section) {
                    echo wp_strip_all_tags($section['desc']);
                };
            } else if (isset($section['callback'])) {
                $callback = $section['callback'];
            } else {
                $callback = null;
            }

            add_settings_section($section['id'], $section['title'], $callback, $section['id']);
        }

        foreach ($this->settings_fields as $section => $field) {
            foreach ($field as $option) {

                $name     = $option['name'];
                $type     = isset($option['type']) ? $option['type'] : 'text';
                $label    = isset($option['label']) ? $option['label'] : '';
                $callback = isset($option['callback']) ? $option['callback'] : [$this, 'callback_' . $type];

                $args = [
                    'id'                => $name,
                    'class'             => isset($option['class']) ? $option['class'] : $name,
                    'label_for'         => "{$section}[{$name}]",
                    'desc'              => isset($option['desc']) ? $option['desc'] : '',
                    'name'              => $label,
                    'section'           => $section,
                    'size'              => isset($option['size']) ? $option['size'] : null,
                    'options'           => isset($option['options']) ? $option['options'] : '',
                    'std'               => isset($option['default']) ? $option['default'] : '',
                    'sanitize_callback' => isset($option['sanitize_callback']) ? $option['sanitize_callback'] : '',
                    'type'              => $type,
                    'placeholder'       => isset($option['placeholder']) ? $option['placeholder'] : '',
                    'min'               => isset($option['min']) ? $option['min'] : '',
                    'max'               => isset($option['max']) ? $option['max'] : '',
                    'step'              => isset($option['step']) ? $option['step'] : '',
                    'charcount'         => isset($option['charcount']) ? $option['charcount'] : '',
                ];

                add_settings_field("{$section}[{$name}]", $label, $callback, $section, $section, $args);
            }
        }

        foreach ($this->settings_sections as $section) {
            register_setting($section['id'], $section['id'], [$this, 'sanitize_options']);
        }
    }

    public function callback_url($args): void
    {
        $this->callback_text($args);
    }

    public function callback_text($args): void
    {
        $value       = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $size        = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $type        = isset($args['type']) ? $args['type'] : 'text';
        $placeholder = empty($args['placeholder']) ? '' : ' placeholder="' . $args['placeholder'] . '"';

        $html = sprintf('<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder);
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function get_option($option, $section, $default = '')
    {
        $options = get_option($section);

        if (isset($options[ $option ])) {
            return $options[ $option ];
        }

        return $default;
    }

    public function get_field_description($args): string
    {
        if (!empty($args['desc'])) {
            $desc = sprintf('<p class="description">%s</p>', $args['desc']);
        } else {
            $desc = '';
        }

        return $desc;
    }

    public function callback_number($args): void
    {
        $value       = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $size        = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $type        = isset($args['type']) ? $args['type'] : 'number';
        $placeholder = empty($args['placeholder']) ? '' : ' placeholder="' . $args['placeholder'] . '"';
        $min         = empty($args['min']) ? '' : ' min="' . $args['min'] . '"';
        $max         = empty($args['max']) ? '' : ' max="' . $args['max'] . '"';
        $step        = empty($args['max']) ? '' : ' step="' . $args['step'] . '"';

        $html = sprintf('<input type="%1$s" class="%2$s-number" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s%7$s%8$s%9$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder, $min, $max, $step);
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function callback_checkbox($args): void
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));

        $html = '<fieldset>';
        $html .= sprintf('<label for="wpuf-%1$s[%2$s]">', $args['section'], $args['id']);
        $html .= sprintf('<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id']);
        $html .= sprintf('<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked($value, 'on', false));
        $html .= sprintf('%1$s</label>', $args['desc']);
        $html .= '</fieldset>';

        echo $html;
    }

    public function callback_multicheck($args): void
    {
        $value = $this->get_option($args['id'], $args['section'], $args['std']);
        $html  = '<fieldset>';
        $html  .= sprintf('<input type="hidden" name="%1$s[%2$s]" value="" />', $args['section'], $args['id']);
        
        foreach ($args['options'] as $key => $label) {
            $checked = isset($value[$key]) ? $value[$key] : '0';
            $html    .= sprintf('<label for="wpuf-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key);
            $html    .= sprintf('<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked($checked, $key, false));
            $html    .= sprintf('%1$s</label><br>', $label);
        }

        $html .= $this->get_field_description($args);
        $html .= '</fieldset>';

        echo $html;
    }

    public function callback_radio($args): void
    {
        $value = $this->get_option($args['id'], $args['section'], $args['std']);
        $html  = '<fieldset>';

        foreach ($args['options'] as $key => $label) {
            $html .= sprintf('<label for="wpuf-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key);
            $html .= sprintf('<input type="radio" class="radio" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked($value, $key, false));
            $html .= sprintf('%1$s</label><br>', $label);
        }

        $html .= $this->get_field_description($args);
        $html .= '</fieldset>';

        echo $html;
    }

    public function callback_select($args): void
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $size  = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $html  = sprintf('<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id']);

        foreach ($args['options'] as $key => $label) {
            $html .= sprintf('<option value="%s"%s>%s</option>', $key, selected($value, $key, false), $label);
        }

        $html .= sprintf('</select>');
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function callback_textarea($args): void
    {
        $value       = esc_textarea($this->get_option($args['id'], $args['section'], $args['std']));
        $size        = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $placeholder = empty($args['placeholder']) ? '' : ' placeholder="' . $args['placeholder'] . '"';
        $charcount   = empty($args['charcount']) ? '' : ' data-count="' . $args['charcount'] . '"';

        $html = sprintf('<textarea %6$s rows="5" cols="55" class="%1$s-text textarea" id="%2$s[%3$s]" name="%2$s[%3$s]"%4$s>%5$s</textarea>', $size, $args['section'], $args['id'], $placeholder, $value, $charcount);
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function callback_html($args): void
    {
        echo $this->get_field_description($args);
    }

    public function callback_file($args): void
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $size  = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $id    = $args['section'] . '[' . $args['id'] . ']';
        $label = isset($args['options']['button_label']) ? $args['options']['button_label'] : __('Choose File', 'jm-tc');

        $html = sprintf('<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value);
        $html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
        $html .= $this->get_field_description($args);

        echo $html;
    }

    private function sanitize_options($options): array
    {
        if (!$options) {
            return (array) $options;
        }

        foreach ($options as $option_slug => $option_value) {
            $sanitize_callback = $this->get_sanitize_callback($option_slug);

            if ($sanitize_callback) {
                $options[$option_slug] = call_user_func($sanitize_callback, $option_value);
                continue;
            }
        }

        return (array) $options;
    }

    private function get_sanitize_callback($slug = '')
    {
        if (empty($slug)) {
            return false;
        }

        foreach ($this->settings_fields as $section => $options) {
            foreach ($options as $option) {
                if ($option['name'] != $slug) {
                    continue;
                }
                return isset($option['sanitize_callback']) && is_callable($option['sanitize_callback']) ? $option['sanitize_callback'] : false;
            }
        }

        return false;
    }
}
