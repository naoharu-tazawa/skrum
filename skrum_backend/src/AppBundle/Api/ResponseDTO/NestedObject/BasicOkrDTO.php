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
    private $okrName;

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
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $ownerGroupId;

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
