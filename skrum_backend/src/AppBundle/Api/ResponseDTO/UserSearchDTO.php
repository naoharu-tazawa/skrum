<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * ユーザ検索DTO

 * @JSON\ExclusionPolicy("none")
 */
class UserSearchDTO
{
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
    private $imageVersion;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $roleAssignmentId;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $roleLevel;

    /**
     * @var \DateTime
     *
     * @JSON\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $lastLogin;

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserSearchDTO
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
     * @return UserSearchDTO
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
     * Set imageVersion
     *
     * @param integer $imageVersion
     *
     * @return UserSearchDTO
     */
    public function setImageVersion($imageVersion)
    {
        $this->imageVersion = $imageVersion;

        return $this;
    }

    /**
     * Get imageVersion
     *
     * @return integer
     */
    public function getImageVersion()
    {
        return $this->imageVersion;
    }

    /**
     * Set roleAssignmentId
     *
     * @param integer $roleAssignmentId
     *
     * @return UserSearchDTO
     */
    public function setRoleAssignmentId($roleAssignmentId)
    {
        $this->roleAssignmentId = $roleAssignmentId;

        return $this;
    }

    /**
     * Get roleAssignmentId
     *
     * @return integer
     */
    public function getRoleAssignmentId()
    {
        return $this->roleAssignmentId;
    }

    /**
     * Set roleLevel
     *
     * @param integer $roleLevel
     *
     * @return UserSearchDTO
     */
    public function setRoleLevel($roleLevel)
    {
        $this->roleLevel = $roleLevel;

        return $this;
    }

    /**
     * Get roleLevel
     *
     * @return integer
     */
    public function getRoleLevel()
    {
        return $this->roleLevel;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     *
     * @return UserSearchDTO
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }
}
