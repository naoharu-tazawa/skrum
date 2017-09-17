<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * OKR基本情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class BasicOkrDTO
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
    private $ownerUserRoleLevel;

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
     * @var string
     *
     * @JSON\Type("string")
     */
    private $ownerGroupType;

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
    private $disclosureType;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $parentOkrId;

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
     * @var \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO")
     */
    private $parentOkr;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $keyResults;

    /**
     * Set okrId
     *
     * @param integer $okrId
     *
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * Set ownerUserRoleLevel
     *
     * @param integer $ownerUserRoleLevel
     *
     * @return BasicOkrDTO
     */
    public function setOwnerUserRoleLevel($ownerUserRoleLevel)
    {
        $this->ownerUserRoleLevel = $ownerUserRoleLevel;

        return $this;
    }

    /**
     * Get ownerUserRoleLevel
     *
     * @return integer
     */
    public function getOwnerUserRoleLevel()
    {
        return $this->ownerUserRoleLevel;
    }

    /**
     * Set ownerGroupId
     *
     * @param integer $ownerGroupId
     *
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * Set ownerGroupType
     *
     * @param string $ownerGroupType
     *
     * @return BasicOkrDTO
     */
    public function setOwnerGroupType($ownerGroupType)
    {
        $this->ownerGroupType = $ownerGroupType;

        return $this;
    }

    /**
     * Get ownerGroupType
     *
     * @return string
     */
    public function getOwnerGroupType()
    {
        return $this->ownerGroupType;
    }

    /**
     * Set ownerCompanyId
     *
     * @param integer $ownerCompanyId
     *
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * Set parentOkrId
     *
     * @param integer $parentOkrId
     *
     * @return BasicOkrDTO
     */
    public function setParentOkrId($parentOkrId)
    {
        $this->parentOkrId = $parentOkrId;

        return $this;
    }

    /**
     * Get parentOkrId
     *
     * @return integer
     */
    public function getParentOkrId()
    {
        return $this->parentOkrId;
    }

    /**
     * Set weightedAverageRatio
     *
     * @param string $weightedAverageRatio
     *
     * @return BasicOkrDTO
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
     * @return BasicOkrDTO
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
     * Set parentOkr
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO $parentOkr
     *
     * @return BasicOkrDTO
     */
    public function setParentOkr($parentOkr)
    {
        $this->parentOkr = $parentOkr;

        return $this;
    }

    /**
     * Get parentOkr
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     */
    public function getParentOkr()
    {
        return $this->parentOkr;
    }

    /**
     * Set keyResults
     *
     * @param array $keyResults
     *
     * @return BasicOkrDTO
     */
    public function setKeyResults($keyResults)
    {
        $this->keyResults = $keyResults;

        return $this;
    }

    /**
     * Get keyResults
     *
     * @return array
     */
    public function getKeyResults()
    {
        return $this->keyResults;
    }
}
