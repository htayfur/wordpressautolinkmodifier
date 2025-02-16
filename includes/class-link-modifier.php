<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_Link_Modifier {
    private $official_domains = [
        // Global government domains
        'gov', 'gov.uk', 'gov.au', 'gc.ca', 'europa.eu',
        // Global education domains
        'edu', 'ac.uk', 'edu.au', 'edu.sg',
        // Global military domains
        'mil',
        // Country specific domains
        'gov.tr', 'edu.tr', 'pol.tr', 'tsk.tr',
        'gov.de', 'gov.fr', 'gov.it', 'gov.es',
        'edu.de', 'edu.fr', 'edu.it', 'edu.es'
    ];
    
    private $rel_attributes = [];
    private $custom_domains = [];
    private $cache_group = 'aelm_cache';
    private $cache_expiration = 3600; // 1 hour

    public function __construct() {
        add_filter('the_content', [$this, 'modify_links'], 999);
        add_filter('widget_text', [$this, 'modify_links'], 999);
        add_filter('widget_text_content', [$this, 'modify_links'], 999);
        add_filter('widget_block_content', [$this, 'modify_links'], 999);

        add_action('save_post', [$this, 'clear_cache']);
        add_action('edited_terms', [$this, 'clear_cache']);
        add_action('switch_theme', [$this, 'clear_cache']);

        $this->load_settings();
        $this->setup_error_handling();
    }

    private function setup_error_handling() {
        set_error_handler([$this, 'error_handler']);
        register_shutdown_function([$this, 'shutdown_handler']);
    }

    public function error_handler($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            return false;
        }

        $error_message = sprintf(
            '[Auto External Link Modifier] Error: %s in %s on line %d',
            $errstr,
            $errfile,
            $errline
        );

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log($error_message);
        }

        return true;
    }

    public function shutdown_handler() {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_COMPILE_ERROR])) {
            $this->error_handler($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    private function load_settings() {
        $this->rel_attributes = get_option('aelm_rel_attributes', ['noopener', 'noreferrer', 'nofollow']);
        $this->custom_domains = get_option('aelm_custom_domains', []);
        
        if (is_string($this->custom_domains)) {
            $this->custom_domains = array_filter(array_map('trim', explode("\n", $this->custom_domains)));
        }
    }

    public function clear_cache($post_id = null) {
        if (function_exists('wp_cache_delete_group')) {
            wp_cache_delete_group($this->cache_group);
        } else {
            wp_cache_flush();
        }
    }

    public function modify_links($content) {
        if (!is_string($content) || empty($content) || strpos($content, '<a') === false) {
            return $content;
        }

        // Generate cache key
        $cache_key = 'aelm_' . md5($content);
        $cached_content = wp_cache_get($cache_key, $this->cache_group);

        if ($cached_content !== false) {
            return $cached_content;
        }

        try {
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();

            $modified = false;
            $links = $dom->getElementsByTagName('a');
            
            $links_to_modify = [];
            foreach ($links as $link) {
                $links_to_modify[] = $link;
            }

            foreach ($links_to_modify as $link) {
                $href = $link->getAttribute('href');

                if (empty($href) || $this->is_internal_link($href) || $this->is_excluded_domain($href)) {
                    continue;
                }

                $link->setAttribute('target', '_blank');

                $rel = $link->getAttribute('rel');
                $current_rels = array_filter(explode(' ', $rel));
                $new_rels = array_unique(array_merge($current_rels, $this->rel_attributes));
                $link->setAttribute('rel', implode(' ', $new_rels));

                $modified = true;
            }

            if (!$modified) {
                wp_cache_set($cache_key, $content, $this->cache_group, $this->cache_expiration);
                return $content;
            }

            $modified_content = preg_replace(
                '/^<!DOCTYPE.+?>/', '', 
                str_replace(
                    ['<html>', '</html>', '<body>', '</body>'], 
                    '', 
                    $dom->saveHTML()
                )
            );

            wp_cache_set($cache_key, $modified_content, $this->cache_group, $this->cache_expiration);
            return $modified_content;

        } catch (Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[Auto External Link Modifier] Exception: ' . $e->getMessage());
            }
            return $content;
        }
    }

    private function is_internal_link($url) {
        try {
            $site_url = parse_url(get_site_url(), PHP_URL_HOST);
            $link_host = parse_url($url, PHP_URL_HOST);

            if (empty($link_host)) {
                return true;
            }

            return $site_url === $link_host;
        } catch (Exception $e) {
            $this->error_handler(E_WARNING, 'URL Parse Error: ' . $e->getMessage(), __FILE__, __LINE__);
            return true;
        }
    }

    private function is_excluded_domain($url) {
        try {
            $domain = parse_url($url, PHP_URL_HOST);
            
            if (empty($domain)) {
                return false;
            }

            $domain = strtolower($domain);
            
            // Check custom domains
            if (in_array($domain, $this->custom_domains)) {
                return true;
            }

            // Check official domains
            foreach ($this->official_domains as $official_domain) {
                if (preg_match('/\.' . preg_quote($official_domain, '/') . '$/i', $domain)) {
                    return true;
                }
            }

            return false;
        } catch (Exception $e) {
            $this->error_handler(E_WARNING, 'Domain Parse Error: ' . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
}