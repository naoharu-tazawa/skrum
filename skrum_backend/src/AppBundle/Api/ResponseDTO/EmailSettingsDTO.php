<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * メール配信設定情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class EmailSettingsDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $okrAchievement;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $okrTimeline;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $okrReminder;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $reportMemberAchievement;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $reportGroupAchievement;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $reportFeedbackTarget;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $serviceNotification;

    /**
     * Set okrAchievement
     *
     * @param integer $okrAchievement
     *
     * @return EmailSettingsDTO
     */
    public function setOkrAchievement($okrAchievement)
    {
        $this->okrAchievement = $okrAchievement;

        return $this;
    }

    /**
     * Get okrAchievement
     *
     * @return integer
     */
    public function getOkrAchievement()
    {
        return $this->okrAchievement;
    }

    /**
     * Set okrTimeline
     *
     * @param integer $okrTimeline
     *
     * @return EmailSettingsDTO
     */
    public function setOkrTimeline($okrTimeline)
    {
        $this->okrTimeline = $okrTimeline;

        return $this;
    }

    /**
     * Get okrTimeline
     *
     * @return integer
     */
    public function getOkrTimeline()
    {
        return $this->okrTimeline;
    }

    /**
     * Set okrReminder
     *
     * @param integer $okrReminder
     *
     * @return EmailSettingsDTO
     */
    public function setOkrReminder($okrReminder)
    {
        $this->okrReminder = $okrReminder;

        return $this;
    }

    /**
     * Get okrReminder
     *
     * @return integer
     */
    public function getOkrReminder()
    {
        return $this->okrReminder;
    }

    /**
     * Set reportMemberAchievement
     *
     * @param integer $reportMemberAchievement
     *
     * @return EmailSettingsDTO
     */
    public function setReportMemberAchievement($reportMemberAchievement)
    {
        $this->reportMemberAchievement = $reportMemberAchievement;

        return $this;
    }

    /**
     * Get reportMemberAchievement
     *
     * @return integer
     */
    public function getReportMemberAchievement()
    {
        return $this->reportMemberAchievement;
    }

    /**
     * Set reportGroupAchievement
     *
     * @param integer $reportGroupAchievement
     *
     * @return EmailSettingsDTO
     */
    public function setReportGroupAchievement($reportGroupAchievement)
    {
        $this->reportGroupAchievement = $reportGroupAchievement;

        return $this;
    }

    /**
     * Get reportGroupAchievement
     *
     * @return integer
     */
    public function getReportGroupAchievement()
    {
        return $this->reportGroupAchievement;
    }

    /**
     * Set reportFeedbackTarget
     *
     * @param integer $reportFeedbackTarget
     *
     * @return EmailSettingsDTO
     */
    public function setReportFeedbackTarget($reportFeedbackTarget)
    {
        $this->reportFeedbackTarget = $reportFeedbackTarget;

        return $this;
    }

    /**
     * Get reportFeedbackTarget
     *
     * @return integer
     */
    public function getReportFeedbackTarget()
    {
        return $this->reportFeedbackTarget;
    }

    /**
     * Set serviceNotification
     *
     * @param integer $serviceNotification
     *
     * @return EmailSettingsDTO
     */
    public function setServiceNotification($serviceNotification)
    {
        $this->serviceNotification = $serviceNotification;

        return $this;
    }

    /**
     * Get serviceNotification
     *
     * @return integer
     */
    public function getServiceNotification()
    {
        return $this->serviceNotification;
    }
}
