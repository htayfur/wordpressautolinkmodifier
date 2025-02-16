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

    public function __construct() {
        // İçerik filtreleri
        add_filter('the_content', [$this, 'modify_links'], 999);
        add_filter('widget_text', [$this, 'modify_links'], 999);
        add_filter('widget_text_content', [$this, 'modify_links'], 999);
        add_filter('widget_block_content', [$this, 'modify_links'], 999);

        // Ayarları yükle
        $this->load_settings();

        // Hata yakalama
        set_error_handler([$this, 'error_handler']);
    }

    private function load_settings() {
        $this->rel_attributes = get_option('aelm_rel_attributes', ['noopener', 'noreferrer', 'nofollow']);
        $this->custom_domains = get_option('aelm_custom_domains', []);
        
        if (is_string($this->custom_domains)) {
            $this->custom_domains = array_filter(array_map('trim', explode("\n", $this->custom_domains)));
        }
    }

    public function error_handler($errno, $errstr, $errfile, $errline) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf(
                '[Auto External Link Modifier] Error: %s in %s on line %d',
                $errstr,
                $errfile,
                $errline
            ));
        }
        return false;
    }

    public function modify_links($content) {
        if (!is_string($content) || empty($content) || strpos($content, '<a') === false) {
            return $content;
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
                return $content;
            }

            $content = $dom->saveHTML();
            return preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(['<html>', '</html>', '<body>', '</body>'], '', $content));

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
            
            // Özel domain kontrolü
            if (in_array($domain, $this->custom_domains)) {
                return true;
            }

            // Resmi domain kontrolü
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