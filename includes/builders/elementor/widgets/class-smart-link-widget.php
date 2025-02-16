<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_Smart_Link_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'aelm_smart_link';
    }

    public function get_title() {
        return __('Smart Link', 'auto-external-link-modifier');
    }

    public function get_icon() {
        return 'eicon-link';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['link', 'external', 'url', 'smart'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Link Settings', 'auto-external-link-modifier'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'link_text',
            [
                'label' => __('Link Text', 'auto-external-link-modifier'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Click here', 'auto-external-link-modifier'),
            ]
        );

        $this->add_control(
            'link_url',
            [
                'label' => __('Link URL', 'auto-external-link-modifier'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $this->add_control(
            'exclude_from_processing',
            [
                'label' => __('Exclude from Link Processing', 'auto-external-link-modifier'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'description' => __('Exclude this link from automatic modifications', 'auto-external-link-modifier'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'auto-external-link-modifier'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => __('Link Color', 'auto-external-link-modifier'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aelm-smart-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'link_typography',
                'selector' => '{{WRAPPER}} .aelm-smart-link',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $link_attrs = [];
        $classes = ['aelm-smart-link'];

        if (!empty($settings['link_url']['url'])) {
            $link_attrs['href'] = $settings['link_url']['url'];

            if ($settings['link_url']['is_external']) {
                $link_attrs['target'] = '_blank';
            }

            if ($settings['link_url']['nofollow'] || !$settings['exclude_from_processing']) {
                $rel_attrs = ['nofollow'];
                
                if ($link_attrs['target'] === '_blank') {
                    $rel_attrs[] = 'noopener';
                    $rel_attrs[] = 'noreferrer';
                }

                $link_attrs['rel'] = implode(' ', array_unique($rel_attrs));
            }

            // Build HTML attributes string
            $html_attrs = '';
            foreach ($link_attrs as $attr => $value) {
                $html_attrs .= sprintf(' %s="%s"', esc_attr($attr), esc_attr($value));
            }

            printf(
                '<a class="%s"%s>%s</a>',
                esc_attr(implode(' ', $classes)),
                $html_attrs,
                esc_html($settings['link_text'])
            );
        }
    }

    protected function content_template() {
        ?>
        <# 
        var link_attrs = {};
        var classes = ['aelm-smart-link'];

        if (settings.link_url.url) {
            link_attrs.href = settings.link_url.url;

            if (settings.link_url.is_external) {
                link_attrs.target = '_blank';
            }

            if (settings.link_url.nofollow || !settings.exclude_from_processing) {
                var rel_attrs = ['nofollow'];
                
                if (link_attrs.target === '_blank') {
                    rel_attrs.push('noopener');
                    rel_attrs.push('noreferrer');
                }

                link_attrs.rel = rel_attrs.join(' ');
            }

            var html_attrs = '';
            _.each(link_attrs, function(value, attr) {
                html_attrs += ' ' + attr + '="' + value + '"';
            });

            #>
            <a class="{{{ classes.join(' ') }}}"{{{ html_attrs }}}>{{{ settings.link_text }}}</a>
            <#
        }
        #>
        <?php
    }
}