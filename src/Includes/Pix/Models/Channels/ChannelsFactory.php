<?php


namespace WP28\PAGUECOMPIX\Includes\Pix\Models\Channels;

use WP28\PAGUECOMPIX\Includes\Core\Config;

abstract class ChannelsFactory
{
    public static string $name;
    public string $value;
    public string $icon;
    public string $link;
    public string $order_id;

    public static function create($name, $value, $order_id)
    {
        self::$name = $name;
        switch ($name){
            default:
            case 'whatsapp':
                return new Whatsapp($value, $order_id);
            case "email":
                return new Email($value, $order_id);
            case "telegram":
                return new Telegram($value, $order_id);
        }
    }

    protected abstract function format_link() : string;

}