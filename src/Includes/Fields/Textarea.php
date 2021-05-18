<?php

namespace WP28\PAGUECOMPIX\Includes\Fields;

use WP28\PAGUECOMPIX\Includes\Core\Config;

/**
 * Class Textarea
 * @package WP28\PAGUECOMPIX\Includes\Fields
 */
class Textarea extends BaseField
{
    /**
     * @var string
     */
    public string $description;
    /**
     * @var string
     */
    public string $default;

    /**
     * Textarea constructor.
     * @param string $title
     * @param string $description
     * @param string $default
     */
    public function __construct(string $title, string $description, string $default = '')
    {
        parent::__construct($title, 'textarea');
        $this->description      = $description;
        if(!empty($default)){
            $this->default      = $default;
        }
    }
}