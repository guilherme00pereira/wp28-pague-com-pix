<?php

namespace WP28\PAGUECOMPIX\Includes\Pix;

use WC_Coupon;
use WP28\PAGUECOMPIX\Includes\Core\Config;

/**
 * Class PixDiscountCoupon
 * @package WP28\PAGUECOMPIX\Includes\Pix
 */
class PixDiscountCoupon
{
    /**
     * @var string
     */
    private static string $coupon_meta_key = '_wp28_pix_payment_method';

    /**
     * Return coupon id from database.
     * @return int|null
     */
    private static function get_coupon_id(): ?int
    {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare(
            "SELECT post_id
			FROM {$wpdb->postmeta}
			WHERE meta_key = '%s'
			AND meta_value = '%s'"
            , self::$coupon_meta_key, Config::getGatewayId()));
    }

    /**
     * Return Pix discount coupon. If it doesn't exists, create a new one.
     * @param $amount
     * @return WC_Coupon
     */
    public static function get_coupon($amount): WC_Coupon
    {
        $id = self::get_coupon_id();
        if (is_null($id)) {
            return self::create_coupon($amount);
        }
        return new WC_Coupon($id);
    }

    /**
     * Create discount coupon fir Pix payment method.
     * @param $amount
     * @return WC_Coupon
     */
    public static function create_coupon($amount): WC_Coupon
    {
        $coupon = new WC_Coupon();

        $code = wc_format_coupon_code($code = 'pix_discount_' . wp_generate_password(20, false));
        $type = self::getCouponType($amount);
        $amount = (float)wc_format_decimal(trim($amount, '%'));
        $coupon->set_code($code);
        $coupon->set_description(esc_html(__('Get a discount paying with Pix', Config::getTextDomain())));
        $coupon->set_amount($amount);
        $coupon->set_discount_type($type);
        $coupon->add_meta_data(self::$coupon_meta_key, Config::getGatewayId(), true);
        $coupon->save();
        return $coupon;
    }

    /**
     * Update Pix coupon value.
     * @param string $amount
     */
    public static function update_coupon(string $amount)
    {
        $id = self::get_coupon_id();
        if(!is_null($id)){
            $type = self::getCouponType($amount);
            $amount = (float)wc_format_decimal(trim($amount, '%'));
            $coupon = new WC_Coupon($id);
            $coupon->set_amount($amount);
            $coupon->set_discount_type($type);
            $coupon->save();
        }
    }

    private static function getCouponType($amount): string
    {
        return false !== strstr($amount, '%') ? 'percent' : 'fixed_cart';
    }
}