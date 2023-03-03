<?php

if (!defined('ABSPATH')) {
    exit;
}

class YaNoipLogger {
    
    
    protected static $_instance = null;
    /**
     * @var /WC_Logger_Interface
     */
    protected $logger;
    
    protected function __construct()
    {
        $this->logger = wc_get_logger();
    }
    
    /**
     * Запись лога
     * @param $data - массив/строка
     */
    public function logSet($data) {
        $message = var_export($data, true);
        $this->logger->debug(sprintf("Сообщение от шлюза ЯндексДеньги (Юмони) ### %s ### \n %s", current_time('Y.m.d H:i:s'),$message));
        return;
    }
    
    /**
     * Singletone
     * @result YaNoipLogger
     */
    public static function getInstance()
    {
        
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    
    
    public function __clone()
    {
        throw new \Exception('Forbiden instance __clone');
    }
    
    public function __wakeup()
    {
        throw new \Exception('Forbiden instance __wakeup');
    }
    
}
