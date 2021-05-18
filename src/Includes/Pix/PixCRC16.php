<?php

namespace WP28\PAGUECOMPIX\Includes\Pix;

/**
 * Class PixCRC16
 * @package WP28\PAGUECOMPIX\Includes\Pix
 */
class PixCRC16
{
    /**
     * Calculates CRC16 CCITT False
     *
     * @see based on: https://stackoverflow.com/questions/30035582/how-to-calculate-crc16-ccitt-in-php-hex by evilReiko
     *
     * @param string $str The payload
     * @return string(4) The 4 bytes string containing the hex CRC16 representation
     */
    public static function checksum(string $str): string
    {
        $crc    = 0xFFFF;
        $length = strlen($str);

        for ($c = 0; $c < $length; $c++) {
            $crc ^= self::charCodeAt($str, $c) << 8;
            for ($i = 0; $i < 8; $i++) {
                if ($crc & 0x8000) {
                    $crc ^= 0x1021;
                }
                $crc &= 0xFFFF;
            }
        }

        return strtoupper(dechex($crc));
    }

    /**
     * @param $str
     * @param $i
     * @return int
     */
    private static function charCodeAt($str, $i): int
    {
        return ord(substr($str, $i, 1));
    }
}
