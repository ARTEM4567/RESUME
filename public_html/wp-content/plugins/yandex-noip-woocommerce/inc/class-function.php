<?php

if (!defined('ABSPATH')) {
    exit;
}

class YaNoipFunction {
    
    private static $_instance = null;
    private $options = [];
    
    /**
     * Ключи массива присылаемый Яндексом
     * @var type
     * https://tech.yandex.ru/money/doc/dg/reference/notification-p2p-incoming-docpage/
     */
    protected $arYandex = array(
        'notification_type',
        'amount',
        'datetime',
        'codepro', //true - значит защищён кодом протекции. Если с карты всегда false
        'unaccepted', //true - значит перевод еще не зачислен
        'sender', //номер счета или пусто если карта
        'sha1_hash',
        'test_notification', //значит тестовый
        'operation_label',
        'operation_id',
        'currency', //643 - рубль
        'label', //Метка платёжа
        'withdraw_amount',
        'lastname', //Ниже только https
        'firstname',
        'fathersname',
        'email',
        'phone',
        'city',
        'street',
        'building',
        'suite',
        'flat',
        'zip',
    );
    
    /**
     * Хэш поля участвующие в проверке
     */
    protected $arHash = array(
        'notification_type',
        'operation_id',
        'amount',
        'currency',
        'datetime',
        'sender',
        'codepro',
        'notification_secret',
        'label',
    );
    
    /**
     *
     * @return YaNoipFunction
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    private function __construct() {
        
        // $this->options = get_option('woocommerce_coderun_yandexnoip_settings',array());
        
    }
    
    
    private function __clone() {
        
    }
    
    private function __wakeup() {
        
    }
    
    /**
     * Получает запрос от Яндекс
     * и обрабатывает его
     * Применяется как шорткод
     */
    public function getRequestYa() {
        $arRequest = $_REQUEST;
        $arResult = array();
        if (empty($arRequest) || !is_array($arRequest)) {
            return false;
        }
        foreach ($arRequest as $key => $strValue) {
            if (in_array($key, $this->arYandex)) {
                
            }
        }
    }
    
    /**
     * @TODO Нужно совместить оплату с событием яндекса, что бы и кошелёк поменялся и секретное слово нужное поймать
     * Проверка подлинности ответа от Яндекс
     * @param string $hahs_request значение sha1_hash из пришедшего массива
     * @param array $arRequest ответ от яндекса
     * @return bool
     */
    public function checkHash($hahs_request, $arRequest) {
        
        if (empty($arRequest) || !is_array($arRequest)) {
            return false;
        }
        $arOptions = $this->options;
        $strValidate = '';
        $strParam = 'notification_type&operation_id&amount&currency&datetime&sender&codepro&notification_secret&label';
        $strValidate = $strParam;
        foreach ($arRequest as $key => $strValue) {
            if (in_array($key, $this->arHash)) {
                if (empty($strValue)) {
                    $strValue = '';
                }
                $strValidate = str_replace($key, $strValue, $strValidate);
            }
        }
        
        try {
            $secret_list_key = $this->getWalletKeyList();
        } catch (Exception $ex) {
            if ($arOptions['logger'] == 'Y') {
                YaNoipLogger::getInstance()->logSet([__LINE__ . ' ' . $ex->getMessage()]);
            }
            return false;
        }
        
        foreach ($secret_list_key as $secret_key) {
            $strValidate = str_replace('notification_secret', $secret_key, $strValidate);
            $strValidate = rtrim($strValidate, 'false');
            $strValidate = rtrim($strValidate);
            $hashValid = sha1($strValidate, false);
            
            if ($arOptions['logger'] == 'Y') {
                YaNoipLogger::getInstance()->logSet([__LINE__ . ' ' . $strValidate]);
                YaNoipLogger::getInstance()->logSet([__LINE__ . ': ' . $hahs_request . ' - ' . $hashValid]);
            }
            if ($hahs_request === $hashValid) {
                return true;
            } else {
                $strValidate = str_replace($secret_key, 'notification_secret', $strValidate); // возврат строки в начальное состояние
            }
        }
        
        return false;
    }
    
    public function setOptions($options)
    {
        if (!empty($this->options)) {
            $this->options = array_merge($this->options, $options);
        } else {
            $this->options = $options;
        }
        
    }
    
