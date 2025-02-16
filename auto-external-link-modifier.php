<?php
/**
 * Plugin Name: Auto External Link Modifier
 * Plugin URI: https://htayfur.com
 * Description: A WordPress plugin that adds custom 'rel' attributes and 'target="_blank"' to external links in posts and pages. Modifies all links except internal links and official institution websites.
 * Version: 1.2.1
 * Author: Hakan Tayfur
 * Author URI: https://htayfur.com
 * License: GPL v2 or later
 * Text Domain: auto-external-link-modifier
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AELM_VERSION', '1.2.1');
define('AELM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AELM_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once AELM_PLUGIN_DIR . 'includes/class-link-modifier.php';
require_once AELM_PLUGIN_DIR . 'includes/class-settings.php';

final class Auto_External_Link_Modifier {
    private static $instance = null;
    public $link_modifier;
    public $settings;

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
        $this->settings = new AELM_Settings();
        $this->link_modifier = new AELM_Link_Modifier();
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