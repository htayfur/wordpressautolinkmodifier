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

    public function __construct() {
        add_filter('the_content', [$this, 'modify_links'], 999);
        $this->load_rel_attributes();
    }

    private function load_rel_attributes() {
        $this->rel_attributes = get_option('aelm_rel_attributes', ['noopener', 'noreferrer', 'nofollow']);
    }

    public function modify_links($content) {
        if (strpos($content, '<a') === false) {
            return $content;
        }

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

            if (empty($href) || $this->is_internal_link($href) || $this->is_official_domain($href)) {
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
        $content = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(['<html>', '</html>', '<body>', '</body>'], '', $content));
        
        return $content;
    }

    private function is_internal_link($url) {
        $site_url = parse_url(get_site_url(), PHP_URL_HOST);
        $link_host = parse_url($url, PHP_URL_HOST);

        if (empty($link_host)) {
            return true;
        }

        return $site_url === $link_host;
    }

    private function is_official_domain($url) {
        $domain = parse_url($url, PHP_URL_HOST);
        
        if (empty($domain)) {
            return false;
        }

        $domain_parts = explode('.', strtolower($domain));
        $domain_length = count($domain_parts);

        if ($domain_length < 2) {
            return false;
        }

        // Check full domain first (e.g., education.gov.au)
        if (in_array($domain, $this->official_domains)) {
            return true;
        }

        // Check domain patterns
        foreach ($this->official_domains as $official_domain) {
            $pattern = preg_quote($official_domain, '/');
            if (preg_match('/\.' . $pattern . '$/', $domain)) {
                return true;
            }
        }

        return false;
    }
}