    /**
     * Приёмщик запросов от Яндекс
     * Выполняет действия с заказом в случае успешной оплаты
     */
    public function getResponseYandex() {
        
        $arOptions = $this->options;
        
        $order_status_success = 'completed';
        
        if (!empty($arOptions['pay_status_order'])) {//0 - статус не выбран
            $order_status_success = $arOptions['pay_status_order'];
        }
        
        if (empty($_REQUEST) || empty($_REQUEST['sha1_hash'])) {
            return false;
        }
        
        //$arOptions = get_option('woocommerce_yandexnoip_settings');
        if ($arOptions['logger'] == 'Y') {
            YaNoipLogger::getInstance()->logSet($_REQUEST);
        }
        
        //Разбор запроса
        $arRequest = $_REQUEST;
        
        //$objFunction = YaNoipFunction::getInstance();

//        $check = $objFunction->checkHash($_REQUEST['sha1_hash'], $arRequest); //Если TRUE то это наш клиент
        $check = $this->checkHash($_REQUEST['sha1_hash'], $arRequest); //Если TRUE то это наш клиент
        
        if ((isset($_REQUEST['unaccepted']) && $_REQUEST['unaccepted'] === 'true') || (isset($_REQUEST['codepro']) && $_REQUEST['codepro'] === 'true')) { //С платёжом нужны доп действия
            return false;
        }
        if (empty($check)) {
            if ($arOptions['logger'] == 'Y') {
                YaNoipLogger::getInstance()->logSet(['Уникальный хэш не распознан!']);
            }
            return false;
        }
        if (empty($_REQUEST['label'])) {//В этом шлюзе это поле содержит номер заказа
            if ($arOptions['logger'] == 'Y') {
                YaNoipLogger::getInstance()->logSet(['Пустое поле label: Должно содержать номер заказа']);
            }
            return false;
        }
        //Операция с заказом
        $order_id = filter_var($_REQUEST['label'], FILTER_SANITIZE_NUMBER_INT);
        
        $sum = filter_var($_REQUEST['withdraw_amount'], FILTER_SANITIZE_STRING);
        YaNoipLogger::getInstance()->logSet(['Сумма пришедшая - ' . $sum]);
        $order = wc_get_order($order_id);
        
        if ($order === false) {
            
            if ($arOptions['logger'] == 'Y') {
                YaNoipLogger::getInstance()->logSet(['Платёжная система не распознала ИД заказа - ' . $order_id]);
            }
            return false;
        }
        
        $sum = apply_filters('coderun_filter_yandex_pay_set_status_order_pay', $sum, $order);
        YaNoipLogger::getInstance()->logSet(['Сумма после coderun_filter_yandex_pay_set_status_order_pay - ' . $sum]);
        
        $order->add_order_note('Произведена оплата заказа ' . $order_id . ' на сумму ' . $sum);
        
        if ($arOptions['logger'] == 'Y') {
            YaNoipLogger::getInstance()->logSet(['Удачная оплата - ' . $order_id]);
        }
        
        $order_data = $order->get_data();
        if (intval($order_data['total']) == intval($sum)
            || intval($sum) > intval($order_data['total'])) {
            $order->add_order_note('Заказ оплачен полностью!');
            $order->update_status($order_status_success); //Даём пользователю скачать товар
            YaNoipLogger::getInstance()->logSet(['Заказ полностью оплачен']);
        } else {
            $order->add_order_note('Заказ оплачен не полностью!');
            $order->update_status('On-Hold');
            YaNoipLogger::getInstance()->logSet(['Заказ оплачен не полностью']);
        }
        //$order->payment_complete();
        $order->save();
        return true;
    }
    
