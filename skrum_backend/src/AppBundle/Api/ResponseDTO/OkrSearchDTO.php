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
    private $ownerUserImageVersion;

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
    private $ownerGroupImageVersion;

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
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $ownerCompanyImageVersion;

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

    /**
     * Set ownerType
     *
     * @param string $ownerType
     *
     * @return OkrSearchDTO
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
     * @return OkrSearchDTO
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
     * @return OkrSearchDTO
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
     * Set ownerUserImageVersion
     *
     * @param integer $ownerUserImageVersion
     *
     * @return OkrSearchDTO
     */
    public function setOwnerUserImageVersion($ownerUserImageVersion)
    {
        $this->ownerUserImageVersion = $ownerUserImageVersion;

        return $this;
    }

    /**
     * Get ownerUserImageVersion
     *
     * @return integer
     */
    public function getOwnerUserImageVersion()
    {
        return $this->ownerUserImageVersion;
    }

    /**
     * Set ownerGroupId
     *
     * @param integer $ownerGroupId
     *
     * @return OkrSearchDTO
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
     * @return OkrSearchDTO
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
     * Set ownerGroupImageVersion
     *
     * @param integer $ownerGroupImageVersion
     *
     * @return OkrSearchDTO
     */
    public function setOwnerGroupImageVersion($ownerGroupImageVersion)
    {
        $this->ownerGroupImageVersion = $ownerGroupImageVersion;

        return $this;
    }

    /**
     * Get ownerGroupImageVersion
     *
     * @return integer
     */
    public function getOwnerGroupImageVersion()
    {
        return $this->ownerGroupImageVersion;
    }

    /**
     * Set ownerCompanyId
     *
     * @param integer $ownerCompanyId
     *
     * @return OkrSearchDTO
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
     * @return OkrSearchDTO
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
     * Set ownerCompanyImageVersion
     *
     * @param integer $ownerCompanyImageVersion
     *
     * @return OkrSearchDTO
     */
    public function setOwnerCompanyImageVersion($ownerCompanyImageVersion)
    {
        $this->ownerCompanyImageVersion = $ownerCompanyImageVersion;

        return $this;
    }

    /**
     * Get ownerCompanyImageVersion
     *
     * @return integer
     */
    public function getOwnerCompanyImageVersion()
    {
        return $this->ownerCompanyImageVersion;
    }
}
