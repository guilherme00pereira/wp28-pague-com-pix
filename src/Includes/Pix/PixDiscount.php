<?php

namespace WP28\PAGUECOMPIX\Includes\Pix;

use WC_Cart;
use WP28\PAGUECOMPIX\Includes\Core\Config;
use WP28\PAGUECOMPIX\Includes\Core\Loader;

/**
 * Class PixDiscount
 * @package WP28\PAGUECOMPIX\Includes\Pix
 */
class PixDiscount
{
    /**
     * @var Loader
     */
    private Loader $loader;

    /**
     * PixDiscount constructor.
     */
    public function __construct()
    {
        $this->loader = Loader::get_instance();

        $this->register_actions_and_filters();
    }

    /**
     * Changes payment title at checkout page.
     * @param $title
     * @param $id
     * @return string
     */
    public function change_payment_title($title, $id): string
    {
        if (!is_checkout() && !(defined('DOING_AJAX') && DOING_AJAX)) {
            return $title;
        }
        $amount = get_option('woocommerce_wp28_pix_gateway_settings')['discount'];
        if (Config::getGatewayId() === $id) {
            if (strstr($amount, '%')) {
                $value = $amount;
            } else {
                $value = wc_price($amount);
            }
            if (0 < $value) {
                $title .= ' <small>(' . sprintf(__('%s off', Config::getTextDomain()), $value) . ')</small>';
            }
        }
        return $title;
    }


    /**
     * Apply Pix discount.
     * @param WC_Cart $cart
     */
    public function apply_discount(WC_Cart $cart)
    {
        if (is_admin() && !defined('DOING_AJAX') || is_cart()) {
            return;
        }
        add_filter('woocommerce_coupons_enabled', '__return_true');

        $chosen_payment_method = WC()->session->chosen_payment_method;
        if (Config::getGatewayId() === $chosen_payment_method) {
            $amount = get_option('woocommerce_wp28_pix_gateway_settings')['discount'];
            if (apply_filters('wc_payment_discounts_apply_discount', 0 < $amount, $cart)) {
                $coupon = PixDiscountCoupon::get_coupon($amount);

                if (!$cart->has_discount($coupon->get_code())) {
                    $cart->add_discount($coupon->get_code());
                }
            } else {
                $this->remove_pix_coupon($cart);
            }
        } else {
            $this->remove_pix_coupon($cart);
        }
    }


    /**
     * Remove Pix coupon.
     * @param WC_Cart $cart
     */
    protected function remove_pix_coupon(WC_Cart $cart): void
    {
        foreach ($cart->get_applied_coupons() as $coupon) {
            if (strpos($coupon, 'pix_discount') !== false) {
                $cart->remove_coupon($coupon);
                $cart->calculate_totals();
            }
        }
    }

    /**
     * @param $order_id
     */
    public function update_order_data($order_id): void
    {
        $payment_method_title = get_post_meta($order_id, '_payment_method_title', true);
        $new_payment_method_title = preg_replace('/<small>.*<\/small>/', '', $payment_method_title);
        update_post_meta($order_id, '_payment_method_title', $new_payment_method_title);
    }

    /**
     * Register actions and filters.
     */
    private function register_actions_and_filters(): void
    {
        $this->loader->add_action('woocommerce_calculate_totals', $this, 'apply_discount');
        $this->loader->add_filter('woocommerce_gateway_title', $this, 'change_payment_title', 10, 2);
        $this->loader->add_action('woocommerce_checkout_order_processed', $this, 'update_order_data');
        try {
            $this->loader->run();
        } catch (\Exception $e) {
            $logger = wc_get_logger();
            $logger->info($e->getMessage(), array('source'=>Config::getName()));
        }
    }
}