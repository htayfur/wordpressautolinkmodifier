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
    private $domain_patterns = [];
    private $cache_group = 'aelm_cache';
    private $cache_expiration = 3600; // 1 hour

    public function __construct() {
        add_filter('the_content', [$this, 'modify_links'], 999);
        add_filter('widget_text', [$this, 'modify_links'], 999);
        add_filter('widget_text_content', [$this, 'modify_links'], 999);
        add_filter('widget_block_content', [$this, 'modify_links'], 999);

        // Admin AJAX handlers for bulk operations
        add_action('wp_ajax_aelm_import_domains', [$this, 'handle_domain_import']);
        add_action('wp_ajax_aelm_export_domains', [$this, 'handle_domain_export']);

        $this->load_settings();
        $this->setup_error_handling();
    }

    public function handle_domain_import() {
        check_admin_referer('aelm_import_domains', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        $file = $_FILES['domain_file'] ?? null;
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            wp_send_json_error('Invalid file upload');
        }

        $content = file_get_contents($file['tmp_name']);
        $domains = array_filter(array_map('trim', explode("\n", $content)));
        
        $valid_domains = array_filter($domains, [$this, 'is_valid_domain_pattern']);
        update_option('aelm_custom_domains', $valid_domains);
        
        wp_send_json_success([
            'message' => sprintf(
                __('Imported %d domains successfully', 'auto-external-link-modifier'),
                count($valid_domains)
            )
        ]);
    }

    public function handle_domain_export() {
        check_admin_referer('aelm_export_domains', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        $domains = get_option('aelm_custom_domains', []);
        $content = implode("\n", $domains);
        
        wp_send_json_success([
            'content' => $content,
            'filename' => 'domains-' . date('Y-m-d') . '.txt'
        ]);
    }

    private function load_settings() {
        $this->rel_attributes = get_option('aelm_rel_attributes', ['noopener', 'noreferrer', 'nofollow']);
        $this->custom_domains = get_option('aelm_custom_domains', []);
        $this->domain_patterns = array_filter($this->custom_domains, [$this, 'is_pattern']);
        
        if (is_string($this->custom_domains)) {
            $this->custom_domains = array_filter(array_map('trim', explode("\n", $this->custom_domains)));
        }
    }

    private function is_pattern($domain) {
        return strpos($domain, '*') !== false || strpos($domain, '^') !== false;
    }

    private function is_valid_domain_pattern($pattern) {
        // Allow wildcard subdomains: *.example.com
        if (strpos($pattern, '*.') === 0) {
            $domain = substr($pattern, 2);
            return $this->is_valid_domain($domain);
        }

        // Allow regex patterns starting with ^
        if (strpos($pattern, '^') === 0) {
            return @preg_match('/' . $pattern . '/', '') !== false;
        }

        return $this->is_valid_domain($pattern);
    }

    private function is_valid_domain($domain) {
        return (bool)preg_match('/^(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z]{2,}$/i', $domain);
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
            error_log('[Auto External Link Modifier] URL Parse Error: ' . $e->getMessage());
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
            
            // Check exact match domains
            if (in_array($domain, $this->custom_domains)) {
                return true;
            }

            // Check wildcard patterns
            foreach ($this->domain_patterns as $pattern) {
                if (strpos($pattern, '*.') === 0) {
                    $base_domain = substr($pattern, 2);
                    if (preg_match('/\.' . preg_quote($base_domain, '/') . '$/', $domain)) {
                        return true;
                    }
                } elseif (strpos($pattern, '^') === 0) {
                    if (preg_match('/' . $pattern . '/', $domain)) {
                        return true;
                    }
                }
            }

            // Check official domains
            foreach ($this->official_domains as $official_domain) {
                if (preg_match('/\.' . preg_quote($official_domain, '/') . '$/i', $domain)) {
                    return true;
                }
            }

            return false;
        } catch (Exception $e) {
            error_log('[Auto External Link Modifier] Domain Parse Error: ' . $e->getMessage());
            return false;
        }
    }
}