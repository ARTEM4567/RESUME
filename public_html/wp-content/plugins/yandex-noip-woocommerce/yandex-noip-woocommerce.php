<?php

/*
  Plugin Name: Yandex Money Woocommerce
  Plugin URI: https://coderun.ru/product/oplata-pri-pomoshhi-yandeks-deneg-v-woocommerce/
  Description: Приём платежей на кошёлек физического лица Яндекс Денег без специальных процедур оформления и заключения договоров.
  Version: 2.5.5
  Author: Djo
  Author URI: https://coderun.ru
 */

if (!defined('ABSPATH')) {
    exit;
}

//Базовая инициализация плагина
add_action('woocommerce_init', function () {

    /**
     * Константы плагина
     */
    define('WC_YANDEXNOIP_URL', plugin_dir_url(__FILE__));
    define('WC_YANDEXNOIP_DIR', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));

    /**
     * Check status
     */
    if (class_exists('WooCommerce_Payment_Status')) {
        add_filter('woocommerce_valid_order_statuses_for_payment', array('WC_YandexNoIP', 'valid_order_statuses_for_payment'), 52, 2);
    }

    include_once dirname(__FILE__) . '/inc/class-logger.php';
    include_once dirname(__FILE__) . '/inc/class-function.php';
    include_once dirname(__FILE__) . '/inc/class-wc.php';

    if (shortcode_exists('wc-yandexnoip-result')) {
        remove_shortcode('wc-yandexnoip-result');
    }

    if (!shortcode_exists('wc-yandexnoip-view-order')) { //Шорткод просмотра заказа

        add_shortcode('wc-yandexnoip-view-order', function(){

            if(empty($_GET['wc_yandexnoip_order_key'])) {
                return;
            }

            $orderKey=filter_var($_GET['wc_yandexnoip_order_key'],FILTER_SANITIZE_STRING);

            if(empty($orderKey)) {
                return;
            }

            $order = wc_get_orders(
                [
                    'limit' => 1,
                    '_order_key' => $orderKey,
                ]
            );

            $order=reset($order);

            $status   = new stdClass();

            $status->name = wc_get_order_status_name( $order->get_status() );

            wc_get_template(
                'myaccount/view-order.php',
                array(
                    'status'   => $status,
                    'order'    => wc_get_order( $order->get_id() ),
                    'order_id' => $order->get_id(),
                )
            );

        });
    }


    /**
     * Добавляет шлюз в WooCommerce
     * */
    add_filter('woocommerce_payment_gateways', function ($methods) {
        $methods[] = WC_YandexNoIP::class;
        return $methods;
    });
});


/**
 * Ссылка в активаторе плагина (правый блок)
 */
add_filter('plugin_row_meta', 'wc_yandexnoip_register_plugins_links_right', 10, 2);

function wc_yandexnoip_register_plugins_links_right($links, $file) {
    $base = plugin_basename(__FILE__);
    if ($file === $base) {
        $links[] = '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=wc_yandexnoip') . '">' . __('Settings') . '</a>';
    }
    return $links;
}

/**
 * Ссылка в активаторе плагина (левый блок)
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wc_yandexnoip_register_plugins_links_left');

function wc_yandexnoip_register_plugins_links_left($links) {
    return array_merge(array('settings' => '<a href="https://coderun.ru">' . __('Разработчик', 'wc-yandexnoip') . '</a>'), $links);
}

//add_filter('woocommerce_get_endpoint_url',function($url, $endpoint, $value, $permalink){
//
//},10,4);

//add_filter( 'user_has_cap', 'bbloomer_order_pay_without_login', 9999, 3 );
//
//function bbloomer_order_pay_without_login( $allcaps, $caps, $args ) {
//    if ( isset( $caps[0], $_GET['key'] ) ) {
//        if ( $caps[0] == 'pay_for_order' ) {
//            $order_id = isset( $args[2] ) ? $args[2] : null;
//            $order = wc_get_order( $order_id );
//            if ( $order ) {
//                $allcaps['pay_for_order'] = true;
//            }
//        }
//    }
//    echo '<pre>';print_r($_GET);echo '</pre>';die();
//    return $allcaps;
//}