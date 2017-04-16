<?php

namespace AppBundle\Utils;

use AppBundle\Utils\Constant;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * ロガーインスタンス管理クラス
 * （シングルトンパターン実装）
 *
 * @author naoharu.tazawa
 */
class LoggerManager
{
    /**
     * monologロガーインスタンス
     *
     * @var Logger
     */
    private $logger;

    /**
     * 自インスタンス
     *
     * @var LoggerManager
     */
    private static $instance;

    /**
     * コンストラクタ
     */
    private function __construct()
    {
        $this->logger = new Logger('app');
        $this->logger->pushHandler(new StreamHandler(Constant::LOG_FILE_PATH_APPLICATION, Logger::DEBUG));
        $this->logger->pushHandler(new StreamHandler(Constant::LOG_FILE_PATH_ALERT, Logger::ALERT));
    }

    /**
     * 自インスタンスを取得
     *
     * @return LoggerManager 自インスタンス
     */
    public static function getInstance()
    {
        if (!self::$instance) self::$instance = new LoggerManager();
        return self::$instance;
    }

    /**
     * monologロガーインスタンスを取得
     *
     * @return Logger monologロガーインスタンス
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
