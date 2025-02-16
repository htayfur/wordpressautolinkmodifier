<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_Settings {
    private $rel_options;

    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);

        $this->rel_options = [
            'noopener' => __('noopener - Prevents the new page from accessing window.opener', 'auto-external-link-modifier'),
            'noreferrer' => __('noreferrer - Prevents passing referrer information', 'auto-external-link-modifier'),
            'nofollow' => __('nofollow - Tells search engines not to follow this link', 'auto-external-link-modifier'),
            'sponsored' => __('sponsored - Marks links as paid/sponsored content', 'auto-external-link-modifier'),
            'ugc' => __('ugc - Marks links as user-generated content', 'auto-external-link-modifier')
        ];
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

        add_settings_section(
            'aelm_main_section',
            __('Link Attributes', 'auto-external-link-modifier'),
            [$this, 'render_section_info'],
            'aelm-settings'
        );

        add_settings_field(
            'aelm_rel_attributes',
            __('REL Attributes', 'auto-external-link-modifier'),
            [$this, 'render_rel_attributes_field'],
            'aelm-settings',
            'aelm_main_section'
        );
    }

    public function sanitize_rel_attributes($input) {
        if (!is_array($input)) {
            return ['noopener', 'noreferrer'];
        }

        return array_intersect($input, array_keys($this->rel_options));
    }

    public function render_section_info() {
        echo '<div class="notice notice-info inline"><p>';
        esc_html_e('Select which rel attributes to add to external links. These attributes help with security and SEO.', 'auto-external-link-modifier');
        echo '</p></div>';
    }

    public function render_rel_attributes_field() {
        $current_values = get_option('aelm_rel_attributes', ['noopener', 'noreferrer']);
        
        echo '<div class="rel-attributes-container" style="max-width:600px;">';
        foreach ($this->rel_options as $value => $label) {
            $checked = in_array($value, $current_values) ? 'checked' : '';
            printf(
                '<label class="rel-attribute-option" style="display:block;margin-bottom:12px;padding:8px;background:#f8f9fa;border-radius:4px;">
                    <input type="checkbox" name="aelm_rel_attributes[]" value="%s" %s>
                    <strong>%s</strong><br>
                    <span class="description" style="display:block;margin-top:4px;margin-left:24px;color:#666;">%s</span>
                </label>',
                esc_attr($value),
                $checked,
                esc_html($value),
                esc_html($label)
            );
        }
        echo '</div>';
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
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