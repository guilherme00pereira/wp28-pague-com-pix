<?php
/*
Plugin Name: WP28 Pague com Pix
Plugin URI: https://www.wp28.dev/pague-com-pix
Description: Permite o pagamento dos pedidos com Pix
Version: 1.0.0
Requires at least: 5.5
Requires PHP: 7.3
Author: WP28
Author URI: https://www.wp28.dev/
Text Domain: wp28-paguecompix
Domain Path: /languages
WC tested up to: 5.1.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit;

require "vendor/autoload.php";

function run_WP28_PAGUECOMPIX()
{
	$plugin = new WP28\PAGUECOMPIX\Pix (
		plugin_dir_path(__FILE__),
		plugin_dir_url(__FILE__),
		plugin_basename(__FILE__),
	);
	$plugin->run();
}

add_action('plugins_loaded', 'run_WP28_PAGUECOMPIX');