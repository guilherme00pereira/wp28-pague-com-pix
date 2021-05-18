<?php

namespace WP28\PAGUECOMPIX\Includes\Pix;

use WC_Order;
use WC_Payment_Gateway;
use WP28\PAGUECOMPIX\Includes\Core\Loader;
use WP28\PAGUECOMPIX\Includes\Core\Config;
use WP28\PAGUECOMPIX\Includes\Fields\Checkbox;
use WP28\PAGUECOMPIX\Includes\Fields\Text;
use WP28\PAGUECOMPIX\Includes\Fields\Textarea;
use WP28\PAGUECOMPIX\Includes\Pix\Models\PixAccount;
use WP28\PAGUECOMPIX\Includes\Pix\Models\Channels\ChannelsFactory;


/**
 * Class PixGateway
 * @package WP28\PAGUECOMPIX\Includes\Pix
 */
class PixGateway extends WC_Payment_Gateway
{
    /**
     * @var Loader
     */
    private Loader $loader;
    /**
     * @var array
     */
    public array $pix_accounts;
    /**
     * @var array
     */
    public array $pix_channels;
    /**
     * @var false|mixed|void
     */
    private $accounts_list;
    /**
     * @var false|mixed|void
     */
    private $contact_channels;
    /**
     * @var string
     */
    public string $instructions;
    /**
     * @var string
     */
    public string $discount;

    /**
     * PixGateway constructor.
     */
    public function __construct()
    {
        $this->loader = Loader::get_instance();
        $this->id = Config::getGatewayId();
        $this->icon = apply_filters('woocommerce_gateway_icon', Config::getAssetsUrl() . 'logo_pix.png');
        $this->has_fields = false;
        $this->method_title = __('Pay with Pix', Config::getTextDomain());
        $this->method_description = __('Allow your customers to pay with Pix using a QR Code.', Config::getTextDomain());

        // define and load settings fields. reference: https://docs.woocommerce.com/document/payment-gateway-api/
        $this->init_form_fields();
        $this->init_settings();

        // initialize class properties
        $this->enabled = $this->get_option('enabled');
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->instructions = $this->get_option('instructions');
        $this->discount = $this->get_option('discount');

        if (get_option('wp28_pix_accounts')) {
            $this->accounts_list = get_option(
                'wp28_pix_accounts', array(
                    array(
                        'bank' => $this->get_option('bank'),
                        'key_type' => $this->get_option('key_type'),
                        'key_code' => $this->get_option('key_code'),
                        'holder_name' => $this->get_option('holder_name'),
                        'holder_city' => $this->get_option('holder_city')
                    )
                )
            );
        }
        if (get_option('wp28_pix_channels')) {
            $this->contact_channels = get_option(
                'wp28_pix_channels', array(
                    array(
                        'contact_name' => $this->get_option('contact_name'),
                        'contact_value' => $this->get_option('contact_value'),
                    )
                )
            );
        }

        $this->pix_accounts = [];
        $this->pix_channels = [];

        // register actions into loader instance and run it
        $this->register_actions_and_filters();
    }

    /**
     * Initializes the form fields displayed in the payments section on the WooCommerce settings page.
     */
    public function init_form_fields()
    {
        $this->form_fields = array();

        $this->form_fields['enabled'] = (array)new Checkbox(
            __('Enable/Disable', Config::getTextDomain()),
            __('Enable Pix', Config::getTextDomain()),
            'yes');

        $this->form_fields['title'] = (array)new Text(
            __('Title', Config::getTextDomain()),
            __('Represents the title visible to the customer',Config::getTextDomain()),
            'Pague com Pix');

        $this->form_fields['description'] = (array)new Textarea(
            __('Description', Config::getTextDomain()),
            __('Text that will appear to your customer when choosing this payment method', Config::getTextDomain()),
            'Ao finalizar a compra, iremos gerar o código Pix para pagamento na próxima tela e disponibilizar um número WhatsApp para você compartilhar o seu comprovante.');

        $this->form_fields['accounts_list'] = array('type' => 'accounts_list');

        $this->form_fields['instructions'] = (array)new Textarea(
            __('Instructions',Config::getTextDomain()),
            __('Instructions that will display on the Thank You page, above the Pix keys', Config::getTextDomain()));

        $this->form_fields['contact_channels'] = array('type' => 'contact_channels');

        $this->form_fields['discount'] = (array)new Text(
            __('Discount', Config::getTextDomain()),
            __('Discount amount to be applied. Enter a fixed amount (e.g. 19.90) or percentage (e.g. 5%)', Config::getTextDomain()),
            '0');
    }

    /**
     * Displays configuration page.
     */
    public function admin_options()
    {
        ob_start();
        include Config::getTemplateDir() . 'gateway-form.php';
        $html = ob_get_clean();
        echo $html;
    }

    /**
     * @return false|string
     */
    public function generate_accounts_list_html()
    {
        ob_start();
        include Config::getTemplateDir() . 'pix-accounts-table.php';
        return ob_get_clean();
    }

    /**
     * @return false|string
     */
    public function generate_contact_channels_html()
    {
        ob_start();
        include Config::getTemplateDir() . 'contact-channels-table.php';
        return ob_get_clean();
    }

    /**
     * Send email after order purchase.
     * @param $order
     * @param $sent_to_admin
     * @param false $plain_text
     */
    public function send_email_instructions($order, $sent_to_admin, $plain_text = false)
    {
        if (!$sent_to_admin && Config::getGatewayId() === $order->get_payment_method() && $order->has_status('on-hold')) {
            $this->configure_pix_section($order, true);
        }
    }

