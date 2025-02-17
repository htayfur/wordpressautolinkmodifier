<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_Divi_Builder extends AELM_Builder_Base {
    /**
     * Constructor
     *
     * @param AELM_Link_Modifier $link_modifier Link modifier instance
     */
    public function __construct(AELM_Link_Modifier $link_modifier) {
        parent::__construct('divi', $link_modifier);
    }

    /**
     * Check if Divi Builder is active
     *
     * @return boolean
     */
    public function is_active() {
        return defined('ET_BUILDER_VERSION');
    }

    /**
     * Register custom modules
     *
     * @return void
     */
    public function register_elements() {
        if (!class_exists('ET_Builder_Module')) {
            return;
        }

        // Load the module class
        require_once AELM_PLUGIN_DIR . 'includes/builders/divi/modules/class-smart-link-module.php';

        // Register the module with Divi
        add_action('et_builder_ready', function() {
            new AELM_Smart_Link_Module();
        });
    }

    /**
     * Extend existing elements
     *
     * @return void
     */
    public function extend_elements() {
        if (!class_exists('ET_Builder_Module')) {
            return;
        }

        add_filter('et_pb_module_shortcode_attributes', [$this, 'extend_module_attributes'], 10, 3);
        add_action('et_builder_framework_loaded', [$this, 'add_custom_fields']);
    }

    /**
     * Add Divi specific hooks
     *
     * @return void
     */
    protected function add_hooks() {
        // Frontend content filters
        add_filter('et_builder_render_layout', [$this, 'process_content'], 999);
        
        // Editor preview styles
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_scripts']);
    }

    /**
     * Extend module attributes
     *
     * @param array $props Module properties
     * @param array $attrs Original attributes
     * @param string $slug Module slug
     * @return array
     */
    public function extend_module_attributes($props, $attrs, $slug) {
        // Add our custom attributes to modules with links
        $link_modules = ['et_pb_button', 'et_pb_text', 'et_pb_blurb'];
        
        if (in_array($slug, $link_modules)) {
            $props['aelm_exclude'] = isset($attrs['aelm_exclude']) ? $attrs['aelm_exclude'] : 'off';
        }

        return $props;
    }

    /**
     * Add custom fields to Divi modules
     *
     * @return void
     */
    public function add_custom_fields() {
        $fields = [
            'aelm_exclude' => [
                'label' => __('Exclude from Link Processing', 'auto-external-link-modifier'),
                'type' => 'yes_no_button',
                'option_category' => 'configuration',
                'options' => [
                    'off' => __('No', 'auto-external-link-modifier'),
                    'on' => __('Yes', 'auto-external-link-modifier'),
                ],
                'default' => 'off',
                'description' => __('Exclude links in this module from automatic modifications', 'auto-external-link-modifier'),
                'tab_slug' => 'custom_css',
                'toggle_slug' => 'visibility',
            ],
        ];

        // Add fields to modules with links
        $link_modules = ['et_pb_button', 'et_pb_text', 'et_pb_blurb'];
        
        foreach ($link_modules as $module) {
            add_filter("et_pb_{$module}_fields", function($module_fields) use ($fields) {
                return array_merge($module_fields, $fields);
            });
        }
    }

    /**
     * Enqueue admin scripts
     *
     * @return void
     */
    public function enqueue_admin_scripts() {
        if (!$this->is_divi_admin_page()) {
            return;
        }

        wp_enqueue_style(
            'aelm-divi-admin',
            AELM_PLUGIN_URL . 'assets/css/divi-admin.css',
            [],
            AELM_VERSION
        );
    }

    /**
     * Enqueue frontend scripts
     *
     * @return void
     */
    public function enqueue_frontend_scripts() {
        if (!et_core_is_fb_enabled()) {
            return;
        }

        wp_enqueue_style(
            'aelm-divi-preview',
            AELM_PLUGIN_URL . 'assets/css/divi-preview.css',
            [],
            AELM_VERSION
        );
    }

    /**
     * Check if current page is Divi admin page
     *
     * @return boolean
     */
    private function is_divi_admin_page() {
        if (!function_exists('et_builder_should_load_framework')) {
            return false;
        }

        return et_builder_should_load_framework();
    }
}