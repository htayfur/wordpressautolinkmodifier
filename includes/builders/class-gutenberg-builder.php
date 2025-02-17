<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_Gutenberg_Builder extends AELM_Builder_Base {
    /**
     * Constructor
     *
     * @param AELM_Link_Modifier $link_modifier Link modifier instance
     */
    public function __construct(AELM_Link_Modifier $link_modifier) {
        parent::__construct('gutenberg', $link_modifier);
    }

    /**
     * Check if Gutenberg is active
     *
     * @return boolean
     */
    public function is_active() {
        return function_exists('register_block_type');
    }

    /**
     * Register custom blocks
     *
     * @return void
     */
    public function register_elements() {
        add_action('init', [$this, 'register_blocks']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
    }

    /**
     * Extend existing elements
     *
     * @return void
     */
    public function extend_elements() {
        // Filter for core paragraph and heading blocks
        add_filter('render_block', [$this, 'process_block_content'], 10, 2);
    }

    /**
     * Add Gutenberg specific hooks
     *
     * @return void
     */
    protected function add_hooks() {
        // Register block category
        add_filter('block_categories_all', [$this, 'register_block_category'], 10, 2);
    }

    /**
     * Register custom block category
     *
     * @param array $categories Block categories
     * @param WP_Post $post Post being loaded
     * @return array
     */
    public function register_block_category($categories, $post) {
        return array_merge($categories, [
            [
                'slug' => 'aelm-blocks',
                'title' => __('External Link Blocks', 'auto-external-link-modifier'),
                'icon' => 'admin-links',
            ],
        ]);
    }

    /**
     * Register blocks
     *
     * @return void
     */
    public function register_blocks() {
        // Register Smart Link block
        register_block_type(AELM_PLUGIN_DIR . 'includes/builders/gutenberg/blocks/smart-link', [
            'render_callback' => [$this, 'render_smart_link_block']
        ]);
    }

    /**
     * Enqueue editor assets
     *
     * @return void
     */
    public function enqueue_editor_assets() {
        // Editor Script
        wp_enqueue_script(
            'aelm-blocks-editor',
            AELM_PLUGIN_URL . 'assets/js/blocks/editor.js',
            ['wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components'],
            AELM_VERSION,
            true
        );

        // Editor Styles
        wp_enqueue_style(
            'aelm-blocks-editor',
            AELM_PLUGIN_URL . 'assets/css/blocks-editor.css',
            ['wp-edit-blocks'],
            AELM_VERSION
        );

        // Localize script
        wp_localize_script('aelm-blocks-editor', 'aelmBlocksData', [
            'pluginUrl' => AELM_PLUGIN_URL,
            'settings' => get_option('aelm_settings', [])
        ]);
    }

    /**
     * Process block content
     *
     * @param string $block_content Block content
     * @param array $block Block attributes
     * @return string
     */
    public function process_block_content($block_content, $block) {
        // Process only specific blocks
        $process_blocks = ['core/paragraph', 'core/heading', 'core/group'];
        
        if (!in_array($block['blockName'], $process_blocks)) {
            return $block_content;
        }

        // Skip if block is marked to exclude
        if (!empty($block['attrs']['aelmExclude'])) {
            return $block_content;
        }

        return $this->process_content($block_content);
    }

    /**
     * Render smart link block
     *
     * @param array $attributes Block attributes
     * @param string $content Block content
     * @return string
     */
    public function render_smart_link_block($attributes, $content = '') {
        $classes = ['aelm-smart-link'];
        
        if (!empty($attributes['className'])) {
            $classes[] = $attributes['className'];
        }

        if (!empty($attributes['alignment'])) {
            $classes[] = 'has-text-align-' . $attributes['alignment'];
        }

        // Build link attributes
        $link_attrs = [
            'class' => 'aelm-link',
            'href' => !empty($attributes['url']) ? esc_url($attributes['url']) : '#'
        ];

        if (!empty($attributes['opensInNewTab'])) {
            $link_attrs['target'] = '_blank';
            $link_attrs['rel'] = 'noopener noreferrer';
        }

        // Build HTML attributes string
        $html_attrs = '';
        foreach ($link_attrs as $key => $value) {
            $html_attrs .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        return sprintf(
            '<div class="%1$s"><a%2$s>%3$s</a></div>',
            esc_attr(implode(' ', $classes)),
            $html_attrs,
            wp_kses_post($attributes['content'] ?? '')
        );
    }
}