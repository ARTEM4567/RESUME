<?php

if (!defined('ABSPATH')) {
    exit;
}

wc_enqueue_js("
	jQuery( function( $ ) {
		$('#paw_all_payment_methods').on('click', function(){
		    $('#paw_wrapper_card').hide();
		    $('#paw_wrapper_iframe').show();
		});
		$('#paw_card_payment_methods').on('click', function(){
		    $('#paw_wrapper_iframe').hide();
		    $('#paw_wrapper_card').show();
		});

        let url = new URL(window.location.href);
        let userWithoutApple = url.searchParams.get('noapplepay');
        if(!userWithoutApple){
            $('.annotation').hide();
        }

        if(!$('#apple_pay_notice').length){
            let msg = $('<div>', {
                        id: 'apple_pay_notice',
                        class: 'update-nag notice',
                        append: $('<p>', {
                                    html: '<strong>".__('Пожалуйста, обратитесь в коммерческий отдел PayAnyWay чтобы выполнить дополнительные настройки для данного способа оплаты.', 'woocommerce_gateway_payanyway')."</strong>',
                                })
                                .add($('<span>'))
                    });
            $('#woocommerce_payanyway_apple_pay').next().after(msg);
            if(!isHttps()){
                let msg = $('<div>', {
                            class: 'error notice',
                            append: $('<p>', {
                                        html: '<strong>".__('Чтобы работала оплата с помощью Apple Pay, ваш сайт должен использовать HTTPS и действующий TLS/SSL-сертификат.', 'woocommerce_gateway_payanyway')."</strong>',
                                    })
                                    .add($('<span>'))
                        });
                $('#woocommerce_payanyway_apple_pay').next().after(msg);
                $('#woocommerce_payanyway_appleenabled')
                    .prop('checked', false)
                    .attr('disabled', true)
            }
        }
	});
    function isHttps(){
        return (document.location.protocol == 'https:');
    }
");
/**
 * Settings for PayAnyWay Gateway.
 */
$settings = array(
    'main' => array(
        'title' => __('PayAnyWay', 'woocommerce_gateway_payanyway'),
        'type' => 'title',
        'description' => __('Настройка приёма электронных платежей через PayAnyWay', 'woocommerce_gateway_payanyway'),
    ),
    'enabled' => array(
        'title' => __('Включить/Выключить', 'woocommerce_gateway_payanyway'),
        'type' => 'checkbox',
        'label' => __('Включен', 'woocommerce_gateway_payanyway'),
        'default' => 'yes',
    ),
    'title' => array(
        'title' => __('Название', 'woocommerce_gateway_payanyway'),
        'type' => 'text',
        'description' => __('Это название, которое пользователь видит во время проверки.', 'woocommerce_gateway_payanyway'),
        'default' => __('PayAnyWay', 'woocommerce_gateway_payanyway'),
    ),
    'MNT_ID' => array(
        'title' => __('Номер счёта', 'woocommerce_gateway_payanyway'),
        'type' => 'text',
        'description' => __('Пожалуйста введите Номер счёта.', 'woocommerce_gateway_payanyway'),
        'default' => '99999999',
    ),
    'MNT_DATAINTEGRITY_CODE' => array(
        'title' => __('Код проверки целостности данных', 'woocommerce_gateway_payanyway'),
        'type' => 'password',
        'description' => __('Пожалуйста введите Код проверки целостности данных, указанный в настройках расширенного счёта', 'woocommerce_gateway_payanyway'),
        'default' => '******',
    ),
    'MNT_TEST_MODE' => array(
        'title' => __('Тестовый режим', 'woocommerce_gateway_payanyway'),
        'type' => 'checkbox',
        'label' => __('Включен', 'woocommerce_gateway_payanyway'),
        'description' => __('В этом режиме плата за товар не снимается.', 'woocommerce_gateway_payanyway'),
        'default' => 'no',
    ),
    'cardform' => array(
        'title' => __('Банковские карты - приоритетный способ оплаты', 'woocommerce_gateway_payanyway'),
        'type' => 'checkbox',
        'label' => __('Включен', 'woocommerce_gateway_payanyway'),
        'description' => __('Покупателю будет предложено ввести данные банковской карты. Сразу, на странице вашего сайта', 'woocommerce_gateway_payanyway'),
        'default' => 'yes'
    ),
    'autosubmitpawform' => array(
        'title' => __('Автоотправка', 'woocommerce_gateway_payanyway'),
        'type' => 'checkbox',
        'label' => __('Включить автоотправку формы оплаты', 'woocommerce_gateway_payanyway'),
        'description' => __('Покупатель, для совершения оплаты, будет автоматически перенаправлен на сайт PayAnyWay. Сможет выбрать желаемый способ оплаты', 'woocommerce_gateway_payanyway'),
        'default' => 'no',
    ),
    'iniframe' => array(
        'title' => __('Форма оплаты в iframe', 'woocommerce_gateway_payanyway'),
        'type' => 'checkbox',
        'label' => __('Встроить форму оплаты в страницу сайта.', 'woocommerce_gateway_payanyway'),
        'description' => __('Форма оплаты, предоставляемая PayAnyWay, будет встроена в страницу вашего сайта. Автоотправка невозможна. Покупатель сможет выбрать желаемый способ оплаты', 'woocommerce_gateway_payanyway'),
        'default' => 'no',
    ),
    'debug' => array(
        'title' => __('Отладка', 'woocommerce_gateway_payanyway'),
        'type' => 'checkbox',
        'label' => __('Включить логирование (<code>woocommerce/logs/payanyway.txt</code>)', 'woocommerce_gateway_payanyway'),
        'default' => 'no',
    ),
    'description' => array(
        'title' => __('Описание', 'woocommerce_gateway_payanyway'),
        'type' => 'textarea',
        'description' => __('Описанием метода оплаты которое клиент будет видеть на вашем сайте.', 'woocommerce_gateway_payanyway'),
        'default' => 'Оплата с помощью payanyway',
    ),
    'instructions' => array(
        'title' => __('Инструкции', 'woocommerce_gateway_payanyway'),
        'type' => 'textarea',
        'description' => __('Инструкции, которые будут добавлены на страницу благодарностей.', 'woocommerce_gateway_payanyway'),
        'default' => 'Оплата с помощью payanyway',
    ),

    'apple_pay' => array(
        'title' => __('Apple Pay', 'woocommerce_gateway_payanyway'),
        'type' => 'title',
        'description' => __('Подключение Apple Pay - бесконтактный способ оплаты. Работает только для устройств Apple.', 'woocommerce_gateway_payanyway'),
    ),
    'appleenabled' => array(
        'title' => __('Включить/Выключить', 'woocommerce_gateway_payanyway'),
        'type' => 'checkbox',
        'label' => __('Включен', 'woocommerce_gateway_payanyway'),
        'default' => 'yes',
    ),
    'applepublicid' => array(
        'title' => __('Публичный идентификатор', 'woocommerce_gateway_payanyway'),
        'type' => 'text',
        'description' => __('Публичный идентификатор (Public Id) вашей учётной записи из личного кабинета moneta.ru', 'woocommerce_gateway_payanyway'),
        'default' => '*************',
    ),
    'applepayee' => array(
        'title' => __('Получатель платежа', 'woocommerce_gateway_payanyway'),
        'type' => 'text',
        'description' => __('Нельзя использовать русские буквы. Например - адрес сайта вашего интернет-магазина. Отобразится покупателю в процессе оплаты.', 'woocommerce_gateway_payanyway'),
        'default' => 'www.site.com',
    ),
    'empty' => array(
        'title' => __('', 'woocommerce_gateway_payanyway'),
        'type' => 'title',
        'description' => __('', 'woocommerce_gateway_payanyway'),
    ),
);

return apply_filters('woocommerce_payanyway_settings', $settings);