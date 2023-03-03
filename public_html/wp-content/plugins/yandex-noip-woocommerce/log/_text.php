<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';


$objYa = YaNoipFunction::getInstance();

$arTest = array(
    'notification_type' => 'card-incoming',
    'zip' => '',
    'bill_id' => '',
    'amount' => '1274.00',
    'firstname' => '',
    'codepro' => 'false',
    'withdraw_amount' => '1300.00',
    'city' => '',
    'unaccepted' => 'false',
    'label' => '7461',
    'building' => '',
    'lastname' => '',
    'datetime' => '2020-01-24T11:59:05Z',
    'suite' => '',
    'sender' => '',
    'phone' => '',
    'sha1_hash' => 'e8e80f91feb74489c667a293ecd298374c984053',
    'street' => '',
    'flat' => '',
    'fathersname' => '',
    'operation_label' => '25bcefa8-0011-5000-a000-1d2858a075b3',
    'operation_id' => '633182345588002012',
    'currency' => '643',
    'email' => '',
    'woocommerce-login-nonce' => '',
    '_wpnonce' => '',
    'woocommerce-reset-password-nonce' => '',
    'woocommerce-edit-address-nonce' => '',
    'save-account-details-nonce' => '',
);


$str='';

foreach ($arTest as $key=>$value) {
    $str .="{$key}:{$value}".'<br>';
}

echo trim($str,':<br>');


var_dump($objYa->checkHash('e8e80f91feb74489c667a293ecd298374c984053', $arTest));