    /**
     * Вернёт форму для оплаты товара (default вариант)
     * @return boolean|string
     */
    public function payForm($args, $pay_settings = array()) {
        $order_id = $args['order_id'];
        $arOptions = $this->options;
        if (isset($arOptions['enabled']) && $arOptions['enabled'] == 'no') {
            return false;
        }
        try {
            $order = new WC_Order($order_id);
            
            if(empty($order)) {
                throw new Exception('Путой заказ');
            }
            $wallets = $this->getWallet();
        } catch (Exception $ex) {
            if ($arOptions['logger'] == 'Y') {
                YaNoipLogger::getInstance()->logSet([__LINE__ . ': ' . $ex->getMessage()]);
            }
            
            return 'Что то пошло не так! Не возможно оплатить заказ!';
        }
        
        // $yandex_number = $arOptions['yandex_number'];
        $yandex_number = $wallets['wallet'];
        $order_sum = $args['order_sum'];
        
        if (empty($order_id) || empty($yandex_number) || empty($order_sum)) {
            
            if ($arOptions['logger'] == 'Y') {
                YaNoipLogger::getInstance()->logSet([__LINE__ . ': ' . __METHOD__]);
            }
            return false;
        }
        $target = 'Оплата заказа № ' . $order_id;
        $arParams = array(
            'target' => $target,
            'order_sum' => $order_sum,
            'order_id' => $order_id,
            'success_url' => $arOptions['page_success'],
            'yandex_number' => $yandex_number,
            'im' => parse_url(get_bloginfo('url'))['host'],
            'woo_options' => $pay_settings,
        );
        $strForm = $this->getCustomForm($arParams);
        return $strForm;
    }
    
    
    /**
     * Вернёт параметры формы для CURL запроса
     * @param type $form
     * @return type
     */
    public function get_parse_form_params($form) {
        $html = new \DOMDocument();
        
        $html->loadHTML('<?xml encoding="' . get_bloginfo('charset') . '" ?>' . $form);
        
        $inputs = $html->getElementsByTagName('input');
        
        $data_request = array();
        
        $form_params = $html->getElementsByTagName('form');
        
        foreach ($form_params as $form) {
            $request_url = $form->getAttribute('action');
        }
        
        foreach ($inputs as $input) {
            $name = trim($input->getAttribute('name'));
            if (empty($name)) {
                continue;
            }
            $data_request[$name] = $input->getAttribute('value');
        }
        
        return array(
            'url' => $request_url,
            'form' => $data_request
        );
    }
    
    /**
     * Форма https://yandex.ru/dev/money/doc/payment-buttons/reference/forms-docpage/
     * Кастомная форма оплаты
     * @param type $arParams
     * @return string
     */
    protected function getCustomForm($arParams) {

        $plugin_params = $arParams['woo_options'];
        $css = file_get_contents(WC_YANDEXNOIP_DIR . '/css/front.css');
        if (isset($plugin_params['pay_metod_style_table']) && $plugin_params['pay_metod_style_table'] === 'yes') {
            $css .= file_get_contents(WC_YANDEXNOIP_DIR . '/css/front_customize.css');
        }
        
        $form = [];
        $form[] = '<ul class="order_details">';
        $form[] = '<li class="order">';
        $form[] = '<form class="noip-form-pay" method="POST" action="https://yoomoney.ru/quickpay/confirm.xml">';
        $form[] = '<input type="hidden" name="receiver" value="' . $arParams['yandex_number'] . '">';
        $form[] = '<input type="hidden" name="formcomment" value="Интернет магазин ' . $arParams['im'] . '">';
        $form[] = '<input type="hidden" name="short-dest" value="' . $arParams['target'] . '">';
        $form[] = '<input type="hidden" name="label" value="' . $arParams['order_id'] . '">';
        $form[] = '<input type="hidden" name="quickpay-form" value="shop">';
        $form[] = '<input type="hidden" name="targets" value="Заказ № ' . $arParams['order_id'] . '">';
        $form[] = '<input type="hidden" name="sum" value="' . $arParams['order_sum'] . '" data-type="number">';
        $form[] = '<input type="hidden" name="comment" value="">';
        $form[] = '<input type="hidden" name="successURL" value="' . $arParams['success_url'] . '">';
        $form[] = '<input type="hidden" name="need-fio" value="false">';
        $form[] = '<input type="hidden" name="need-email" value="false">';
        $form[] = '<input type="hidden" name="need-phone" value="false">';
        $form[] = '<input type="hidden" name="need-address" value="false">';
        $form['pay_metod_card'] = '';
        $form['pay_metod_yandex'] = '';
        $form['pay_metod_sms'] = '';
        $form[] = '<input class="checkout-button button" type="submit" value="Оплатить">';
        $form[] = '</form>';
        $form[] = '</li>';
        $form[] = '</ul>';
        $form[] = ' <style>' . $css . '</style>';
        
        if (isset($plugin_params['pay_metod_yandex']) && $plugin_params['pay_metod_yandex'] === 'yes') {
            $form['pay_metod_yandex'] = sprintf('<label><input type="radio" name="paymentType" %s value="PC">%s</label>',
                checked($plugin_params['default_selected_pay_metod'], 'yandex', false), $plugin_params['pay_metod_yandex_label']);
        }
        if (isset($plugin_params['pay_metod_card']) && $plugin_params['pay_metod_card'] === 'yes') {
            $form['pay_metod_card'] = sprintf('<label><input type="radio" name="paymentType" %s value="AC">%s</label>',
                checked($plugin_params['default_selected_pay_metod'], 'card', false), $plugin_params['pay_metod_card_label']);
        }
        if (isset($plugin_params['pay_metod_sms']) && $plugin_params['pay_metod_sms'] === 'yes') {
            $form['pay_metod_sms'] = sprintf('<label><input type="radio" name="paymentType" %s value="MC">%s</label>',
                checked($plugin_params['default_selected_pay_metod'], 'sms', false), $plugin_params['pay_metod_sms_label']);
        }
    
        $form = array_filter($form);
        $strForm = implode('', $form);
        
        
        return $strForm;
    }
    
