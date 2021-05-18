<?php

namespace WP28\PAGUECOMPIX\Includes\Fields;

use WP28\PAGUECOMPIX\Includes\Core\Config;

/**
 * Class Text
 * @package WP28\PAGUECOMPIX\Includes\Fields
 */
class Text extends BaseField
{
    /**
     * @var string
     */
    public string $description;
    /**
     * @var bool
     */
    public bool $desc_tip;
    /**
     * @var string
     */
    public string $default;

    /**
     * Text constructor.
     * @param string $title
     * @param string $description
     * @param string $default
     * @param bool $desc_tip
     */
    public function __construct(string $title, string $description, string $default = '', bool $desc_tip = false)
    {
        parent::__construct($title, 'text', null, true);
        $this->description      = $description;
        $this->desc_tip         = $desc_tip;
        if(!empty($default)){
            $this->default          = $default;
        }
    }
}