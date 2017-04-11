<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TOkrActivity
 *
 * @ORM\Table(name="t_okr_activity", indexes={@ORM\Index(name="idx_okr_activity_01", columns={"okr_id"}), @ORM\Index(name="idx_okr_activity_02", columns={"activity_datetime"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TOkrActivityRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TOkrActivity
{
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=2, nullable=false)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activity_datetime", type="datetime", nullable=false)
     */
    private $activityDatetime;

    /**
     * @var integer
     *
     * @ORM\Column(name="target_value", type="bigint", nullable=true)
     */
    private $targetValue;

    /**
     * @var integer
     *
     * @ORM\Column(name="achieved_value", type="bigint", nullable=true)
     */
    private $achievedValue;

    /**
     * @var string
     *
     * @ORM\Column(name="achievement_rate", type="decimal", precision=4, scale=1, nullable=true)
     */
    private $achievementRate;

    /**
     * @var string
     *
     * @ORM\Column(name="changed_percentage", type="decimal", precision=4, scale=1, nullable=true)
     */
    private $changedPercentage;

    /**
     * @var integer
     *
     * @ORM\Column(name="new_parent_okr_id", type="integer", nullable=true)
     */
    private $newParentOkrId;

    /**
     * @var integer
     *
     * @ORM\Column(name="previous_parent_okr_id", type="integer", nullable=true)
     */
    private $previousParentOkrId;

    /**
     * @var integer
     *
     * @ORM\Column(name="new_timeframe_id", type="integer", nullable=true)
     */
    private $newTimeframeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="previous_timeframe_id", type="integer", nullable=true)
     */
    private $previousTimeframeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="new_owner_company_id", type="integer", nullable=true)
     */
    private $newOwnerCompanyId;

    /**
     * @var integer
     *
     * @ORM\Column(name="previous_owner_company_id", type="integer", nullable=true)
     */
    private $previousOwnerCompanyId;

    /**
     * @var integer
     *
     * @ORM\Column(name="new_owner_group_id", type="integer", nullable=true)
     */
    private $newOwnerGroupId;

    /**
     * @var integer
     *
     * @ORM\Column(name="previous_owner_group_id", type="integer", nullable=true)
     */
    private $previousOwnerGroupId;

    /**
     * @var integer
     *
     * @ORM\Column(name="new_owner_user_id", type="integer", nullable=true)
     */
    private $newOwnerUserId;

    /**
     * @var integer
     *
     * @ORM\Column(name="previous_owner_user_id", type="integer", nullable=true)
     */
    private $previousOwnerUserId;

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
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\TOkr
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TOkr")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="okr_id", referencedColumnName="okr_id")
     * })
     */
    private $okr;



    /**
     * Set type
     *
     * @param string $type
     *
     * @return TOkrActivity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set activityDatetime
     *
     * @param \DateTime $activityDatetime
     *
     * @return TOkrActivity
     */
    public function setActivityDatetime($activityDatetime)
    {
        $this->activityDatetime = $activityDatetime;

        return $this;
    }

    /**
     * Get activityDatetime
     *
     * @return \DateTime
     */
    public function getActivityDatetime()
    {
        return $this->activityDatetime;
    }

    /**
     * Set targetValue
     *
     * @param integer $targetValue
     *
     * @return TOkrActivity
     */
    public function setTargetValue($targetValue)
    {
        $this->targetValue = $targetValue;

        return $this;
    }

    /**
     * Get targetValue
     *
     * @return integer
     */
    public function getTargetValue()
    {
        return $this->targetValue;
    }

    /**
     * Set achievedValue
     *
     * @param integer $achievedValue
     *
     * @return TOkrActivity
     */
    public function setAchievedValue($achievedValue)
    {
        $this->achievedValue = $achievedValue;

        return $this;
    }

    /**
     * Get achievedValue
     *
     * @return integer
     */
    public function getAchievedValue()
    {
        return $this->achievedValue;
    }

    /**
     * Set achievementRate
     *
     * @param string $achievementRate
     *
     * @return TOkrActivity
     */
    public function setAchievementRate($achievementRate)
    {
        $this->achievementRate = $achievementRate;

        return $this;
    }

    /**
     * Get achievementRate
     *
     * @return string
     */
    public function getAchievementRate()
    {
        return $this->achievementRate;
    }

    /**
     * Set changedPercentage
     *
     * @param string $changedPercentage
     *
     * @return TOkrActivity
     */
    public function setChangedPercentage($changedPercentage)
    {
        $this->changedPercentage = $changedPercentage;

        return $this;
    }

    /**
     * Get changedPercentage
     *
     * @return string
     */
    public function getChangedPercentage()
    {
        return $this->changedPercentage;
    }

    /**
     * Set newParentOkrId
     *
     * @param integer $newParentOkrId
     *
     * @return TOkrActivity
     */
    public function setNewParentOkrId($newParentOkrId)
    {
        $this->newParentOkrId = $newParentOkrId;

        return $this;
    }

    /**
     * Get newParentOkrId
     *
     * @return integer
     */
    public function getNewParentOkrId()
    {
        return $this->newParentOkrId;
    }

    /**
     * Set previousParentOkrId
     *
     * @param integer $previousParentOkrId
     *
     * @return TOkrActivity
     */
    public function setPreviousParentOkrId($previousParentOkrId)
    {
        $this->previousParentOkrId = $previousParentOkrId;

        return $this;
    }

    /**
     * Get previousParentOkrId
     *
     * @return integer
     */
    public function getPreviousParentOkrId()
    {
        return $this->previousParentOkrId;
    }

    /**
     * Set newTimeframeId
     *
     * @param integer $newTimeframeId
     *
     * @return TOkrActivity
     */
    public function setNewTimeframeId($newTimeframeId)
    {
        $this->newTimeframeId = $newTimeframeId;

        return $this;
    }

    /**
     * Get newTimeframeId
     *
     * @return integer
     */
    public function getNewTimeframeId()
    {
        return $this->newTimeframeId;
    }

    /**
     * Set previousTimeframeId
     *
     * @param integer $previousTimeframeId
     *
     * @return TOkrActivity
     */
    public function setPreviousTimeframeId($previousTimeframeId)
    {
        $this->previousTimeframeId = $previousTimeframeId;

        return $this;
    }

    /**
     * Get previousTimeframeId
     *
     * @return integer
     */
    public function getPreviousTimeframeId()
    {
        return $this->previousTimeframeId;
    }

    /**
     * Set newOwnerCompanyId
     *
     * @param integer $newOwnerCompanyId
     *
     * @return TOkrActivity
     */
    public function setNewOwnerCompanyId($newOwnerCompanyId)
    {
        $this->newOwnerCompanyId = $newOwnerCompanyId;

        return $this;
    }

    /**
     * Get newOwnerCompanyId
     *
     * @return integer
     */
    public function getNewOwnerCompanyId()
    {
        return $this->newOwnerCompanyId;
    }

    /**
     * Set previousOwnerCompanyId
     *
     * @param integer $previousOwnerCompanyId
     *
     * @return TOkrActivity
     */
    public function setPreviousOwnerCompanyId($previousOwnerCompanyId)
    {
        $this->previousOwnerCompanyId = $previousOwnerCompanyId;

        return $this;
    }

    /**
     * Get previousOwnerCompanyId
     *
     * @return integer
     */
    public function getPreviousOwnerCompanyId()
    {
        return $this->previousOwnerCompanyId;
    }

    /**
     * Set newOwnerGroupId
     *
     * @param integer $newOwnerGroupId
     *
     * @return TOkrActivity
     */
    public function setNewOwnerGroupId($newOwnerGroupId)
    {
        $this->newOwnerGroupId = $newOwnerGroupId;

        return $this;
    }

    /**
     * Get newOwnerGroupId
     *
     * @return integer
     */
    public function getNewOwnerGroupId()
    {
        return $this->newOwnerGroupId;
    }

    /**
     * Set previousOwnerGroupId
     *
     * @param integer $previousOwnerGroupId
     *
     * @return TOkrActivity
     */
    public function setPreviousOwnerGroupId($previousOwnerGroupId)
    {
        $this->previousOwnerGroupId = $previousOwnerGroupId;

        return $this;
    }

    /**
     * Get previousOwnerGroupId
     *
     * @return integer
     */
    public function getPreviousOwnerGroupId()
    {
        return $this->previousOwnerGroupId;
    }

    /**
     * Set newOwnerUserId
     *
     * @param integer $newOwnerUserId
     *
     * @return TOkrActivity
     */
    public function setNewOwnerUserId($newOwnerUserId)
    {
        $this->newOwnerUserId = $newOwnerUserId;

        return $this;
    }

    /**
     * Get newOwnerUserId
     *
     * @return integer
     */
    public function getNewOwnerUserId()
    {
        return $this->newOwnerUserId;
    }

    /**
     * Set previousOwnerUserId
     *
     * @param integer $previousOwnerUserId
     *
     * @return TOkrActivity
     */
    public function setPreviousOwnerUserId($previousOwnerUserId)
    {
        $this->previousOwnerUserId = $previousOwnerUserId;

        return $this;
    }

    /**
     * Get previousOwnerUserId
     *
     * @return integer
     */
    public function getPreviousOwnerUserId()
    {
        return $this->previousOwnerUserId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TOkrActivity
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
     * @return TOkrActivity
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
     * @return TOkrActivity
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

    /**
     * Set okr
     *
     * @param \AppBundle\Entity\TOkr $okr
     *
     * @return TOkrActivity
     */
    public function setOkr(\AppBundle\Entity\TOkr $okr = null)
    {
        $this->okr = $okr;

        return $this;
    }

    /**
     * Get okr
     *
     * @return \AppBundle\Entity\TOkr
     */
    public function getOkr()
    {
        return $this->okr;
    }
}
