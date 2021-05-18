<?php

namespace WP28\PAGUECOMPIX\Includes\Pix\Models\Channels;

use WP28\PAGUECOMPIX\Includes\Core\Config;

class Whatsapp extends ChannelsFactory
{
    private const BRCODE    = "55";
    private const URL       = "https://wa.me/";

    public function __construct($value, $order_id)
    {
        $this->value    = $value;
        $this->order_id = $order_id;
        $this->icon     = Config::getAssetsUrl() . 'whatsapp.svg';
        $this->link     = $this->format_link();
    }

    protected function format_link() : string
    {
        return self::URL . $this->phone_number() . "?text=" . $this->message();
    }

    private function phone_number() : string
    {
        return self::BRCODE . preg_replace("/\D/", "" ,$this->value);
    }

    private function message() : string
    {
        return urlencode("Comprovante de pagamento via Pix do pedido {$this->order_id}");
    }


}