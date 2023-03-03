<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL

//Begin Really Simple SSL Load balancing fix
if ((isset($_ENV["HTTPS"]) && ("on" == $_ENV["HTTPS"]))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "1") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "on") !== false))
|| (isset($_SERVER["HTTP_CF_VISITOR"]) && (strpos($_SERVER["HTTP_CF_VISITOR"], "https") !== false))
|| (isset($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_X_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_PROTO"]) && (strpos($_SERVER["HTTP_X_PROTO"], "SSL") !== false))
) {
$_SERVER["HTTPS"] = "on";
}
//END Really Simple SSL
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'ivangrish9' );


/** Имя пользователя MySQL */
define( 'DB_USER', 'ivangrish9' );


/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', 'YEVhkl0rZ2jhMgbp' );


/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );


/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );


/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Yzo9I?ftFKab=GtXzoCvG&V>/xP^Q4e|:/Uol6U@}+ya[g>HH-`:KyIhDsAA?alK' );

define( 'SECURE_AUTH_KEY',  '0{J=,u>X%Gt;7V&+N)TXTnk|!>2|b`qag`MWgi=~/XR,M+}B0V,7_acLL52dAT/V' );

define( 'LOGGED_IN_KEY',    'GP=?<;D#OW@S@jjaMqa~q{r)]P=7roetQks&fxTYzd[:R>w__fH`G~WE/L!AYJ)c' );

define( 'NONCE_KEY',        'eg:@vMq~}e>^d{;WZR_4P4[RR6 (V}z-X2HvqtqK,[q%N!h[GWUKj/YEa6+3`3|3' );

define( 'AUTH_SALT',        '=H|c!UqZ9,LUu`i;B8iV[^0>8s/3aa~T&gup[HB*p M2FPNB4%,@d23*z_IRjVCQ' );

define( 'SECURE_AUTH_SALT', 'bh:-kG(dZ5c=:bXY6`PJ?6@-a-=[fm=su<tg_T*N6A=H!-;FP)2A4d}+o}OcG#GS' );

define( 'LOGGED_IN_SALT',   'oDK7AG,el89Nir{0)!;w|EzPT2Qw&AzG85QyI8;C3s%$tV%Xt:LL%T~4~(lSlr[}' );

define( 'NONCE_SALT',       'Q_dGhiU:yKqR..|UHjAA#m*V]x8[7xxJz2GRpUM=_F9ov1V/!Ocn3tJ/SdT:l5KB' );


/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';


/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
