<?php


namespace WP28\PAGUECOMPIX\Includes\Core;

/**
 * Class Controller
 * @package WP28\PAGUECOMPIX\Includes\Core
 */
class Controller
{
    /**
     * @var Loader
     */
    private Loader $loader;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->loader = Loader::get_instance();
        $this->load_actions_and_filters();
        $this->register_script_and_styles();
    }

    /**
     * Loads text domain.
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(Config::getTextDomain(), false, dirname(Config::getPluginBase()) . '/src/Languages/');
    }

    /**
     * Add this payment gateway to WooCommerce.
     * @param $methods
     * @return array
     */
    public function add_gateway($methods): array
    {
        $methods[] = Config::getPaymentMethod();
        return $methods;
    }

    /**
     * Adds a link to configuration page on the plugins page.
     * @param $links
     * @return array
     */
    public function plugin_action_links($links): array
    {
        $plugin_links = array();
        $plugin_links[] = '<a href="' . esc_url(admin_url('admin.php?page=wc-settings&tab=checkout&section=wp28_pix_gateway')) . '">Configuração</a>';
        return array_merge($plugin_links, $links);
    }

    /**
     * Hide this payment method if WordPress is configured for a country other than Brazil.
     * @param $available_gateways
     * @return mixed
     */
    public function hides_when_is_outside_brazil($available_gateways)
    {
        if (isset($_REQUEST['country']) && 'BR' !== $_REQUEST['country']) {
            unset($available_gateways[Config::getGatewayId()]);
        }
        return $available_gateways;
    }

    /**
     * Enqueue styles and scripts.
     */
    public function register_script_and_styles(): void
    {
        if (isset($_GET['page']) && 'wc-settings' === $_GET['page'] && isset($_GET['section']) && Config::getGatewayId() === $_GET['section']) {
            wp_enqueue_script('wp28pix_settingspage_scripts', Config::getAssetsUrl() . 'js/settings-page.js', [], false, true);
            wp_enqueue_script('wp28pix_maskedinput_scripts', Config::getAssetsUrl() . 'js/jquery.mask.js', [], false, true);
        }
        //if (is_checkout()) {
        wp_enqueue_script('wp28pix_checkout_scripts', Config::getAssetsUrl() . 'js/checkout-reload.js', [], false, true);
        //}

        //if (is_wc_endpoint_url('order-received') || is_wc_endpoint_url( 'view-order' )) {
        wp_enqueue_script('wp28pix_order_scripts', Config::getAssetsUrl() . 'js/pix-order-page.js', [], false, true);
        wp_enqueue_style('wp28pix_thankyoupage_style', Config::getAssetsUrl() . 'css/pix-table.css');
        //}
    }

    /**
     * Load actions and filters.
     */
    public function load_actions_and_filters(): void
    {
        $this->loader->add_action('init', $this, 'load_plugin_textdomain');
        $this->loader->add_filter('woocommerce_payment_gateways', $this, 'add_gateway');
        $this->loader->add_filter('plugin_action_links_' . Config::getPluginBase(), $this, 'plugin_action_links');
        $this->loader->add_filter('woocommerce_available_payment_gateways', $this, 'hides_when_is_outside_brazil');
        try {
            $this->loader->run();
        } catch (\Exception $e) {
            $logger = wc_get_logger();
            $logger->info($e->getMessage(), array('source'=>Config::getName()));
        }
    }
}