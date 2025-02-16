<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_Settings {
    private $rel_options;
    private $settings_errors = [];

    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);

        $this->rel_options = [
            'noopener' => __('noopener - Prevents the new page from accessing window.opener', 'auto-external-link-modifier'),
            'noreferrer' => __('noreferrer - Prevents passing referrer information', 'auto-external-link-modifier'),
            'nofollow' => __('nofollow - Tells search engines not to follow this link', 'auto-external-link-modifier'),
            'sponsored' => __('sponsored - Marks links as paid/sponsored content', 'auto-external-link-modifier'),
            'ugc' => __('ugc - Marks links as user-generated content', 'auto-external-link-modifier')
        ];
    }

    public function enqueue_admin_scripts($hook) {
        if ('settings_page_aelm-settings' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'aelm-admin-styles',
            plugins_url('assets/css/admin.css', dirname(__FILE__)),
            [],
            AELM_VERSION
        );

        wp_enqueue_script(
            'aelm-admin-script',
            plugins_url('assets/js/admin.js', dirname(__FILE__)),
            ['jquery'],
            AELM_VERSION,
            true
        );
    }

    public function add_settings_page() {
        add_options_page(
            __('External Link Settings', 'auto-external-link-modifier'),
            __('External Links', 'auto-external-link-modifier'),
            'manage_options',
            'aelm-settings',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting(
            'aelm_settings',
            'aelm_rel_attributes',
            [
                'type' => 'array',
                'default' => ['noopener', 'noreferrer'],
                'sanitize_callback' => [$this, 'sanitize_rel_attributes']
            ]
        );

        register_setting(
            'aelm_settings',
            'aelm_custom_domains',
            [
                'type' => 'array',
                'default' => [],
                'sanitize_callback' => [$this, 'sanitize_custom_domains']
            ]
        );

        // Ana Ayarlar Bölümü
        add_settings_section(
            'aelm_main_section',
            __('Link Attributes', 'auto-external-link-modifier'),
            [$this, 'render_main_section_info'],
            'aelm-settings'
        );

        // REL Özellikleri Alanı
        add_settings_field(
            'aelm_rel_attributes',
            __('REL Attributes', 'auto-external-link-modifier'),
            [$this, 'render_rel_attributes_field'],
            'aelm-settings',
            'aelm_main_section'
        );

        // Hariç Tutulan Domainler Bölümü
        add_settings_section(
            'aelm_domains_section',
            __('Excluded Domains', 'auto-external-link-modifier'),
            [$this, 'render_domains_section_info'],
            'aelm-settings'
        );

        // Özel Domain Listesi Alanı
        add_settings_field(
            'aelm_custom_domains',
            __('Custom Domains', 'auto-external-link-modifier'),
            [$this, 'render_custom_domains_field'],
            'aelm-settings',
            'aelm_domains_section'
        );
    }

    public function sanitize_rel_attributes($input) {
        if (!is_array($input)) {
            return ['noopener', 'noreferrer'];
        }
        return array_intersect($input, array_keys($this->rel_options));
    }

    public function sanitize_custom_domains($input) {
        if (empty($input)) {
            return [];
        }

        if (is_string($input)) {
            $domains = array_filter(array_map('trim', explode("\n", $input)));
        } else {
            $domains = array_filter(array_map('trim', (array)$input));
        }

        $valid_domains = [];
        foreach ($domains as $domain) {
            $domain = strtolower($domain);
            if (preg_match('/^(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z]{2,}$/', $domain)) {
                $valid_domains[] = $domain;
            } else {
                $this->settings_errors[] = sprintf(
                    __('Invalid domain format: %s', 'auto-external-link-modifier'),
                    esc_html($domain)
                );
            }
        }

        return $valid_domains;
    }

    public function render_main_section_info() {
        echo '<div class="notice notice-info inline"><p>';
        esc_html_e('Select which rel attributes to add to external links. These attributes help with security and SEO.', 'auto-external-link-modifier');
        echo '</p></div>';
    }

    public function render_domains_section_info() {
        echo '<div class="notice notice-info inline"><p>';
        esc_html_e('Add custom domains to exclude from link modifications. One domain per line.', 'auto-external-link-modifier');
        echo '</p></div>';
    }

    public function render_rel_attributes_field() {
        $current_values = get_option('aelm_rel_attributes', ['noopener', 'noreferrer']);
        
        echo '<div class="rel-attributes-container">';
        foreach ($this->rel_options as $value => $label) {
            $checked = in_array($value, $current_values) ? 'checked' : '';
            printf(
                '<label class="rel-attribute-option">
                    <input type="checkbox" name="aelm_rel_attributes[]" value="%s" %s>
                    <strong>%s</strong><br>
                    <span class="description">%s</span>
                </label>',
                esc_attr($value),
                $checked,
                esc_html($value),
                esc_html($label)
            );
        }
        echo '</div>';
    }

    public function render_custom_domains_field() {
        $domains = get_option('aelm_custom_domains', []);
        if (is_array($domains)) {
            $domains = implode("\n", $domains);
        }
        ?>
        <textarea name="aelm_custom_domains" rows="8" class="large-text code" placeholder="example.com"><?php echo esc_textarea($domains); ?></textarea>
        <p class="description">
            <?php esc_html_e('Enter one domain per line. Example: example.com', 'auto-external-link-modifier'); ?>
        </p>
        <?php
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Hata mesajlarını göster
        if (!empty($this->settings_errors)) {
            echo '<div class="notice notice-error"><p>';
            echo implode('<br>', array_map('esc_html', $this->settings_errors));
            echo '</p></div>';
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('aelm_settings');
                do_settings_sections('aelm-settings');
                submit_button(__('Save Changes', 'auto-external-link-modifier'));
                ?>
            </form>
        </div>
        <?php
    }
}