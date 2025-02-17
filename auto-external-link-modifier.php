<?php
/**
 * Plugin Name: Auto External Link Modifier
 * Plugin URI: https://htayfur.com
 * Description: A WordPress plugin that adds custom 'rel' attributes and 'target="_blank"' to external links in posts and pages. Modifies all links except internal links and official institution websites.
 * Version: 2.0.0
 * Author: Hakan Tayfur
 * Author URI: https://htayfur.com
 * License: GPL v2 or later
 * Text Domain: auto-external-link-modifier
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AELM_VERSION', '2.0.0');
define('AELM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AELM_PLUGIN_URL', plugin_dir_url(__FILE__));

spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'AELM_';
    $base_dir = AELM_PLUGIN_DIR . 'includes/';

    // Check if the class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);
    $relative_class = strtolower(str_replace('_', '-', $relative_class));

    // Replace namespace separators with directory separators
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

require_once AELM_PLUGIN_DIR . 'includes/builders/class-builder-interface.php';
require_once AELM_PLUGIN_DIR . 'includes/class-link-modifier.php';
require_once AELM_PLUGIN_DIR . 'includes/class-settings.php';

final class Auto_External_Link_Modifier {
    private static $instance = null;
    public $link_modifier;
    public $settings;
    public $builders = [];

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
        add_action('admin_init', [$this, 'check_environment']);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'add_action_links']);
    }

    public function init() {
        load_plugin_textdomain('auto-external-link-modifier', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
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
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>';
                echo esc_html__('Auto External Link Modifier requires PHP 7.4 or higher.', 'auto-external-link-modifier');
                echo '</p></div>';
            });
        }

        if (version_compare($GLOBALS['wp_version'], '5.0', '<')) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>';
                echo esc_html__('Auto External Link Modifier requires WordPress 5.0 or higher.', 'auto-external-link-modifier');
                echo '</p></div>';
            });
        }

        // Check for page builders
        $missing_builders = [];
        
        if (!defined('ELEMENTOR_VERSION')) {
            $missing_builders[] = 'Elementor';
        }
        
        if (!defined('WPB_VC_VERSION')) {
            $missing_builders[] = 'WPBakery Page Builder';
        }

        if (!defined('ET_BUILDER_VERSION')) {
            $missing_builders[] = 'Divi Builder';
        }

        if (!empty($missing_builders)) {
            add_action('admin_notices', function() use ($missing_builders) {
                echo '<div class="notice notice-warning is-dismissible"><p>';
                printf(
                    esc_html__('For full functionality, Auto External Link Modifier recommends installing: %s', 'auto-external-link-modifier'),
                    implode(', ', $missing_builders)
                );
                echo '</p></div>';
            });
        }
    }

    public function add_action_links($links) {
        $plugin_links = [
            '<a href="' . admin_url('options-general.php?page=aelm-settings') . '">' . __('Settings', 'auto-external-link-modifier') . '</a>',
        ];
        return array_merge($plugin_links, $links);
    }
}

function AELM() {
    return Auto_External_Link_Modifier::instance();
}

AELM();