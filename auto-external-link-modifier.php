<?php
/**
 * Plugin Name: Auto External Link Modifier
 * Plugin URI: https://htayfur.com
 * Description: A WordPress plugin that adds custom 'rel' attributes and 'target="_blank"' to external links in posts and pages. Modifies all links except internal links and official institution websites.
 * Version: 2.0.2
 * Author: Hakan Tayfur
 * Author URI: https://htayfur.com
 * License: GPL v2 or later
 * Text Domain: auto-external-link-modifier
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AELM_VERSION', '2.0.2');
define('AELM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AELM_PLUGIN_URL', plugin_dir_url(__FILE__));

// First, load polyfill to ensure all functions are available
require_once AELM_PLUGIN_DIR . 'includes/class-polyfill.php';
AELM_Polyfill::register();
AELM_Polyfill::register_asset_functions();

// Plugin autoloader
spl_autoload_register(function ($class) {
    $prefix = 'AELM_';
    $base_dir = AELM_PLUGIN_DIR . 'includes/';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $relative_class = strtolower(str_replace('_', '-', $relative_class));
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Required core files
require_once AELM_PLUGIN_DIR . 'includes/class-plugin-loader.php';
require_once AELM_PLUGIN_DIR . 'includes/class-compatibility-checker.php';
require_once AELM_PLUGIN_DIR . 'includes/builders/class-builder-interface.php';
require_once AELM_PLUGIN_DIR . 'includes/class-link-modifier.php';
require_once AELM_PLUGIN_DIR . 'includes/class-settings.php';

final class Auto_External_Link_Modifier {
    private static $instance = null;
    public $loader;
    public $compatibility;
    public $link_modifier;
    public $settings;
    public $builders = [];

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->loader = new AELM_Plugin_Loader();
        $this->compatibility = new AELM_Compatibility_Checker();
        
        if ($this->compatibility->check_all()) {
            $this->loader->add_action('plugins_loaded', $this, 'init', 10, 1);
            $this->loader->add_action('admin_init', $this, 'check_environment', 10, 1);
            $this->loader->add_filter('plugin_action_links_' . plugin_basename(__FILE__), $this, 'add_action_links', 10, 1);
            $this->loader->run();
        } else {
            $this->compatibility->display_notices();
        }
    }

    public function init() {
        load_plugin_textdomain(
            'auto-external-link-modifier',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
        
        // Initialize core components
        $this->settings = new AELM_Settings();
        $this->link_modifier = new AELM_Link_Modifier();

        // Load and initialize builders
        $this->load_builders();
    }

    private function load_builders() {
        $builder_files = glob(AELM_PLUGIN_DIR . 'includes/builders/*/class-*-builder.php');
        
        foreach ($builder_files as $file) {
            if (preg_match('/class-(.+)-builder\.php$/', $file, $matches)) {
                $builder_name = $matches[1];
                $class_name = 'AELM_' . ucfirst($builder_name) . '_Builder';
                
                if (class_exists($class_name)) {
                    $builder = new $class_name($this->link_modifier);
                    if ($builder->is_active()) {
                        $this->builders[$builder_name] = $builder;
                        $builder->init();
                    }
                }
            }
        }
    }

    public function check_environment() {
        if (version_compare(PHP_VERSION, '7.4', '<')) {
            $this->loader->add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>';
                echo esc_html__('Auto External Link Modifier requires PHP 7.4 or higher.', 'auto-external-link-modifier');
                echo '</p></div>';
            }, 10, 1);
        }

        if (version_compare($GLOBALS['wp_version'], '5.0', '<')) {
            $this->loader->add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>';
                echo esc_html__('Auto External Link Modifier requires WordPress 5.0 or higher.', 'auto-external-link-modifier');
                echo '</p></div>';
            }, 10, 1);
        }

        // Check for page builders
        $missing_builders = $this->compatibility->get_error_messages();
        if (!empty($missing_builders)) {
            $this->loader->add_action('admin_notices', function() use ($missing_builders) {
                echo '<div class="notice notice-warning is-dismissible"><p>';
                echo esc_html(implode(' ', $missing_builders));
                echo '</p></div>';
            }, 10, 1);
        }
    }

    public function add_action_links($links) {
        $plugin_links = [
            '<a href="' . admin_url('options-general.php?page=aelm-settings') . '">' . 
            esc_html__('Settings', 'auto-external-link-modifier') . '</a>',
        ];
        return array_merge($plugin_links, $links);
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserialize
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}

function AELM() {
    return Auto_External_Link_Modifier::instance();
}

add_action('plugins_loaded', 'AELM', 5);