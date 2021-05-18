<?php

namespace WP28\PAGUECOMPIX\Includes\Core;

class Config
{
    private static string $version;
    private static string $url;
    private static string $dir;
    private static string $template_dir;
    private static string $assets_url;
    private static string $slug;
    private static string $plugin_base;
    private static string $text_domain;
    private static string $name;
    private static string $gateway_id;
    private static string $payment_method;

    public static function init($dir, $url, $plugin_base)
    {
        self::$version          = '1.0.0';
        self::$url              = $url;
        self::$dir              = $dir;
        self::$template_dir     = $dir . 'src/Includes/templates/';
        self::$assets_url       = $url . 'src/Assets/';
        self::$plugin_base      = $plugin_base;
        self::$slug             = trim(dirname($plugin_base), '/');
        self::$text_domain      = 'wp28-pague-com-pix';
        self::$name             = 'WP28 Pague com Pix';
        self::$gateway_id       = 'wp28_pix_gateway';
        self::$payment_method   = 'WP28\PAGUECOMPIX\Includes\Pix\PixGateway';
    }

    /**
     * @return string
     */
    public static function getPaymentMethod(): string
    {
        return self::$payment_method;
    }

    /**
     * @return string
     */
    public static function getVersion(): string
    {
        return self::$version;
    }

    /**
     * @return string
     */
    public static function getUrl(): string
    {
        return self::$url;
    }

    /**
     * @return string
     */
    public static function getDir(): string
    {
        return self::$dir;
    }

    /**
     * @return string
     */
    public static function getAssetsUrl(): string
    {
        return self::$assets_url;
    }

    /**
     * @return string
     */
    public static function getSlug(): string
    {
        return self::$slug;
    }

    /**
     * @return string
     */
    public static function getPluginBase(): string
    {
        return self::$plugin_base;
    }

    /**
     * @return string
     */
    public static function getTextDomain(): string
    {
        return self::$text_domain;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return self::$name;
    }

    /**
     * @return string
     */
    public static function getGatewayId(): string
    {
        return self::$gateway_id;
    }

    /**
     * @return string
     */
    public static function getTemplateDir(): string
    {
        return self::$template_dir;
    }
}
