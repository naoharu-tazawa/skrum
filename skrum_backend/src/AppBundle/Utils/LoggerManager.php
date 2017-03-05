<?php

namespace AppBundle\Utils;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use AppBundle\Utils\Constant;

/**
 * ロガーインスタンス管理クラス
 * （シングルトン実装）
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
     * 当クラスのインスタンス
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
     * 当クラスのインスタンスを返却
     *
     * @return LoggerManager 当クラスのインスタンス
     */
    public static function getInstance()
    {
        if (self::$instance === null) self::$instance = new LoggerManager();
        return self::$instance;
    }

    /**
     * monologロガーインスタンスを返却
     *
     * @return Logger monologロガーインスタンス
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
