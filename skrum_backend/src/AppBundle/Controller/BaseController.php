<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Utils\LoggerManager;

/**
 * ベースコントローラ（被継承クラス）
 *
 * @author naoharu.tazawa
 */
class BaseController extends Controller
{
    /**
     * DEBUGログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     */
    protected function logDebug($message, array $context = array())
    {
        LoggerManager::getInstance()->getLogger()->addDebug($message, $context);
    }

    /**
     * INFOログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     */
    protected function logInfo($message, array $context = array())
    {
        LoggerManager::getInstance()->getLogger()->addInfo($message, $context);
    }

    /**
     * WARNログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     */
    protected function logWarning($message, array $context = array())
    {
        LoggerManager::getInstance()->getLogger()->addWarning($message, $context);
    }

    /**
     * ERRORログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     */
    protected function logError($message, array $context = array())
    {
        LoggerManager::getInstance()->getLogger()->addError($message, $context);
    }

    /**
     * CRITICALログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     */
    protected function logCritical($message, array $context = array())
    {
        LoggerManager::getInstance()->getLogger()->addCritical($message, $context);
    }

    /**
     * ALERTログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     */
    protected function logAlert($message, array $context = array())
    {
        LoggerManager::getInstance()->getLogger()->addAlert($message, $context);
    }


    //----------------------------------------------
    //ここからサービスクラスの取得メソッド
    //----------------------------------------------

    protected function getSampleService()
    {
        return $this->get('api.sample_service');
    }
}
