<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_Elementor_Builder extends AELM_Builder_Base {
    /**
     * Constructor
     *
     * @param AELM_Link_Modifier $link_modifier Link modifier instance
     */
    public function __construct(AELM_Link_Modifier $link_modifier) {
        parent::__construct('elementor', $link_modifier);
    }

    /**
     * Check if Elementor is active
     *
     * @return boolean
     */
    public function is_active() {
        return defined('ELEMENTOR_VERSION');
    }

    /**
     * Register custom widgets
     *
     * @return void
     */
    public function register_elements() {
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }

    /**
     * Extend existing Elementor elements
     *
     * @return void
     */
    public function extend_elements() {
        add_action('elementor/element/after_section_end', [$this, 'add_link_controls'], 10, 2);
    }

    /**
     * Add hooks for Elementor
     *
     * @return void
     */
    protected function add_hooks() {
        // Frontend content filters
        add_filter('elementor/frontend/the_content', [$this, 'process_content'], 20);
        add_filter('elementor/widget/render_content', [$this, 'process_content'], 20);

        // Editor preview filters
        add_filter('elementor/preview/enqueue_styles', [$this, 'enqueue_preview_styles']);
    }

    /**
     * Register Elementor widgets
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager
     */
    public function register_widgets($widgets_manager) {
        require_once AELM_PLUGIN_DIR . 'includes/builders/elementor/widgets/class-smart-link-widget.php';
        $widgets_manager->register(new AELM_Smart_Link_Widget());
    }

    /**
     * Add link controls to existing elements
     *
     * @param \Elementor\Element_Base $element Element instance
     * @param array $section_id Section ID
     */
    public function add_link_controls($element, $section_id) {
        if ($section_id === 'section_links') {
            $element->start_controls_section(
                'aelm_link_section',
                [
                    'label' => __('Link Settings', 'auto-external-link-modifier'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $element->add_control(
                'aelm_exclude_links',
                [
                    'label' => __('Exclude Links', 'auto-external-link-modifier'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => '',
                    'description' => __('Exclude links in this element from modification', 'auto-external-link-modifier'),
                ]
            );

            $element->end_controls_section();
        }
    }

    /**
     * Enqueue preview styles
     *
     * @return void
     */
    public function enqueue_preview_styles() {
        wp_enqueue_style(
            'aelm-elementor-preview',
            AELM_PLUGIN_URL . 'assets/css/elementor-preview.css',
            [],
            AELM_VERSION
        );
    }

    /**
     * Process content with exclusion check
     *
     * @param string $content The content to process
     * @return string
     */
    public function process_content($content) {
        // Check if current element is excluded
        if ($this->is_element_excluded()) {
            return $content;
        }

        return parent::process_content($content);
    }

    /**
     * Check if current element is excluded
     *
     * @return boolean
     */
    private function is_element_excluded() {
        if (!class_exists('\Elementor\Plugin')) {
            return false;
        }

        $element = \Elementor\Plugin::$instance->elements_manager->get_current_element();
        if (!$element) {
            return false;
        }

        return $element->get_settings('aelm_exclude_links') === 'yes';
    }
}