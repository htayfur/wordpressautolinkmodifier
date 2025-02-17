<?php
if (!defined('ABSPATH')) {
    exit;
}

class AELM_Plugin_Loader {
    private $actions = [];
    private $filters = [];
    
    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @param string $hook          The name of the WordPress action.
     * @param object $component     A reference to the instance of the object.
     * @param string $callback      The name of the function.
     * @param int    $priority      Optional. The priority at which the function should be fired. Default is 10.
     * @param int    $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @param string $hook          The name of the WordPress filter.
     * @param object $component     A reference to the instance of the object.
     * @param string $callback      The name of the function.
     * @param int    $priority      Optional. The priority at which the function should be fired. Default is 10.
     * @param int    $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * A utility function that is used to register the actions and hooks into a single collection.
     *
     * @access private
     * @param array  $hooks         The collection of hooks.
     * @param string $hook          The name of the WordPress filter.
     * @param object $component     A reference to the instance of the object.
     * @param string $callback      The name of the function.
     * @param int    $priority      The priority at which the function should be fired.
     * @param int    $accepted_args The number of arguments that should be passed to the $callback.
     * @return array The collection of actions and filters registered with WordPress.
     */
    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args) {
        $hooks[] = [
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        ];

        return $hooks;
    }

    /**
     * Register the filters and actions with WordPress.
     */
    public function run() {
        foreach ($this->filters as $hook) {
            add_filter(
                $hook['hook'],
                [$hook['component'], $hook['callback']],
                $hook['priority'],
                $hook['accepted_args']
            );
        }

        foreach ($this->actions as $hook) {
            add_action(
                $hook['hook'],
                [$hook['component'], $hook['callback']],
                $hook['priority'],
                $hook['accepted_args']
            );
        }
    }

    /**
     * Check if required classes exist
     *
     * @return array Array of missing classes
     */
    public function check_dependencies() {
        $missing = [];

        $required_classes = [
            'Elementor\Widget_Base' => 'Elementor',
            'WPBakeryShortCode' => 'WPBakery Page Builder',
            'ET_Builder_Module' => 'Divi Builder'
        ];

        foreach ($required_classes as $class => $name) {
            if (!class_exists($class)) {
                $missing[] = $name;
            }
        }

        return $missing;
    }
}