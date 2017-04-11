<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TOkr
 *
 * @ORM\Table(name="t_okr", indexes={@ORM\Index(name="idx_okr_01", columns={"timeframe_id"}), @ORM\Index(name="idx_okr_02", columns={"parent_okr_id"}), @ORM\Index(name="idx_okr_04", columns={"owner_group_id"}), @ORM\Index(name="idx_okr_05", columns={"owner_user_id"}), @ORM\Index(name="idx_okr_06", columns={"tree_left", "tree_right"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TOkrRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TOkr
{
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=2, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string", length=255, nullable=true)
     */
    private $detail;

    /**
     * @var integer
     *
     * @ORM\Column(name="target_value", type="bigint", nullable=false)
     */
    private $targetValue = '100';

    /**
     * @var integer
     *
     * @ORM\Column(name="achieved_value", type="bigint", nullable=false)
     */
    private $achievedValue = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="achievement_rate", type="decimal", precision=4, scale=1, nullable=false)
     */
    private $achievementRate;

    /**
     * @var float
     *
     * @ORM\Column(name="tree_left", type="float", precision=10, scale=0, nullable=true)
     */
    private $treeLeft;

    /**
     * @var float
     *
     * @ORM\Column(name="tree_right", type="float", precision=10, scale=0, nullable=true)
     */
    private $treeRight;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=45, nullable=true)
     */
    private $unit = '％';

    /**
     * @var string
     *
     * @ORM\Column(name="owner_type", type="string", length=2, nullable=false)
     */
    private $ownerType;

    /**
     * @var integer
     *
     * @ORM\Column(name="owner_company_id", type="integer", nullable=true)
     */
    private $ownerCompanyId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=2, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="disclosure_type", type="string", length=2, nullable=false)
     */
    private $disclosureType;

    /**
     * @var string
     *
     * @ORM\Column(name="weighted_average_ratio", type="decimal", precision=4, scale=1, nullable=true)
     */
    private $weightedAverageRatio;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ratio_locked_flg", type="boolean", nullable=true)
     */
    private $ratioLockedFlg;

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
     * @ORM\Column(name="okr_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $okrId;

    /**
     * @var \AppBundle\Entity\MGroup
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner_group_id", referencedColumnName="group_id")
     * })
     */
    private $ownerGroup;

    /**
     * @var \AppBundle\Entity\MUser
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner_user_id", referencedColumnName="user_id")
     * })
     */
    private $ownerUser;

    /**
     * @var \AppBundle\Entity\TOkr
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TOkr")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_okr_id", referencedColumnName="okr_id")
     * })
     */
    private $parentOkr;

    /**
     * @var \AppBundle\Entity\TTimeframe
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TTimeframe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="timeframe_id", referencedColumnName="timeframe_id")
     * })
     */
    private $timeframe;



    /**
     * Set type
     *
     * @param string $type
     *
     * @return TOkr
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
     * Set name
     *
     * @param string $name
     *
     * @return TOkr
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set detail
     *
     * @param string $detail
     *
     * @return TOkr
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set targetValue
     *
     * @param integer $targetValue
     *
     * @return TOkr
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
     * @return TOkr
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
     * @return TOkr
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
     * Set treeLeft
     *
     * @param float $treeLeft
     *
     * @return TOkr
     */
    public function setTreeLeft($treeLeft)
    {
        $this->treeLeft = $treeLeft;

        return $this;
    }

    /**
     * Get treeLeft
     *
     * @return float
     */
    public function getTreeLeft()
    {
        return $this->treeLeft;
    }

    /**
     * Set treeRight
     *
     * @param float $treeRight
     *
     * @return TOkr
     */
    public function setTreeRight($treeRight)
    {
        $this->treeRight = $treeRight;

        return $this;
    }

    /**
     * Get treeRight
     *
     * @return float
     */
    public function getTreeRight()
    {
        return $this->treeRight;
    }

    /**
     * Set unit
     *
     * @param string $unit
     *
     * @return TOkr
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set ownerType
     *
     * @param string $ownerType
     *
     * @return TOkr
     */
    public function setOwnerType($ownerType)
    {
        $this->ownerType = $ownerType;

        return $this;
    }

    /**
     * Get ownerType
     *
     * @return string
     */
    public function getOwnerType()
    {
        return $this->ownerType;
    }

    /**
     * Set ownerCompanyId
     *
     * @param integer $ownerCompanyId
     *
     * @return TOkr
     */
    public function setOwnerCompanyId($ownerCompanyId)
    {
        $this->ownerCompanyId = $ownerCompanyId;

        return $this;
    }

    /**
     * Get ownerCompanyId
     *
     * @return integer
     */
    public function getOwnerCompanyId()
    {
        return $this->ownerCompanyId;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return TOkr
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return TOkr
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return TOkr
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set disclosureType
     *
     * @param string $disclosureType
     *
     * @return TOkr
     */
    public function setDisclosureType($disclosureType)
    {
        $this->disclosureType = $disclosureType;

        return $this;
    }

    /**
     * Get disclosureType
     *
     * @return string
     */
    public function getDisclosureType()
    {
        return $this->disclosureType;
    }

    /**
     * Set weightedAverageRatio
     *
     * @param string $weightedAverageRatio
     *
     * @return TOkr
     */
    public function setWeightedAverageRatio($weightedAverageRatio)
    {
        $this->weightedAverageRatio = $weightedAverageRatio;

        return $this;
    }

    /**
     * Get weightedAverageRatio
     *
     * @return string
     */
    public function getWeightedAverageRatio()
    {
        return $this->weightedAverageRatio;
    }

    /**
     * Set ratioLockedFlg
     *
     * @param boolean $ratioLockedFlg
     *
     * @return TOkr
     */
    public function setRatioLockedFlg($ratioLockedFlg)
    {
        $this->ratioLockedFlg = $ratioLockedFlg;

        return $this;
    }

    /**
     * Get ratioLockedFlg
     *
     * @return boolean
     */
    public function getRatioLockedFlg()
    {
        return $this->ratioLockedFlg;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TOkr
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
     * @return TOkr
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
     * @return TOkr
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
     * Get okrId
     *
     * @return integer
     */
    public function getOkrId()
    {
        return $this->okrId;
    }

    /**
     * Set ownerGroup
     *
     * @param \AppBundle\Entity\MGroup $ownerGroup
     *
     * @return TOkr
     */
    public function setOwnerGroup(\AppBundle\Entity\MGroup $ownerGroup = null)
    {
        $this->ownerGroup = $ownerGroup;

        return $this;
    }

    /**
     * Get ownerGroup
     *
     * @return \AppBundle\Entity\MGroup
     */
    public function getOwnerGroup()
    {
        return $this->ownerGroup;
    }

    /**
     * Set ownerUser
     *
     * @param \AppBundle\Entity\MUser $ownerUser
     *
     * @return TOkr
     */
    public function setOwnerUser(\AppBundle\Entity\MUser $ownerUser = null)
    {
        $this->ownerUser = $ownerUser;

        return $this;
    }

    /**
     * Get ownerUser
     *
     * @return \AppBundle\Entity\MUser
     */
    public function getOwnerUser()
    {
        return $this->ownerUser;
    }

    /**
     * Set parentOkr
     *
     * @param \AppBundle\Entity\TOkr $parentOkr
     *
     * @return TOkr
     */
    public function setParentOkr(\AppBundle\Entity\TOkr $parentOkr = null)
    {
        $this->parentOkr = $parentOkr;

        return $this;
    }

    /**
     * Get parentOkr
     *
     * @return \AppBundle\Entity\TOkr
     */
    public function getParentOkr()
    {
        return $this->parentOkr;
    }

    /**
     * Set timeframe
     *
     * @param \AppBundle\Entity\TTimeframe $timeframe
     *
     * @return TOkr
     */
    public function setTimeframe(\AppBundle\Entity\TTimeframe $timeframe = null)
    {
        $this->timeframe = $timeframe;

        return $this;
    }

    /**
     * Get timeframe
     *
     * @return \AppBundle\Entity\TTimeframe
     */
    public function getTimeframe()
    {
        return $this->timeframe;
    }
}
