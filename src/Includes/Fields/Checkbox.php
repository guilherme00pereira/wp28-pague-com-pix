<?php

namespace WP28\PAGUECOMPIX\Includes\Fields;

use WP28\PAGUECOMPIX\Includes\Core\Config;

/**
 * Class Checkbox
 * @package WP28\PAGUECOMPIX\Includes\Fields
 */
class Checkbox extends BaseField
{
    /**
     * @var string
     */
    public string $label;
    /**
     * @var string
     */
    public string $default;

    /**
     * Checkbox constructor.
     * @param string $title
     * @param string $label
     * @param string $default
     */
    public function __construct(string $title, string $label, string $default = 'yes')
    {
        parent::__construct($title, 'checkbox');
        $this->label    = $label;
        $this->default  = $default;
    }
}