<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_Smart_Link_Shortcode extends WPBakeryShortCode {
    /**
     * Constructor
     */
    public function __construct() {
        add_shortcode('aelm_smart_link', [$this, 'render']);
    }

    /**
     * Render shortcode
     *
     * @param array $atts Shortcode attributes
     * @param string $content Shortcode content
     * @return string
     */
    public function render($atts, $content = null) {
        $defaults = [
            'text' => __('Click here', 'auto-external-link-modifier'),
            'link' => '',
            'exclude' => '',
            'color' => '',
            'css' => ''
        ];

        $atts = vc_map_get_attributes($this->getShortcode(), $atts);
        $atts = shortcode_atts($defaults, $atts);

        // Parse link
        $link = vc_build_link($atts['link']);
        if (empty($link['url'])) {
            return '';
        }

        // Build link attributes
        $attributes = [];
        $classes = ['aelm-smart-link'];

        // Add URL
        $attributes['href'] = esc_url($link['url']);

        // Add title if set
        if (!empty($link['title'])) {
            $attributes['title'] = esc_attr($link['title']);
        }

        // Add target if set
        if (!empty($link['target'])) {
            $attributes['target'] = esc_attr($link['target']);
        }

        // Add rel attributes
        $rel_attrs = [];
        if (!empty($link['rel'])) {
            $rel_attrs = explode(' ', $link['rel']);
        }

        // Add noopener and noreferrer for blank target
        if ($attributes['target'] === '_blank') {
            $rel_attrs = array_merge($rel_attrs, ['noopener', 'noreferrer']);
        }

        // Set rel attribute if we have any
        if (!empty($rel_attrs)) {
            $attributes['rel'] = implode(' ', array_unique($rel_attrs));
        }

        // Add custom CSS class
        if (!empty($atts['css'])) {
            $classes[] = vc_shortcode_custom_css_class($atts['css']);
        }

        // Add custom color style
        $styles = [];
        if (!empty($atts['color'])) {
            $styles[] = 'color: ' . esc_attr($atts['color']);
        }

        // Build HTML attributes string
        $html_attrs = '';
        foreach ($attributes as $key => $value) {
            $html_attrs .= ' ' . $key . '="' . $value . '"';
        }

        // Build style attribute if needed
        if (!empty($styles)) {
            $html_attrs .= ' style="' . implode('; ', $styles) . '"';
        }

        // Build final HTML
        $output = sprintf(
            '<a class="%s"%s>%s</a>',
            esc_attr(implode(' ', $classes)),
            $html_attrs,
            esc_html(!empty($atts['text']) ? $atts['text'] : $link['title'])
        );

        return $output;
    }
}