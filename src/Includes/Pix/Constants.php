<?php

namespace WP28\PAGUECOMPIX\Includes\Pix;

/**
 * Class Constants
 * @package WP28\PAGUECOMPIX\Includes\Pix
 */
class Constants
{

    // Root ID's
    const PAYLOAD_FORMAT_INDICATOR_ID           = '00';
    const POINT_OF_INITIATION_ID                = '01';
    const MERCHANT_ACCOUNT_INFORMATION_ID       = '26';
    const MERCHANT_CATEGORY_CODE_ID             = '52';
    const TRANSACTION_CURRENCY_ID               = '53';
    const TRANSACTION_AMOUNT_ID                 = '54';
    const COUNTRY_CODE_ID                       = '58';
    const MERCHANT_NAME_ID                      = '59';
    const MERCHANT_CITY_ID                      = '60';
    const ADDITIONAL_DATA_FIELD_TEMPLATE_ID     = '62';
    const CRC16_ID                              = '63';

    // Merchant Account ID's
    const GUI_ID        = '00';
    const CHAVE_ID      = '01';

    // Additional Fields ID's
    const TXID          = '05';
    const PSST_ID       = '50';
    const PSST_GUI      = '00';
    const PSST_VERSION  = '01';
}