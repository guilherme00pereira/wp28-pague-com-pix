<?php

if (!defined('ABSPATH')) {
    exit;
}

use WP28\PAGUECOMPIX\Includes\Core\Config;

?>



<div id="pix-section">
    <div class="pix-instructions">
        <?php echo esc_html($this->instructions) ?>
    </div>
    <?php foreach ($this->pix_accounts as $account) { ?>
        <div style="margin: 50px auto;padding: 10px;border: 1px solid #CCCCCC;width: 300px;box-shadow: 5px 5px 5px #CCCCCC;">
            <div>
                <h3 style="text-align: center;font-size: 1.75em;text-transform: uppercase;"><?php echo esc_html($account->bank_name) ?></h3>
                <div style="word-wrap: break-word;font-size: 1.2em;text-align: center;color: #AAAAAA;"><?php _e("Key: ", Config::getTextDomain()) . esc_html($account->key_type) ?></div>
                <div style="word-wrap: break-word;font-size: 1.2em;text-align: center;"><?php echo esc_html($account->key_code) ?></div>
            </div>
            <div class="pix-card-content">
                <img style="cursor:pointer; display: initial;" class="wcpix-img-copy-code"
                     src="<?php echo $account->qrCode(); ?>" alt="QR Code"/>
            </div>
        </div>
    <?php } ?>
    <div style="display: flex;justify-content: center;flex-flow: row wrap;max-width: 100%;">
        <div style="display: flex;font-size: 1.2em;flex-basis: 100%;justify-content: center;margin: 2rem 0;text-align: center;">
            <?php esc_html_e("Share the payment receipt through the channels indicated below:", Config::getTextDomain()) ?>
        </div>
        <div style="display: flex;justify-content: space-around;flex-flow: column;">
            <?php foreach ($this->pix_channels as $pix_channel) { ?>
                <div style="text-align: center; margin: 1.5em;">
                    <a href="<?php echo esc_attr($pix_channel->link) ?>" target="_blank">
                        <img src="<?php echo esc_attr($pix_channel->icon)  ?>" alt="<?php echo esc_attr($pix_channel->name) ?>" width="50" height="50" />
                        <p><?php echo esc_html($pix_channel->value) ?></p>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

