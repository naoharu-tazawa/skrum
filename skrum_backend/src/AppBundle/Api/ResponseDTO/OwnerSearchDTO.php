<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * オーナー検索DTO

 * @JSON\ExclusionPolicy("none")
 */
class OwnerSearchDTO
{
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
     * Set ownerType
     *
     * @param string $ownerType
     *
     * @return OwnerSearchDTO
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
     * @return OwnerSearchDTO
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
     * @return OwnerSearchDTO
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
     * @return OwnerSearchDTO
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
     * @return OwnerSearchDTO
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
     * @return OwnerSearchDTO
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
     * @return OwnerSearchDTO
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
     * @return OwnerSearchDTO
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
     * @return OwnerSearchDTO
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
}
