<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * タイムフレームDTO

 * @JSON\ExclusionPolicy("none")
 */
class TimeframeDTO
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
     * @return TimeframeDTO
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
     * @return TimeframeDTO
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
     * Set defaultFlg
     *
     * @param integer $defaultFlg
     *
     * @return TimeframeDTO
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
