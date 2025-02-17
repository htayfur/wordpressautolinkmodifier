<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_Compatibility_Checker {
    /**
     * Required WordPress functions
     *
     * @var array
     */
    private $required_functions = [
        'add_action',
        'add_filter',
        'plugin_dir_path',
        'plugin_dir_url',
        'wp_enqueue_style',
        'wp_enqueue_script',
        'wp_localize_script',
        'wp_add_inline_script',
        'esc_html__',
        'esc_attr__',
        'esc_html_e',
        'esc_attr_e',
        'esc_url',
        'esc_attr',
        'esc_html',
        'wp_kses_post'
    ];

    /**
     * Required PHP extensions
     *
     * @var array
     */
    private $required_extensions = [
        'dom',
        'json',
        'libxml',
        'mbstring'
    ];

    /**
     * Missing requirements
     *
     * @var array
     */
    private $missing = [
        'functions' => [],
        'extensions' => [],
        'classes' => []
    ];

    /**
     * Check all compatibility requirements
     *
     * @return boolean
     */
    public function check_all() {
        $this->check_functions();
        $this->check_extensions();
        $this->check_classes();

        return empty($this->missing['functions']) && 
               empty($this->missing['extensions']) && 
               empty($this->missing['classes']);
    }

    /**
     * Check required WordPress functions
     */
    private function check_functions() {
        foreach ($this->required_functions as $function) {
            if (!function_exists($function)) {
                $this->missing['functions'][] = $function;
            }
        }
    }

    /**
     * Check required PHP extensions
     */
    private function check_extensions() {
        foreach ($this->required_extensions as $extension) {
            if (!extension_loaded($extension)) {
                $this->missing['extensions'][] = $extension;
            }
        }
    }

    /**
     * Check required classes
     */
    private function check_classes() {
        $required_classes = [
            'Elementor\Widget_Base' => 'Elementor',
            'WPBakeryShortCode' => 'WPBakery Page Builder',
            'ET_Builder_Module' => 'Divi Builder'
        ];

        foreach ($required_classes as $class => $name) {
            if (!class_exists($class)) {
                $this->missing['classes'][] = $name;
            }
        }
    }

    /**
     * Get error messages for missing requirements
     *
     * @return array
     */
    public function get_error_messages() {
        $messages = [];

        if (!empty($this->missing['functions'])) {
            $messages[] = sprintf(
                'Missing WordPress functions: %s',
                implode(', ', $this->missing['functions'])
            );
        }

        if (!empty($this->missing['extensions'])) {
            $messages[] = sprintf(
                'Missing PHP extensions: %s',
                implode(', ', $this->missing['extensions'])
            );
        }

        if (!empty($this->missing['classes'])) {
            $messages[] = sprintf(
                'Missing required plugins: %s',
                implode(', ', $this->missing['classes'])
            );
        }

        return $messages;
    }

    /**
     * Display admin notices for missing requirements
     */
    public function display_notices() {
        if (empty($this->missing['functions']) && 
            empty($this->missing['extensions']) && 
            empty($this->missing['classes'])) {
            return;
        }

        add_action('admin_notices', function() {
            foreach ($this->get_error_messages() as $message) {
                printf(
                    '<div class="notice notice-error"><p>%s</p></div>',
                    esc_html($message)
                );
            }
        });
    }
}