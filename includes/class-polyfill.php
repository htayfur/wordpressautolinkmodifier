<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_Polyfill {
    /**
     * Register core WordPress function polyfills
     */
    public static function register() {
        self::register_escaping_functions();
        self::register_plugin_functions();
        self::register_admin_functions();
    }

    /**
     * Register escaping function polyfills
     */
    private static function register_escaping_functions() {
        if (!function_exists('esc_html')) {
            function esc_html($text) {
                return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            }
        }

        if (!function_exists('esc_attr')) {
            function esc_attr($text) {
                return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            }
        }

        if (!function_exists('esc_url')) {
            function esc_url($url) {
                return filter_var($url, FILTER_SANITIZE_URL);
            }
        }

        if (!function_exists('wp_kses_post')) {
            function wp_kses_post($content) {
                return strip_tags($content, '<a><p><br><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6>');
            }
        }

        if (!function_exists('esc_html__')) {
            function esc_html__($text, $domain = 'default') {
                return esc_html($text);
            }
        }

        if (!function_exists('esc_attr__')) {
            function esc_attr__($text, $domain = 'default') {
                return esc_attr($text);
            }
        }
    }

    /**
     * Register plugin function polyfills
     */
    private static function register_plugin_functions() {
        if (!function_exists('plugin_basename')) {
            function plugin_basename($file) {
                return basename(dirname($file)) . '/' . basename($file);
            }
        }

        if (!function_exists('plugin_dir_path')) {
            function plugin_dir_path($file) {
                return trailingslashit(dirname($file));
            }
        }

        if (!function_exists('plugin_dir_url')) {
            function plugin_dir_url($file) {
                $url = trailingslashit(plugins_url('', $file));
                if (is_ssl()) {
                    $url = str_replace('http://', 'https://', $url);
                }
                return $url;
            }
        }
    }

    /**
     * Register admin function polyfills
     */
    private static function register_admin_functions() {
        if (!function_exists('add_action')) {
            function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
                global $wp_filter;
                if (!isset($wp_filter[$tag])) {
                    $wp_filter[$tag] = [];
                }
                $wp_filter[$tag][] = [
                    'function' => $function_to_add,
                    'priority' => $priority,
                    'accepted_args' => $accepted_args
                ];
                return true;
            }
        }

        if (!function_exists('add_filter')) {
            function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
                return add_action($tag, $function_to_add, $priority, $accepted_args);
            }
        }

        if (!function_exists('admin_url')) {
            function admin_url($path = '') {
                return get_option('siteurl') . '/wp-admin/' . ltrim($path, '/');
            }
        }

        if (!function_exists('load_plugin_textdomain')) {
            function load_plugin_textdomain($domain, $deprecated = false, $plugin_rel_path = '') {
                return true; // Minimal implementation
            }
        }
    }

    /**
     * Register script and style function polyfills
     */
    public static function register_asset_functions() {
        if (!function_exists('wp_enqueue_script')) {
            function wp_enqueue_script($handle, $src = '', $deps = [], $ver = false, $in_footer = false) {
                echo sprintf('<script src="%s"></script>', esc_url($src));
            }
        }

        if (!function_exists('wp_enqueue_style')) {
            function wp_enqueue_style($handle, $src = '', $deps = [], $ver = false, $media = 'all') {
                echo sprintf('<link rel="stylesheet" href="%s" media="%s">', esc_url($src), esc_attr($media));
            }
        }

        if (!function_exists('wp_localize_script')) {
            function wp_localize_script($handle, $object_name, $l10n) {
                echo sprintf(
                    '<script>var %s = %s;</script>',
                    esc_js($object_name),
                    wp_json_encode($l10n)
                );
            }
        }
    }

    /**
     * Safe JSON encode
     */
    private static function wp_json_encode($data, $options = 0, $depth = 512) {
        return function_exists('wp_json_encode') 
            ? wp_json_encode($data, $options, $depth)
            : json_encode($data, $options, $depth);
    }

    /**
     * Safe JavaScript escaping
     */
    private static function esc_js($text) {
        return function_exists('esc_js')
            ? esc_js($text)
            : addslashes($text);
    }
}