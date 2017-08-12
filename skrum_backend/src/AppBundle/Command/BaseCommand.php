<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\Utils\LoggerManager;
use Monolog\Logger;

/**
 * ベースコマンド（被継承クラス）
 *
 * @author naoharu.tazawa
 */
class BaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('base');
    }

    /**
     * ロガー取得
     *
     * @return Logger monologロガーインスタンス
     */
    private function getLogger(): Logger
    {
        return LoggerManager::getInstance()->getLogger();
    }

    /**
     * DEBUGログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return boolean Whether the record has been processed
     */
    protected function logDebug(string $message, array $context = array()): bool
    {
        return $this->getLogger()->addDebug($message, $context);
    }

    /**
     * INFOログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return boolean Whether the record has been processed
     */
    protected function logInfo(string $message, array $context = array()): bool
    {
        return $this->getLogger()->addInfo($message, $context);
    }

    /**
     * WARNログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return boolean Whether the record has been processed
     */
    protected function logWarning(string $message, array $context = array()): bool
    {
        return $this->getLogger()->addWarning($message, $context);
    }

    /**
     * ERRORログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return boolean Whether the record has been processed
     */
    protected function logError(string $message, array $context = array()): bool
    {
        return $this->getLogger()->addError($message, $context);
    }

    /**
     * CRITICALログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return boolean Whether the record has been processed
     */
    protected function logCritical(string $message, array $context = array()): bool
    {
        return $this->getLogger()->addCritical($message, $context);
    }

    /**
     * ALERTログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return boolean Whether the record has been processed
     */
    protected function logAlert(string $message, array $context = array()): bool
    {
        return $this->getLogger()->addAlert($message, $context);
    }


    //----------------------------------------------
    //ここからサービスクラスの取得メソッド
    //----------------------------------------------

    protected function getAchievementRateLogService()
    {
        return $this->getContainer()->get('batch.achievement_rate_log_service');
    }

    protected function getAchievementRegistrationReminderEmailService()
    {
        return $this->getContainer()->get('batch.achievement_registration_reminder_email_service');
    }

    protected function getAdditionalUsersBulkRegistrationService()
    {
        return $this->getContainer()->get('batch.additional_users_bulk_registration_service');
    }

    protected function getEmailSendingService()
    {
        return $this->getContainer()->get('batch.email_sending_service');
    }

    protected function getFeedbackTargetReportEmailService()
    {
        return $this->getContainer()->get('batch.feedback_target_report_email_service');
    }

    protected function getGroupReportEmailService()
    {
        return $this->getContainer()->get('batch.group_report_email_service');
    }

    protected function getMemberReportEmailService()
    {
        return $this->getContainer()->get('batch.member_report_email_service');
    }
}
