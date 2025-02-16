<?php
if (!defined('ABSPATH')) {
    exit;
}

interface AELM_Builder_Interface {
    /**
     * Initialize the builder integration
     *
     * @return void
     */
    public function init();

    /**
     * Check if the builder is active
     *
     * @return boolean
     */
    public function is_active();

    /**
     * Register custom widgets/elements
     *
     * @return void
     */
    public function register_elements();

    /**
     * Process content from the builder
     *
     * @param string $content The content to process
     * @return string
     */
    public function process_content($content);

    /**
     * Add custom controls to existing elements
     *
     * @return void
     */
    public function extend_elements();
}

/**
 * Abstract base class for builders
 */
abstract class AELM_Builder_Base implements AELM_Builder_Interface {
    /**
     * Builder name
     *
     * @var string
     */
    protected $name;

    /**
     * Link modifier instance
     *
     * @var AELM_Link_Modifier
     */
    protected $link_modifier;

    /**
     * Constructor
     *
     * @param string $name Builder name
     * @param AELM_Link_Modifier $link_modifier Link modifier instance
     */
    public function __construct($name, AELM_Link_Modifier $link_modifier) {
        $this->name = $name;
        $this->link_modifier = $link_modifier;
    }

    /**
     * Get builder name
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Initialize the builder
     *
     * @return void
     */
    public function init() {
        if (!$this->is_active()) {
            return;
        }

        $this->register_elements();
        $this->extend_elements();
        $this->add_hooks();
    }

    /**
     * Add builder specific hooks
     *
     * @return void
     */
    abstract protected function add_hooks();

    /**
     * Process content from the builder
     *
     * @param string $content The content to process
     * @return string
     */
    public function process_content($content) {
        return $this->link_modifier->modify_links($content);
    }
}