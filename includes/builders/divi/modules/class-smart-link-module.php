<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_Smart_Link_Module extends ET_Builder_Module {
    public function init() {
        $this->name = esc_html__('Smart Link', 'auto-external-link-modifier');
        $this->plural = esc_html__('Smart Links', 'auto-external-link-modifier');
        $this->slug = 'aelm_smart_link';
        $this->vb_support = 'on';
        $this->main_css_element = '%%order_class%%.aelm_smart_link';

        $this->settings_modal_toggles = [
            'general' => [
                'toggles' => [
                    'main_content' => esc_html__('Link Content', 'auto-external-link-modifier'),
                    'link' => esc_html__('Link Settings', 'auto-external-link-modifier'),
                ],
            ],
            'advanced' => [
                'toggles' => [
                    'text' => esc_html__('Text', 'auto-external-link-modifier'),
                    'alignment' => esc_html__('Alignment', 'auto-external-link-modifier'),
                ],
            ],
        ];
    }

    public function get_fields() {
        return [
            'title' => [
                'label' => esc_html__('Link Text', 'auto-external-link-modifier'),
                'type' => 'text',
                'option_category' => 'basic_option',
                'description' => esc_html__('Enter the text for your link.', 'auto-external-link-modifier'),
                'toggle_slug' => 'main_content',
                'dynamic_content' => 'text',
            ],
            'url' => [
                'label' => esc_html__('Link URL', 'auto-external-link-modifier'),
                'type' => 'text',
                'option_category' => 'basic_option',
                'description' => esc_html__('Enter the destination URL for your link.', 'auto-external-link-modifier'),
                'toggle_slug' => 'link',
                'dynamic_content' => 'url',
            ],
            'url_new_window' => [
                'label' => esc_html__('Open in New Window', 'auto-external-link-modifier'),
                'type' => 'yes_no_button',
                'option_category' => 'configuration',
                'options' => [
                    'off' => esc_html__('No', 'auto-external-link-modifier'),
                    'on' => esc_html__('Yes', 'auto-external-link-modifier'),
                ],
                'default' => 'on',
                'toggle_slug' => 'link',
                'description' => esc_html__('Choose whether your link opens in a new window.', 'auto-external-link-modifier'),
            ],
            'exclude_processing' => [
                'label' => esc_html__('Exclude from Processing', 'auto-external-link-modifier'),
                'type' => 'yes_no_button',
                'option_category' => 'configuration',
                'options' => [
                    'off' => esc_html__('No', 'auto-external-link-modifier'),
                    'on' => esc_html__('Yes', 'auto-external-link-modifier'),
                ],
                'default' => 'off',
                'toggle_slug' => 'link',
                'description' => esc_html__('Choose whether to exclude this link from automatic modifications.', 'auto-external-link-modifier'),
            ],
            'alignment' => [
                'label' => esc_html__('Alignment', 'auto-external-link-modifier'),
                'type' => 'text_align',
                'option_category' => 'layout',
                'options' => et_builder_get_text_orientation_options(['justified']),
                'default' => 'left',
                'tab_slug' => 'advanced',
                'toggle_slug' => 'alignment',
                'description' => esc_html__('Align your link to the left, right or center of the module.', 'auto-external-link-modifier'),
            ],
        ];
    }

    public function get_advanced_fields_config() {
        return [
            'fonts' => [
                'link' => [
                    'label' => esc_html__('Link', 'auto-external-link-modifier'),
                    'css' => [
                        'main' => "{$this->main_css_element} a",
                        'hover' => "{$this->main_css_element} a:hover",
                    ],
                    'font_size' => [
                        'default' => '14px',
                    ],
                    'line_height' => [
                        'default' => '1.7em',
                    ],
                ],
            ],
            'margin_padding' => [
                'css' => [
                    'padding' => "{$this->main_css_element}",
                    'margin' => "{$this->main_css_element}",
                    'important' => 'all',
                ],
            ],
            'text' => [
                'use_background_layout' => false,
                'css' => [
                    'main' => "{$this->main_css_element}",
                ],
            ],
            'text_shadow' => [
                'default' => false,
            ],
            'background' => [
                'css' => [
                    'main' => "{$this->main_css_element}",
                ],
            ],
            'borders' => [
                'default' => [
                    'css' => [
                        'main' => [
                            'border_styles' => "{$this->main_css_element}",
                        ],
                    ],
                ],
            ],
            'box_shadow' => [
                'default' => [
                    'css' => [
                        'main' => "{$this->main_css_element}",
                    ],
                ],
            ],
        ];
    }

    public function render($attrs, $content = null, $render_slug) {
        $title = $this->props['title'];
        $url = $this->props['url'];
        $url_new_window = $this->props['url_new_window'];
        $exclude_processing = $this->props['exclude_processing'];
        $alignment = $this->props['alignment'];

        // Build link attributes
        $link_attrs = [];
        $classes = ['aelm-smart-link'];

        // Add URL
        if (!empty($url)) {
            $link_attrs['href'] = esc_url($url);
        }

        // Add target
        if ('on' === $url_new_window) {
            $link_attrs['target'] = '_blank';
            $link_attrs['rel'] = 'noopener noreferrer';
        }

        // Add alignment class
        if ('left' !== $alignment) {
            $classes[] = "et_pb_text_align_{$alignment}";
            $this->add_classname("et_pb_text_align_{$alignment}");
        }

        // Add custom class if excluded from processing
        if ('on' === $exclude_processing) {
            $classes[] = 'aelm-excluded';
        }

        // Build HTML attributes string
        $html_attrs = '';
        foreach ($link_attrs as $key => $value) {
            $html_attrs .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        // Build link HTML
        $output = sprintf(
            '<div class="%1$s">
                <a class="%2$s"%3$s>%4$s</a>
            </div>',
            $this->module_classname($render_slug),
            esc_attr(implode(' ', $classes)),
            $html_attrs,
            esc_html($title)
        );

        return $output;
    }
}