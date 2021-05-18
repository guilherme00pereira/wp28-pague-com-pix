<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
delete_option('woocommerce_wp28_pix_gateway_settings');
delete_option('wp28_pix_accounts');
delete_option('wp28_pix_channels');