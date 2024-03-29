<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TEmailSettings
 *
 * @ORM\Table(name="t_email_settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TEmailSettingsRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TEmailSettings
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="okr_achievement", type="string", length=2, nullable=false)
     */
    private $okrAchievement = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="okr_timeline", type="string", length=2, nullable=false)
     */
    private $okrTimeline = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="one_on_one", type="string", length=2, nullable=false)
     */
    private $oneOnOne = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="okr_deadline_reminder", type="string", length=2, nullable=false)
     */
    private $okrDeadlineReminder = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="okr_reminder", type="string", length=2, nullable=false)
     */
    private $okrReminder = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="report_member_achievement", type="string", length=2, nullable=false)
     */
    private $reportMemberAchievement = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="report_group_achievement", type="string", length=2, nullable=false)
     */
    private $reportGroupAchievement = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="report_feedback_target", type="string", length=2, nullable=false)
     */
    private $reportFeedbackTarget = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="service_notification", type="string", length=2, nullable=false)
     */
    private $serviceNotification = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return TEmailSettings
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set okrAchievement
     *
     * @param string $okrAchievement
     *
     * @return TEmailSettings
     */
    public function setOkrAchievement($okrAchievement)
    {
        $this->okrAchievement = $okrAchievement;

        return $this;
    }

    /**
     * Get okrAchievement
     *
     * @return string
     */
    public function getOkrAchievement()
    {
        return $this->okrAchievement;
    }

    /**
     * Set okrTimeline
     *
     * @param string $okrTimeline
     *
     * @return TEmailSettings
     */
    public function setOkrTimeline($okrTimeline)
    {
        $this->okrTimeline = $okrTimeline;

        return $this;
    }

    /**
     * Get okrTimeline
     *
     * @return string
     */
    public function getOkrTimeline()
    {
        return $this->okrTimeline;
    }

    /**
     * Set oneOnOne
     *
     * @param string $oneOnOne
     *
     * @return TEmailSettings
     */
    public function setOneOnOne($oneOnOne)
    {
        $this->oneOnOne = $oneOnOne;

        return $this;
    }

    /**
     * Get oneOnOne
     *
     * @return string
     */
    public function getOneOnOne()
    {
        return $this->oneOnOne;
    }

    /**
     * Set okrDeadlineReminder
     *
     * @param string $okrDeadlineReminder
     *
     * @return TEmailSettings
     */
    public function setOkrDeadlineReminder($okrDeadlineReminder)
    {
        $this->okrDeadlineReminder = $okrDeadlineReminder;

        return $this;
    }

    /**
     * Get okrDeadlineReminder
     *
     * @return string
     */
    public function getOkrDeadlineReminder()
    {
        return $this->okrDeadlineReminder;
    }

    /**
     * Set okrReminder
     *
     * @param string $okrReminder
     *
     * @return TEmailSettings
     */
    public function setOkrReminder($okrReminder)
    {
        $this->okrReminder = $okrReminder;

        return $this;
    }

    /**
     * Get okrReminder
     *
     * @return string
     */
    public function getOkrReminder()
    {
        return $this->okrReminder;
    }

    /**
     * Set reportMemberAchievement
     *
     * @param string $reportMemberAchievement
     *
     * @return TEmailSettings
     */
    public function setReportMemberAchievement($reportMemberAchievement)
    {
        $this->reportMemberAchievement = $reportMemberAchievement;

        return $this;
    }

    /**
     * Get reportMemberAchievement
     *
     * @return string
     */
    public function getReportMemberAchievement()
    {
        return $this->reportMemberAchievement;
    }

    /**
     * Set reportGroupAchievement
     *
     * @param string $reportGroupAchievement
     *
     * @return TEmailSettings
     */
    public function setReportGroupAchievement($reportGroupAchievement)
    {
        $this->reportGroupAchievement = $reportGroupAchievement;

        return $this;
    }

    /**
     * Get reportGroupAchievement
     *
     * @return string
     */
    public function getReportGroupAchievement()
    {
        return $this->reportGroupAchievement;
    }

    /**
     * Set reportFeedbackTarget
     *
     * @param string $reportFeedbackTarget
     *
     * @return TEmailSettings
     */
    public function setReportFeedbackTarget($reportFeedbackTarget)
    {
        $this->reportFeedbackTarget = $reportFeedbackTarget;

        return $this;
    }

    /**
     * Get reportFeedbackTarget
     *
     * @return string
     */
    public function getReportFeedbackTarget()
    {
        return $this->reportFeedbackTarget;
    }

    /**
     * Set serviceNotification
     *
     * @param string $serviceNotification
     *
     * @return TEmailSettings
     */
    public function setServiceNotification($serviceNotification)
    {
        $this->serviceNotification = $serviceNotification;

        return $this;
    }

    /**
     * Get serviceNotification
     *
     * @return string
     */
    public function getServiceNotification()
    {
        return $this->serviceNotification;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TEmailSettings
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return TEmailSettings
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return TEmailSettings
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
