<?php

namespace WP28\PAGUECOMPIX\Includes\Pix\Models;

use WP28\PAGUECOMPIX\Includes\Pix\Constants;
use WP28\PAGUECOMPIX\Includes\Pix\PixCRC16;

class PixDataObject
{
    private array $data_objects;

    public function __construct()
    {
        $this->data_objects = [];
    }

    public function add(string $key, string $value): PixDataObject
    {
        $this->data_objects[$key] = $value;
        return $this;
    }

    public function __toString(): string
    {
        ksort($this->data_objects);
        $data = '';
        foreach ($this->data_objects as $key => $value) {
            $key = str_pad($key, 2, '0', STR_PAD_LEFT);
            $length = mb_strlen($value);
            $length = str_pad($length, 2, '0', STR_PAD_LEFT);
            $data .= "{$key}{$length}{$value}";
        }
        if (isset($this->data_objects[Constants::CRC16_ID])) {
            $data = mb_substr($data, 0, -4);
            $doCRC16 = PixCRC16::checksum($data);
            $data .= $doCRC16;
        }
        return $data;
    }
}
