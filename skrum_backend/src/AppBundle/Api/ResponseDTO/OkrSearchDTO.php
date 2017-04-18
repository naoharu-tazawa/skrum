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
    private $userId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $userName;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $groupId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $groupName;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $companyId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $companyName;

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
     * Set userId
     *
     * @param integer $userId
     *
     * @return OkrSearchDTO
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return OkrSearchDTO
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set groupId
     *
     * @param integer $groupId
     *
     * @return OkrSearchDTO
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * Get groupId
     *
     * @return integer
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set groupName
     *
     * @param string $groupName
     *
     * @return OkrSearchDTO
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;

        return $this;
    }

    /**
     * Get groupName
     *
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * Set companyId
     *
     * @param integer $companyId
     *
     * @return OkrSearchDTO
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Get companyId
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return OkrSearchDTO
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }
}
