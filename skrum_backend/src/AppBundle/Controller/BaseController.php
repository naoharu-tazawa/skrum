<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Utils\LoggerManager;

/**
 * ベースコントローラ（被継承クラス）
 *
 * @author naoharu.tazawa
 */
class BaseController extends FOSRestController
{
    /**
     * ロガー取得
     *
     * @return Monolog\Logger monologロガーインスタンス
     */
    private function getLogger()
    {
        return LoggerManager::getInstance()->getLogger();
    }

    /**
     * DEBUGログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logDebug($message, array $context = array())
    {
        return $this->getLogger()->addDebug($message, $context);
    }

    /**
     * INFOログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logInfo($message, array $context = array())
    {
        return $this->getLogger()->addInfo($message, $context);
    }

    /**
     * WARNログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logWarning($message, array $context = array())
    {
        return $this->getLogger()->addWarning($message, $context);
    }

    /**
     * ERRORログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logError($message, array $context = array())
    {
        return $this->getLogger()->addError($message, $context);
    }

    /**
     * CRITICALログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logCritical($message, array $context = array())
    {
        return $this->getLogger()->addCritical($message, $context);
    }

    /**
     * ALERTログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logAlert($message, array $context = array())
    {
        return $this->getLogger()->addAlert($message, $context);
    }


    //----------------------------------------------
    //ここからサービスクラスの取得メソッド
    //----------------------------------------------

    protected function getSampleService()
    {
        return $this->get('api.sample_service');
    }
}