    /**
     * Вернёт кошелёк необходимый в данный момент времени
     */
    protected function getWallet() {
        $arOptions = $this->options;
        
        //Номер кошелька и слова из массива
        $old_number_walet = intval(get_option('yandex-noip-woocommerce_old_number_walet', -1));
        
        $wallets = $this->wallet_split($arOptions['yandex_multi_number']); //все кошельки пользователя + секретные слова
        
        if (!is_array($wallets) || count($wallets) < 1) {
            throw new \Exception('Список кошельков пуст', 200);
        }
        
        if ($old_number_walet === -1) {//Нет значения
            $old_number_walet = 0;
        }
        
        for ($i = $old_number_walet; $i <= 900; $i++) {
            if (isset($wallets[$i])) {
                $old_number_walet = $i;
                break;
            } else {
                $old_number_walet = 0;
            }
        }
        $secret_key = null;
        $wallet = null;
        $params_wallets = explode('::', $wallets[$old_number_walet]);
        $wallet = $params_wallets[0];
        $secret_key = $params_wallets[1];
        if (empty($secret_key) || empty($wallet)) {
            throw new \Exception('Не распознаные кошелёк и секртеное слово', 200);
        }
        
        $new_number_walet = intval($old_number_walet) + 1;
        update_option('yandex-noip-woocommerce_old_number_walet', $new_number_walet); //Следующий номер который будет использован
        if ($arOptions['logger'] == 'Y') {
            YaNoipLogger::getInstance()->logSet(['Для оплаты выдал кошелёк' => $wallet, 'Секретное слово' => $secret_key]);
        }
        
        return [
            'wallet' => $wallet,
            'secret_key' => $secret_key,
        ];
    }
    
    /**
     * Массив секретных слов
     */
    protected function getWalletKeyList() {
        $arOptions = $this->options;
        
        $result = array();
        
        $wallets = $this->wallet_split($arOptions['yandex_multi_number']); //все кошельки пользователя + секретные слова
        if ($arOptions['logger'] == 'Y') {
            YaNoipLogger::getInstance()->logSet(['Полный список кошельков для проверки' => $wallets]);
        }
        
        if (!is_array($wallets) || count($wallets) < 1) {
            if ($arOptions['logger'] == 'Y') {
                YaNoipLogger::getInstance()->logSet(['Кошельки' => $arOptions['yandex_multi_number']]);
            }
            throw new \Exception('Список кошельков пуст', 200);
        }
        
        foreach ($wallets as $value) {
            $arParams = explode('::', $value);
            $result[] = $arParams[1]; //секретное слово
        }
        if ($arOptions['logger'] == 'Y') {
            YaNoipLogger::getInstance()->logSet(['Выдал секретные фразы для проверки' => $result]);
        }
        return $result;
    }
    
    protected function wallet_split($wallets) {
        
        $arValues = preg_split('/[\r\n]+/', $wallets, -1, PREG_SPLIT_NO_EMPTY);
        
        if (is_array($arValues)) {
            foreach ($arValues as $key => &$walet_key) {
                $walet_key = trim($walet_key);
                if (empty($walet_key)) {
                    unset($arValues[$key]);
                }
            }
        }
        return array_unique($arValues);
    }
    
}
