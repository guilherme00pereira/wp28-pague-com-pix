<?php

namespace WP28\PAGUECOMPIX\Includes\Pix\Models\Channels;

use WP28\PAGUECOMPIX\Includes\Core\Config;

class Telegram extends ChannelsFactory
{
    private const URL   = "https://t.me/";

    public function __construct($value, $order_id)
    {
        $this->value    = $value;
        $this->order_id = $order_id;
        $this->icon     = Config::getAssetsUrl() . 'telegram.svg';
        $this->link     = $this->format_link();
    }

    protected function format_link(): string
    {
        return self::URL . $this->value . "?text=" . $this->message();
    }

    private function message() : string
    {
        return urlencode("Comprovante de pagamento via Pix do pedido {$this->order_id}");
    }
}