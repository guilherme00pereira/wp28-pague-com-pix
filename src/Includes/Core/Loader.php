<?php

namespace WP28\PAGUECOMPIX\Includes\Core;

use Exception;

/**
 * Class Loader
 * @package WP28\PAGUECOMPIX\Includes\Core
 */
class Loader
{
    /**
     * @var array
     */
    protected array $actions;
    /**
     * @var array
     */
    protected array $filters;

    /**
     * @var ?Loader
     */
    private static ?Loader $instance = null;

    /**
     * Loader constructor.
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * @return Loader
     */
    public static function get_instance(): Loader
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * @param $hook
     * @param $component
     * @param $callback
     * @param int $priority
     * @param int $accepted_args
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1)
    {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }


    /**
     * @param $hook
     * @param $component
     * @param $callback
     * @param int $priority
     * @param int $accepted_args
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1)
    {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }


    /**
     * @param $hooks
     * @param $hook
     * @param $component
     * @param $callback
     * @param $priority
     * @param $accepted_args
     * @return mixed
     */
    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args)
    {
        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );
        return $hooks;
    }


    /**
     * @throws Exception
     */
    public function run()
    {

        foreach ($this->filters as $hook) {
            if (!method_exists($hook['component'], $hook['callback'])) {
                throw new Exception("Can't add filter. Method " . $hook['callback'] . " doesn't exist.");
            }
            add_filter($hook['hook'], ($hook['component'] === null ? $hook['callback'] : array($hook['component'], $hook['callback'])), $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->actions as $hook) {
            if (!method_exists($hook['component'], $hook['callback'])) {
                throw new Exception("Can't add action. Method " . $hook['callback'] . "doesn't exist.");
            }
            add_action($hook['hook'], ($hook['component'] === null ? $hook['callback'] : array($hook['component'], $hook['callback'])), $hook['priority'], $hook['accepted_args']);
        }
    }

    /**
     *
     */
    private function reset()
    {
        $this->filters = array();
        $this->actions = array();
    }
}
