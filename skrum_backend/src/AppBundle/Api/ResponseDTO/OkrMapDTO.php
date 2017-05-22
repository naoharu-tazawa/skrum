<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * OKRマップ情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class OkrMapDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $okrId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $okrType;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $okrName;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $okrDetail;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $targetValue;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $achievedValue;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $unit;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $achievementRate;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $ownerType;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $ownerUserId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $ownerUserName;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $ownerGroupId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $ownerGroupName;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $ownerCompanyId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $ownerCompanyName;

    /**
     * @var \DateTime
     *
     * @JSON\Type("DateTime<'Y-m-d'>")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @JSON\Type("DateTime<'Y-m-d'>")
     */
    private $endDate;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $status;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $weightedAverageRatio;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $ratioLockedFlg;

    /**
     * @var boolean
     *
     * @JSON\Type("boolean")
     */
    private $hidden;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $children;

    /**
     * Set okrId
     *
     * @param integer $okrId
     *
     * @return OkrMapDTO
     */
    public function setOkrId($okrId)
    {
        $this->okrId = $okrId;

        return $this;
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
     * Set okrType
     *
     * @param string $okrType
     *
     * @return OkrMapDTO
     */
    public function setOkrType($okrType)
    {
        $this->okrType = $okrType;

        return $this;
    }

    /**
     * Get okrType
     *
     * @return string
     */
    public function getOkrType()
    {
        return $this->okrType;
    }

    /**
     * Set okrName
     *
     * @param string $okrName
     *
     * @return OkrMapDTO
     */
    public function setOkrName($okrName)
    {
        $this->okrName = $okrName;

        return $this;
    }

    /**
     * Get okrName
     *
     * @return string
     */
    public function getOkrName()
    {
        return $this->okrName;
    }

    /**
     * Set okrDetail
     *
     * @param string $okrDetail
     *
     * @return OkrMapDTO
     */
    public function setOkrDetail($okrDetail)
    {
        $this->okrDetail = $okrDetail;

        return $this;
    }

    /**
     * Get okrDetail
     *
     * @return string
     */
    public function getOkrDetail()
    {
        return $this->okrDetail;
    }

    /**
     * Set targetValue
     *
     * @param integer $targetValue
     *
     * @return OkrMapDTO
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
     * @return OkrMapDTO
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
     * Set unit
     *
     * @param string $unit
     *
     * @return OkrMapDTO
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
     * Set achievementRate
     *
     * @param string $achievementRate
     *
     * @return OkrMapDTO
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
     * Set ownerType
     *
     * @param string $ownerType
     *
     * @return OkrMapDTO
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
     * Set ownerUserId
     *
     * @param integer $ownerUserId
     *
     * @return OkrMapDTO
     */
    public function setOwnerUserId($ownerUserId)
    {
        $this->ownerUserId = $ownerUserId;

        return $this;
    }

    /**
     * Get ownerUserId
     *
     * @return integer
     */
    public function getOwnerUserId()
    {
        return $this->ownerUserId;
    }

    /**
     * Set ownerUserName
     *
     * @param string $ownerUserName
     *
     * @return OkrMapDTO
     */
    public function setOwnerUserName($ownerUserName)
    {
        $this->ownerUserName = $ownerUserName;

        return $this;
    }

    /**
     * Get ownerUserName
     *
     * @return string
     */
    public function getOwnerUserName()
    {
        return $this->ownerUserName;
    }

    /**
     * Set ownerGroupId
     *
     * @param integer $ownerGroupId
     *
     * @return OkrMapDTO
     */
    public function setOwnerGroupId($ownerGroupId)
    {
        $this->ownerGroupId = $ownerGroupId;

        return $this;
    }

    /**
     * Get ownerGroupId
     *
     * @return integer
     */
    public function getOwnerGroupId()
    {
        return $this->ownerGroupId;
    }

    /**
     * Set ownerGroupName
     *
     * @param string $ownerGroupName
     *
     * @return OkrMapDTO
     */
    public function setOwnerGroupName($ownerGroupName)
    {
        $this->ownerGroupName = $ownerGroupName;

        return $this;
    }

    /**
     * Get ownerGroupName
     *
     * @return string
     */
    public function getOwnerGroupName()
    {
        return $this->ownerGroupName;
    }

    /**
     * Set ownerCompanyId
     *
     * @param integer $ownerCompanyId
     *
     * @return OkrMapDTO
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
     * Set ownerCompanyName
     *
     * @param string $ownerCompanyName
     *
     * @return OkrMapDTO
     */
    public function setOwnerCompanyName($ownerCompanyName)
    {
        $this->ownerCompanyName = $ownerCompanyName;

        return $this;
    }

    /**
     * Get ownerCompanyName
     *
     * @return string
     */
    public function getOwnerCompanyName()
    {
        return $this->ownerCompanyName;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return OkrMapDTO
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
     * @return OkrMapDTO
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
     * @return OkrMapDTO
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
     * Set weightedAverageRatio
     *
     * @param string $weightedAverageRatio
     *
     * @return OkrMapDTO
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
     * @param integer $ratioLockedFlg
     *
     * @return OkrMapDTO
     */
    public function setRatioLockedFlg($ratioLockedFlg)
    {
        $this->ratioLockedFlg = $ratioLockedFlg;

        return $this;
    }

    /**
     * Get ratioLockedFlg
     *
     * @return integer
     */
    public function getRatioLockedFlg()
    {
        return $this->ratioLockedFlg;
    }

    /**
     * Set hidden
     *
     * @param boolean $hidden
     *
     * @return OkrMapDTO
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden
     *
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set children
     *
     * @param array $children
     *
     * @return OkrMapDTO
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }
}
