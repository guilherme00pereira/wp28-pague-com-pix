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
        add_action('admin_init', array($this, 'redirect_after_activation'));
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
    public function woocommerce_missing_notice(): void
    {
        ob_start();
        include Config::getTemplateDir() . '/notices/woocommerce-missing.php';
        $html = ob_get_clean();
        echo $html;
    }

    public static function activate()
    {
        if (
            (isset($_REQUEST['action']) && 'activate-selected' === $_REQUEST['action']) &&
            (isset($_POST['checked']) && count($_POST['checked']) > 1)) {
            return;
        }
        add_option(Config::getName() . '-redirect-activation', wp_get_current_user()->ID);
    }

    public static function redirect_after_activation()
    {
        if (intval(get_option(Config::getName() . '-redirect-activation', false)) === wp_get_current_user()->ID) {
            delete_option(Config::getName() . '-redirect-activation');
            if (wp_safe_redirect(admin_url('/options-general.php?page=plugin-admin-page'))) {
                exit;
            }
        }
    }
}