    /**
     * Check and return if this plugin can be available.
     * @return bool
     */
    public function is_available(): bool
    {
        return (
            'yes' === $this->enabled &&
            'BRL' === get_woocommerce_currency() &&
            get_option('wp28_pix_accounts')
        );
    }

    /**
     *
     */
    public function payment_fields()
    {
        $description = $this->get_description();
        if ($description) {
            echo wpautop(wptexturize($description));
        }
    }

    /**
     * @param int $order_id
     * @return array
     */
    public function process_payment($order_id): array
    {
        // process payment. reference:  https://docs.woocommerce.com/document/payment-gateway-api/
        $order = wc_get_order($order_id);
        $order->update_status('on-hold', __('Awaiting offline payment', Config::getTextDomain()));
        WC()->cart->empty_cart();
        return array(
            'result' => 'success',
            'redirect' => $this->get_return_url($order)
        );
    }

    /**
     * Save Pix metadata.
     */
    public function save_pix_metadata()
    {
        // save pix accounts
        if (isset($_POST['pix_key_code']) && count(array_filter($_POST['pix_key_code'])) > 0) {
            $accounts = array();
            $banks = wc_clean(wp_unslash($_POST['pix_bank']));
            $key_types = wc_clean(wp_unslash($_POST['pix_key_type']));
            $key_codes = wc_clean(wp_unslash($_POST['pix_key_code']));
            $holder_names = wc_clean(wp_unslash($_POST['pix_holder_name']));
            $holder_cities = wc_clean(wp_unslash($_POST['pix_holder_city']));

            foreach ($banks as $i => $name) {
                $accounts[] = array(
                    'bank' => $banks[$i],
                    'key_type' => $key_types[$i],
                    'key_code' => $key_codes[$i],
                    'holder_name' => $holder_names[$i],
                    'holder_city' => $holder_cities[$i],
                );
            }
            update_option('wp28_pix_accounts', $accounts);
        } else {
            delete_option('wp28_pix_accounts');
        };
        // save pix channels
        if (isset($_POST['pix_contact_value']) && count(array_filter($_POST['pix_contact_value'])) > 0) {
            $channels = array();
            $names = wc_clean(wp_unslash($_POST['pix_contact_name']));
            $values = wc_clean(wp_unslash($_POST['pix_contact_value']));
            foreach ($names as $i => $name) {
                $channels[] = array(
                    'contact_name' => $names[$i],
                    'contact_value' => $values[$i],
                );
            }
            update_option('wp28_pix_channels', $channels);
        } else {
            delete_option('wp28_pix_channels');
        }

        // save discount amount
        $discount_key = 'woocommerce_' . Config::getGatewayId() . '_discount';
        if (isset($_POST[$discount_key])) {
            $amount = (string)wc_clean(wp_unslash($_POST[$discount_key]));
            PixDiscountCoupon::update_coupon($amount);
        }
    }

    /**
     * Displays payment info at ThankYou page.
     * @param $order_id
     */
    public function thankyou_page($order_id)
    {
        $order = wc_get_order($order_id);
        $this->configure_pix_section($order);
    }

    /**
     * Displays payment info at account orders page.
     * @param $order
     */
    public function order_page($order)
    {
        if (Config::getGatewayId() === $order->payment_method) {
            $this->configure_pix_section($order);
        }
    }

    /**
     * @param $order
     * @param false $for_email
     */
    private function configure_pix_section($order, $for_email = false): void
    {
        $order_id = (string)$order->get_id();
        $this->load_pix_channels($order_id);
        $this->load_pix_accounts($order);
        ob_start();
        if($for_email){
            include Config::getTemplateDir() . 'mail-order-received.php';
        } else {
            include Config::getTemplateDir() . 'pix-section.php';
        }
        $html = ob_get_clean();
        echo $html;
    }

    /**
     *
     * @param WC_Order $order
     */
    private function load_pix_accounts(WC_Order $order): void
    {
        $order_id = (string)$order->get_id();
        foreach ($this->accounts_list as $account) {
            $pix_account = new PixAccount(
                $account['bank'],
                $account['key_type'],
                $account['key_code'],
                $order->total,
                $account['holder_name'],
                $account['holder_city'],
                $order_id
            );
            array_push($this->pix_accounts, $pix_account);
        }
    }

    /**
     * @param string $order_id
     */
    private function load_pix_channels(string $order_id): void
    {
        if(!empty($this->contact_channels)) {
            foreach ($this->contact_channels as $channel) {
                $pix_channel = ChannelsFactory::create(
                    $channel['contact_name'],
                    $channel['contact_value'],
                    $order_id
                );
                array_push($this->pix_channels, $pix_channel);
            }
        }
    }

    /**
     * Register actions, filters, and enqueue scripts and styles
     */
    private function register_actions_and_filters()
    {
        $this->loader->add_action('woocommerce_update_options_payment_gateways_' . $this->id, $this, 'process_admin_options');
        $this->loader->add_action('woocommerce_update_options_payment_gateways_' . $this->id, $this, 'save_pix_metadata');
        $this->loader->add_action('woocommerce_thankyou_' . $this->id, $this, 'thankyou_page');
        $this->loader->add_action('woocommerce_email_before_order_table', $this, 'send_email_instructions', 10, 3);
        if (is_account_page()) {
            $this->loader->add_action('woocommerce_order_details_after_order_table', $this, 'order_page');
        }
        try {
            $this->loader->run();
        } catch (\Exception $e) {
            $logger = wc_get_logger();
            $logger->info($e->getMessage(), array('source'=>Config::getName()));
        }
    }
}
