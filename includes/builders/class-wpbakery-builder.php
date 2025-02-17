<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_WPBakery_Builder extends AELM_Builder_Base {
    /**
     * Constructor
     *
     * @param AELM_Link_Modifier $link_modifier Link modifier instance
     */
    public function __construct(AELM_Link_Modifier $link_modifier) {
        parent::__construct('wpbakery', $link_modifier);
    }

    /**
     * Check if WPBakery is active
     *
     * @return boolean
     */
    public function is_active() {
        return defined('WPB_VC_VERSION');
    }

    /**
     * Register custom shortcodes
     *
     * @return void
     */
    public function register_elements() {
        if (!class_exists('WPBakeryShortCode')) {
            return;
        }

        // Load shortcode class
        require_once AELM_PLUGIN_DIR . 'includes/builders/wpbakery/shortcodes/class-smart-link-shortcode.php';

        // Register the shortcode with WPBakery
        vc_map([
            'name' => __('Smart Link', 'auto-external-link-modifier'),
            'base' => 'aelm_smart_link',
            'category' => __('Link Elements', 'auto-external-link-modifier'),
            'icon' => 'icon-wpb-ui-link',
            'params' => [
                [
                    'type' => 'textfield',
                    'holder' => 'div',
                    'class' => '',
                    'heading' => __('Link Text', 'auto-external-link-modifier'),
                    'param_name' => 'text',
                    'value' => __('Click here', 'auto-external-link-modifier'),
                    'description' => __('Enter the link text', 'auto-external-link-modifier')
                ],
                [
                    'type' => 'vc_link',
                    'holder' => 'div',
                    'class' => '',
                    'heading' => __('URL (Link)', 'auto-external-link-modifier'),
                    'param_name' => 'link',
                    'description' => __('Add your external link', 'auto-external-link-modifier')
                ],
                [
                    'type' => 'checkbox',
                    'holder' => 'div',
                    'class' => '',
                    'heading' => __('Exclude from Processing', 'auto-external-link-modifier'),
                    'param_name' => 'exclude',
                    'description' => __('Exclude this link from automatic modifications', 'auto-external-link-modifier'),
                    'value' => [__('Yes', 'auto-external-link-modifier') => 'yes']
                ],
                [
                    'type' => 'colorpicker',
                    'holder' => 'div',
                    'class' => '',
                    'heading' => __('Link Color', 'auto-external-link-modifier'),
                    'param_name' => 'color',
                    'description' => __('Choose link color', 'auto-external-link-modifier'),
                    'group' => __('Design Options', 'auto-external-link-modifier')
                ]
            ]
        ]);
    }

    /**
     * Extend existing elements
     *
     * @return void
     */
    public function extend_elements() {
        if (!class_exists('WPBakeryShortCode')) {
            return;
        }

        // Add parameters to existing elements that have links
        add_action('vc_after_init', function() {
            $link_elements = ['vc_btn', 'vc_custom_heading', 'vc_single_image'];

            foreach ($link_elements as $element) {
                vc_add_param($element, [
                    'type' => 'checkbox',
                    'heading' => __('Exclude from Link Processing', 'auto-external-link-modifier'),
                    'param_name' => 'aelm_exclude',
                    'description' => __('Exclude links in this element from automatic modifications', 'auto-external-link-modifier'),
                    'value' => [__('Yes', 'auto-external-link-modifier') => 'yes'],
                    'group' => __('Link Settings', 'auto-external-link-modifier')
                ]);
            }
        });
    }

    /**
     * Add WPBakery specific hooks
     *
     * @return void
     */
    protected function add_hooks() {
        // Frontend content filters
        add_filter('vc_shortcode_output', [$this, 'process_shortcode_output'], 10, 3);
        
        // Editor preview styles
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    /**
     * Process shortcode output
     *
     * @param string $output Shortcode output
     * @param string $shortcode Shortcode name
     * @param array $args Shortcode arguments
     * @return string
     */
    public function process_shortcode_output($output, $shortcode, $args) {
        if (empty($output)) {
            return $output;
        }

        // Skip processing if excluded
        if (!empty($args['aelm_exclude']) && $args['aelm_exclude'] === 'yes') {
            return $output;
        }

        return $this->process_content($output);
    }

    /**
     * Enqueue admin scripts
     *
     * @return void
     */
    public function enqueue_admin_scripts() {
        if (!$this->is_wpbakery_admin_page()) {
            return;
        }

        wp_enqueue_style(
            'aelm-wpbakery-admin',
            AELM_PLUGIN_URL . 'assets/css/wpbakery-admin.css',
            [],
            AELM_VERSION
        );
    }

    /**
     * Check if current page is WPBakery admin page
     *
     * @return boolean
     */
    private function is_wpbakery_admin_page() {
        global $pagenow;
        return $pagenow === 'post.php' || $pagenow === 'post-new.php';
    }
}