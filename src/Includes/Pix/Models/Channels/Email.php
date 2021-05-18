<?php

namespace WP28\PAGUECOMPIX\Includes\Pix\Models\Channels;

use WP28\PAGUECOMPIX\Includes\Core\Config;

class Email extends ChannelsFactory
{
    private const URL   = "mailto:";

    public function __construct($value, $order_id)
    {
        $this->value    = $value;
        $this->order_id = $order_id;
        $this->icon     = Config::getAssetsUrl() . 'mail.svg';
        $this->link     = $this->format_link();
    }

    protected function format_link(): string
    {
        return self::URL . $this->value . "?" . $this->subject() . "&" . $this->body_message();
    }

    private function subject() : string
    {
        return urlencode("Comprovante de pagamento");
    }

    private function body_message() : string
    {
        return urlencode("Comprovante de pagamento via Pix do pedido {$this->order_id}");
    }

}