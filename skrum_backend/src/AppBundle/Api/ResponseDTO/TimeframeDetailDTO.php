<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * タイムフレーム詳細DTO

 * @JSON\ExclusionPolicy("none")
 */
class TimeframeDetailDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $timeframeId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $timeframeName;

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
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $defaultFlg;

    /**
     * Set timeframeId
     *
     * @param integer $timeframeId
     *
     * @return TimeframeDetailDTO
     */
    public function setTimeframeId($timeframeId)
    {
        $this->timeframeId = $timeframeId;

        return $this;
    }

    /**
     * Get timeframeId
     *
     * @return integer
     */
    public function getTimeframeId()
    {
        return $this->timeframeId;
    }

    /**
     * Set timeframeName
     *
     * @param string $timeframeName
     *
     * @return TimeframeDetailDTO
     */
    public function setTimeframeName($timeframeName)
    {
        $this->timeframeName = $timeframeName;

        return $this;
    }

    /**
     * Get timeframeName
     *
     * @return string
     */
    public function getTimeframeName()
    {
        return $this->timeframeName;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return TimeframeDetailDTO
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
     * @return TimeframeDetailDTO
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
     * Set defaultFlg
     *
     * @param integer $defaultFlg
     *
     * @return TimeframeDetailDTO
     */
    public function setDefaultFlg($defaultFlg)
    {
        $this->defaultFlg = $defaultFlg;

        return $this;
    }

    /**
     * Get defaultFlg
     *
     * @return integer
     */
    public function getDefaultFlg()
    {
        return $this->defaultFlg;
    }
}
