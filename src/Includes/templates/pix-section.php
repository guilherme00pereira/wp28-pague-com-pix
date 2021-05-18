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
        <div class="pix-card">
            <div class="pix-card-title">
                <h3><?php echo esc_html($account->bank_name) ?></h3>
                <div class="pix-card-title-type"><?php _e("Key: ", Config::getTextDomain()) . esc_html($account->key_type) ?></div>
                <div class="pix-card-title-key"><?php echo esc_html($account->key_code) ?></div>
            </div>
            <div class="pix-card-content">
                <img style="cursor:pointer; display: initial;" class="wcpix-img-copy-code"
                     src="<?php echo esc_attr($account->qrCode()) ?>" alt="QR Code"/>
            </div>
            <div class="pix-card-actions pix-tooltip">
                <button class="button-primary copyButton">
                    <span class="pix-tooltiptext"><?php esc_html_e("Copy Pix Key") ?></span>
                    <?php esc_html_e("Copy link", Config::getTextDomain()); ?>
                </button>
                <input class="linkToCopy" value="<?php echo esc_attr($account->pix_hash) ?>" style="position: absolute; z-index: -999; opacity: 0;" />
            </div>
        </div>
    <?php }
    if(!empty($this->pix_channels)){ ?>
        <div class="pix-channels">
            <div class="channels-instructions">
                <?php esc_html_e("Share the payment receipt through the channels indicated below:", Config::getTextDomain()) ?>
            </div>
            <div class="wrap">
                <?php foreach ($this->pix_channels as $pix_channel) { ?>
                    <div class="channel">
                        <a href="<?php echo esc_attr($pix_channel->link) ?>" target="_blank">
                            <img src="<?php echo esc_attr($pix_channel->icon)  ?>" alt="<?php echo esc_attr($pix_channel->name) ?>" width="50" height="50" />
                            <p><?php echo esc_html($pix_channel->value) ?></p>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>