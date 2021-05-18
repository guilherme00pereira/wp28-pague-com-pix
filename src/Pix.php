<?php

namespace WP28\PAGUECOMPIX;

use WP28\PAGUECOMPIX\Includes\Core\Controller;
use WP28\PAGUECOMPIX\Includes\Core\Loader;
use WP28\PAGUECOMPIX\Includes\Core\Config;
use WP28\PAGUECOMPIX\Includes\Pix\PixDiscount;

/**
 * Class Pix
 * @package WP28\PAGUECOMPIX
 */
class Pix
{
    /**
     * Pix constructor.
     * @param $dir
     * @param $url
     * @param $plugin_base
     */
    public function __construct($dir, $url, $plugin_base)
    {
        Config::init($dir, $url, $plugin_base);
    }


    /**
     * Load package classes.
     */
    public function run()
    {
        if ($this->woocommerce_is_installed_and_activated()) {
            new Controller();
            new PixDiscount();
        }
    }


    /**
     * Check if WooCommerce is installed and activated.
     * @return bool
     */
    private function woocommerce_is_installed_and_activated(): bool
    {
        if (class_exists('WC_Payment_Gateway')) {
            return true;
        } else {
            add_action('admin_notices', array(__CLASS__, 'woocommerce_missing_notice'));
            return false;
        }
    }

    /**
     * Echoes missing WooCommerce notice. Template displays options to activate or even install WooCommerce.
     */
    public function woocommerce_missing_notice() : void
    {
        ob_start();
        include Config::getTemplateDir() . '/notices/woocommerce-missing.php';
        $html = ob_get_clean();
        echo $html;
    }
}