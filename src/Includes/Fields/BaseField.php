<?php

namespace WP28\PAGUECOMPIX\Includes\Fields;

/**
 * Class BaseField
 * @package WP28\PAGUECOMPIX\Includes\Fields
 */
class BaseField
{
    /**
     * @var string
     */
    public string $title;
    /**
     * @var string
     */
    public string $type;
    /**
     * @var string
     */
    public string $cssClass;
    /**
     * @var string|bool
     */
    public string $required;
    /**
     * @var array
     */
    public array $customAttributes;

    /**
     * BaseField constructor.
     * @param string $title
     * @param string $type
     * @param string|null $class
     * @param bool $required
     * @param array|null $attributes
     */
    public function __construct(string $title, string $type, string $class = null, bool $required = false, array $attributes = null)
    {
        $this->title            = $title;
        $this->type             = $type;
        if(!is_null($class)){
            $this->cssClass     = $class;
        }
        if(!is_null($required)){
            $this->required     = $required;
        }
        if(!is_null($attributes)){
            $this->customAttributes     = $attributes;
        }
    }
}