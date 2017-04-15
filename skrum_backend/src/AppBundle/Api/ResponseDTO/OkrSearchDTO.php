<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * OKR検索DTO

 * @JSON\ExclusionPolicy("none")
 */
class OkrSearchDTO
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
     * Set okrId
     *
     * @param integer $okrId
     *
     * @return OkrSearchDTO
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
     * @return OkrSearchDTO
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
}
