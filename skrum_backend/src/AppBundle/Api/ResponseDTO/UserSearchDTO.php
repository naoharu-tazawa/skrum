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
}
