<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_YandexNoIP extends WC_Payment_Gateway {
    
    /**
     * Уникальный ИД шлюза
     *
     * @var string
     */
    public $id = 'coderun_yandexnoip';
    
    /**
     * Текущая валюта
     *
     * @var string
     */
    public $currency;
    
    /**
     * Все поддерживаемые валюты
     *
     * @var array
     */
    public $currency_all = array('RUB');
    
    /**
     * Адрес приёма уведомлений
     * @var string
     */
    protected $notification_url='';
    
    /**
     * URL на страницу оплаты
     *
     * @var string
     */
    public $form_url = '';
    
    /**
     * Коэфициент пересчёта суммы заказа
     * @var int
     */
    public $exchange_rate = 1;
    
    /**
     * @var string
     */
    protected $page_success_custom_url = '';
    
    /**
     * ИД страницы для перехода после оплаты
     * @var int
     */
    protected $page_success = 0;
    
    public function __construct() {
        
        $this->method_title = 'Онлайн оплата Yoomoney физ. лица';
        
        $this->method_supports = 'Позволяет принимать платежи через шлюз Яндекс Деньги';
        
        $this->method_description = 'Приём платежей через Яндекс.Деньги, Visa, MasterCard, Mir';
        
        $this->supports = ['products'];
        
        /**
         * Текущие валюты
         */
        $this->currency = get_woocommerce_currency();
        
        $this->currency_all = array_keys(get_woocommerce_currencies());
        
        /**
         * Адрес для приёма ответов от банка
         */
        $this->notification_url = site_url() . '/?wc-api=' . $this->id;
        
        // Регистрация WooCommerce обработчика ответа
        add_action("woocommerce_api_{$this->id}", [$this, 'checkBankResponse']);
        
        /**
         * Загрузка настроек
         */
        $this->init_settings();
        $this->init_options();
        $this->init_form_fields();
        
        
        
        if (is_admin()) {
            /**
             * Сохраняет настройки
             */
            if (current_user_can('manage_options')) {
                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
                
            }
            
            wp_enqueue_script("admin-".$this->id, plugins_url('/js/admin.js', __DIR__));
            
            wp_enqueue_style(
                "admin-".$this->id,
                plugins_url('/css/admin.css', __DIR__)
            );
            
            
        }
        /**
         * Страница подтверждения заказа и перехода к оплате
         */
        add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));
    }
    
    /**
     * Init settings for gateways.
     */
    public function init_settings() {
        parent::init_settings();
        
        // Readonly option.
        $this->settings['notification_url'] = $this->notification_url;
    }
    
    /**
     * Load options.
     */
    protected function init_options() {
        
        /**
         * Включен ли шлюз?
         */
        if ($this->get_option('enabled','') !== 'yes') {
            $this->enabled = false;
        }
        
        $this->title = $this->get_option('title', $this->method_title);
        
        $this->description = $this->get_option('description', $this->method_description);
        
        if ($this->get_option('enable_icon') === 'yes') {
            
            $this->icon = plugins_url('assets/img/yandexnoip.png', __DIR__);
            
            //$this->icon = apply_filters('woocommerce_yandexnoip_icon', WC_YANDEXNOIP_URL . '/assets/img/yandexnoip.png');
        }
        
        $this->exchange_rate = $this->get_option('exchange_rate',1);
        $this->page_success = $this->get_option('page_success',0);
        $this->page_success_custom_url = $this->get_option('page_success_custom_url','');
    }
    
    /**
     * Initialise Gateway Settings Form Fields
     *
     * @access public
     * @return void
     */
    public function init_form_fields() {
        
        $this->form_fields = array
        (
            'enabled' => array
            (
                'title' => __('Включить/Отключить шлюз', 'wc-yandexnoip'),
                'type' => 'checkbox',
                'label' => __('Включить', 'wc-yandexnoip'),
                'default' => 'yes'
            ),
            'title' => array(
                'title' => __('Название', 'wc-yandexnoip'),
                'type' => 'text',
                'description' => __('Это название, которое пользователь видит в заказе.', 'wc-yandexnoip'),
                'default' => __('Онлайн оплата', 'wc-yandexnoip')
            ),
            'interface_description' => array(
                'title' => __('Что это за шлюз', 'wc-yandexnoip'),
                'type' => 'title',
                'description' => 'Данный шлюз позволяет оплачивать заказы в вашем интернет магазине через систему Яндекс Денег. Для того что бы всё'
                    . 'заработало, вам достаточно в кошельке яндекс денег указать url страницы на вашем сайте для приёма уведомлений и секретное слово.'
                    . 'Секретное слово сохраните в настройки шлюза.',
            ),
            'interface' => array(
                'title' => __('Основные настройки', 'wc-yandexnoip'),
                'type' => 'title',
                'description' => 'Для заполнения параметров перейдите в ваш <a href="https://yoomoney.ru/transfer/myservices/http-notification">Yoomoney Аккаунт</a> или
вы можете ознакомится с инструкцией по настройке плагина <a href="https://coderun.ru/blog/kak-prinimat-platezhi-na-yandeks-dengi-iz-magazina-woocommerce/">здесь</a>' ,
            ),
            'enable_icon' => array
            (
                'title' => __('Показать изображение шлюза', 'wc-yandexnoip'),
                'type' => 'checkbox',
                'label' => __('Показать', 'wc-yandexnoip'),
                'default' => 'yes'
            ),
            'yandex_multi_number' => array(
                'title' => __('Все ваши кошельки ЯндексДеньги', 'wc-yandexnoip'),
                'type' => 'textarea',
                'description' => __('Ваши номера кошельков и секретные слова к ним. На одной строке нужно заполнить "НОМЕР_КОШЕЛЬКА::СЕКРЕТНОЕ_СЛОВО".'
                    . 'Обратите внимание между номером и секретным словом нужно ставить :: По одной паре на каждой строке, количество строк не ограниченно.'
                    . 'При оплате заказов, кошельки для зачислени денег будут подставляться случайным образом.'),
                'default' => __('', 'wc-yandexnoip')
            ),
            /**
             * Адрес для уведомлений
             */
            'notification_url' => [
                'title' => 'Страница для связи с Яндекс',
                'type' => 'text',
                'disabled' => true,
                'description' => __('Данная страница должна быть указанна в аккаунте Яндекс.Денег как страница приёма запросов от платёжной системы.'
                    . 'Именно url до этой странице вы укажите в настройках Яндекс Денег', 'wc-yandexnoip'),
                'default' => $this->notification_url,
            ],
            'page_success' => array(
                'title' => __('Страница для перехода из платёжной системы. ', 'wc-yandexnoip'),
                'type' => 'select',
                'options' => $this->get_pages('Выберите страницу...'),
                'description' => __('Укажите страницу на которую перейдёт пользователь при переходе из платёжной системы обратно на сайте.
                Выберите "WooCommerce Order received" для указания стандартной страницы order-received.
                Так же вы можете добавить на произвольную страницу шорт-код [wc-yandexnoip-view-order] для отображения информации о заказе', 'wc-yandexnoip'),
                'default' => ''
            ),
            'page_success_custom_url' => [
                'title' => __('Произвольная страница для перехода', 'wc-yandexnoip'),
                'type' => 'text',
                'description' => 'Вместо настройки "Страница для перехода из платёжной системы" вы можете указать свою ссылку на страницу для перехода. Если поле заполнено, будет использована эта ссылка.
                Пример: https://coderun.ru/ или https://coderun.ru/my-account/orders/. Поддерживается автоподстановка ИД заказа.
                Пример: https://coderun.ru/my-account/orders/[order_id]/ - значение [order_id] будет заменено на номер заказа и
                результатом будет такой URL https://coderun.ru/my-account/orders/9088/, где 9088 реально созданный клиентский заказ',
                'default' => ''
            ],
            'pay_status_order' => array
            (
                'title' => __('Статус заказа после полной его оплаты', 'wc-yandexnoip'),
                'type' => 'select',
                'options' => $this->get_order_status('Выберите статус...'),
                'description' => __('После оплаты, этот статус будет присвоен заказу', 'wc-yandexnoip'),
                'default' => ''
            ),
            'pay_metod_card' => array
            (
                'title' => __('Способы оплаты - карта', 'wc-yandexnoip'),
                'type' => 'checkbox',
                'description' => __('Добавляет возможность оплаты через карту', 'wc-yandexnoip'),
                'default' => 'yes'
            ),
            'pay_metod_yandex' => array
            (
                'title' => __('Способы оплаты - Юмони Кошелёк', 'wc-yandexnoip'),
                'type' => 'checkbox',
                'description' => __('Добавляет возможность оплаты через Юмони Кошелёк', 'wc-yandexnoip'),
                'default' => 'yes'
            ),
            'pay_metod_sms' => array
            (
                'title' => __('Способы оплаты - СМС', 'wc-yandexnoip'),
                'type' => 'checkbox',
                'description' => __('Добавляет возможность оплаты через СМС', 'wc-yandexnoip'),
                'default' => 'no'
            ),
            'pay_metod_card_label' => array
            (
                'title' => __('Название для "Способ оплаты - карта"', 'wc-yandexnoip'),
                'type' => 'text',
                'description' => __('Укажите название, которое будет видеть покупатель на форме оплаты у вас на сайте для этого типа оплаты', 'wc-yandexnoip'),
                'default' => 'При помощи карты'
            ),
            'pay_metod_yandex_label' => array
            (
                'title' => __('Название для "Способ оплаты - Юмони Кошелёк"', 'wc-yandexnoip'),
                'type' => 'text',
                'description' => __('Укажите название, которое будет видеть покупатель на форме оплаты у вас на сайте для этого типа оплаты', 'wc-yandexnoip'),
                'default' => 'Юмони Кошелёк'
            ),
            'pay_metod_sms_label' => array
            (
                'title' => __('Название для "Способ оплаты - СМС"', 'wc-yandexnoip'),
                'type' => 'text',
                'description' => __('Укажите название, которое будет видеть покупатель на форме оплаты у вас на сайте для этого типа оплаты', 'wc-yandexnoip'),
                'default' => 'Оплата по СМС'
            ),
            'pay_metod_style_table' => array
            (
                'title' => __('Положение способов оплаты друг под другом', 'wc-yandexnoip'),
                'type' => 'checkbox',
                'description' => __('Подключается дополнительный css файл стилей к форме. Если не указанно, то используется значение по умолчанию - в строку слева на право', 'wc-yandexnoip'),
                'default' => 'no'
            ),
            'default_selected_pay_metod' => array
            (
                'title' => __('Метод оплаты по умолчанию', 'wc-yandexnoip'),
                'type' => 'select',
                'options' => array(
                    '-1' => 'Укажите способ оплаты',
                    'yandex' => 'Яндекс Кошелёк',
                    'card' => 'Карта',
                    'sms' => 'Смс',
                ),
                'description' => __('Если вы используете несколько методов оплаты, укажите какой будет выбран по умолчанию.', 'wc-yandexnoip'),
                'default' => 'card'
            ),
            'pay_end_step' => array
            (
                'title' => __('Сразу отправлять на страницу оплаты', 'wc-yandexnoip'),
                'type' => 'checkbox',
                'description' => __('Укажите, если требуется что бы покупатель сразу попадал на страницу оплаты. Важно: Должен быть установлен 1-способ оплаты и он же должен быть выбран по умолчанию!', 'wc-yandexnoip'),
                'default' => 'no'
            ),
            'description' => array
            (
                'title' => __('Описание шлюза', 'wc-yandexnoip'),
                'type' => 'textarea',
                'description' => __('Описание шлюза оплаты для посетителей интернет магазина', 'wc-yandexnoip'),
                'default' => __('Оплата онлайн', 'wc-yandexnoip')
            ),
            'exchange_rate' => [
                'title' => 'Коэффициент пересчёта суммы оплаты',
                'description' => 'Число. Итогавая сумма заказа будет умножена на это число. Данной опцией можно пользоваться и для тех ситуаций когда валюта вашего
                магазина отлична от рубля. По умолчанию всегда 1',
                'type' => 'text',
                'default' => $this->exchange_rate,
            ],
            'logger' => array
            (
                'title' => __('Вести лог?', 'wc-yandexnoip'),
                'type' => 'select',
                'description' => __('Лог запросов приходящих от Яндекс. Для просмотра логов перейдите в WooCommerce - Статус - Журнал и выберите лог с текущей датой', 'wc-yandexnoip'),
                'default' => '000',
                'options' => array
                (
                    'N' => __('Выключить', 'wc-yandexnoip'),
                    'Y' => __('Включить', 'wc-yandexnoip'),
                )
            ),
        );
        
    }
    
    /**
     * Страницы сайта для селектов
     * @param type $title
     * @param type $indent
     * @return string
     */
    public function get_pages($title = false, $indent = true) {
        $wp_pages = get_pages('sort_column=menu_order');
        $page_list = array();
        if ($title)
            $page_list[] = $title;
        foreach ($wp_pages as $page) {
            $prefix = '';
            if ($indent) {
                $has_parent = $page->post_parent;
                while ($has_parent) {
                    $prefix .= ' - ';
                    $next_page = get_page($has_parent);
                    $has_parent = $next_page->post_parent;
                }
            }
            $page_list[$page->ID] = $prefix . $page->post_title;
        }
        $page_list['endpoint_order-received'] = 'WooCommerce Order received';
        return $page_list;
    }
    
    /**
     * Статусы заказа
     * @param type $title
     * @param type $indent
     * @return type
     */
    public function get_order_status($title = false, $indent = true) {
        
        
        $arStatusWc = wc_get_order_statuses();
        
        $arStatus = array();
        
        foreach ($arStatusWc as $key => $status_title) {
            $key = str_replace('wc-', '', $key);
            $arStatus[$key] = $status_title;
        }
        
        $select = array();
        
        if ($title) {
            $select[0] = $title;
        }
        
        return ($select + $arStatus);
    }
    
    /**
     * @param $statuses
     * @param $order
     * @return mixed
     */
    public static function valid_order_statuses_for_payment($statuses, $order) {
        if ($order->payment_method !== 'yandexnoip') {
            return $statuses;
        }
        
        $option_value = get_option('woocommerce_payment_status_action_pay_button_controller', array());
        
        if (!is_array($option_value)) {
            $option_value = array('pending', 'failed');
        }
        
        if (is_array($option_value) && !in_array('pending', $option_value, false)) {
            $pending = array('pending');
            $option_value = array_merge($option_value, $pending);
        }
        
        return $option_value;
    }
    
    /**
     * Проверка ответа от банка
     */
    public function checkBankResponse() {
        
        $classCheck = YaNoipFunction::getInstance();
        
        $classCheck->setOptions([
            'logger' => $this->get_option('logger'),
            'pay_status_order' => $this->get_option('pay_status_order'),
            'exchange_rate' => $this->get_option('exchange_rate',1),
            'yandex_multi_number' => $this->get_option('yandex_multi_number',[]),
            'notification_url' => $this->get_option('notification_url',''),
            // 'page_success' => $this->get_return_url(null),
        
        ]);
        
        $result = $classCheck->getResponseYandex();
        
        if(!$result) {
            wp_send_json(['error' => 401], 401);
        }
        
        wp_send_json(['error' => 0], 200);
        
    }
    
    /**
     * Форма оплаты
     *
     * @param $order_id
     *
     * @return string Payment form
     * */
    public function generate_form($order_id) {
        
        $order = wc_get_order($order_id);
        
        /**
         * Выбраные методы оплаты
         */
        $pay_method_active = array_filter(
            array(
                ($this->get_option('pay_metod_card', '') == 'no' ? false : true),
                ($this->get_option('pay_metod_yandex', '') == 'no' ? false : true),
                ($this->get_option('pay_metod_sms', '') == 'no' ? false : true)
            ));
        
        
        /**
         * Form parameters
         */
        $args = array();
        
        
        $optionsExchangeRate = floatval($this->exchange_rate);
        
        if(empty($optionsExchangeRate)) {
            $optionsExchangeRate = 1;
        }
        $out_sum = ($order->get_total() * $optionsExchangeRate);
        
        $out_sum = number_format($out_sum, 2, '.', '');
        
        $out_sum = apply_filters('coderun_filter_yandex_pay_set_sum_order_generate_form', $out_sum, $order);
        
        $args['OutSum'] = $out_sum;
        
        /**
         * Order id
         */
        $args['InvId'] = $order_id;
        
        /**
         * Описание товара
         */
        $description = '';
        $items = $order->get_items();
        foreach ($items as $item) {
            $description .= $item['name'];
        }
        if (strlen($description) > 99) {
            $description = __('Номер заказа: ' . $order_id, 'wc-yandexnoip');
        }
        $args['InvDesc'] = $description;
        
        /**
         * Rewrite currency from order
         */
        $this->currency = $order->get_currency();
        
        /**
         * Установка валют to yandexnoip
         */
        if ($this->currency === 'USD') {
            $args['OutSumCurrency'] = 'USD';
        } elseif ($this->currency === 'EUR') {
            $args['OutSumCurrency'] = 'EUR';
        } elseif ($this->currency === 'RUB') {
            $args['OutSumCurrency'] = 'RUB';
        } else {
            $args['OutSumCurrency'] = 'RUB';
        }
        
        
        /**
         * Billing email
         */
        if (!empty($order->get_billing_email())) {
            $args['Email'] = $order->get_billing_email();
        }
        
        /**
         * Encoding
         */
        $args['Encoding'] = 'utf-8';
        
        
        /**
         * Execute filter woocommerce_yandexnoip_args
         */
        $args = apply_filters('woocommerce_yandexnoip_args', $args);
        
        /**
         * Form inputs generic
         */
        $args_array = array();
        foreach ($args as $key => $value) {
            $args_array[] = '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';
        }
        
        $objPay = YaNoipFunction::getInstance();
        
        $objPay->setOptions([
            'logger' => $this->get_option('logger'),
            'pay_status_order' => $this->get_option('pay_status_order'),
            'exchange_rate' => $this->get_option('exchange_rate',1),
            'yandex_multi_number' => $this->get_option('yandex_multi_number',[]),
            'notification_url' => $this->get_option('notification_url',''),
            'page_success' => $this->get_return_url($order),
        ]);
        
        if (count($pay_method_active) > 0) {
            $form_pay = $objPay->payForm(array(
                'order_id' => $order_id,
                'order_sum' => $out_sum,
            ), $this->settings);
        }
        
        
        if (count($pay_method_active) === 1 && $this->get_option('pay_end_step') === 'yes') {//Сразу переход на страницу яндекс
            $form_pay .= '<style>body{display: none;}</style>';
            $form_pay .= '<script>jQuery(document).ready(function () {
                jQuery(\'.woocommerce\').find(\'form\').unbind(\'submit\');
                jQuery(\'.noip-form-pay\').submit();
            })</script>';
        }
        
        return $form_pay . '<form action="' . esc_url($this->form_url) . '" method="POST" id="yandexnoip_payment_form" accept-charset="utf-8">' . "\n" .
            implode("\n", $args_array) .
            '<a class="button cancel" href="' . $order->get_cancel_order_url() . '">' . __('Вернуться в корзину', 'wc-yandexnoip') . '</a>' . "\n" .
            '</form>';
    }
    
    /**
     * Процесс оплаты и возврат результата
     *
     */
    public function process_payment($order_id) {
        $order = wc_get_order($order_id);
        
        $order->add_order_note(__('Начало процедуры оплаты', 'wc-yandexnoip'));
        if (function_exists('wp_generate_uuid4')) {
            $order->set_transaction_id(wp_generate_uuid4());
            $order->save();
        }
        
        return array
        (
            'result' => 'success',
            'redirect' => add_query_arg('order-pay', $order->get_id(), add_query_arg('key', $order->order_key, get_permalink(wc_get_page_id('pay'))))
        );
    }
    
    /**
     * Страница подтверждения и перехода к оплате
     * */
    public function receipt_page($order) {
        echo '<p>' . __('Спасибо за ваш заказ! Пройдите процедуру оплаты', 'wc-yandexnoip') . '</p>';
        echo $this->generate_form($order);
    }
    
    /**
     *
     *
     * @param WC_Order|null $order Order object.
     * @return string
     */
    public function get_return_url( $order = null ) {
        if ( $order ) {
            $return_url = $order->get_checkout_order_received_url();
        } else {
            $return_url = wc_get_endpoint_url( 'order-received', '', wc_get_checkout_url() );
        }
        if (!empty($this->page_success) && $order instanceof \WC_Order) {
            if ($this->page_success === 'endpoint_order-received') {
                $link = $order->get_checkout_order_received_url();
            } else {
                $link = \get_permalink($this->page_success);
            }
            if ($link) {
                $return_url = $link;
                if ( is_ssl() || 'yes' === get_option( 'woocommerce_force_ssl_checkout' )) {
                    $return_url = str_replace( 'http:', 'https:', $link);
                }
                $return_url = add_query_arg( 'key', $order->get_order_key(), $return_url);
                $return_url = add_query_arg( 'orderId', $order->get_id(), $return_url );
                $return_url = add_query_arg( 'wc_yandexnoip_order_key', $order->get_order_key(), $return_url );
            }
        }
        // Если установлено это значение, считаем что оно приоритетно
        if (!empty($this->page_success_custom_url) && filter_var($this->page_success_custom_url, FILTER_VALIDATE_URL) !== false) {
            $return_url = $this->page_success_custom_url;
            if ($order instanceof \WC_Order) {
                $return_url = str_replace('[order_id]', $order->get_id(), $return_url);
            }
        }
        
        return apply_filters( 'woocommerce_get_return_url', $return_url, $order );
    }
    
}
