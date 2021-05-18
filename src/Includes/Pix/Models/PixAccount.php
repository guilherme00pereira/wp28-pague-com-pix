<?php

namespace WP28\PAGUECOMPIX\Includes\Pix\Models;

use chillerlan\QRCode\QRCode;
use WP28\PAGUECOMPIX\Includes\Pix\Constants;

class PixAccount
{
    public string $bank_name;
    public string $key_code;
    public string $key_type;
    public string $pix_hash;
    

    public function __construct(
        string $bank, 
        string $key_type,
        string $key_code, 
        string $order_total,
        string $holder_name, 
        string $holder_city, 
        string $order_id)
    {
        $merchant_account   = new PixDataObject();
        $merchant_account->add(Constants::GUI_ID, 'BR.GOV.BCB.PIX');
        $merchant_account->add(Constants::CHAVE_ID, $this->sanitize_key($key_code, $key_type));

        $additional_psst     = new PixDataObject();
        $additional_psst->add(Constants::PSST_GUI, 'BR.GOV.BCB.BRCODE');
        $additional_psst->add(Constants::PSST_VERSION, '1.0.0');

        $additional_data    = new PixDataObject();
        $additional_data->add(Constants::TXID, 'PEDIDO: ' . $order_id);
        $additional_data->add(Constants::PSST_ID, $additional_psst);

        $root = new PixDataObject();
        $root->add(Constants::PAYLOAD_FORMAT_INDICATOR_ID, '01');
        $root->add(Constants::MERCHANT_ACCOUNT_INFORMATION_ID, $merchant_account);
        $root->add(Constants::ADDITIONAL_DATA_FIELD_TEMPLATE_ID, $additional_data);
        $root->add(Constants::MERCHANT_CATEGORY_CODE_ID, '0000');
        $root->add(Constants::TRANSACTION_CURRENCY_ID, '986');
        $root->add(Constants::TRANSACTION_AMOUNT_ID, $order_total);
        $root->add(Constants::COUNTRY_CODE_ID, 'BR');
        $root->add(Constants::MERCHANT_NAME_ID, mb_substr($holder_name, 0, 25));
        $root->add(Constants::MERCHANT_CITY_ID, $holder_city);
        $root->add(Constants::CRC16_ID, 'FFFF');
        
        $this->bank_name    = $bank;
        $this->key_code     = $key_code;
        $this->key_type     = $key_type;
        $this->pix_hash     = $root;
    }

    public function sanitize_key($value, $type) : string
    {
        if(in_array($type, ['cpf', 'cnpj'])){
            return preg_replace("/\D/", "" ,$value);
        } elseif ('telefone' === $type){
            return '+55' . preg_replace("/\D/", "" ,$value);
        } elseif ('evp' === $type) {
            return strtoupper($value);
        } else {
            return $value;
        }
    }

    public function qrCode()
    {
        return (new QRCode())->render($this->pix_hash);
    }

    public function hash()
    {
        return $this->pix_hash;
    }
}